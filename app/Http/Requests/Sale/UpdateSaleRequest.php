<?php

namespace App\Http\Requests\Sale;

use App\Models\FiscalYear;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
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
            'sale_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validatePaymentDate($attribute, $value, $fail);
                }
            ],
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'sale_note' => 'nullable|string'
        ];
    }

    private function validatePaymentDate($attribute, $value, $fail)
    {
        $fiscalYear = FiscalYear::where('start_date', '<=', $value)
        ->where('end_date', '>=', $value)
        ->where('status', 'running')
        ->first();
        if (!$fiscalYear) {
            $fail(__('validation.custom.sale.saleDate.date'));
        }
    }

    public function messages(): array
    {
        return [
            'sale_date.required' => __('validation.custom.sale.saleDate.required'),
            'paid_amount.numeric' => _('validation.custom.sale.paidAmount.numeric'),
            'paid_amount.min' => _('validation.custom.sale.paidAmount.min'),
            'discount.numeric' => _('validation.custom.sale.discount.numeric'),
            'discount.min' => _('validation.custom.sale.discount.min'),
            'sale_note.string' => _('validation.custom.sale.note.string')
        ];
    }
}
