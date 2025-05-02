<?php

namespace App\Http\Requests\Sale;

use App\Models\FiscalYear;
use Illuminate\Foundation\Http\FormRequest;

class CreateSaleRequest extends FormRequest
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
            'items.*.brand_id' => 'required|exists:brands,id',
            'items.*.total_box' => 'nullable|numeric|min:0',
            'items.*.total_poly' => 'required|numeric|min:0',
            'items.*.mir' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'sale_type' => 'required|string|in:larvae,other',
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
            'items.*.brand_id.required' => _('validation.custom.sale.brandId.required'),
            'items.*.brand_id.exists' => _('validation.custom.sale.brandId.exists'),
            'items.*.total_box.numeric' => _('validation.custom.sale.totalBox.numeric'),
            'items.*.total_box.min' => _('validation.custom.sale.totalBox.min'),
            'items.*.total_poly.required' => _('validation.custom.sale.totalPoly.required'),
            'items.*.total_poly.numeric' => _('validation.custom.sale.totalPoly.numeric'),
            'items.*.total_poly.min' => _('validation.custom.sale.totalPoly.min'),
            'items.*.mir.required' => _('validation.custom.sale.mir.required'),
            'items.*.mir.numeric' => _('validation.custom.sale.mir.numeric'),
            'items.*.mir.min' => _('validation.custom.sale.mir.min'),
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
            'sale_type.required' => _('validation.custom.sale.saleType.required'),
            'sale_type.string' => _('validation.custom.sale.saleType.string'),
            'sale_type.in' => _('validation.custom.sale.saleType.in'),
            'sale_note.string' => _('validation.custom.sale.note.string')
        ];
    }
}
