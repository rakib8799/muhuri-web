<?php

namespace App\Http\Requests\HR\EmployeeManagement;

use App\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeBasicInformationRequest extends FormRequest
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
        $employeeId = $this->route('employee')->id;
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:hr_employees,email,' . $employeeId,
            'staff_id' => 'required|unique:hr_employees,staff_id,' . $employeeId,
            'social_security_number' => 'nullable|string',
            'mobile_number' => 'required|string|size:11|regex:/^\d+$/',
            'dob' => 'required|date',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender' => 'nullable|string|in:'.implode(',', [Constants::MALE, Constants::FEMALE, Constants::OTHERS]),
            'marital_status' => 'nullable|string|in:'.implode(',', [Constants::SINGLE, Constants::MARRIED, Constants::LEGAL_COHABITANT, Constants::WIDOWER, Constants::DIVORCED])
        ];
    }

    /**
     * Vlidation Messages
     */
    public function messages(): array
    {
        return [
            'first_name.required' => __('validation.custom.employeeManagement.employee.first_name.required'),
            'first_name.string' => __('validation.custom.employeeManagement.employee.first_name.string'),
            'last_name.required' => __('validation.custom.employeeManagement.employee.last_name.required'),
            'last_name.string' => __('validation.custom.employeeManagement.employee.last_name.string'),
            'email.required' => __('validation.custom.employeeManagement.employee.email.required'),
            'email.email' => __('validation.custom.employeeManagement.employee.email.email'),
            'email.unique' => __('validation.custom.employeeManagement.employee.email.unique'),
            'staff_id.required' => __('validation.custom.employeeManagement.employee.staff_id.required'),
            'staff_id.unique' => __('validation.custom.employeeManagement.employee.staff_id.unique'),
            'social_security_number.string' => __('validation.custom.employeeManagement.employee.social_security_number.string'),
            'mobile_number.required' => __('validation.custom.employeeManagement.employee.mobile_number.required'),
            'mobile_number.string' => __('validation.custom.employeeManagement.employee.mobile_number.string'),
            'mobile_number.size' => __('validation.custom.employeeManagement.employee.mobile_number.size'),
            'mobile_number.regex' => __('validation.custom.employeeManagement.employee.mobile_number.regex'),
            'dob.required' => __('validation.custom.employeeManagement.employee.dob.required'),
            'dob.date' => __('validation.custom.employeeManagement.employee.dob.date'),
            'blood_group.string' => __('validation.custom.employeeManagement.employee.blood_group.string'),
            'blood_group.in' => __('validation.custom.employeeManagement.employee.blood_group.in'),
            'gender.string' => __('validation.custom.employeeManagement.employee.gender.string'),
            'gender.in' => __('validation.custom.employeeManagement.employee.gender.in'),
            'marital_status.string' => __('validation.custom.employeeManagement.employee.marital_status.string'),
            'marital_status.in' => __('validation.custom.employeeManagement.employee.marital_status.in')
        ];
    }
}
