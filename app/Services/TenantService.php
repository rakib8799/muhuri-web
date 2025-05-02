<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class TenantService
{
    private mixed $tenantBaseUrl;

    public function __construct()
    {
        $this->tenantBaseUrl = env('TENANT_API_URL');
    }

    public function sendHttpRequest(string $method, string $url, array $data = []): array
    {
        $response = Http::withHeaders(['Accept' => 'application/json',])->$method($url, $data);
        return json_decode($response, true);
    }

    public function getTenantByDomain(string $domain): array
    {
        $apiUrl = $this->tenantBaseUrl . '/api/v1/tenant-databases/' . $domain;
        $data = $this->sendHttpRequest('get', $apiUrl);
        return $data['tenant_db'];
    }

    public function getAllTenants(): array
    {
        $apiUrl = $this->tenantBaseUrl . '/api/v1/tenants';
        $data = $this->sendHttpRequest('get', $apiUrl);
        $tenants = [];
        foreach ($data as $tenant) {
            $tenantDb = $tenant['tenant_db'];
            $tenants[] = $tenantDb;
        }
        return $tenants;
    }

    public function setDatabaseConnection($tenant): void
    {
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => $tenant['db_host'],
            'database' => $tenant['db_name'],
            'username' => $tenant['db_username'],
            'password' => $tenant['db_password'],
            'port' => $tenant['db_port'],
        ]);
        Config::set('database.default', 'tenant');
    }
}
