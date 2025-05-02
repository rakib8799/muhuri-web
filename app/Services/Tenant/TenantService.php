<?php

namespace App\Services\Tenant;

use App\Constants\Constants;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class TenantService
{
    private mixed $tenantBaseUrl;
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->tenantBaseUrl = env('TENANT_API_URL');
        $this->userService = $userService;
    }

    public function sendHttpRequest(string $method, string $url, array $data = [])
    {
        return Http::withHeaders(['Accept' => 'application/json',])->$method($url, $data);
//        var_dump($response);
//        return json_decode($response, true);
    }

    public function getTenantByDomain(string $domain)
    {
        $apiUrl = $this->tenantBaseUrl . '/api/v1/tenants/' . $domain;
        return $this->sendHttpRequest('get', $apiUrl);
    }

    public function getAllTenants(): array
    {
        $tenants = [];

        try {
            $apiUrl = $this->tenantBaseUrl . '/api/v1/tenants';
            $response = $this->sendHttpRequest('get', $apiUrl);
            // TODO: check if response is ok
            $data = json_decode($response, true);

            foreach ($data as $tenant) {
                $tenantDb = $tenant['tenant_db'];
                $tenants[] = $tenantDb;
            }
            return $tenants;
        }
        catch (\Exception $e){

        }
        return $tenants;

    }

    public function setDatabaseConnection($tenant): void
    {
        $dbConnectionString = "database.connections.". $tenant['db_name'];
        $connectionName = $tenant['db_name'];
        $connectionArray = [
            'driver' => 'mysql',
            'host' => $tenant['db_host'],
            'database' => $tenant['db_name'],
            'username' => $tenant['db_username'],
            'password' => $tenant['db_password'],
            'port' => $tenant['db_port'],
        ];
//        Config::set($dbConnectionString, $connectionArray);
//        Config::set('database.default', $tenant['db_name']);

        // Set the configuration for this specific connection
        Config::set("database.connections.{$connectionName}", $connectionArray);

        // Set the default database connection to the new tenant-specific connection
        Config::set('database.default', $connectionName);

        // Clear any cached connection and reconnect using the new configuration
        DB::purge($connectionName);    // Purge the previous connection
        DB::reconnect($connectionName); // Reconnect to the newly set connection
    }

    public function runMigrations(): void
    {
        Artisan::call('migrate', ['--force' => true]);
        \Log::info('Migrations run successfully');
    }

    public function runSeeders(): void
    {
        $seeders = [
            'CountrySeeder',
            'ConfigurationSeeder',
            'LanguageSeeder',
            'PermissionSeeder',
            'RoleSeeder',
            'ItemUnitSeeder',
            'DivisionSeeder',
            'DistrictSeeder',
            'UpazilaSeeder',
            'UnionSeeder',
            'SmsTemplateSeeder'
        ];

        foreach ($seeders as $seeder) {
            Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
        }
    }
    public function createAdminUser($validatedData)
    {
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['mobile_number_verified_at'] = now();
        $user = $this->userService->create($validatedData);
        $user->assignRole(Constants::ROLE_SUPER_ADMIN);
        return $user;
    }
}
