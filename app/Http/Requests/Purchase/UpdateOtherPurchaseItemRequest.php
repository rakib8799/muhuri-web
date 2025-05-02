<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOtherPurchaseItemRequest extends FormRequest
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
            'quantity' => 'nullable|numeric|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'item_id.required' => _('validation.custom.purchase.itemsId.required'),
            'item_id.exists' => _('validation.custom.purchase.itemsId.exists'),
            'quantity.numeric' => _('validation.custom.purchase.quantity.numeric'),
            'quantity.min' => _('validation.custom.purchase.quantity.min'),
            'unit_price.numeric' => _('validation.custom.purchase.unitPrice.numeric'),
            'unit_price.min' => _('validation.custom.purchase.unitPrice.min'),
            'total_price.required' => _('validation.custom.purchase.totalPrice.required'),
            'total_price.numeric' => _('validation.custom.purchase.totalPrice.numeric'),
            'total_price.min' => _('validation.custom.purchase.totalPrice.min'),
            'note.string' => _('validation.custom.purchase.note.string'),
        ];
    }
}
