<?php

namespace App\Http\Requests\SMS\SMSTemplate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSMSTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'sms_template_en' => 'required|string',
            'sms_template_bn' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.custom.smsTemplate.name.required'),
            'name.string' => __('validation.custom.smsTemplate.name.string'),
            'sms_template_en.required' => __('validation.custom.smsTemplate.sms_template_en.required'),
            'sms_template_en.string' => __('validation.custom.smsTemplate.sms_template_en.string'),
            'sms_template_bn.required' => __('validation.custom.smsTemplate.sms_template_bn.required'),
            'sms_template_bn.string' => __('validation.custom.smsTemplate.sms_template_bn.string'),
        ];
    }
}
