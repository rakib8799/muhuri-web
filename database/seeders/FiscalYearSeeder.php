<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FiscalYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fiscal_years')->truncate();
        DB::table('fiscal_years')->insert([
            [
                'fiscal_year' => 2022,
                'start_date' => '2022-01-01',
                'end_date' => '2022-12-31',
                'status' => 'end',
                'is_active' => 1,
                'central_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fiscal_year' => 2023,
                'start_date' => '2023-01-01',
                'end_date' => '2024-01-21',
                'status' => 'end',
                'is_active' => 1,
                'central_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fiscal_year' => 2024,
                'start_date' => '2024-01-22',
                'end_date' => '2025-01-15',
                'status' => 'end',
                'is_active' => 1,
                'central_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fiscal_year' => 2025,
                'start_date' => '2025-01-16',
                'end_date' => '2025-12-31',
                'status' => 'running',
                'is_active' => 1,
                'central_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
