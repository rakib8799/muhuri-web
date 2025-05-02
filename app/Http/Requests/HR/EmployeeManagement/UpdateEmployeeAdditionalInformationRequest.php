<?php

namespace App\Http\Requests\HR\EmployeeManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeAdditionalInformationRequest extends FormRequest
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
            'user_id' => 'nullable|exists:users,id',
            'permanent_address' => 'nullable|string',
            'address_line_one' => 'nullable|string',
            'address_line_two' => 'nullable|string',
            'floor' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_relation' => 'nullable|string',
            'emergency_contact_number' => 'nullable|string'
        ];
    }

    /**
     * Vlidation Messages
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => __('validation.custom.employeeManagement.employee.additionalInfo.user_id.exists'),
            'permanent_address.string' => __('validation.custom.employeeManagement.employee.additionalInfo.permanent_address.string'),
            'address_line_one.string' => __('validation.custom.commonComponents.addressComponent.address_line_one.string'),
            'address_line_two.string' => __('validation.custom.commonComponents.addressComponent.address_line_two.string'),
            'floor.string' => __('validation.custom.commonComponents.addressComponent.floor.string'),
            'city.string' => __('validation.custom.commonComponents.addressComponent.city.string'),
            'state.string' => __('validation.custom.commonComponents.addressComponent.state.string'),
            'zip_code.string' => __('validation.custom.commonComponents.addressComponent.zip_code.string'),
            'country_id.exists' => __('validation.custom.employeeManagement.employee.additionalInfo.country_id.exists'),
            'emergency_contact_name.string' => __('validation.custom.employeeManagement.employee.additionalInfo.emergency_contact_name.string'),
            'emergency_contact_relation.string' => __('validation.custom.employeeManagement.employee.additionalInfo.emergency_contact_relation.string'),
            'emergency_contact_number.string' => __('validation.custom.employeeManagement.employee.additionalInfo.emergency_contact_number.string')
        ];
    }
}
