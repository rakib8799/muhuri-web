<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'internal_server_error' => 'An error occurred on the server. Please try again later',
    'otp_sent' => 'Verification code has been sent',
    'otp_resent' => 'The verification code has been re-sent',
    'otp_sending_blocked' => 'Your number has been blocked for 5 minutes. Please wait',
    'user_inactive' => 'Your account has been deactivated. Please contact the administrator',
    'user_deleted' => 'Your information has been deleted',


    /*
    |--------------------------------------------------------------------------
    | Custom Message Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom messages.
    |
    */

    'custom' => [
        'configuration' => [
            'update' => [
                'basicInfo' => [
                    'success' => 'Company\'s basic information has been updated successfully',
                    'error' => 'Company\'s basic information could not be updated'
                ],
                'additionalInfo' => [
                    'success' => 'Company\'s additional information has been updated successfully',
                    'error' => 'Company\'s additional information could not be updated'
                ],
                'contactInfo' => [
                    'success' => 'Company\'s contact information has been updated successfully',
                    'error' => 'Company\'s contact information could not be updated'
                ]
            ]
        ],

        'user' => [
            'store' => [
                'success' => 'New user created successfully',
                'error' => 'New user could not be created'
            ],
            'update' => [
                'basic' => [
                    'success' => 'User\'s information updated successfully',
                    'error' => 'User could not be updated'
                ],
                'profile' => [
                    'success' => 'User\'s profile updated successfully',
                    'error' => 'User\'s profile could not be updated'
                ],
                'updateDetails' => [
                    'success' => 'Details of user updated successfully',
                    'error' => 'Details of user could not be updated'
                ],
                'updateEmail' => [
                    'success' => 'Email of user updated successfully',
                    'error' => 'Email of user could not be updated'
                ],
                'updateMobileNumber' => [
                    'success' => 'Mobile number of user updated successfully',
                    'error' => 'Mobile number of user could not be updated'
                ],
                'updateRoles' => [
                    'success' => 'Roles of user updated successfully',
                    'error' => 'Roles of user could not be updated'
                ],
                'updatePassword' => [
                    'success' => 'Password updated successfully',
                    'error' => 'Password could not be updated'
                ]
            ],
            'destroy' => [
                'success' => 'User deleted successfully',
                'error' => 'User could not be deleted'
            ],
            'changeStatus' => [
                'activate' => 'User is activated',
                'deactivate' => 'User is deactivated',
                'error' => 'User status could not be changed'
            ]
        ],

        'role' => [
            'store' => [
                'success' => 'New role created successfully',
                'error' => 'New role could not be created'
            ],
            'update' => [
                'success' => 'Role and permission updated successfully',
                'error' => 'Role and permission could not be updated'
            ],
            'destroy' => [
                'basic' => [
                    'success' => 'Role and permission deleted successfully',
                    'error' => 'Role and permission could not be deleted',
                ],
                'removeUserFromRole' => [
                    'success' => 'User removed from role successfully',
                    'error' => 'User removed from role could not be deleted'
                ]
            ],
            'changeStatus' => [
                'activate' => 'Role is activated',
                'deactivate' => 'Role is deactivated',
                'error' => 'Super admin status can not be changed'
            ]
        ],

        'sms' => [
            'template' => [
                'store' => [
                    'success' => 'SMS template created successfully',
                    'error' => 'SMS template could not be created'
                ],
                'destroy' => [
                    'success' => 'SMS template deleted successfully',
                    'error' => 'SMS template could not be deleted'
                ],
                'update' => [
                    'success' => 'SMS template updated successfully',
                    'error' => 'SMS template could not be updated'
                ],
                'changeStatus' => [
                    'activate' => 'SMS template is activated',
                    'deactivate' => 'SMS template is deactivated',
                    'error' => 'SMS template status could not be changed'
                ]
            ]
        ],

        'employeeManagement' => [
            'employee' => [
                'store' => [
                    'success' => 'New employee created successfully',
                    'error' => 'New employee could not be created'
                ],
                'destroy' => [
                    'success' => 'Employee deleted successfully',
                    'error' => 'Employee could not be deleted'
                ],
                'update' => [
                    'basicInfo' => [
                        'success' => 'Employee\'s basic information has been updated successfully',
                        'error' => 'Employee\'s basic information could not be updated'
                    ],
                    'additionalInfo' => [
                        'success' => 'Employee\'s additional information has been updated successfully',
                        'error' => 'Employee\'s additional information could not be updated'
                    ],
                    'workInfo' => [
                        'success' => 'Employee\'s work information has been updated successfully',
                        'error' => 'Employee\'s work information could not be updated'
                    ],
                    'departureInfo' => [
                        'success' => 'Employee departed successfully',
                        'error' => 'Employee could not be departed'
                    ],
                    'rejoinInfo' => [
                        'success' => 'Employee rejoined successfully',
                        'error' => 'Employee could not be rejoined'
                    ]
                ],
                'changeStatus' => [
                    'activate' => 'Employee is activated',
                    'deactivate' => 'Employee is deactivated',
                    'error' => 'Employee status could not be changed'
                ]
            ]
        ],

        'jobPosition' => [
            'store' => [
                'success' => 'New job position created successfully',
                'error' => 'New job position could not be created'
            ],
            'update' => [
                'success' => 'Job position updated successfully',
                'error' => 'Job position could not be updated'
            ],
            'destroy' => [
                'success' => 'Job position deleted successfully',
                'error' => 'Job position could not be deleted'
            ],
            'changeStatus' => [
                'activate' => 'Job position is activated',
                'deactivate' => 'Job position is deactivated',
                'error' => 'Job position status could not be changed'
            ]
        ],

        'department' => [
            'store' => [
                'success' => 'New department created successfully',
                'error' => 'New department could not be created'
            ],
            'update' => [
                'success' => 'Department updated successfully',
                'error' => 'Department could not be updated'
            ],
            'destroy' => [
                'success' => 'Department deleted successfully',
                'error' => 'Department could not be deleted'
            ],
            'changeStatus' => [
                'activate' => 'Department is activated',
                'deactivate' => 'Department is deactivated'
            ]
        ],

        'subscription' => [
            'update' => [
                'success' => 'Subscription updated successfully',
                'error' => 'Your current subscription plan is not available anymore'
            ],
            'renew' => [
                'success' => 'Subscription renewed successfully',
                'error' => 'Subscription could not be renewed',
            ]
        ],

        'buyer' => [
            'store' => [
                'success' => 'New buyer is created successfully',
                'error' => 'New buyer could not be created'
            ],
            'update' => [
                'success' => 'Buyer is updated successfully',
                'error' => 'Buyer could not be updated'
            ],
            'destroy' => [
                'success' => 'Buyer is deleted successfully',
                'error' => 'Buyer could not be deleted'
            ],
            'changeStatus' => [
                'activate' => 'Buyer is activated',
                'deactivate' => 'Buyer is deactivated',
                'error' => 'Buyer status could not be changed'
            ]
        ],

        'payment' => [
            'store' => [
                'success' => 'New Payment is created successfully',
                'error' => 'New Payment could not be created'
            ],
            'update' => [
                'success' => 'Payment is updated successfully',
                'error' => 'Payment could not be updated'
            ],
            'destroy' => [
                'success' => 'Payment is deleted successfully',
                'error' => 'Payment could not be deleted'
            ]
        ],

        'sale' => [
            'store' => [
                'success' => 'Sale created successfully',
                'error' => 'Sale could not be created'
            ],
            'update' => [
                'success' => 'Sale is updated successfully',
                'error' => 'Sale could not be updated'
            ],
            'destroy' => [
                'success' => 'Sale is deleted successfully',
                'error' => 'Sale could not be deleted'
            ],
            'otherStore' => [
                'success' => 'Other sale created successfully',
                'error' => 'Other sale could not be created'
            ],
            'otherUpdate' => [
                'success' => 'Other sale is updated successfully',
                'error' => 'Other sale could not be updated'
            ],
            'otherDestroy' => [
                'success' => 'Other sale is deleted successfully',
                'error' => 'Other sale could not be deleted'
            ]
        ],

        'purchase' => [
            'store' => [
                'success' => 'Purchase created successfully',
                'error' => 'Purchase could not be created'
            ],
            'update' => [
                'success' => 'Purchase is updated successfully',
                'error' => 'Purchase could not be updated'
            ],
            'destroy' => [
                'success' => 'Purchase is deleted successfully',
                'error' => 'Purchase could not be deleted'
            ],
            'otherStore' => [
                'success' => 'Purchase other created successfully',
                'error' => 'Purchase other could not be created'
            ],
            'otherUpdate' => [
                'success' => 'Purchase other is updated successfully',
                'error' => 'Purchase other could not be updated'
            ],
            'OtherDestroy' => [
                'success' => 'Purchase other is deleted successfully',
                'error' => 'Purchase other could not be deleted'
            ]
        ],

        'brand' => [
            'store' => [
                'success' => 'Brand is created successfully',
                'error' => 'Brand could not be created'
            ],
            'update' => [
                'success' => 'Brand is updated successfully',
                'error' => 'Brand could not be updated'
            ],
            'destroy' => [
                'success' => 'Brand is deleted successfully',
                'error' => 'Brand could not be deleted'
            ]
        ],

        'expense' => [
            'store' => [
                'success' => 'Expense is created successfully',
                'error' => 'Expense could not be created'
            ],
        ],

        'fiscalYear' => [
            'store' => [
                'success' => 'Fiscal Year created successfully',
                'error' => 'Fiscal Year could not be created'
            ],
            'update' => [
                'success' => 'Fiscal Year is updated successfully',
                'error' => 'Fiscal Year could not be updated'
            ],
            'changeStatus' => [
                'activate' => 'Fiscal year status is activated',
                'deactivate' => 'Fiscal year status is deactivated',
                'error' => 'Fiscal year status could not be changed'
            ],
            'closeFiscalYear' => [
                'success' => 'Fiscal year closed successfully',
                'error' => 'Fiscal year could not be closed'
            ],
            'startFiscalYear' => [
                'success' => 'Fiscal year started successfully',
                'error' => 'Fiscal year could not be started'
            ]
        ],

        'auth' => [
            'sessionExpired' => 'Session expired. Please login again.',
            'userNotFound' => 'User not found.',
            'otpExpired' => 'OTP is expired. Please try again...',
            'otpInvalid' => 'OTP is invalid. Please try again...'
        ]
    ]
];
