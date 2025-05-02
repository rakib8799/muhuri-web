<?php

namespace App\Services\Auth;


use App\Services\Core\BaseModelService;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class AuthenticationLogService extends BaseModelService
{
    public function model(): string
    {
        return AuthenticationLog::class;
    }

    public function getAuthenticationLogs($user)
    {
        return $this->model()::where('authenticatable_id', $user->id)
            ->orderBy('login_at', 'desc')
            ->get();
    }
}
