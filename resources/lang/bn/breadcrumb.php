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
            'parentInfo' => 'সেটিংস',
            'childInfo' => [
                'basicInfo' => 'মৌলিক তথ্য',
                'additionalInfo' => 'অতিরিক্ত তথ্য',
                'contactInfo' => 'যোগাযোগের তথ্য',
                "smsSendPermission" => "এসএমএস পাঠানোর অনুমতি"
            ]
        ],

        'user' => [
            'parentInfo' => 'ইউজার ম্যানেজমেন্ট',
            'childInfo' => [
                'index' => 'ইউজার',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'বিস্তারিত',
                'profile' => 'প্রোফাইল'
            ]
        ],

        'role' => [
            'childInfo' => [
                'index' => 'রোল',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'বিস্তারিত'
            ]
        ],
        'sms' => [
            'parentInfo' => 'এসএমএস',
            'childInfo' => [
                'index' => 'ড্যাশবোর্ড',
                'smsPurchase' => 'এসএমএস ক্রয়',
                'smsLog' => 'এসএমএস লগ',
                'smsPurchaseReport' => 'এসএমএস ক্রয় রিপোর্ট',
                'smsTemplate' => 'এসএমএস টেমপ্লেট',
                'addSMSTemplate' => 'নতুন এসএমএস টেমপ্লেট',
                'editSMSTemplate' => 'এডিট এসএমএস টেমপ্লেট'
            ]
        ],

        'employee' => [
            'parentInfo' => 'কর্মচারী',
            'childInfo' => [
                'index' => 'কর্মচারীসমূহ',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'বিস্তারিত'
            ]
        ],

        'jobPosition' => [
            'childInfo' => 'চাকুরীর পদসমূহ'
        ],

        'department' => [
            'parentInfo' => 'ডিপার্টমেন্ট',
            'childInfo' => [
                'index' => 'ডিপার্টমেন্টসমূহ',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'বিস্তারিত'
            ]
        ],

        'subscription' => [
            'parentInfo' => 'সাবস্ক্রিপশন',
            'childInfo' => [
                'index' => 'সাবস্ক্রিপশনসমূহ',
                'edit' => 'এডিট'
            ]
        ],

        'buyer' => [
            'parentInfo' => 'ক্রেতা ম্যানেজমেন্ট',
            'childInfo' => [
                'index' => 'ক্রেতা',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'show' => 'বিস্তারিত',
                'paymentDetails' => 'পেমেন্ট বিস্তারিত'
            ]
        ],

        'sale' => [
            'parentInfo' => 'অ‍‍‍র্ডার ম্যানেজমেন্ট',
            'childInfo' => [
                'index' => 'বিক্রয়',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'show' => 'বিস্তারিত'
            ],
            "other" => [
                'childInfo' => [
                    'index' => 'অন্যান্য বিক্রয়',
                    "create" => "নতুন",
                    'edit' => 'এডিট'
                ],
            ]
        ],

        'brand' => [
            'parentInfo' => 'ব্র্যান্ডসমূহ',
            'childInfo' => [
                'index' => 'ব্র্যান্ডের তালিকা',
                'create' => 'নতুন',
                'edit' => 'এডিট',
            ]
        ],

        'item' => [
            'parentInfo' => 'আইটেম',
            'childInfo' => [
                'sale' => 'বিক্রয়',
                'purchase' => 'ক্রয়',
                'expense' => 'খরচ',
            ]
        ],

        'expense' => [
            'parentInfo' => 'নগদ খরচ ম্যানেজমেন্ট',
            'childInfo' => [
                'index' => 'খরচসমূহ',
                'create' => 'নতুন',
                'edit' => 'এডিট',
            ]
        ],

        'report' => [
            'parentInfo' => 'রিপো‍র্ট',
            'childInfo' => [
                'atAGlance' => 'এক নজরে',
                'buyerDue'  => 'পাওনা',
                'sale' => 'বিক্রয়'
            ]
        ],

        'fiscalYear' => [
            'parentInfo' => 'অ‍র্থ বছর',
            'childInfo' => [
                'index' => 'অ‍র্থ বছরের তালিকা',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'show' => 'বিস্তারিত'
            ]
        ],

        'supplier' => [
            'parentInfo' => 'সরবরাহকারী ব্যবস্থাপনা',
            'childInfo' => [
                'index' => 'সরবরাহকারীগণ',
                'create' => 'নতুন',
                'edit' => 'এডিট',
            ]
        ],

        'purchase' => [
            'parentInfo' => 'ক্রয় ম্যানেজমেন্ট',
            'childInfo' => [
                'index' => 'ক্রয়',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'show' => 'বিস্তারিত'
            ],
            'other' => [
                'childInfo' => [
                    'create' => 'নতুন',
                    'edit' => 'এডিট'
                ]
            ]
        ],

    ]
];
