<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('user')->id;
        return [
            'name' => 'required',
            'mobile_number' => 'required|unique:users,mobile_number,' . $userId,
            'roles' => 'nullable|array'
        ];
    }

     /**
     * Vlidation Messages
     */
    public function messages()
    {
        return [
            'name.required' => __('validation.custom.user.name.required'),
            'mobile_number.required' => __('validation.custom.user.mobile_number.required'),
            'mobile_number.unique' => __('validation.custom.user.mobile_number.unique'),
            'password.required' => __('validation.custom.user.password.required'),
            'roles.array' => __('validation.custom.user.roles.array')
        ];
    }
}
