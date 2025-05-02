<?php

namespace App\Services\SMS;

use App\Models\User;
use App\Services\ConfigurationService;
use Illuminate\Support\Facades\Password;

class OTPService
{
    protected SMSGatewayService $smsGatewayService;
    private ConfigurationService $configurationService;

    public function __construct(SMSGatewayService $smsGatewayService, ConfigurationService $configurationService)
    {
        $this->smsGatewayService = $smsGatewayService;
        $this->configurationService = $configurationService;
    }

    public function sendSMS($mobileNumber, $otp): bool
    {
        $message = "Your MUHURI OTP is {$otp}";
        $response = $this->smsGatewayService->sendSMS($mobileNumber, $message, 'OTP', false);
        return $response == 'success';
    }

    public function sendOTP($company): bool
    {
//        $isProduction = env('APP_ENV') === 'production';
        $isProduction = true;
        $otpExpiryTimeMinutes = intVal($this->configurationService->getConfiguration('otp_expiry_time_minutes'));
        if (!$company->otp || $company->otp_expire_at < now()) {
            if ($isProduction) {
                $otp = rand(1000, 9999);
                $company->otp = $otp;
                $company->otp_expire_at = now()->addMinutes($otpExpiryTimeMinutes);
                $company->save();
                $this->sendSMS($company->mobile_number, $company->otp);
            } else {
                // Set a fixed OTP for dev environment
                $company->otp = '1234';
                $company->otp_expire_at = now()->addMinutes($otpExpiryTimeMinutes);
                $company->save();
            }

            return true;
        }

        return false;
    }

    public function verifyOTP($company, $otp): bool
    {
        if ($company && $company->otp == $otp && $company->otp_expire_at > now()) {
            $company->otp = null;
            $company->otp_expire_at = null;
            $company->save();
            return true;
        }
        return false;
    }

    public function sendOtpAndStoreSession(User $user, $purpose = 'login')
    {
        $this->sendOTP($user);
        $token = Password::createToken($user);
        session([
            'otp_user_id' => $user->id,
            'otp_purpose' => $purpose,
            'token' => $token,
            'status' => 'sent'
        ]);
    }
}
