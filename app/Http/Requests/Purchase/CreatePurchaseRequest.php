<?php

namespace App\Http\Requests\Purchase;

use App\Models\FiscalYear;
use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
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
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validatePaymentDate($attribute, $value, $fail);
                }
            ],
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.brand_id' => 'nullable',
            'items.*.total_box' => 'nullable|numeric|min:0',
            'items.*.total_poly' => 'required|numeric|min:0',
            'items.*.mir' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
            'items.*.note' => 'nullable',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'purchase_type' => 'required|string|in:larvae,other',
            'purchase_note' => 'nullable|string'
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
            'supplier_id.required' => __('validation.custom.purchase.supplierId.required'),
            'supplier_id.exists' => __('validation.custom.purchase.supplierId.exists'),
            'purchase_date.required' => __('validation.custom.purchase.purchaseDate.required'),
            'items.required' => __('validation.custom.purchase.items.required'),
            'items.array' => _('validation.custom.purchase.items.array'),
            'items.*.item_id.required' => _('validation.custom.purchase.itemsId.required'),
            'items.*.item_id.exists' => _('validation.custom.purchase.itemsId.exists'),
            'items.*.total_box.numeric' => _('validation.custom.purchase.totalBox.numeric'),
            'items.*.total_box.min' => _('validation.custom.purchase.totalBox.min'),
            'items.*.total_poly.required' => _('validation.custom.purchase.totalPoly.required'),
            'items.*.total_poly.numeric' => _('validation.custom.purchase.totalPoly.numeric'),
            'items.*.total_poly.min' => _('validation.custom.purchase.totalPoly.min'),
            'items.*.mir.required' => _('validation.custom.purchase.mir.required'),
            'items.*.mir.numeric' => _('validation.custom.purchase.mir.numeric'),
            'items.*.mir.min' => _('validation.custom.purchase.mir.min'),
            'items.*.quantity.required' => _('validation.custom.purchase.quantity.required'),
            'items.*.quantity.numeric' => _('validation.custom.purchase.quantity.numeric'),
            'items.*.quantity.min' => _('validation.custom.purchase.quantity.min'),
            'items.*.unit_price.required' => _('validation.custom.purchase.unitPrice.required'),
            'items.*.unit_price.numeric' => _('validation.custom.purchase.unitPrice.numeric'),
            'items.*.unit_price.min' => _('validation.custom.purchase.unitPrice.min'),
            'items.*.total_price.required' => _('validation.custom.purchase.totalPrice.required'),
            'items.*.total_price.numeric' => _('validation.custom.purchase.totalPrice.numeric'),
            'items.*.total_price.min' => _('validation.custom.purchase.totalPrice.min'),
            'paid_amount.numeric' => _('validation.custom.purchase.paidAmount.numeric'),
            'paid_amount.min' => _('validation.custom.purchase.paidAmount.min'),
            'discount.numeric' => _('validation.custom.purchase.discount.numeric'),
            'discount.min' => _('validation.custom.purchase.discount.min'),
            'purchase_type.required' => _('validation.custom.purchase.purchaseType.required'),
            'purchase_type.string' => _('validation.custom.purchase.purchaseType.string'),
            'purchase_type.in' => _('validation.custom.purchase.purchaseType.in'),
            'purchase_note.string' => _('validation.custom.purchase.note.string')
        ];
    }
}
