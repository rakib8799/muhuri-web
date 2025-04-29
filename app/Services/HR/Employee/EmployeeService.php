<?php

namespace App\Services\HR\Employee;

use App\Constants\Constants;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HR\Employee;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\Core\BaseModelService;

class EmployeeService extends BaseModelService
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function model(): string
    {
        return Employee::class;
    }

    public function getEmployees()
    {
        $employees = $this->model()::with('country', 'user')->get()->map(function ($employee) {
            if($employee->user->last_login_at) {
                $lastLogin = Carbon::parse($employee->user->last_login_at);
                $employee->user->last_login_at = $lastLogin->diffForHumans();
            } else {
                $employee->user->last_login_at = "Never logged in";
            }
            return $employee;
        });
        return $employees;
    }

    public function getActiveEmployees()
    {
        $activeEmployees = $this->model()::where('is_active', 1)->get();
        return $activeEmployees;
    }

    public function createEmployee($validatedData)
    {
        $result = DB::transaction(function () use ($validatedData) {
            $email = $validatedData['email'];
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                    'password' => Hash::make('12345'),
                ]
            );

            $user->assignRole(Constants::ROLE_EMPLOYEE);
            $validatedData['user_id'] = $user->id;
            $employee = $this->create($validatedData);
            return $employee;
        });
        return $result;
    }

    public function updateEmployee(Employee $employee, $validatedData)
    {
        $result = DB::transaction(function () use($employee, $validatedData) {
            if(isset($validatedData['manager_id'])) {
                $manager = $this->find($validatedData['manager_id']);
                $manager->user->assignRole(Constants::ROLE_LINE_MANAGER);
            }
            $this->update($employee, $validatedData);

            if (array_key_exists('job_positions', $validatedData)) {
                $employee->jobPositions()->sync($validatedData['job_positions']);
            }

            // Update user name
            if(isset($validatedData['first_name'])) {
                $user = $employee->user;
                if($user) {
                    $user->name = $validatedData['first_name'].' '.$validatedData['last_name'];
                    $user->save();
                }
            }
            return $employee;
        });
        return $result;
    }

    public function deleteEmployee(Employee $employee)
    {
        $this->delete($employee->id);
        return true;
    }

    /**
     * Get user, country, department, branch, manager and leave manager of an employee
     */
    public function getEmployeeDetails(Employee $employee)
    {
        return $employee->load('user', 'country', 'department');
    }

    public function changeStatus(Employee $employee, $isActive)
    {
        $result = DB::transaction(function () use ($employee, $isActive) {
            $this->update($employee, ['is_active' => !$isActive]);
            if($employee && $employee->is_active == !$isActive){
                $this->userService->find($employee->user_id)->update(['is_active' => !$isActive]);
            }
            return $employee;
        });
        return $result;
    }

    public function handleDeparture(Employee $employee, $validatedData)
    {
        $employee->is_active = false;
        $employee->is_departed = true;
        $result = DB::transaction(function () use ($employee, $validatedData) {
            $updatedEmployee = $this->updateEmployee($employee, $validatedData);
            if($updatedEmployee && $employee->is_departed == true){
                $this->userService->find($employee->user_id)->update(['is_active' => false]);
            }
            return true;
        });
        return $result ? true : false;
    }

    public function handleRejoin(Employee $employee, $validatedData)
    {
        $employee->is_active = true;
        $employee->is_departed = false;
        $result = DB::transaction(function () use ($employee, $validatedData) {
            $updatedEmployee = $this->updateEmployee($employee, $validatedData);
            if($updatedEmployee && $employee->is_departed == false){
                $this->userService->find($employee->user_id)->update(['is_active' => true]);
            }
            return true;
        });
        return $result ? true : false;
    }

    protected function transformEmployeeDetails($employee)
    {
        if($employee) {
            $lastPunch = optional($employee->attendances->first());
            return [
                'employee_id' => $employee->id,
                'name' => $this->getEmployeeFullName($employee),
                'last_punch' => $lastPunch->toArray() ?? null
            ];
        }
    }

    public function getEmployeeFullName(Employee $employee)
    {
        return $employee->first_name." ".$employee->last_name;
    }
}
