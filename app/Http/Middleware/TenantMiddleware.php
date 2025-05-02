<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Services\Tenant\TenantService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TenantMiddleware
{
    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function handle($request, Closure $next)
    {
        $domain = $this->getDomain($request);
        \Log::info('Domain: ' . $domain);
        $response = $this->tenantService->getTenantByDomain($domain);
        $tenant = json_decode($response, true);
        if ($response->getStatusCode() != Response::HTTP_OK  || !$tenant || ($tenant['tenant_db'] ?? null) === null) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Tenant not found');
        }

        $tenant = json_decode($response, true);
        \Log::info('Tenant:', ['tenant' => $tenant]);
        $this->tenantService->setDatabaseConnection($tenant['tenant_db']);
        return $next($request);
    }

    private function getDomain($request): string
    {
        $origin = $request->headers->get('ORIGIN');
        $parsedUrl = parse_url($origin);
        $domain = $parsedUrl['host'] ?? '';
        if ($domain !== null && $domain !== '') {
            return $domain;
        }
        return $request->getHost();
    }

}
