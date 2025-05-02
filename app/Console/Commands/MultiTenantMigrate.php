<?php

namespace App\Console\Commands;

use App\Services\Tenant\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


# php artisan multi-tenant:migrate

class MultiTenantMigrate extends Command
{
    protected $signature = 'multi-tenant:migrate';
    protected $description = 'Run migrations for all tenants from the tenant service';

    private TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        parent::__construct();
        $this->tenantService = $tenantService;
    }

    public function handle(): void
    {
        $this->call('migrate', ['--force' => true]);
        $this->info("Migration completed default DB!\n");
        $tenants = $this->tenantService->getAllTenants();
        foreach ($tenants as $tenant) {
            $this->info("Migrating for tenant: {$tenant['db_name']}");
            $this->tenantService->setDatabaseConnection($tenant);
            DB::purge($tenant['db_name']);
            DB::reconnect($tenant['db_name']);
            $this->call('migrate', ['--force' => true]);
            $this->info("Migration completed for tenant: {$tenant['db_name']}\n");
        }
        $this->info('All migrations completed.');
    }
}
