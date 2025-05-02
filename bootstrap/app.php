<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Sentry\Laravel\Integration;
use App\Http\Middleware\Language;
use Illuminate\Foundation\Application;
use App\Http\Middleware\IsRoleEmployee;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Middleware\TenantMiddleware;
use App\Http\Middleware\SetWorkspaceName;
use App\Http\Middleware\CheckIsRoleDeletable;
use App\Http\Middleware\CheckIsRoleSuperAdmin;
use App\Http\Middleware\HandleInertiaRequests;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\CheckIsEmployeeDeparted;
use App\Http\Middleware\CheckIsFiscalYearEditable;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Route middleware groups
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            Language::class
        ]);

        $middleware->api(append: [
            EnsureFrontendRequestsAreStateful::class,
            TenantMiddleware::class,
            SetWorkspaceName::class,
        ]);

        // Middleware aliases
        $middleware->alias([
            'role.isSuperAdmin' => CheckIsRoleSuperAdmin::class,
            'role.isDeletable' => CheckIsRoleDeletable::class,
            'employee.departed' => CheckIsEmployeeDeparted::class,
            'permission' => PermissionMiddleware::class,
            'api.key' => ApiKeyMiddleware::class,
            'role.isEmployee' => IsRoleEmployee::class,
            'check-fiscal-year.isEditable' => CheckIsFiscalYearEditable::class
        ]);
        $middleware->append(TenantMiddleware::class);
        $middleware->append(SetWorkspaceName::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);

        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if (in_array($response->getStatusCode(), [500, 404])) {
                return Inertia::render('Error/Error', ['status' => $response->getStatusCode(), 'message' => $exception->getMessage()])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            }
            return $response;
        });
    })->create();
