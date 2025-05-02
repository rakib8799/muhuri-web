<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
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
            'type' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => __('validation.custom.item.type.required'),
            'type.string' => __('validation.custom.item.type.string'),
            'name.required' => __('validation.custom.item.name.required'),
            'name.string' => __('validation.custom.item.name.string')
        ];
    }
}
