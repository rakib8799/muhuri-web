<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\NewPasswordControllerForMobileNumber;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PasswordResetLinkControllerForMobileNumber;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('forgot-password/mobile-number', [PasswordResetLinkControllerForMobileNumber::class, 'create'])
    ->name('password.request.mobileNumber');

    Route::post('forgot-password/mobile-number', [PasswordResetLinkControllerForMobileNumber::class, 'store'])->name('password.mobileNumber');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');

    Route::get('reset-password/mobile-number/{token}', [NewPasswordControllerForMobileNumber::class, 'create'])
                ->name('password.reset.mobileNumber');

    Route::post('reset-password/mobile-number', [NewPasswordControllerForMobileNumber::class, 'store'])
                ->name('password.store.mobileNumber');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-otp', [OtpVerificationController::class, 'show'])
        ->name('otp.verification.show');

    Route::post('verify-otp/{user}', [OtpVerificationController::class, 'send'])
        ->name('otp.verification.send');

    Route::post('otp/verification-sms', [OtpVerificationController::class, 'verify'])
        ->name('otp.verification.verify');

    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('signup-confirmation', function () {
        return Inertia::render('Auth/SignUpConfirmation', [
            'pageTitle' => __('pageTitle.custom.auth.signUpConfirmation')
        ]);
    });

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
