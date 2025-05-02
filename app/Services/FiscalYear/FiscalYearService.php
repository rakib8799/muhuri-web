<?php

namespace App\Services\FiscalYear;
use App\Models\FiscalYear;
use App\Services\Core\HelperService;
use Illuminate\Support\Facades\Cache;
use App\Services\Core\BaseModelService;

class FiscalYearService extends BaseModelService
{
    public function model(): string
    {
        return FiscalYear::class;
    }

    public function getCurrentFiscalYear()
    {
        return $this->model()::where('status','running')->first();
    }

    public function getFiscalYearsByStatus($statuses = ['running','pending'])
    {
        return $this->model()::whereIn('status', $statuses)->get();
    }

    public function getFiscalYears()
    {
        $cacheKey = 'fiscal_years';
        if(Cache::has($cacheKey)){
           return Cache::get($cacheKey);
        }
        $fiscalYears = $this->model()::whereIn('status',['running','end'])
            ->select('id','fiscal_year','start_date','end_date','status','is_active','note')
            ->get();

        Cache::put($cacheKey, $fiscalYears);
        return $fiscalYears;
    }

    public function getFiscalYearByYear($year)
    {
        return $this->model()::where('fiscal_year',$year)->first();
    }

    public function getFiscalYearByDate($date)
    {
        return $this->model()::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();
    }

    public function getRunningFiscalYear()
    {
        return $this->model()::where('status', 'running')->first();
    }

    public function changeStatus(FiscalYear $fiscalYear, $isActive)
    {
        $isActive = ($isActive == true) ? false : true;
        $this->update($fiscalYear, ['is_active' => $isActive]);
        return $fiscalYear;
    }

    public function syncData($request)
    {
        try{
            $fiscalYears = $request->all();

            if(isset($fiscalYears['id'])){
                $fiscalYears = [$fiscalYears];
            }

            foreach($fiscalYears as $fiscalYear){
                $fiscalYear['central_id'] = $fiscalYear['id'];
                $this->model()::updateOrCreate([
                    'central_id' => $fiscalYear['id']
                ], $fiscalYear);
            }
        }catch(\Exception $e){
            HelperService::captureException($e);
        }
    }

}
