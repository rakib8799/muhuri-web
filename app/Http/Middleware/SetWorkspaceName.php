<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetWorkspaceName
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract subdomain (workspace name)
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];  // Assuming the workspace is the first part of the domain

        // Store it in the session or application container
        app()->singleton('workspaceName', fn () => $subdomain);

        return $next($request);
    }
}
