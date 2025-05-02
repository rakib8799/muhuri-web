<?php

namespace App\Http\Controllers\API\Tenant;

use App\Constants\Constants;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Tenant\TenantService;
use App\Http\Requests\API\Tenant\CreateUserRequest;

class TenantController extends Controller
{
    private TenantService $tenantService;
    private ConfigurationService $configurationService;
    public function __construct(TenantService $tenantService, ConfigurationService $configurationService)
    {
        $this->tenantService = $tenantService;
        $this->configurationService = $configurationService;
    }

    public function runMigrations(Request $request): JsonResponse
    {
        $this->tenantService->runMigrations();
        return response()->json([
            'message' => 'Migrations ran successfully'
        ]);
    }

    public function runSeeders(Request $request): JsonResponse
    {
        $this->tenantService->runSeeders();
        $requestedData = [
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'category' => $request->category,
            'workspace' => $request->workspace,
            'workspace_domain' => $request->workspace_domain,
            'mobile_number' => $request->mobile_number,
            'additional_mobile_number' => $request->additional_mobile_number,
            'address' => $request->address
        ];
        $this->configurationService->updateConfiguration($requestedData);
        return response()->json([
            'message' => 'Seeders ran successfully'
        ]);
    }

    public function createAdminUser(CreateUserRequest $createUserRequest): JsonResponse
    {
        $validatedData = $createUserRequest->validated();
        $user = $this->tenantService->createAdminUser($validatedData);
        $user->save();

        $appEnv = config('app.env');

        if ($appEnv === 'production') {
            $baseUrl = $validatedData['workspace'] . '.' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN;
        } else if ($appEnv === 'staging') {
            $baseUrl = $validatedData['workspace'] . '.' . Constants::STAGING_SERVER . '.' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN;
        } else {
            $baseUrl = $validatedData['workspace'] . '.' . Constants::DEV_SERVER . '.' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN;
        }

        return response()->json([
            'message' => 'Admin user created successfully',
            'baseUrl' => $baseUrl
        ]);
    }
}
