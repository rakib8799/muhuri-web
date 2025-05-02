<?php
namespace App\Services\Payment\Bkash;

use App\Services\Payment\PaymentMethodService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BkashService
{
    protected $grantToken;
    protected $paymentMethod;
    protected PaymentMethodService $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
        $this->paymentMethod = $this->paymentMethodService->getPaymentMethodBySlug('bkash');
        $this->getGrantToken();
    }

    private function sendRequest(string $requestUrl, string $method = 'POST', array $requestData = [], array $headers = [], bool $auth = true)
    {
        try {
            $headers = array_merge([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ], $headers);

            if ($auth) {
                $this->getGrantToken();
                $headers['Authorization'] = "Bearer {$this->grantToken}";
                $headers['X-App-Key'] = $this->paymentMethod->app_key;
            }

            $response = Http::withHeaders($headers)->{$method}($requestUrl, $requestData);

            if ($auth && $response->status() === 401) {
                $this->getGrantToken(true);
                $headers['Authorization'] = "Bearer {$this->grantToken}";
                $response = Http::withHeaders($headers)->{$method}($requestUrl, $requestData);
            }

            return [
                'status' => $response->status(),
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'data' => $e->getMessage()
            ];
        }
    }

    private function getGrantToken(bool $forceRefresh = false)
    {
        if (!$forceRefresh && $this->grantToken) {
            return;
        }

        $cacheKey = 'bkash-grant-token';
        if (!$forceRefresh && Cache::has($cacheKey)) {
            $this->grantToken = Cache::get($cacheKey);
            return;
        }

        $requestUrl = "{$this->paymentMethod->base_url}/token/grant";
        $requestData = [
            'app_key' => $this->paymentMethod->app_key,
            'app_secret' => $this->paymentMethod->app_secret
        ];
        $headers = [
            'username' => $this->paymentMethod->username,
            'password' => $this->paymentMethod->password
        ];

        $response = $this->sendRequest($requestUrl, 'POST', $requestData, $headers, false);
        $responseData = $response['data'];

        if ($response['status'] === 200 && isset($responseData['id_token'])) {
            $this->grantToken = $responseData['id_token'];
            Cache::put($cacheKey, $this->grantToken, now()->addMinutes(60));
        } else {
            throw new \Exception('Failed to get grant token.');
        }
    }

    public function createPayment(string $requestUrl, array $requestData)
    {
        return $this->sendRequest($requestUrl, 'POST', $requestData);
    }

    public function executePayment(string $requestUrl, array $requestData)
    {
        return $this->sendRequest($requestUrl, 'POST', $requestData);
    }
}
