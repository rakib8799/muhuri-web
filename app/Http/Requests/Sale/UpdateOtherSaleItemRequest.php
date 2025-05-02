<?php

namespace App\Http\Requests\Sale;

use App\Models\FiscalYear;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOtherSaleItemRequest extends FormRequest
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
            'quantity' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0.01',
            'total_price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'item_id.required' => _('validation.custom.sale.itemsId.required'),
            'item_id.exists' => _('validation.custom.sale.itemsId.exists'),
            'quantity.required' => _('validation.custom.sale.quantity.required'),
            'quantity.numeric' => _('validation.custom.sale.quantity.numeric'),
            'quantity.min' => _('validation.custom.sale.quantity.min'),
            'unit_price.required' => _('validation.custom.sale.unitPrice.required'),
            'unit_price.numeric' => _('validation.custom.sale.unitPrice.numeric'),
            'unit_price.min' => _('validation.custom.sale.unitPrice.min'),
            'total_price.required' => _('validation.custom.sale.totalPrice.required'),
            'total_price.numeric' => _('validation.custom.sale.totalPrice.numeric'),
            'total_price.min' => _('validation.custom.sale.totalPrice.min'),
        ];
    }

}
