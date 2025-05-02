<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;
        return [
            'name' => ['required', 'string', 'max:255'],
            'mobile_number' => 'required|unique:users,mobile_number,' . $userId
        ];
    }
}
