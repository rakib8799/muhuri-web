<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\CreateOtherPurchaseRequest;
use App\Http\Requests\Purchase\CreatePurchaseRequest;
use App\Http\Requests\Purchase\UpdateOtherPurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Models\Purchase\Purchase;
use App\Services\BrandService;
use App\Services\ConfigurationService;
use App\Services\Core\HelperService;
use App\Services\Item\ItemService;
use App\Services\Item\ItemUnitService;
use App\Services\Purchase\PurchaseItemService;
use App\Services\Purchase\PurchaseService;
use App\Services\Supplier\SupplierService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PurchaseController extends Controller implements HasMiddleware
{
    protected PurchaseService $purchaseService;
    protected SupplierService $supplierService;
    protected ItemService $itemService;
    protected ItemUnitService $itemUnitService;
    protected BrandService $brandService;
    protected PurchaseItemService $purchaseItemService;
    protected ConfigurationService $configurationService;

    public function __construct(PurchaseService $purchaseService, SupplierService $supplierService, ItemService $itemService, ItemUnitService $itemUnitService, BrandService $brandService, PurchaseItemService $purchaseItemService, ConfigurationService $configurationService)
    {
        $this->purchaseService = $purchaseService;
        $this->supplierService = $supplierService;
        $this->itemService = $itemService;
        $this->itemUnitService = $itemUnitService;
        $this->brandService = $brandService;
        $this->purchaseItemService = $purchaseItemService;
        $this->configurationService = $configurationService;
    }

    public static function middleware(): array
    {
        return[
            new middleware('permission:can-view-purchase', only: ['index']),
            new middleware('permission:can-create-purchase', only: ['create', 'store']),
            new middleware('permission:can-view-details-purchase', only: ['show']),
            new middleware('permission:can-edit-purchase', only: ['edit','update']),
            new middleware('permission:can-delete-purchase', only: ['destroy']),
            new middleware('permission:can-create-other-purchase', only: ['createOtherPurchase','storeOtherPurchase']),
            new middleware('permission:can-edit-other-purchase', only: ['editOtherPurchase','updateOtherPurchase']),
            new middleware('permission:can-delete-other-purchase', only: ['destroyOtherPurchase'])
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('purchases');
        $purchases = $this->purchaseService->getPurchases();
        $suppliers = $this->supplierService->getSuppliers();

        $responseData = [
            'purchases' => $purchases,
            'suppliers' => $suppliers,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.purchase.index')
        ];

        return Inertia::render('Purchase/Index', $responseData);
    }

    public function create(Request $request)
    {
        $breadcrumbs = Breadcrumbs::generate('addPurchase');
        $suppliers = $this->supplierService->getSuppliers();
        $createdSupplierId = $request->query('supplier');
        $purchaseItems = $this->itemService->getItems('purchase','larvae');
        $items = $this->itemService->itemDetails($purchaseItems);
        $itemUnits = $this->itemUnitService->getItemUnits();
        $brands = $this->brandService->getBrands();
        $responseData = [
            'suppliers' => $suppliers,
            'createdSupplierId' => $createdSupplierId,
            'items' => $items,
            'itemUnits' => $itemUnits,
            'brands' => $brands,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle'=> __('pageTitle.custom.purchase.create')
        ];

        return Inertia::render('Purchase/Create', $responseData);
    }

    public function store(CreatePurchaseRequest $request)
    {
        $validatedData = $request->validated();
        $purchase = $this->purchaseService->createPurchase($validatedData);
        $status = $purchase ? 'success' : 'error';
        $message = $purchase ? __('message.custom.purchase.store.success') : __('message.custom.purchase.store.error');
        return redirect()->route('purchases.show', $purchase->id)->with($status, $message);
    }

    public function show(Purchase $purchase)
    {
        $breadcrumbs = Breadcrumbs::generate('purchaseDetails', $purchase);
        $supplier = $this->supplierService->getSupplierById($purchase->supplier_id);
        $address = HelperService::getAddress($supplier);
        $purchaseItems = $this->purchaseItemService->getPurchaseItems($purchase);
        $purchaseTypeItems = $this->itemService->getItems('purchase');
        $items = $this->itemService->itemDetails($purchaseTypeItems);
        $brands = $this->brandService->getBrands();
        $configuration = $this->configurationService->getCompanyPublicConfiguration();
        $responseData = [
            'supplier' => $supplier,
            'purchase' => $purchase,
            'address' => $address,
            'purchaseItems' => $purchaseItems,
            'items' => $items,
            'brands' => $brands,
            'configuration' => $configuration,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.purchase.show')
        ];

        return Inertia::render('Purchase/Show', $responseData);
    }

    public function edit(Purchase $purchase)
    {
        $breadcrumbs = Breadcrumbs::generate('editPurchase', $purchase);
        $supplier = $this->supplierService->getSupplierById($purchase->supplier->id);
        $purchaseItems = $this->itemService->getItems('purchase','larvae');
        $items = $this->itemService->itemDetails($purchaseItems);
        $itemUnits = $this->itemUnitService->getItemUnits();
        $brands = $this->brandService->getBrands();
        $purchase = $this->purchaseService->getPurchaseById($purchase->id);
        $responseData = [
            'supplier' => $supplier,
            'items' => $items,
            'itemUnits' => $itemUnits,
            'brands' => $brands,
            'purchase' =>   $purchase,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle'=> __('pageTitle.custom.purchase.edit')
        ];
        if($purchase->purchase_type === 'larvae'){
            return Inertia::render('Purchase/Edit', $responseData);
        }
        return redirect()->route('purchases.others.edit', ['purchase'=> $purchase->id]);
    }

    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->purchaseService->updatePurchase($purchase, $validatedData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ? __('message.custom.purchase.update.success') : __('message.custom.purchase.update.error');
        return redirect()->route('purchases.show', $purchase->id)->with($status, $message);
    }

    public function destroy(Purchase $purchase)
    {
        $isDeleted = $this->purchaseService->deletePurchase($purchase);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ? __('message.custom.purchase.destroy.success') : __('message.custom.purchase.destroy.error');
        return redirect()->route('purchases.index')->with($status, $message);
    }

    //Other Purchases
    public function createOtherPurchase(Request $request)
    {
        $breadcrumbs = Breadcrumbs::generate('addOtherPurchase');
        $suppliers = $this->supplierService->getSuppliers();
        $createdSupplierId = $request->query('supplier');
        $purchaseItems = $this->itemService->getItems('purchase','others');
        $items = $this->itemService->itemDetails($purchaseItems);
        $itemUnits = $this->itemUnitService->getItemUnits();
        $brands = $this->brandService->getBrands();
        $responseData = [
            'suppliers' => $suppliers,
            'createdSupplierId' => $createdSupplierId,
            'items' => $items,
            'itemUnits' => $itemUnits,
            'brands' => $brands,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle'=> __('pageTitle.custom.purchase.otherCreate')
        ];

        return Inertia::render('Purchase/Other/Create', $responseData);
    }

    public function storeOtherPurchase(CreateOtherPurchaseRequest $request)
    {
        $validatedData = $request->validated();
        $purchase = $this->purchaseService->createPurchase($validatedData);
        $status = $purchase ? 'success' : 'error';
        $message = $purchase ? __('message.custom.purchase.otherStore.success') : __('message.custom.purchase.otherStore.error');
        return redirect()->route('purchases.show', $purchase->id)->with($status, $message);
    }

    public function editOtherPurchase(Purchase $purchase)
    {
        $breadcrumbs = Breadcrumbs::generate('editOtherPurchase', $purchase);
        $supplier = $this->supplierService->getSupplierById($purchase->supplier->id);
        $purchaseItems = $this->itemService->getItems('purchase','others');
        $items = $this->itemService->itemDetails($purchaseItems);
        $itemUnits = $this->itemUnitService->getItemUnits();
        $brands = $this->brandService->getBrands();
        $purchase = $this->purchaseService->getPurchaseById($purchase->id);
        $responseData = [
            'supplier' => $supplier,
            'items' => $items,
            'itemUnits' => $itemUnits,
            'brands' => $brands,
            'purchase' =>   $purchase,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle'=> __('pageTitle.custom.purchase.otherEdit')
        ];
        return Inertia::render('Purchase/Other/Edit', $responseData);
    }

    public function updateOtherPurchase(UpdateOtherPurchaseRequest $request, Purchase $purchase)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->purchaseService->updatePurchase($purchase, $validatedData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ? __('message.custom.purchase.otherUpdate.success') : __('message.custom.purchase.otherUpdate.error');
        return redirect()->route('purchases.show', $purchase->id)->with($status, $message);
    }

    public function destroyOtherPurchase(Purchase $purchase)
    {
        $isDeleted = $this->purchaseService->deletePurchase($purchase);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ? __('message.custom.purchase.otherDestroy.success') : __('message.custom.purchase.otherDestroy.error');
        return redirect()->route('purchases.index')->with($status, $message);
    }
}
