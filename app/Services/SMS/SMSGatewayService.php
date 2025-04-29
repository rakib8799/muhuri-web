<?php

namespace App\Services\SMS;

use App\Services\ConfigurationService;
use App\Services\Core\HelperService;
use Illuminate\Support\Facades\Http;

class SMSGatewayService
{

    private ConfigurationService $configurationService;
    private SMSLogService $smsLogService;
    private SMSCreditService $smsCreditService;

    public function __construct(
        ConfigurationService $configurationService,
        SMSLogService        $smsLogService,
        SMSCreditService     $smsCreditService
    )
    {
        $this->configurationService = $configurationService;
        $this->smsLogService = $smsLogService;
        $this->smsCreditService = $smsCreditService;
    }

    public function sendSMS($number, $message, $smsType, $willCountSMS = true)
    {
        $isSendingSms = (int)$this->configurationService->getConfiguration('is_sending_sms');
        if ($isSendingSms !== 1) {
            return;
        }

        //Check SMS Type is OPT
        $isOTP = (strtolower($smsType) === 'otp');

        //Check SMS Credit and create SMS Log
        if(!$isOTP){
            $credit = $this->smsCreditService->getOrCreateSMSCredit();
            $remainingCredit = $credit->remaining_credits;
            if ($remainingCredit <= 2) {
                $data = [
                    "number" => $number,
                    "message" => $message
                ];
                $responseBody = json_encode([
                    'response_code' => 0,
                    'error_message' => 'Insufficient balance to send SMS'
                ]);
                $this->smsLogService->createSmsLog([
                    'mobile_number' => $number,
                    'request_body' => $data,
                    'response_body' => $responseBody,
                    'sms_type' => $smsType,
                    'message' => $message,
                    'sms_count' => 0,
                    'status' => 'failed'
                ]);

                return response()->json(['message' => 'SMS not sent. Only 2 or fewer credits left.'], 400);
            }
        }


        $apiKey = $this->configurationService->getConfiguration('sms_service_api_key');
        $senderId = $this->configurationService->getConfiguration('sms_service_sender_id');
        $baseUrl = $this->configurationService->getConfiguration('sms_service_base_url');

        $data = [
            "api_key" => $apiKey,
            "senderid" => $senderId,
            "number" => $number,
            "message" => $message
        ];

        $response = Http::post($baseUrl, $data);
        $responseData = json_decode($response->body(), true);

        $smsCount = HelperService::getSmsCount($responseData);
        $status = (isset($responseData['response_code']) && $responseData['response_code'] == 202) ? 'sent' : 'failed';

        $this->smsLogService->createSmsLog([
            'mobile_number' => $number,
            'request_body' => $data,
            'response_body' => $response->body(),
            'sms_type' => $smsType,
            'message' => $message,
            'sms_count' => $smsCount,
            'status' => $status
        ]);

        if (!$isOTP && $status == 'sent' && $willCountSMS) {
            $this->smsCreditService->updateSMSCredit($smsCount);
        }

        return $response->body();
    }
}
