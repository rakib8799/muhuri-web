<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleItemRequest extends FormRequest
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
            'item_id' => 'required|exists:items,id',
            'brand_id' => 'required|exists:brands,id',
            'total_box' => 'nullable|numeric|min:0',
            'total_poly' => 'required|numeric|min:0',
            'mir' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'item_id.required' => _('validation.custom.sale.itemsId.required'),
            'item_id.exists' => _('validation.custom.sale.itemsId.exists'),
            'brand_id.required' => _('validation.custom.sale.brandId.required'),
            'brand_id.exists' => _('validation.custom.sale.brandId.exists'),
            'total_box.numeric' => _('validation.custom.sale.totalBox.numeric'),
            'total_box.min' => _('validation.custom.sale.totalBox.min'),
            'total_poly.required' => _('validation.custom.sale.totalPoly.required'),
            'total_poly.numeric' => _('validation.custom.sale.totalPoly.numeric'),
            'total_poly.min' => _('validation.custom.sale.totalPoly.min'),
            'mir.required' => _('validation.custom.sale.mir.required'),
            'mir.numeric' => _('validation.custom.sale.mir.numeric'),
            'mir.min' => _('validation.custom.sale.mir.min'),
            'quantity.required' => _('validation.custom.sale.quantity.required'),
            'quantity.numeric' => _('validation.custom.sale.quantity.numeric'),
            'quantity.min' => _('validation.custom.sale.quantity.min'),
            'unit_price.required' => _('validation.custom.sale.unitPrice.required'),
            'unit_price.numeric' => _('validation.custom.sale.unitPrice.numeric'),
            'unit_price.min' => _('validation.custom.sale.unitPrice.min'),
            'total_price.required' => _('validation.custom.sale.totalPrice.required'),
            'total_price.numeric' => _('validation.custom.sale.totalPrice.numeric'),
            'total_price.min' => _('validation.custom.sale.totalPrice.min'),
            'note.string' => _('validation.custom.sale.note.string')
        ];
    }
}
