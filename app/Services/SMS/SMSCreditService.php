<?php

namespace App\Services\SMS;

use App\Models\SMS\SMSCredit;
use App\Services\Core\BaseModelService;
use Illuminate\Support\Facades\DB;

class SMSCreditService extends BaseModelService
{
    public function model(): string
    {
        return SMSCredit::class;
    }

    public function getOrCreateSMSCredit()
    {
        $smsCredit = $this->model()::first();
        if (!$smsCredit) {
            $smsCredit = $this->create([
                'total_credits' => 0,
                'used_credits' => 0,
                'remaining_credits' => 0,
            ]);
        }
        return $smsCredit;
    }

    public function updateSMSCredit($usedCredits)
    {
        DB::transaction(function () use ($usedCredits) {
            $smsCredit = $this->getOrCreateSMSCredit();
            $smsCredit->used_credits += $usedCredits;
            $smsCredit->remaining_credits -= $usedCredits;
            $smsCredit->save();
        });
    }
}
