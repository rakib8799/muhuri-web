<?php

namespace App\Http\Requests\Sale;

use App\Models\FiscalYear;
use Illuminate\Foundation\Http\FormRequest;

class CreateOtherSaleRequest extends FormRequest
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
            'buyer_id' => 'required|exists:buyers,id',
            'sale_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validatePaymentDate($attribute, $value, $fail);
                }
            ],
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'nullable|numeric',
            'items.*.unit_price' => 'nullable|numeric',
            'items.*.total_price' => 'required|numeric|min:0',
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
            'buyer_id.required' => __('validation.custom.sale.buyerId.required'),
            'buyer_id.exists' => __('validation.custom.sale.buyerId.exists'),
            'sale_date.required' => __('validation.custom.sale.saleDate.required'),
            'items.required' => __('validation.custom.sale.items.required'),
            'items.array' => _('validation.custom.sale.items.array'),
            'items.*.item_id.required' => _('validation.custom.sale.itemsId.required'),
            'items.*.item_id.exists' => _('validation.custom.sale.itemsId.exists'),
            'items.*.quantity.required' => _('validation.custom.sale.quantity.required'),
            'items.*.quantity.numeric' => _('validation.custom.sale.quantity.numeric'),
            'items.*.quantity.min' => _('validation.custom.sale.quantity.min'),
            'items.*.unit_price.required' => _('validation.custom.sale.unitPrice.required'),
            'items.*.unit_price.numeric' => _('validation.custom.sale.unitPrice.numeric'),
            'items.*.unit_price.min' => _('validation.custom.sale.unitPrice.min'),
            'items.*.total_price.required' => _('validation.custom.sale.totalPrice.required'),
            'items.*.total_price.numeric' => _('validation.custom.sale.totalPrice.numeric'),
            'items.*.total_price.min' => _('validation.custom.sale.totalPrice.min'),
            'paid_amount.numeric' => _('validation.custom.sale.paidAmount.numeric'),
            'paid_amount.min' => _('validation.custom.sale.paidAmount.min'),
            'discount.numeric' => _('validation.custom.sale.discount.numeric'),
            'discount.min' => _('validation.custom.sale.discount.min'),
            'sale_note.string' => _('validation.custom.sale.note.string')
        ];
    }

}
