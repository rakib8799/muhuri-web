<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required',
            'mobile_number' => 'required|unique:users,mobile_number',
            'password' => 'required',
            'roles' => 'nullable|array'
        ];
    }

    function messages()
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
