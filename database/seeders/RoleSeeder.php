<?php

namespace Database\Seeders;

use App\Constants\Constants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();

        $roles = [
            [
                'id' => 1,
                'name' => Constants::ROLE_SUPER_ADMIN,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
                'central_id' => 1,
            ],
            [
                'id' => 2,
                'name' => Constants::ROLE_ADMIN,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
                'central_id' => 2,
            ],
            [
                'id' => 3,
                'name' => Constants::ROLE_MANAGER,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
                'central_id' => 3,
            ],
            [
                'id' => 4,
                'name' => Constants::ROLE_MUHURI,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
                'central_id' => 4,
            ]
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }
        $superAdmin = Role::find(1);
        $superAdminPermissions = Permission::all();
        $superAdmin->syncPermissions($superAdminPermissions);

        $manager = Role::find(3);
        $managerPermissions = Permission::whereNotIn('id', [41, 42, 43, 44, 45])->get();
        $manager->syncPermissions($managerPermissions);

        $muhuri = Role::find(4);
        $muhuriPermissions = Permission::whereIn('id', [41, 42, 43, 44, 45, 65, 66, 69])->get();
        $muhuri->syncPermissions($muhuriPermissions);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
