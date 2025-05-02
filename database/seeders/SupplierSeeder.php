<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('suppliers')->truncate();
        DB::table('suppliers')->insert([
            [
                'id' => 1,
                'name' => 'বাবুল ট্রেডার্স',
                'mobile_number' => '01712813113',
                'previous_due' => '0.00',
                'division_id' => 3,
                'district_id' => 27,
                'upazila_id' => 206,
                'union_id' => null,
                'created_by' => 3,
                'updated_by' => 3,
                'is_active' => 1,
                'is_editable' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'আলভী ফিস',
                'mobile_number' => '01711161322',
                'previous_due' => '32400.00',
                'division_id' => 3,
                'district_id' => 27,
                'upazila_id' => 211,
                'union_id' => null,
                'created_by' => 3,
                'updated_by' => 3,
                'is_active' => 1,
                'is_editable' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'নগদ ক্রয়',
                'mobile_number' => '01711161322',
                'previous_due' => '0',
                'division_id' => null,
                'district_id' => null,
                'upazila_id' => null,
                'union_id' => NULL,
                'created_by' => 3,
                'updated_by' => 3,
                'is_active' => 1,
                'is_editable' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'নগদ ক্রয়',
                'mobile_number' => '01711161322',
                'previous_due' => '0',
                'division_id' => null,
                'district_id' => null,
                'upazila_id' => null,
                'union_id' => NULL,
                'created_by' => 4,
                'updated_by' => 4,
                'is_active' => 1,
                'is_editable' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
