<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Breadcrumbs
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom breadcrumbs.
    |
    */

    'custom' => [
        'configuration' => [
            'parentInfo' => 'Settings',
            'childInfo' => [
                'basicInfo' => 'Basic Information',
                'additionalInfo' => 'Additional Information',
                'contactInfo' => 'Contact Information',
                "smsSendPermission" => "SMS Send Permission"
            ]
        ],

        'user' => [
            'parentInfo' => 'User Management',
            'childInfo' => [
                'index' => 'Users',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details',
                'profile' => 'Profile'
            ]
        ],

        'role' => [
            'childInfo' => [
                'index' => 'Roles',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details'
            ]
        ],

        'sms' => [
            'parentInfo' => 'SMS',
            'childInfo' => [
                'index' => 'Dashboard',
                'smsPurchase' => 'SMS Purchase',
                'smsLog' => 'SMS Log',
                'smsPurchaseReport' => 'SMS Purchase Report',
                'smsTemplate' => 'SMS Template',
                'addSMSTemplate' => 'Add SMS Template',
                'editSMSTemplate' => 'Edit SMS Template'
            ]
        ],

        'branch' => [
            'parentInfo' => 'Branch',
            'childInfo' => [
                'index' => 'Branches',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details'
            ]
        ],

        'employee' => [
            'parentInfo' => 'Employee',
            'childInfo' => [
                'index' => 'Employees',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details'
            ]
        ],

        'jobPosition' => [
            'childInfo' => 'Job Positions'
        ],

        'department' => [
            'parentInfo' => 'Department',
            'childInfo' => [
                'index' => 'Departments',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details'
            ]
        ],

        'subscription' => [
            'parentInfo' => 'Subscription',
            'childInfo' => [
                'index' => 'Subscriptions',
                'edit' => 'Edit'
            ]
        ],

        'buyer' => [
            'parentInfo' => 'Buyer Management',
            'childInfo' => [
                'index' => 'Buyers',
                'create' => 'Add',
                'edit' => 'Edit',
                'show' => 'Details',
                'paymentDetails' => 'Payment Details'
            ]
        ],

        'sale' => [
            'parentInfo' => 'Order Management',
            'childInfo' => [
                'index' => 'Sales',
                'create' => 'Add',
                'edit' => 'Edit',
                'show' => 'Details'
            ],
            "other" => [
                'childInfo' => [
                    'index' => 'Other Sales',
                    'create' => 'Add',
                    'edit' => 'Edit'
                ]
            ]
        ],

        'brand' => [
            'parentInfo' => 'Brands',
            'childInfo' => [
                'index' => 'Brand List',
                'create' => 'Add',
                'edit' => 'Edit',
            ]
        ],

        'item' => [
            'parentInfo' => 'Items',
            'childInfo' => [
                'sale' => 'Sale',
                'purchase' => 'Purchase',
                'expense' => 'Expense',
            ]
        ],

        'expense' => [
            'parentInfo' => 'Expense Management',
            'childInfo' => [
                'index' => 'Expenses',
                'create' => 'Add',
                'edit' => 'Edit',
            ]
        ],

        'report' => [
            'parentInfo' => 'Reports',
            'childInfo' => [
                'atAGlance' => 'At a Glance',
                'buyerDue'  => 'Buyer Due',
                'sale' => 'Sale'
            ]
        ],

        'fiscalYear' => [
            'parentInfo' => 'Fiscal Year',
            'childInfo' => [
                'index' => 'Fiscal Years',
                'create' => 'Add',
                'edit' => 'Edit',
                'show' => 'Details'
            ]
        ],

        'supplier' => [
            'parentInfo' => 'Supplier Management',
            'childInfo' => [
                'index' => 'Suppliers',
                'create' => 'Add',
                'edit' => 'Edit',
            ]
        ],

        'purchase' => [
            'parentInfo' => 'Purchase Management',
            'childInfo' => [
                'index' => 'Purchases',
                'create' => 'Add',
                'edit' => 'Edit',
                'show' => 'Details'
            ],
            'other' => [
                'childInfo' => [
                    'create' => 'Add',
                    'edit' => 'Edit'
                ]
            ]
        ],
    ]
];
