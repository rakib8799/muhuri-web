<?php

namespace App\Http\Requests\Buyer;

use Illuminate\Foundation\Http\FormRequest;

class CreateBuyerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|numeric|min:11|unique:buyers,mobile_number',
            'previous_due' => 'nullable|numeric|min:0',
            'division_id' => 'nullable|exists:location_divisions,id',
            'district_id' => 'nullable|exists:location_districts,id',
            'upazila_id' => 'nullable|exists:location_upazilas,id',
            'union_id' => 'nullable|exists:location_unions,id',
            'village' => 'nullable|string',
            'note' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.custom.buyer.name.required'),
            'name.string' => __('validation.custom.buyer.name.string'),
            'name.max' => __('validation.custom.buyer.name.max'),
            'mobile_number.required' => __('validation.custom.buyer.mobile_number.required'),
            'mobile_number.unique' => __('validation.custom.buyer.mobile_number.unique'),
            'mobile_number.numeric' => __('validation.custom.buyer.mobile_number.numeric'),
            'mobile_number.min' => __('validation.custom.buyer.mobile_number.min'),
            'previous_due.numeric' => __('validation.custom.buyer.previous_due.numeric'),
            'division_id.exists' => __('validation.custom.buyer.division_id.exists'),
            'district_id.exists' => __('validation.custom.buyer.district_id.exists'),
            'upazila_id.exists' => __('validation.custom.buyer.upazila_id.exists'),
            'union_id.exists' => __('validation.custom.buyer.union_id.exists'),
            'village.string' => __('validation.custom.buyer.village.string'),
            'note.string' => __('validation.custom.buyer.note.string')
        ];
    }
}
