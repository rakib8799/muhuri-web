<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('buyers')->truncate();
        DB::table('buyers')->insert([
            [
                'id' => 1,
                'name' => 'নগদ বিক্রয়',
                'mobile_number' => '00000000000',
                'previous_due' => '0.00',
                'division_id' => null,
                'district_id' => null,
                'upazila_id' => null,
                'union_id' => null,
                'village' => null,
                'note' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'is_active' => 1,
                'is_editable' => 0,
                'is_default' => 1,
                'default_buyer_id' => 1,
                'created_at' => '2023-06-30 11:35:52',
                'updated_at' => '2023-06-30 11:35:52',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'মো: আহেদুজ্জামান ডালিম',
                'mobile_number' => '01717007029',
                'previous_due' => '0.00',
                'division_id' => null,
                'district_id' => null,
                'upazila_id' => null,
                'union_id' => null,
                'village' => null,
                'note' => null,
                'created_by' => 2,
                'updated_by' => 2,
                'is_active' => 1,
                'is_editable' => 0,
                'is_default' => 1,
                'default_buyer_id' => 1,
                'created_at' => '2023-06-30 11:35:52',
                'updated_at' => '2023-06-30 11:35:52',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'অ‍র্ণব কুমার ঘোষ',
                'mobile_number' => '01717582133',
                'previous_due' => '0.00',
                'division_id' => null,
                'district_id' => null,
                'upazila_id' => null,
                'union_id' => null,
                'village' => null,
                'note' => null,
                'created_by' => 2,
                'updated_by' => 2,
                'is_active' => 1,
                'is_editable' => 0,
                'is_default' => 1,
                'default_buyer_id' => 1,
                'created_at' => '2023-06-30 11:35:52',
                'updated_at' => '2023-06-30 11:35:52',
                'deleted_at' => null,
            ],

        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
