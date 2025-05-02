<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use App\Services\Auth\AuthService;
use App\Services\SMS\OTPService;
use Illuminate\Support\Facades\Auth;

class OtpVerificationController extends Controller
{
    protected OTPService $otpService;
    protected AuthService $authService;

    public function __construct(OTPService $otpService, AuthService $authService)
    {
        $this->otpService = $otpService;
        $this->authService = $authService;
    }

    public function show()
    {
        if(session()->has('otp_user_id')) {
            $user = User::find(session('otp_user_id'));
            $responseData = [
                'user' => $user,
                'status' => session('status')
            ];

            session()->forget('status');
            return Inertia::render('Auth/VerifyOTP', $responseData);
        }

        return Inertia::render('Auth/Login', ['pageTitle' => __('pageTitle.custom.login')]);
    }

    public function send(User $user)
    {
        $this->otpService->sendOtpAndStoreSession($user);
        return redirect()->route('otp.verification.show');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required',
            'otp' => 'required'
        ]);

        if (!session()->has('otp_user_id')) {
            session(['status' => __('message.custom.auth.sessionExpired')]);
            return redirect()->route('login');
        }

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            session(['status' => __('message.custom.auth.userNotFound')]);
            return redirect()->route('login');
        }

        if ($user && now() > $user->otp_expire_at) {
            session(['status' => __('message.custom.auth.otpExpired')]);
            return redirect()->route('otp.verification.show');
        }

        if ($this->otpService->verifyOTP($user, $request->otp)) {
            $user->update(['mobile_number_verified_at' => now()]);

            $otpPurpose = session('otp_purpose');
            $token = session('token');
            session()->forget(['otp_user_id', 'otp_purpose', 'token']);

            if ($otpPurpose === 'reset') {
                session(['mobile_number' => $user->mobile_number]);
                Auth::logout();
                return redirect()->route('password.reset.mobileNumber', ['token' => $token]);
            }

            return $this->authService->login($request, $user);
        }

        session(['status' => __('message.custom.auth.otpInvalid')]);
        return redirect()->route('otp.verification.show');
    }
}

