<?php

namespace App\Http\Requests\FiscalYear;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFiscalYearRequest extends FormRequest
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
        $fiscalYearId = $this->route('fiscal_year')->id;
        return [
            'fiscal_year' => 'required|integer|unique:fiscal_years,fiscal_year,' . $fiscalYearId,
            'start_date'  => 'required|date|before:end_date',
            'end_date'    => 'required|date|after:start_date',
            'note'        => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'fiscal_year.required' => __('validation.custom.fiscalYear.fiscal_year.required'),
            'fiscal_year.integer'  =>  __('validation.custom.fiscalYear.fiscal_year.integer'),
            'fiscal_year.unique'   => __('validation.custom.fiscalYear.fiscal_year.unique'),
            'start_date.required'  => __('validation.custom.fiscalYear.start_date.required'),
            'start_date.date'      => __('validation.custom.fiscalYear.start_date.date'),
            'start_date.before'    => __('validation.custom.fiscalYear.start_date.before'),
            'end_date.required'    => __('validation.custom.fiscalYear.end_date.required'),
            'end_date.date'        => __('validation.custom.fiscalYear.end_date.date'),
            'end_date.after'       => __('validation.custom.fiscalYear.end_date.after'),
            'note.string'          => __('validation.custom.fiscalYear.note.string')
        ];
    }
}
