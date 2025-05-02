<?php

namespace App\Http\Middleware;

use App\Constants\Constants;
use Closure;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsRoleEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->User()->hasRole(Constants::ROLE_EMPLOYEE)) {
            return $next($request);
        } else {
            return redirect()->route('alert')->with('message', "You don't have permission");
        }
    }
}
