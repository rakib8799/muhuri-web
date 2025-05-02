<?php

namespace App\Http\Requests\Supplier;

use App\Models\FiscalYear;
use App\Services\Core\HelperService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSupplierPaymentRequest extends FormRequest
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
        $paymentMethods = HelperService::getPaymentMethodEnum();

        return [
            'amount' => 'required|numeric|gt:0',
            'payment_date' => [
                function ($attribute, $value, $fail) {
                    $this->validatePaymentDate($attribute, $value, $fail);
                }
            ],
            'payment_method' => [
                'required',
                Rule::in($paymentMethods),
            ],
            'note' => 'nullable',
        ];
    }

    private function validatePaymentDate($attribute, $value, $fail)
    {
        $fiscalYear = FiscalYear::where('start_date', '<=', $value)
        ->where('end_date', '>=', $value)
        ->where('status', 'running')
        ->first();
        if (!$fiscalYear) {
            $fail(__('validation.custom.payment.payment_date.date'));
        }
    }

    public function messages(): array
    {
        return [
            'amount.required' => __('validation.custom.payment.amount.required'),
            'amount.numeric' => __('validation.custom.payment.amount.numeric'),
            'amount.gt' => __('validation.custom.payment.amount.gt'),
            'payment_date.required' => __('validation.custom.payment.payment_date.required'),
            'payment_method.required' => __('validation.custom.payment.payment_method.required'),
            'payment_method.in' => __('validation.custom.payment.payment_method.in')
        ];
    }
}
