<?php

namespace App\Http\Controllers\Auth;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SMS\OTPService;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkControllerForMobileNumber extends Controller
{
    protected UserService $userService;
    protected OTPService $otpService;

    public function __construct(UserService $userService, OTPService $otpService)
    {
        $this->userService = $userService;
        $this->otpService = $otpService;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
            'pageTitle' => __('pageTitle.custom.auth.forgotPassword')
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'mobile_number' => 'required|regex:/^01[3-9]\d{8}$/|exists:users,mobile_number',
        ],  [
            'mobile_number.required' => __('validation.custom.user.mobile_number.required'),
            'mobile_number.regex' => __('validation.custom.user.mobile_number.regex'),
            'mobile_number.exists' => __('validation.custom.user.mobile_number.exists')
        ]);

        $request->merge([
            'email' => $request->input('mobile_number') . '@' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN,
        ]);


        $user = User::where('email', $request->input('email'))->first();
        if(!$user) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $this->otpService->sendOtpAndStoreSession($user, 'reset');
        Auth::login($user);
        return redirect()->route('otp.verification.show');
    }
}
