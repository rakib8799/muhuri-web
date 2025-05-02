<?php

namespace App\Http\Requests\HR\EmployeeManagement;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDepartureRequest extends FormRequest
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
            'departure_reason_id' => 'nullable|exists:hr_departure_reasons,id',
            'departure_description' => 'nullable|string',
            'departure_date' => 'nullable|date'
        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [
            'departure_reason_id.exists' => __('validation.custom.employeeManagement.employeeDeparture.departure_reason_id.exists'),
            'departure_description.string' => __('validation.custom.employeeManagement.employeeDeparture.departure_description.string'),
            'departure_date.date' => __('validation.custom.employeeManagement.employeeDeparture.departure_date.date')
        ];
    }
}
