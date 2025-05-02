<?php

namespace App\Http\Middleware;

use Closure;

class ApiKeyMiddleware
{

    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey) {
            return response()->json(['error' => 'API key is missing'], 401);
        }

        $validApiKey = env('API_KEY');
        if ($apiKey !== $validApiKey) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }
        return $next($request);
    }
}
