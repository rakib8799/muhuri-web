<?php

namespace Database\Seeders;

use App\Constants\Constants;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        $user = User::factory()->create([
            'name' => Constants::ROLE_ADMIN,
            'email' => '01673628369' . '@' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN,
            'mobile_number' => '01673628369',
            'password' => Hash::make('12345'),
        ]);
        $user->assignRole(Constants::ROLE_SUPER_ADMIN);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
