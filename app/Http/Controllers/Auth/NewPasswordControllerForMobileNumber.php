<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordControllerForMobileNumber extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        $mobileNumber = session('mobile_number');
        $user = $this->userService->getUserByMobileNumber($mobileNumber);
        if($user && is_null($user->mobile_number_verified_at)) {
            $user->mobile_number_verified_at = Carbon::now();
            $user->save();
        }

        return Inertia::render('Auth/ResetPassword', [
            'mobile_number' => $mobileNumber,
            'token' => $request->token,
            'pageTitle' => __('pageTitle.custom.auth.resetPassword')
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'mobile_number' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ], [
            'token.required' => __('validation.custom.user.token.required'),
            'mobile_number.required' => __('validation.custom.user.mobile_number.required'),
            'password.required' => __('validation.custom.user.password.required'),
            'password.min' => __('validation.custom.user.password.min'),
            'password_confirmation.required' => __('validation.custom.user.password_confirmation.required'),
            'password_confirmation.same' => __('validation.custom.user.password_confirmation.same'),
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('mobile_number', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                if(session()->has('mobile_number')) {
                    session()->forget('mobile_number');
                }
            }
        );

        session(['status' => $status]);
        return redirect()->route('login');
    }
}
