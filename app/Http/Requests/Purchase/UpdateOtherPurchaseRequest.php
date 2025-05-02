<?php

namespace App\Http\Requests\Purchase;

use App\Models\FiscalYear;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOtherPurchaseRequest extends FormRequest
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
            'purchase_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validatePaymentDate($attribute, $value, $fail);
                }
            ],
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ];
    }

    private function validatePaymentDate($attribute, $value, $fail)
    {
        $fiscalYear = FiscalYear::where('start_date', '<=', $value)
        ->where('end_date', '>=', $value)
        ->where('status', 'running')
        ->first();
        if (!$fiscalYear) {
            $fail(__('validation.custom.purchase.purchaseDate.date'));
        }
    }

    public function messages(): array
    {
        return [
            'purchase_date.required' => __('validation.custom.purchase.purchaseDate.required'),
            'paid_amount.numeric' => _('validation.custom.purchase.paidAmount.numeric'),
            'paid_amount.min' => _('validation.custom.purchase.paidAmount.min'),
            'discount.numeric' => _('validation.custom.purchase.discount.numeric'),
            'discount.min' => _('validation.custom.purchase.discount.min'),
        ];
    }
}
