<?php

namespace App\Http\Requests\Configuration;

use App\Constants\Constants;
use App\Models\Country;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigurationRequest extends FormRequest
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
            'name' => 'nullable|string',
            'name_bn' => 'nullable|string',
            'category' => 'nullable|string',
            'trade_license_number' => 'nullable|numeric',
            'industry' => 'nullable|string',
            'organization_number' => 'nullable|numeric',
            'country_id' => [
                function ($attribute, $value, $fail) {
                    $this->validateCountry($attribute, $value, $fail);
                },
            ],
            'other_language_id' => [
                function ($attribute, $value, $fail) {
                    $this->validateLanguage($attribute, $value, $fail);
                },
            ],
            'timezone_id' => [
                function ($attribute, $value, $fail) {
                    $this->validateTimezone($attribute, $value, $fail);
                },
            ],
            'date_format' => 'nullable|string|in:YYYY-MM-DD,MM-DD-YYYY,DD-MM-YYYY',
            'is_roster' => 'nullable|boolean',
            'week_start_day' => 'nullable|string|in:'. implode(',', [Constants::SUNDAY, Constants::MONDAY, Constants::TUESDAY, Constants::WEDNESDAY, Constants::THURSDAY, Constants::FRIDAY, Constants::SATURDAY]),
            'weekends' => 'nullable|array',
            'lunch_break_time' => 'nullable|numeric',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'admin_email' => 'nullable|email',
            'telephone' => 'nullable|string',
            'mobile_number' => 'nullable|string',
            'additional_mobile_number' => 'nullable|string',
            'address' => 'nullable|string',
            'address_line_one' => 'nullable|string',
            'address_line_two' => 'nullable|string',
            'floor' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string'
        ];
    }

    private function validateCountry($attribute, $value, $fail)
    {
        $country = Country::find($value);
        if ($value!= NULL && !$country) {
            $fail(__('validation.custom.configuration.country_id.exists'));
        }
    }

    private function validateLanguage($attribute, $value, $fail)
    {
        $language = Language::find($value);
        if ($value!= NULL && !$language) {
            $fail(__('validation.custom.configuration.other_language_id.exists'));
        }
    }

    public function messages(): array
    {
        return [
            'name.string' => __('validation.custom.configuration.name.string'),
            'name_bn.string' => __('validation.custom.configuration.name_bn.string'),
            'category.string' => __('validation.custom.configuration.category.string'),
            'trade_license_number.numeric' => __('validation.custom.configuration.trade_license_number.numeric'),
            'industry.string' => __('validation.custom.configuration.industry.string'),
            'organization_number.numeric' => __('validation.custom.configuration.organization_number.numeric'),
            'date_format.string' => __('validation.custom.configuration.date_format.string'),
            'date_format.in' => __('validation.custom.configuration.date_format.in'),
            'is_roster.boolean' => __('validation.custom.configuration.is_roster.boolean'),
            'week_start_day.string' => __('validation.custom.configuration.week_start_day.string'),
            'week_start_day.in' => __('validation.custom.configuration.week_start_day.in'),
            'weekends.array' => __('validation.custom.configuration.weekends.array'),
            'lunch_break_time.numeric' => __('validation.custom.configuration.lunch_break_time.numeric'),
            'email.email' => __('validation.custom.configuration.email.email'),
            'admin_email.email' => __('validation.custom.configuration.admin_email.email'),
            'website.url' => __('validation.custom.configuration.website.url'),
            'telephone.string' => __('validation.custom.configuration.telephone.string'),
            'address.string' => __('validation.custom.configuration.address.string'),
            'mobile_number.string' => __('validation.custom.configuration.mobile_number.string'),
            'additional_mobile_number.string' => __('validation.custom.configuration.additional_mobile_number.string'),
            'address_line_one.string' => __('validation.custom.commonComponents.addressComponent.address_line_one.string'),
            'address_line_two.string' => __('validation.custom.commonComponents.addressComponent.address_line_two.string'),
            'floor.string' => __('validation.custom.commonComponents.addressComponent.floor.string'),
            'city.string' => __('validation.custom.commonComponents.addressComponent.city.string'),
            'state.string' => __('validation.custom.commonComponents.addressComponent.state.string'),
            'zip_code.string' => __('validation.custom.commonComponents.addressComponent.zip_code.string')
        ];
    }
}
