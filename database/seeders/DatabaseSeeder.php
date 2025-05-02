<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            ConfigurationSeeder::class,
            LanguageSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ItemSeeder::class,
            ItemUnitSeeder::class,
            BuyerSeeder::class,
            DivisionSeeder::class,
            DistrictSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,
            FiscalYearSeeder::class,
            BrandSeeder::class,
            SupplierSeeder::class,
            PaymentMethodSeeder::class,
            SmsTemplateSeeder::class,
            SubscriptionPlanSeeder::class,
            SubscriptionSeeder::class,
        ]);
    }
}


