<?php

namespace App\Http\Requests\API\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|unique:users,mobile_number',
            'password' => 'required',
            'workspace' => 'required'
        ];
    }

    function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email',
            'email.unique' => 'Email has already been taken',
            'mobile_number.required' => 'Mobile number is required',
            'mobile_number.unique' => 'Mobile number has already been taken',
            'password.required' => 'Password is required',
            'workspace.required' => 'Workspace is required',
        ];
    }
}
