<?php

namespace Database\Seeders;

use App\Constants\Constants;
use App\Models\HR\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hr_employees')->truncate();

        $employees = [
            [
                'staff_id' => 'ND1001',
                'first_name' => 'Arnab Kumar',
                'last_name' => 'Ghosh',
                'email' => 'arnab@nonditosoft.com',
                'social_security_number' => '1001',
                'mobile_number' => '01681091177',
                'job_title' => 'CEO',
                'branch_id' => 1
            ],
            [
                'staff_id' => 'ND1002',
                'first_name' => 'Mukti Rani',
                'last_name' => 'Ghosh',
                'email' => 'mukti@nonditosoft.com',
                'social_security_number' => '1002',
                'mobile_number' => '01681091178',
                'job_title' => 'Senior Software Engineer',
                'branch_id' => 1
            ],
            [
                'staff_id' => 'ND1003',
                'first_name' => 'Rakibul',
                'last_name' => 'Islam',
                'email' => 'rakib.nonditosoft@gmail.com',
                'social_security_number' => '1003',
                'mobile_number' => '01681091176',
                'job_title' => 'Software Engineer-II',
                'branch_id' => 1
            ],
            [
                'staff_id' => 'ND1004',
                'first_name' => 'Tanvir',
                'last_name' => 'Hossain',
                'email' => 'tanvir.nonditosoft@gmail.com',
                'social_security_number' => '1004',
                'mobile_number' => '01681091171',
                'job_title' => 'Software Engineer-II',
                'branch_id' => 1
            ],
            [
                'staff_id' => 'ND1005',
                'first_name' => 'Rahul Dev',
                'last_name' => 'Paul',
                'email' => 'rahul.nonditosoft@gmail.com',
                'social_security_number' => '1005',
                'mobile_number' => '0168109112',
                'job_title' => 'Junior Software Engineer',
                'branch_id' => 1
            ],
            [
                'staff_id' => 'ND1006',
                'first_name' => 'Md. Rejwan',
                'last_name' => 'Ahmed',
                'email' => 'rejwan.nonditosoft@gmail.com',
                'social_security_number' => '1006',
                'mobile_number' => '01681091173',
                'job_title' => 'Junior Software Engineer',
                'branch_id' => 1
            ],
        ];

        foreach($employees as $employee)
        {
            $user = User::create([
                'name' => $employee['first_name'] . ' ' . $employee['last_name'],
                'email' => $employee['email'],
                'password' => Hash::make('12345'),
            ]);
            $user->assignRole(Constants::ROLE_EMPLOYEE);
            $employee['user_id'] = $user->id;
            $employee['country_id'] = 18;   // 18 = Bangladesh
            $employee['created_by'] = 1;
            $employee['updated_by'] = 1;
            $employeeObj = Employee::create($employee);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
