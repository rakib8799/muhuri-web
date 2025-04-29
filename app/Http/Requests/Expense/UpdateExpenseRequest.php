<?php

namespace App\Http\Requests\Expense;

use App\Models\FiscalYear;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'item_id' => 'required|exists:items,id',
            'expense_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validateExpenseDate($attribute, $value, $fail);
                }
            ],
            'amount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string'
        ];
    }

    private function validateExpenseDate($attribute, $value, $fail)
    {
        $fiscalYear = FiscalYear::where('start_date', '<=', $value)
        ->where('end_date', '>=', $value)
        ->where('status', 'running')
        ->first();
        if (!$fiscalYear) {
            $fail(__('validation.custom.expense.expense_date.date'));
        }
    }

    public function messages(): array
    {
        return [
            'expense_date.required' => __('validation.custom.expense.expense_date.required'),
            'amount.numeric' => _('validation.custom.expense.amount.numeric'),
            'amount.min' => _('validation.custom.expense.amount.min'),
        ];
    }
}
