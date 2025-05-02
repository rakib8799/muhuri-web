<?php

namespace App\Services\User;

use App\Constants\Constants;
use App\Models\HR\Employee;
use App\Models\User;
use App\Services\ConfigurationService;
use App\Services\Core\BaseModelService;
use App\Services\Permission\RoleService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseModelService
{
    private RoleService $roleService;
    private ConfigurationService $configurationService;

    public function __construct(RoleService $roleService, ConfigurationService $configurationService)
    {
        $this->roleService = $roleService;
        $this->configurationService = $configurationService;
    }

    public function model(): string
    {
        return User::class;
    }

    /**
     * Get users and their roles
     * Convert the last_login into human readable format
     */
    public function getUsers()
    {
        $users = $this->model()::with('roles')->get()->map(function ($user) {
            if ($user->last_login_at) {
                $lastLogin = Carbon::parse($user->last_login_at);
                $user->last_login_at = $lastLogin->diffForHumans();
            } else {
                $user->last_login_at = "Never logged in";
            }
            return $user;
        });
        return $users;
    }

    /**
     * Create user
     * Assign Roles
     */
    public function createUser($validatedData)
    {
        $validatedData['password'] = Hash::make($validatedData['password']);
        $result = DB::transaction(function () use ($validatedData) {
            $user = $this->create($validatedData);
            if ($validatedData['roles']) {
                $user->assignRole($validatedData['roles']);
            }
            $this->logActivity($user, 'created', 'created');
            return $user;
        });

        return $result;
    }

    public function updateUser(User $user, $validatedData)
    {
        $result = DB::transaction(function () use ($user, $validatedData) {

            $oldUser = clone $user;
            $updatedName = $validatedData['name'] ?? $oldUser->name;
            $updatedEmail = $validatedData['mobile_number'] . '@' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN ?? $oldUser->email;
            $updatedMobileNumber = $validatedData['mobile_number'] ?? $oldUser->mobile_number;
            $user->update($validatedData);

            if (array_key_exists('roles', $validatedData)) {
                $user->syncRoles($validatedData['roles']);
            }

            $isRoleRemoved = array_diff($this->roleIdsToArray($oldUser), $this->roleIdsToArray($user));
            $isRoleAdded = array_diff($this->roleIdsToArray($user), $this->roleIdsToArray($oldUser));

            if(($oldUser->name != $updatedName || $oldUser->email != $updatedEmail || $oldUser->mobile_number != $updatedMobileNumber) && ($isRoleRemoved || $isRoleAdded)) {
                // log if user name or email and roles are updated
                $this->logActivity($user, 'updated', 'userAndRoles', $oldUser);
            } else if($oldUser->email != $updatedEmail && $oldUser->name == $updatedName) {
                // log if user email is updated
                $this->logActivity($user, 'updated', 'email', $oldUser);
            } else if($oldUser->mobile_number != $updatedMobileNumber && $oldUser->name == $updatedName) {
                // log if user mobile number is updated
                $this->logActivity($user, 'updated', 'mobileNumber', $oldUser);
            }
            else if($oldUser->name != $updatedName || $oldUser->email != $updatedEmail || $oldUser->mobile_number != $updatedMobileNumber) {
                // log if user name or email or mobile number is updated
                $this->logActivity($user, 'updated', 'user', $oldUser);
            } else if($isRoleRemoved || $isRoleAdded) {
                // log if user roles are updated
                $this->logActivity($user, 'updated', 'roles', $oldUser);
            }
            return $user;
        });
        return $result ?? false;
    }

    public function deleteUser(User $user)
    {
        // Check if the user is referred to in other tables
        if ($this->hasReferences($user)) {
            return false;
        }

        // if user is assigned any role remove data from pivot table when deleting user.
        $result = DB::transaction(function () use ($user) {
            $oldUser = clone $user;
            $isDeleted = $this->delete($user->id);
            if ($isDeleted) {
                $user->roles()->detach();
                $this->logActivity($user, 'deleted', 'deleted', $oldUser);
            }
            return true;
        });
        return $result ? true : false;
    }

    private function hasReferences(User $user)
    {
        // Check for references in Employee table
        if (Employee::where('user_id', $user->id)->exists()) {
            return true;
        }
    }

    public function assignRoleToUser($validatedData)
    {
        $userId = $validatedData['user_id'];
        $roleId = $validatedData['role_id'];

        $user = $this->getUserById($userId);
        $role = $this->roleService->getRoleById($roleId);

        if ($user->hasRole($role)) {
            return false;
        }

        $user->assignRole($role);
        return true;
    }

    public function removeRoleFromUser($validatedData)
    {
        $userId = $validatedData['user_id'];
        $roleId = $validatedData['role_id'];

        $user = $this->getUserById($userId);
        $role = $this->roleService->getRoleById($roleId);

        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return true;
        }
        return false;
    }

    public function getUserById($userId)
    {
        return User::find($userId)->with('roles');
    }

    public function getUserByEmail($validatedData)
    {
        return $this->model()::where('email', $validatedData['email'])->first();
    }

    public function getUserByMobileNumber($mobileNumber)
    {
        return $this->model()::where('mobile_number', $mobileNumber)->first();
    }

    public function getUserDetails(User $user)
    {
        $user = $user->load('roles');
        return $user;
    }

    public function updatePassword(User $user, $validatedData)
    {
        $oldUser = clone $user;
        $password = Hash::make($validatedData['password']);
        $user->update(['password' => $password]);
        $this->logActivity($user, 'updated', 'password', $oldUser);
        return $user;
    }

    public function changeStatus(User $user, $isActive)
    {
        $result = DB::transaction(function () use ($user, $isActive) {
            $oldUser = clone $user;
            $isActive = ( $isActive == true ) ? false : true;
            $user->update(['is_active' => $isActive]);
            $this->logActivity($user, 'updated', 'status', $oldUser);

            return $user;
        });
        return $result;

    }

    protected function logActivity(User $user, $event, $log, $oldUser = null)
    {
        if($event === 'deleted' || $event === 'updated') {
            $properties['old'] = $this->extractUserProperties($oldUser);
        }
        if($event === 'created' || $event === 'updated') {
            $properties['attributes'] = $this->extractUserProperties($user, $event);
        }

        $messageList = [
            'created' => "Created new user - $user->name",
            'userAndRoles' => "Updated the user and its roles - $user->name",
            'user' => "Updated the user - $user->name",
            'email' => "Updated the user email - $user->name",
            'mobileNumber' => "Updated the user mobile number - $user->name",
            'roles' => "Updated the user roles - $user->name",
            'deleted' => "Deleted the user - $user->name",
            'status' => "Changed the status of the user - $user->name",
            'password' => "Changed the password of the user - $user->name"
        ];
        $message = $messageList[$log];
        activity()
            ->performedOn($user)
            ->event($event)
            ->withProperties($properties)
            ->log($message);
    }

    private function roleIdsToArray(User $user)
    {
        return $user->roles->pluck('id')->toArray();
    }

    private function extractUserProperties($user, $event = null) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'is_active' => ($event === 'created') ? 1 : $user->is_active,
            'last_login_at' => $user->last_login_at,
            'role_ids' => $this->roleIdsToArray($user),
        ];
    }
}
