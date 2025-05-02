<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Supplier\Supplier;
use App\Services\Supplier\SupplierService;
use App\Services\Supplier\SupplierSummaryService;
use App\Services\Core\HelperService;
use App\Services\Purchase\PurchaseService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\Supplier\SupplierPaymentService;

class SupplierController extends Controller implements HasMiddleware
{
    private SupplierService $supplierService;
    private SupplierPaymentService $supplierPaymentService;
    private SupplierSummaryService $supplierSummaryService;
    private PurchaseService $purchaseService;

    public function __construct(
        SupplierService $supplierService,
        SupplierPaymentService $supplierPaymentService,
        SupplierSummaryService $supplierSummaryService,
        PurchaseService $purchaseService
    )
    {
        $this->supplierService = $supplierService;
        $this->supplierPaymentService = $supplierPaymentService;
        $this->supplierSummaryService = $supplierSummaryService;
        $this->purchaseService = $purchaseService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-supplier', only: ['index']),
            new Middleware('permission:can-view-details-supplier', only: ['show','supplierInvoiceTransaction']),
            new middleware('permission:can-create-supplier', only: ['create','store','storeSupplier']),
            new middleware('permission:can-edit-supplier', only: ['edit','update','changeStatus']),
            new middleware('permission:can-delete-supplier', only: ['destroy'])
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('suppliers');
        $suppliers = $this->supplierService->getSuppliers();
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'suppliers' => $suppliers,
            'pageTitle' => __('pageTitle.custom.supplier.index')
        ];
        return Inertia::render('Supplier/Index', $responseData);
    }

    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addSuppliers');
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.supplier.create')
        ];
        return Inertia::render('Supplier/Create',$responseData);
    }

    public function store(CreateSupplierRequest $request)
    {
        $validatedData = $request->validated();
        $supplier = $this->supplierService->create($validatedData);
        $status = $supplier ? 'success' : 'error';
        $message = $supplier ? __('message.custom.supplier.store.success') : __('message.custom.supplier.store.error');
        if ($request->routeIs('purchases.supplier.store')) {
            return redirect()->route('purchases.create', ['supplier' => $supplier->id])->with($status, $message);
        } else if ($request->routeIs('purchases.others.supplier.store')) {
            return redirect()->route('purchases.others.create', ['supplier' => $supplier->id])->with($status, $message);
        }
        return redirect()->route('suppliers.index')->with($status, $message);
    }

    public function show(Supplier $supplier)
    {
        $breadcrumbs = Breadcrumbs::generate('supplierDetails', $supplier);
        $address = HelperService::getAddress($supplier);
        $paymentMethods = HelperService::getPaymentMethodEnum();
        $supplierPayments = $this->supplierPaymentService->getPayments($supplier);
        $supplierSummary = $this->supplierSummaryService->getSupplierSummary($supplier);
        $sales = $this->purchaseService->getPurchases($supplier);
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'supplier' => $supplier,
            'address' => $address,
            'paymentMethods' => $paymentMethods,
            'supplierPayments' => $supplierPayments,
            'supplierSummary' => $supplierSummary,
            'sales' => $sales,
            'pageTitle' => __('pageTitle.custom.supplier.show')
        ];

        return Inertia::render('Supplier/Show', $responseData);
    }

    public function edit(Supplier $supplier)
    {
        $breadcrumbs = Breadcrumbs::generate('editSupplier', $supplier);
        $responseData = [
            'supplier' => $supplier,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.supplier.edit')
        ];

        return Inertia::render('Supplier/Create', $responseData);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->supplierService->update($supplier, $validatedData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ? __('message.custom.supplier.update.success') : __('message.custom.supplier.update.error');
        return redirect()->route('suppliers.index')->with($status, $message);
    }

    public function destroy(Supplier $supplier)
    {
        $isDeleted = $this->supplierService->delete($supplier->id);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ? __('message.custom.supplier.destroy.success') : __('message.custom.supplier.destroy.error');
        return redirect()->route('suppliers.index')->with($status, $message);
    }

    public function changeStatus(Supplier $supplier, Request $request)
    {
        $supplier = $this->supplierService->changeStatus($supplier, $request->is_active);
        $status = $supplier ? 'success' : 'error';
        $message = $supplier ? ($supplier->is_active? __('message.custom.supplier.changeStatus.activate') : __('message.custom.supplier.changeStatus.deactivate')) : __('message.custom.supplier.changeStatus.error');
        return redirect()->back()->with($status, $message);
    }
}
