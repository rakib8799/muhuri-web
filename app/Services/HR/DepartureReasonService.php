<?php

namespace App\Services\HR;

use App\Models\HR\DepartureReason;
use App\Services\Core\BaseModelService;

class DepartureReasonService extends BaseModelService
{
    public function model(): string
    {
        return DepartureReason::class;
    }

    public function getDepartureReasons()
    {
        $departments = $this->model()::all();
        return $departments;
    }
}
