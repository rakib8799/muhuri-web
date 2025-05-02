<?php

namespace App\Services\HR;


use App\Models\HR\JobPosition;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobPositionService extends BaseModelService
{

    public function model(): string
    {
        return JobPosition::class;
    }

    public function getJobPositions($isActive = false)
    {
        return $isActive ? $this->model()::where('is_active', true)->get() : $this->model()::all();
    }

    public function createJobPosition($validatedData)
    {
        $validatedData['slug'] = Str::slug($validatedData['name']);
        $jobPosition = $this->create($validatedData);
        return $jobPosition;
    }

    public function deleteJobPosition(JobPosition $jobPosition)
    {
        $result = DB::transaction(function () use ($jobPosition) {
            $isDeleted = $this->delete($jobPosition->id);
            if ($isDeleted) {
                $this->removeJobPositionFromEmployees($jobPosition);
            }
            return $isDeleted;
        });
        return $result ? true : false;
    }

    public function changeStatus(JobPosition $jobPosition, $isActive)
    {
        $isActive = ($isActive == true) ? false : true;
        $result = DB::transaction(function () use ($jobPosition, $isActive) {
            $this->update($jobPosition, ['is_active' => $isActive]);
            if ($isActive == false) {
                $this->removeJobPositionFromEmployees($jobPosition);
            }
            return $jobPosition;
        });
        return $result;
    }

    public function getActiveJobPositions()
    {
        $jobPositions = JobPosition::where('is_active', 1)->get();
        return $jobPositions->isEmpty() ? false : $jobPositions;
    }

    private function removeJobPositionFromEmployees(JobPosition $jobPosition)
    {
        $jobPosition->employees()->detach();
        return true;
    }

    public function syncData($request)
    {
        try{
            $jobPositions = $request->all();

            if(isset($jobPositions['id'])){
                $jobPositions = [$jobPositions];
            }

            foreach($jobPositions as $jobPosition){
                $jobPosition['central_id'] = $jobPosition['id'];
                $this->model()::updateOrCreate([
                    'central_id' => $jobPosition['id']
                ], $jobPosition);
            }
        }catch(\Exception $e){
            HelperService::captureException($e);
        }
    }

    public function syncJobPositionDelete($data)
    {
        try{
            $jobPosition = $this->model()::where('central_id', $data['id'])->first();
            $jobPosition->delete();
        }catch(\Exception $e){
            HelperService::captureException($e);
        }
    }
}
