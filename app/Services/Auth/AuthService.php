<?php

namespace App\Services\Auth;

use App\Domains\Auth\Events\Login;
use App\Models\User;
use App\Services\Core\BaseModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService extends BaseModelService
{
    public function model(): string
    {
        return User::class;
    }

    public function adminLogin($validatedData)
    {
        if(Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            $user = Auth::user();
            $token =  $user->createToken('admin-auth-token')->plainTextToken;
            return $token;
        } else {
            return false;
        }
    }

    public function login(Request $request, User $user)
    {
        $user->update(['last_login_at' => now()]);

        event(new Login($user));

        $request->session()->regenerate();

        $permissions = $user->getPermissionsViaRoles()->toArray();
        return redirect()->route('dashboard')->with('permissions', $permissions);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return true;
    }
}
