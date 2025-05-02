<?php

namespace App\Services\SMS;

use App\Models\SMS\SMSTemplate;
use App\Services\Core\BaseModelService;

class SMSTemplateService extends BaseModelService
{
    public function model(): string
    {
        return SMSTemplate::class;
    }


    public function createSmsTemplate($data): SMSTemplate
    {
        return $this->create($data);
    }

    public function getSmsTemplate($templateSlug, $language = 'en')
    {
        $column = 'sms_template_' . strtolower($language);
        return $this->model()::where('slug', $templateSlug)->value($column);
    }

    public function changeStatus(SMSTemplate $smsTemplate, $isActive)
    {
        $isActive = ($isActive == true) ? false : true;
        $smsTemplate->is_active = $isActive;
        $smsTemplate->save();
        return $smsTemplate;
    }
}
