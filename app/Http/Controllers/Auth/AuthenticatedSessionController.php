<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use App\Services\SMS\OTPService;

class AuthenticatedSessionController extends Controller
{
    protected OTPService $otpService;
    protected AuthService $authService;

    public function __construct(OTPService $otpService, AuthService $authService)
    {
        $this->otpService = $otpService;
        $this->authService = $authService;
    }

    /**
     * Display the login view.
     */
    public function create(): Response
    {
        $status = session('status');
        if(session()->has('status')) {
            session()->forget('status');
        }
        return Inertia::render('Auth/Login', [
            // 'canResetPassword' => Route::has('password.request'),
            'canResetPassword' => Route::has('password.request.mobileNumber'),
            'status' => $status,
            'pageTitle' => "Login",
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->merge([
            'email' => $request->input('mobile_number') . '@muhuri.app',
        ]);

        $request->authenticate();
        $user = $request->user();

        if (!$user->mobile_number_verified_at) {
            $this->otpService->sendOtpAndStoreSession($user);
            return redirect()->route('otp.verification.show');
        }

        return $this->authService->login($request, $user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
