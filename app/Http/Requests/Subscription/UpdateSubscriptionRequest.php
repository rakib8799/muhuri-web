<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
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
            'subscription_plan_id' => 'required|exists:subscription_plans,id'
        ];
    }

     /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [
            'subscription_plan_id.required' => __('validation.custom.subscription.subscription_plan_id.required'),
            'subscription_plan_id.exists' => __('validation.custom.subscription.subscription_plan_id.exists')
        ];
    }
}
