<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use App\Models\Configuration;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'message' => $request->session()->get('message'),
            ],
            'workspace' => Configuration::where('option_name', 'workspace')->value('option_value'),
            'workspace_domain' => Configuration::where('option_name', 'workspace_domain')->value('option_value'),
            'logo' => Configuration::where('option_name', 'logo')->value('option_value'),
        ];
    }
}
