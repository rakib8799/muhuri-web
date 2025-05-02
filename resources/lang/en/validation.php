<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'numeric' => 'The :attribute must not be greater than :max.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'string' => 'The :attribute must not be greater than :max characters.',
        'array' => 'The :attribute must not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],

        'configuration' => [
            'name' => [
                'string' => 'Company name must be a string'
            ],
            'name_bn' => [
                'string' => 'Company\'s bangla name must be a string'
            ],
            'category' => [
                'string' => 'Company category must be a string'
            ],
            'trade_license_number' => [
                'numeric' => 'Trade license number must be a number'
            ],
            'industry' => [
                'string' => 'Industry name must be a string'
            ],
            'other_language_id' => [
                'exists' => 'Selected language does not exist'
            ],
            'timezone_id' => [
                'exists' => 'Selected timezone does not exist'
            ],
            'date_format' => [
                'string' => 'Date format must be a string',
                'in' => 'Selected date format is invalid'
            ],
            'is_roster' => [
                'boolean' => 'Roster field must be boolean'
            ],
            'week_start_day' => [
                'string' => 'Week start day must be a string',
                'in' => 'Selected week start day is invalid'
            ],
            'weekends' => [
                'array' => 'Weekends must be an array'
            ],
            'lunch_break_time' => [
                'numeric' => 'Lunch break time must be a number'
            ],
            'website' => [
                'url' => 'Please provide a valid URL'
            ],
            'organization_number' => [
                'numeric' => 'Organization number must be a number',
                'unique' => 'Organization number has already been taken'
            ],
            'email' => [
                'email' => 'Please provide a valid email',
                'unique' => 'Email has already been taken'
            ],
            'admin_email' => [
                'email' => 'Please provide a valid email',
                'unique' => 'Admin email has already been taken'
            ],
            'telephone' => [
                'string' => 'Telephone number must be a string'
            ],
            'country_id' => [
                'exists' => 'Selected country does not exist'
            ],
            'address' => [
                'string' => 'Address must be a string'
            ],
            'mobile_number' => [
                'string' => 'Mobile number must be a string'
            ],
            'additional_mobile_number' => [
                'string' => 'Additional mobile number must be a string'
            ],
        ],

        'user' => [
            'name' => [
                'required' => 'Name is required'
            ],
            'email' => [
                'required' => 'Email is required',
                'email' => 'Please provide a valid email',
                'unique' => 'Email has already been taken',
            ],
            'mobile_number' => [
                'required' => 'Mobile number is required',
                'unique' => 'Mobile number has already been taken',
                'regex' => 'Please enter a valid mobile number',
                'exists' => 'The mobile number does not match our records'
            ],
            'current_password' => [
                'required' => 'Current password is required',
                'current_password' => 'Current password is incorrect'
            ],
            'password' => [
                'required' => 'Password is required',
                'min' => 'Password must be at least 8 characters'
            ],
            'password_confirmation' => [
                'required' => 'Password confirmation is required',
                'same' => 'Password confirmation does not match'
            ],
            'token' => [
                'required' => 'Token is required'
            ]
        ],

        'role' => [
            'name' => [
                'required' => 'Name is required',
                'unique' => 'Name already exists'
            ],
            'permission_ids' => [
                'array' => 'Permissions must be an array'
            ],
            'permission_id' => [
                'required' => 'Permission is required',
                'exists' => 'Selected permission does not exist'
            ],
            'user_id' => [
                'required' => 'User is required',
                'exists' => 'Selected user does not exist'
            ],
            'role_id' => [
                'required' => 'Role is required',
                'exists' => 'Selected role does not exist'
            ]
        ],

        'employeeManagement' => [
            'employeeDocument' => [
                'name' => [
                    'required' => 'Name is required'
                ],
                'file_path' => [
                    'required' => 'File path is required'
                ],
                'employee_id' => [
                    'required' => 'Employee is required',
                    'exists' => 'Selected employee does not exist'
                ],
                'source' => [
                    'required' => 'Source is required',
                    'in' => 'Source must be one of: employee, office'
                ],
                'document_type_id' => [
                    'required' => 'Document type is required',
                    'exists' => 'Selected document does not exist'
                ]
            ],
            'employee' => [
                'first_name' => [
                    'required' => 'First name is required',
                    'string' => 'First name must be a string'
                ],
                'last_name' => [
                    'required' => 'Last name is required',
                    'string' => 'Last name must be a string'
                ],
                'email' => [
                    'required' => 'Email is required',
                    'email' => 'Please provide a valid email',
                    'unique' => 'Email has already been taken',
                ],
                'staff_id' => [
                    'required' => 'Staff ID is required',
                    'unique' => 'Staff ID has already been taken'
                ],
                'social_security_number' => [
                    'string' => 'Social security number must be a string'
                ],
                'mobile_number' => [
                    'required' => 'Mobile number is required',
                    'string' => 'Mobile number must be a string',
                    'size' => 'Mobile number must be 11 digit',
                    'regex' => 'Mobile number contains only digits'
                ],
                'dob' => [
                    'required' => 'Date of birth is required',
                    'date' => 'Date of birth must be a valid date'
                ],
                'blood_group' => [
                    'string' => 'Blood group must be a string',
                    'in' => 'Blood group must be one of: A+,A-,B+,B-,AB+,AB-,O+,O-'
                ],
                'gender' => [
                    'string' => 'Gender must be a string',
                    'in' => 'Gender must be one of: male,female,others'
                ],
                'marital_status' => [
                    'string' => 'Marital status must be a string',
                    'in' => 'Marital status must be one of: single,married,legal-cohabitant,widower,divorced'
                ],
                'additionalInfo' => [
                    'user_id' => [
                        'exists' => 'Selected user does not exist'
                    ],
                    'permanent_address' => [
                        'string' => 'Permanent address must be a string'
                    ],
                    'country_id' => [
                        'exists' => 'Selected country does not exist'
                    ],
                    'emergency_contact_name' => [
                        'string' => 'Emergency contact name must be a string'
                    ],
                    'emergency_contact_relation' => [
                        'string' => 'Emergency contact relation must be a string'
                    ],
                    'emergency_contact_number' => [
                        'string' => 'Emergency contact number must be a string'
                    ]
                ],
                'workInfo' => [
                    'job_title' => [
                        'string' => 'Job title must be a string'
                    ],
                    'job_positions' => [
                        'required' => 'Job positions are required',
                        'exists' => 'Selected job positions do not exist'
                    ],
                    'department_id' => [
                        'exists' => 'Selected department does not exist'
                    ],
                    'branch_id' => [
                        'required' => 'Branch is required',
                        'exists' => 'Selected branch does not exist'
                    ],
                    'manager_id' => [
                        'exists' => 'Selected manager does not exist'
                    ],
                    'leave_manager_id' => [
                        'exists' => 'Selected leave manager does not exist'
                    ],
                    'salary' => [
                        'numeric' => 'Salary must be a number'
                    ],
                    'hourly_rate' => [
                        'numeric' => 'Hourly rate must be a number'
                    ],
                    'hour_limit_weekly' => [
                        'integer' => 'Weekly hour limit must be an integer'
                    ],
                    'over_time_rate' => [
                        'numeric' => 'Overtime rate must be a number'
                    ],
                    'over_time_limit' => [
                        'integer' => 'Overtime limit must be an integer'
                    ],
                    'joining_date' => [
                        'required' => 'Joining date is required',
                        'date' => 'Joining date must be a valid date'
                    ],
                    'linkedin_profile' => [
                        'url' => 'Linkedin profile must be a valid URL'
                    ],
                    'etin' => [
                        'numeric' => 'E-tin number must be a number'
                    ],
                    'passport_number' => [
                        'string' => 'Passport number must be a string'
                    ],
                    'visa_number' => [
                        'string' => 'Visa number must be a string'
                    ],
                    'visa_expire_date' => [
                        'date' => 'Visa expiry date must be a valid date'
                    ],
                    'work_permit_number' => [
                        'numeric' => 'Work permit number must be a number'
                    ],
                    'work_permit_expiration_date' => [
                        'date' => 'Work permit expiration date must be a valid date'
                    ],
                    'departure_date' => [
                        'date' => 'Departure date must be a valid date'
                    ],
                    'departure_reason' => [
                        'string' => 'Departure reason must be a string'
                    ],
                    'is_nfc_card_issued' => [
                        'boolean' => 'NFC card issued must be boolean'
                    ],
                    'is_geo_fencing_enabled' => [
                        'boolean' => 'Geo fencing enabled must be boolean'
                    ],
                    'is_photo_taking_enabled' => [
                        'boolean' => 'Photo taking enabled must be boolean'
                    ]
                ]
            ],
            'employeeDeparture' => [
                'departure_reason_id' => [
                    'exists' => 'Selected departure reason does not exist'
                ],
                'departure_description' => [
                    'string' => 'Departure description must be a string'
                ],
                'departure_date' => [
                    'date' => 'Departure date must be a valid date'
                ]
            ],
        ],

        'jobPosition' => [
            'name' => [
                'required' => 'Name is required',
                'unique' => 'Name already exists'
            ]
        ],

        'department' => [
            'name' => [
                'required' => 'Name is required',
                'unique' => 'Name already exists'
            ],
            'parent_id' => [
                'exists' => 'Selected parent department does not exist'
            ]
        ],

        'subscription' => [
            'subscription_plan_id' => [
                'required' => 'Subscription plan ID is required',
                'exists' => 'Selected subscription plan does not exist'
            ]
        ],

        'buyer' => [
            'name' => [
                'required' => 'Name is required',
                'string' => 'Name must be a string',
                'max' => 'Name must be less than 255 characters'
            ],
            'mobile_number' => [
                'required' => 'Mobile number is required',
                'unique' => 'Mobile number has already been taken',
                'numeric' => 'Mobile number must be a number',
                'min' => 'Mobile number must be at least 11 digits'
            ],
            'previous_due' => [
                'required' => 'Previous due is required',
                'numeric' => 'Previous due must be a number'
            ],
            'division_id' => [
                'exists' => 'Division must be a valid division'
            ],
            'district_id' => [
                'exists' => 'District must be a valid district'
            ],
            'upazila_id' => [
                'exists' => 'Upazila must be a valid district'
            ],
            'union_id' => [
                'exists' => 'Union must be a valid district'
            ],
            'village' => [
                'string' => 'Village must be a string'
            ],
            'note' => [
                'string' => 'Note must be a string'
            ]
        ],

        'payment' => [
            'amount' =>[
                'required' => 'Amount is required',
                'numeric' => 'Amount must be a number',
                'gt' => 'Amount must be greater than 0'
            ],
            'payment_date' => [
                'required' => 'Payment date is required',
                'date' => 'Payment date must be a valid date'
            ],
            'payment_method' => [
                'required' => 'Payment method is required',
                'in' => 'Payment method must be one of: cash,bank,bkash,nagad,rocket,other'
            ]
        ],

        'sale' => [
            'buyerId' =>[
                'required' => 'Buyer is required.',
                'exists' => 'Buyer does not exist.',
            ],
            'brandId' => [
                'required' => 'Brand is required.',
                'exists' => 'Brand does not exist.',
            ],
            'saleDate' => [
                'required' => 'Sale date is required',
                'date' => 'Sale date must be a valid date'
            ],
            'items' => [
                'required' => 'At least one item is required.',
                'array' => 'Items must be an array.',
            ],
            'itemsId' => [
                'required' => 'Item is required.',
                'exists' => 'Selected item does not exist.',
            ],
            'totalBox' => [
                'numeric' => 'Box  must be a valid number.',
                'min' => 'Box cannot be negative.',
            ],
            'totalPoly' => [
                'required' => 'Poly is required.',
                'numeric' => 'Poly must be a valid number.',
                'min' => 'Poly cannot be negative.',
            ],
            'mir' => [
                'required' => 'Mir is required.',
                'numeric' => 'Mir must be a valid number.',
                'min' => 'Mir cannot be negative.',
            ],
            'quantity' => [
                'required' => 'Quantity is required.',
                'numeric' => 'Quantity must be a valid number.',
                'min' => 'Quantity cannot be negative.',
            ],
            'unitPrice' => [
                'required' => 'Unit Price is required.',
                'numeric' => 'Unit Price must be a valid number.',
                'min' => 'Unit Price cannot be negative.',
            ],
            'totalPrice' => [
                'required' => 'Total Price is required.',
                'numeric' => 'Total Price must be a valid number.',
                'min' => 'Total Price cannot be negative.',
            ],
            'paidAmount' => [
                'numeric' => 'Paid amount must be a valid number.',
                'min' => 'Paid amount cannot be negative.',
            ],
            'discount' => [
                'numeric' => 'Discount amount must be a valid number.',
                'min' => 'Discount amount cannot be negative.',
            ],
            'saleType' => [
                'required' => 'Sale type is required.',
                'string' => 'Sale type must be a string',
                'in' => 'Selected sale type is invalid'
            ],
            'note' => [
                'string' => 'Note must be a string'
            ]
        ],

        'purchase' => [
            'supplierId' =>[
                'required' => 'Supplier is required.',
                'exists' => 'Supplier does not exist.',
            ],
            'brandId' => [
                'required' => 'Brand is required.',
                'exists' => 'Brand does not exist.',
            ],
            'purchaseDate' => [
                'required' => 'Purchase date is required',
                'date' => 'Purchase date must be a valid date'
            ],
            'items' => [
                'required' => 'At least one item is required.',
                'array' => 'Items must be an array.',
            ],
            'itemsId' => [
                'required' => 'Item is required.',
                'exists' => 'Selected item does not exist.',
            ],
            'totalBox' => [
                'numeric' => 'Box  must be a valid number.',
                'min' => 'Box cannot be negative.',
            ],
            'totalPoly' => [
                'required' => 'Poly is required.',
                'numeric' => 'Poly must be a valid number.',
                'min' => 'Poly cannot be negative.',
            ],
            'mir' => [
                'required' => 'Mir is required.',
                'numeric' => 'Mir must be a valid number.',
                'min' => 'Mir cannot be negative.',
            ],
            'quantity' => [
                'required' => 'Quantity is required.',
                'numeric' => 'Quantity must be a valid number.',
                'min' => 'Quantity cannot be negative.',
            ],
            'unitPrice' => [
                'required' => 'Unit Price is required.',
                'numeric' => 'Unit Price must be a valid number.',
                'min' => 'Unit Price cannot be negative.',
            ],
            'totalPrice' => [
                'required' => 'Total Price is required.',
                'numeric' => 'Total Price must be a valid number.',
                'min' => 'Total Price cannot be negative.',
            ],
            'paidAmount' => [
                'numeric' => 'Paid amount must be a valid number.',
                'min' => 'Paid amount cannot be negative.',
            ],
            'discount' => [
                'numeric' => 'Discount amount must be a valid number.',
                'min' => 'Discount amount cannot be negative.',
            ],
            'purchaseType' => [
                'required' => 'Purchase type is required.',
                'string' => 'Purchase type must be a string',
                'in' => 'Selected purchase type is invalid'
            ],
            'note' => [
                'string' => 'Note must be a string'
            ]
        ],

        'brand' => [
            'name' =>[
                'required' => 'Brand is required',
                'string' => 'Brand must be a string',
                'max' => 'Brand must be within 255 characters'
            ],
        ],

        'expense' => [
            'type' =>[
                'required' => 'Type is required',
                'string' => 'Type must be a string'
            ],
            'name' =>[
                'required' => 'Name is required',
                'string' => 'Name must be a string'
            ],
            'slug' =>[
                'required' => 'Slug is required',
                'unique' => 'Slug is already taken'
            ]
        ],

        'fiscalYear' => [
            'fiscalYear' => [
                'required' => 'Fiscal year is required.',
                'integer'  => 'Fiscal year must be an integer.',
                'unique'   => 'Fiscal year already exists.',
            ],
            'startDate' => [
                'required' => 'Start date is required.',
                'date'     => 'Start date must be a valid date.',
                'before'   => 'Start date must be before the end date.',
            ],
            'endDate' => [
                'required' => 'End date is required.',
                'date'     => 'End date must be a valid date.',
                'after'    => 'End date must be after the start date.',
            ],
            'note' => [
                'string' => 'Note must be a string.',
            ]
        ],

        'commonComponents' => [
            'addressComponent' => [
                'address_line_one' => [
                    'string' => 'Address line one must be a string'
                ],
                'address_line_two' => [
                    'string' => 'Address line two must be a string'
                ],
                'floor' => [
                    'string' => 'Floor\'s number must be a string'
                ],
                'city' => [
                    'string' => 'City\'s name must be a string'
                ],
                'state' => [
                    'string' => 'State\'s name must be a string'
                ],
                'zip_code' => [
                    'string' => 'Zip code must be a string'
                ]
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
    'name_required' => 'Name is required',
    'email_required' => 'Email is required',
    'domain_required' => 'Domain is required',
    'workspace_required' => 'Workspace is required',
    'password_required' => 'Password is required',
    'password_confirmation_required' => 'Password confirmation is required',
    'tenant_id_required' => 'Tenant ID is required',
    'db_name_required' => 'Database name is required',
    'db_username_required' => 'Database username is required',
    'db_password_required' => 'Database password is required',
    'db_host_required' => 'Database host is required',
    'db_port_required' => 'Database port is required',
    'db_type_required' => 'Database type is required'

];
