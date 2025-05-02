<?php

namespace App\Services\Supplier;

use App\Models\Supplier\Supplier;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SupplierService extends BaseModelService
{
    private SupplierSummaryService $supplierSummaryService;

    public function __construct(SupplierSummaryService $supplierSummaryService)
    {
        $this->supplierSummaryService = $supplierSummaryService;
    }

    public function model(): string
    {
        return Supplier::class;
    }

    public function getSuppliers($orderBy = 'name', $direction = 'asc')
    {
        $workspaceName = app('workspaceName');
        $cacheKey = "{$workspaceName}-suppliers-{$orderBy}-{$direction}";
        $cacheTag = "{$workspaceName}-suppliers";

        if (Cache::tags($cacheTag)->has($cacheKey)) {
            return Cache::tags($cacheTag)->get($cacheKey);
        }

        $suppliers = $this->model()::orderBy($orderBy, $direction)->get();

        Cache::tags($cacheTag)->put($cacheKey, $suppliers);
        return $suppliers;
    }


    public function getSupplierById($supplierId)
    {
        return $this->model()::where('id', $supplierId)->first();
    }

    public function getSupplierDetails(Supplier $supplier, $month = null, $year = null)
    {
        $supplierSummary = $this->supplierSummaryService->getSupplierSummary($supplier, $month, $year);
        $address = HelperService::getAddress($supplier);
        $supplier->address = $address;
        $supplier->total_transaction = $supplierSummary['total_transaction'] ?? 0;
        $supplier->total_payment = $supplierSummary['total_payment'] ?? 0;
        $supplier->total_due = $supplierSummary['total_due'] ?? 0;
        $supplier->total_due += $supplier->previous_due;
        return $supplier;
    }

    public function deleteSupplier($supplier)
    {
        return DB::transaction(function () use ($supplier) {
            $supplier->delete();
            $supplier->deleted_by = auth()->user()->id;
            $supplier->save();
        });
    }

    public function getSupplierDue()
    {
        $suppliers = $this->getSuppliers();
        $supplierDue = [];
        foreach ($suppliers as $supplier) {
            $supplierSummary = $this->getSupplierDetails($supplier);
            if ($supplierSummary->total_due == 0) {
                continue;
            }
            $supplierDue[] = [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'total_transaction' => $supplierSummary->total_transaction,
                'total_payment' => $supplierSummary->total_payment,
                'total_due' => $supplierSummary->total_due,
                'mobile_number' => $supplier->mobile_number ?? '',
            ];
        }
        return $supplierDue;
    }

    public function changeStatus(Supplier $supplier, $isActive)
    {
        $isActive = ($supplier->is_active) ? false : true;
        $supplier->is_active = $isActive;
        $supplier->save();
        return $supplier;
    }
}
