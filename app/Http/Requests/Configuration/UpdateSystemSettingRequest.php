<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemSettingRequest extends FormRequest
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
            'option_name' => 'required|string|in:is_sending_sms',
            'option_value' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'option_name.required' => 'The Option Name is required.',
            'option_name.string' => 'The Option Name must be a string.',
            'option_name.in' => 'Invalid Option Name provided.',
            'option_value.required' => 'The Option Value is required.',
            'option_value.boolean' => 'The Option Value must be a boolean.',
        ];
    }
}
