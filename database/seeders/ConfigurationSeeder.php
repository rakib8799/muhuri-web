<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('configurations')->truncate();

        $configurations = [
            [
                'option_name' => 'name',
                'option_value' => ''
            ],
            [
                'option_name' => 'name_bn',
                'option_value' => ''
            ],
            [
                'option_name' => 'category',
                'option_value' => ''
            ],
            [
                'option_name' => 'workspace',
                'option_value' => ''
            ],
            [
                'option_name' => 'workspace_domain',
                'option_value' => ''
            ],
            [
                'option_name' => 'email',
                'option_value' => ''
            ],
            [
                'option_name' => 'mobile_number',
                'option_value' => ''
            ],
            [
                'option_name' => 'additional_mobile_number',
                'option_value' => ''
            ],
            [
                'option_name' => 'address',
                'option_value' => ''
            ],
            [
                'option_name' => 'trade_license_number',
                'option_value' => ''
            ],
            [
                'option_name' => 'country_id',
                'option_value' => 18
            ],
            [
                'option_name' => 'support_email',
                'option_value' => 'support@muhuri.app'
            ],
            [
                'option_name' => 'support_telephone',
                'option_value' => '+8801673628369'
            ],
            [
                'option_name' => 'sms_service_base_url',
                'option_value' => 'https://bulksmsbd.net/api/smsapi',
            ],
            [
                'option_name' => 'sms_service_api_key',
                'option_value' => '0FkewrJVzaCvpQtpFhKM',
            ],
            [
                'option_name' => 'sms_service_sender_id',
                'option_value' => '8809617611758',
            ],
            [
                'option_name' => 'otp_expiry_time_minutes',
                'option_value' => 2
            ],
            [
                'option_name' => 'sms_rate',
                'option_value' => 2
            ],
            [
                'option_name' => 'is_sending_sms',
                'option_value' => true
            ]
        ];

        foreach ($configurations as $configuration) {
            Configuration::create($configuration);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
