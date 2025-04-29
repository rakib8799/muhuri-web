<?php

namespace App\Services\CentralAdmin;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Services\ConfigurationService;

class CentralAdminService
{
    protected ConfigurationService $configurationService;
    private mixed $centralAdminAPIUrl;
    private mixed $data;
    private mixed $url;
    private mixed $method;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
        $this->centralAdminAPIUrl = env("CENTRAL_ADMIN_API_URL");
        $this->data = null;
        $this->url = null;
        $this->method = null;
    }

    public function sendRequest(): Response
    {
        $data = $this->data;
        $header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Api-Key' => env('CENTRAL_ADMIN_API_KEY'),
        ];

        $url = $this->centralAdminAPIUrl . $this->url;

        if ($this->method === 'POST') {
            $response = Http::withHeaders($header)->post($url, $data);
        } else {
            $response = Http::withHeaders($header)->get($url, $data);
        }
        return $response;
    }

    // Send current subscription to central-admin
    public function syncSubscription($validatedData)
    {
        $workspace = $this->configurationService->getConfiguration('workspace');
        $validatedData['workspace'] = $workspace;
        $this->data = $validatedData;
        $this->url = '/api/v1/central-admin/sync/subscription';
        $this->method = 'POST';
        return $this->sendRequest();
    }
}
