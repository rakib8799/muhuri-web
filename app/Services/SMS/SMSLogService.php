<?php

namespace App\Services\SMS;


use App\Models\SMS\SMSLog;
use App\Services\Core\BaseModelService;

class SMSLogService extends BaseModelService
{
    public function model(): string
    {
        return SMSLog::class;
    }


    public function createSmsLog($data): SMSLog
    {
        return $this->create($data);
    }
}
