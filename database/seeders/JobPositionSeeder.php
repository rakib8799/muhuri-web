<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('job_positions')->truncate();

        DB::table('job_positions')->insert([
            [
                'name' => 'মুহুরী',
                'slug' => 'muhuri',
                'is_active' => true,
                'is_editable' => true,
                'central_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ম্যানেজার',
                'slug' => 'manager',
                'is_active' => true,
                'is_editable' => true,
                'central_id' => 2,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
