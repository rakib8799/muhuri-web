<?php

namespace App\Http\Requests\HR\EmployeeManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeWorkInformationRequest extends FormRequest
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
            'job_title' => 'nullable|string',
            'job_positions' => 'required|exists:hr_job_positions,id',
            'department_id' => 'nullable|exists:hr_departments,id',
            'branch_id' => 'required|exists:branches,id',
            'manager_id' => 'nullable|exists:hr_employees,id',
            'leave_manager_id' => 'nullable|exists:hr_employees,id',
            'salary' => 'nullable|numeric',
            'hourly_rate' => 'nullable|numeric',
            'hour_limit_weekly' => 'nullable|integer',
            'over_time_rate' => 'nullable|numeric',
            'over_time_limit' => 'nullable|integer',
            'joining_date' => 'required|date',
            'linkedin_profile' => 'nullable|url',
            'etin_number' => 'nullable|numeric',
            'passport_number' => 'nullable|string',
            'visa_number' => 'nullable|numeric',
            'visa_expire_date' => 'nullable|date',
            'work_permit_number' => 'nullable|numeric',
            'work_permit_expiration_date' => 'nullable|date',
            'departure_date' => 'nullable|date',
            'departure_reason' => 'nullable|string',
            'is_nfc_card_issued' => 'boolean',
            'is_geo_fencing_enabled' => 'boolean',
            'is_photo_taking_enabled' => 'boolean',
        ];
    }

    /**
     * Vlidation Messages
     */
    public function messages(): array
    {
        return [
            'job_title.string' => __('validation.custom.employeeManagement.employee.workInfo.job_title.string'),
            'job_positions.required' => __('validation.custom.employeeManagement.employee.workInfo.job_positions.required'),
            'job_positions.exists' => __('validation.custom.employeeManagement.employee.workInfo.job_positions.exists'),
            'department_id.exists' => __('validation.custom.employeeManagement.employee.workInfo.department_id.exists'),
            'branch_id.required' => __('validation.custom.employeeManagement.employee.workInfo.branch_id.required'),
            'branch_id.exists' => __('validation.custom.employeeManagement.employee.workInfo.branch_id.exists'),
            'manager_id.exists' => __('validation.custom.employeeManagement.employee.workInfo.manager_id.exists'),
            'leave_manager_id.exists' => __('validation.custom.employeeManagement.employee.workInfo.leave_manager_id.exists'),
            'salary.numeric' => __('validation.custom.employeeManagement.employee.workInfo.salary.numeric'),
            'hourly_rate.numeric' => __('validation.custom.employeeManagement.employee.workInfo.hourly_rate.numeric'),
            'hour_limit_weekly.integer' => __('validation.custom.employeeManagement.employee.workInfo.hour_limit_weekly.integer'),
            'over_time_rate.numeric' => __('validation.custom.employeeManagement.employee.workInfo.over_time_rate.numeric'),
            'over_time_limit.integer' => __('validation.custom.employeeManagement.employee.workInfo.over_time_limit.integer'),
            'joining_date.required' => __('validation.custom.employeeManagement.employee.workInfo.joining_date.required'),
            'joining_date.date' => __('validation.custom.employeeManagement.employee.workInfo.joining_date.date'),
            'linkedin_profile.url' => __('validation.custom.employeeManagement.employee.workInfo.linkedin_profile.url'),
            'etin.numeric' => __('validation.custom.employeeManagement.employee.workInfo.etin.numeric'),
            'passport_number.string' => __('validation.custom.employeeManagement.employee.workInfo.passport_number.string'),
            'visa_number.numeric' => __('validation.custom.employeeManagement.employee.workInfo.visa_number.numeric'),
            'visa_expire_date.date' => __('validation.custom.employeeManagement.employee.workInfo.visa_expire_date.date'),
            'work_permit_number.numeric' => __('validation.custom.employeeManagement.employee.workInfo.work_permit_number.numeric'),
            'work_permit_expiration_date.date' => __('validation.custom.employeeManagement.employee.workInfo.work_permit_expiration_date.date'),
            'departure_date.date' => __('validation.custom.employeeManagement.employee.workInfo.departure_date.date'),
            'departure_reason.string' => _('validation.custom.employeeManagement.employee.workInfo.departure_reason.string'),
            'is_nfc_card_issued.boolean' => _('validation.custom.employeeManagement.employee.workInfo.is_nfc_card_issued.boolean'),
            'is_geo_fencing_enabled.boolean' => _('validation.custom.employeeManagement.employee.workInfo.is_geo_fencing_enabled.boolean'),
            'is_photo_taking_enabled.boolean' => _('validation.custom.employeeManagement.employee.workInfo.is_photo_taking_enabled.boolean')
        ];
    }
}
