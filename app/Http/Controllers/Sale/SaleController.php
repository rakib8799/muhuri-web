<?php

namespace App\Http\Controllers\Sale;

use App\Http\Requests\Sale\CreateOtherSaleRequest;
use App\Http\Requests\Sale\UpdateOtherSaleRequest;
use App\Http\Requests\Sale\UpdateSaleRequest;
use App\Services\ConfigurationService;
use Inertia\Inertia;
use App\Models\Sale\Sale;
use Illuminate\Http\Request;
use App\Services\BrandService;
use App\Services\SMS\SMSService;
use App\Services\LocationService;
use App\Services\Item\ItemService;
use App\Services\Sale\SaleService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Buyer\BuyerService;
use App\Services\Core\HelperService;
use App\Services\Item\ItemUnitService;
use App\Services\Sale\SaleItemService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Http\Requests\Sale\CreateSaleRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SaleController extends Controller implements HasMiddleware
{
    protected BuyerService $buyerService;
    protected LocationService $locationService;
    protected ItemService $itemService;
    protected ItemUnitService $itemUnitService;
    protected BrandService $brandService;
    protected SaleService $saleService;
    protected SaleItemService $saleItemService;
    protected SMSService $smsService;
    protected ConfigurationService $configurationService;

    public function __construct(BuyerService $buyerService, LocationService $locationService, ItemService $itemService, ItemUnitService $itemUnitService, BrandService $brandService, SaleService $saleService, SaleItemService $saleItemService, SMSService $smsService, ConfigurationService $configurationService)
    {
        $this->buyerService = $buyerService;
        $this->locationService = $locationService;
        $this->itemService = $itemService;
        $this->itemUnitService = $itemUnitService;
        $this->brandService = $brandService;
        $this->saleService = $saleService;
        $this->saleItemService = $saleItemService;
        $this->smsService = $smsService;
        $this->configurationService = $configurationService;
    }

    public static function middleware(): array
    {
        return[
            new middleware('permission:can-view-sale', only: ['index','show']),
            new middleware('permission:can-create-sale', only: ['create','store']),
            new middleware('permission:can-edit-sale', only: ['edit','update']),
            new middleware('permission:can-delete-sale', only: ['destroy']),
            new middleware('permission:can-view-other-sale', only: ['index']),
            new middleware('permission:can-create-other-sale', only: ['createOtherSale','storeOtherSale']),
            new middleware('permission:can-edit-other-sale', only: ['editOtherSale','updateOtherSale']),
            new middleware('permission:can-delete-other-sale', only: ['destroyOtherSale']),
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('sales');
        $sales = $this->saleService->getSales();
        $buyers = $this->buyerService->getBuyers();
        $responseData = [
            'sales' => $sales,
            'buyers' => $buyers,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.sale.index')
        ];

        return Inertia::render('Sale/Index', $responseData);
    }

    public function create(Request $request)
    {
        $breadcrumbs = Breadcrumbs::generate('addSale');
        $buyers = $this->buyerService->getBuyers();
        $createdBuyerId = $request->query('buyer');
        $saleItems = $this->itemService->getItems('sale','larvae');
        $items = $this->itemService->itemDetails($saleItems);
        $itemUnits = $this->itemUnitService->getItemUnits();
        $brands = $this->brandService->getBrands();
        $responseData = [
            'buyers' => $buyers,
            'createdBuyerId' => $createdBuyerId,
            'items' => $items,
            'itemUnits' => $itemUnits,
            'brands' => $brands,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle'=> __('pageTitle.custom.sale.create')
        ];

        return Inertia::render('Sale/Create', $responseData);
    }

    public function store(CreateSaleRequest $request)
    {
        $validatedData = $request->validated();
        $sale = $this->saleService->createSale($validatedData);
        $status = $sale ? 'success' : 'error';
        $message = $sale ? __('message.custom.sale.store.success') : __('message.custom.sale.store.error');
        try{
            $this->smsService->sendSaleConfirmation($sale);
            //Here call the getSmsCount for update the credit
        }catch(\Exception $e){
            Log::error('SMS Error: '. $e->getMessage());
        }
        return redirect()->route('sales.show', $sale->id)->with($status, $message);
    }

    public function show(Sale $sale)
    {
        $breadcrumbs = Breadcrumbs::generate('saleDetails', $sale);
        $buyer = $this->buyerService->getBuyerById($sale->buyer_id);
        $address = HelperService::getAddress($buyer);
        $saleItems = $this->saleItemService->getSaleItems($sale);
        $saleTypeItems = $this->itemService->getItems('sale');
        $items = $this->itemService->itemDetails($saleTypeItems);
        $brands = $this->brandService->getBrands();
        $configuration = $this->configurationService->getCompanyPublicConfiguration();
        $responseData = [
            'buyer' => $buyer,
            'sale' => $sale,
            'address' => $address,
            'saleItems' => $saleItems,
            'items' => $items,
            'brands' => $brands,
            'configuration' => $configuration,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.sale.show')
        ];

        return Inertia::render('Sale/Show', $responseData);
    }

    public function edit(Sale $sale)
    {
        $breadcrumbs = Breadcrumbs::generate('editSale', $sale);
        $buyer = $this->buyerService->getBuyerById($sale->buyer->id);
        $saleItems = $this->itemService->getItems('sale','larvae');
        $items = $this->itemService->itemDetails($saleItems);
        $itemUnits = $this->itemUnitService->getItemUnits();
        $brands = $this->brandService->getBrands();
        $sale = $this->saleService->getSaleById($sale->id);
        $responseData = [
            'buyer' => $buyer,
            'items' => $items,
            'itemUnits' => $itemUnits,
            'brands' => $brands,
            'sale' =>   $sale,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle'=> __('pageTitle.custom.sale.edit')
        ];
        if($sale->sale_type === 'larvae'){
            return Inertia::render('Sale/Edit', $responseData);
        }
        return redirect()->route('sales.others.edit', ['sale'=> $sale->id]);
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->saleService->updateSale($sale, $validatedData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ? __('message.custom.sale.update.success') : __('message.custom.sale.update.error');
        return redirect()->route('sales.show', $sale->id)->with($status, $message);
    }

    public function destroy(Sale $sale)
    {
        $isDeleted = $this->saleService->deleteSale($sale);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ? __('message.custom.sale.destroy.success') : __('message.custom.sale.destroy.error');
        return redirect()->route('sales.index')->with($status, $message);
    }

    public function createOtherSale(Request $request)
    {
        $breadcrumbs = Breadcrumbs::generate('addOtherSale');
        $buyers = $this->buyerService->getBuyers();
        $createdBuyerId = $request->query('buyer');
        $saleItems = $this->itemService->getItems('sale','others');
        $items = $this->itemService->itemDetails($saleItems);
        $itemUnits = $this->itemUnitService->getItemUnits();
        $brands = $this->brandService->getBrands();
        $responseData = [
            'buyers' => $buyers,
            'createdBuyerId' => $createdBuyerId,
            'items' => $items,
            'itemUnits' => $itemUnits,
            'brands' => $brands,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle'=> __('pageTitle.custom.sale.createOther')
        ];

        return Inertia::render('Sale/Other/Create', $responseData);
    }

    public function storeOtherSale(CreateOtherSaleRequest $request)
    {
        $validatedData = $request->validated();
        $sale = $this->saleService->createSale($validatedData);
        $status = $sale ? 'success' : 'error';
        $message = $sale ? __('message.custom.sale.otherStore.success') : __('message.custom.sale.otherStore.error');
        try{
            $this->smsService->sendSaleConfirmation($sale);
            //Here call the getSmsCount for update the credit
        }catch(\Exception $e){
            Log::error('SMS Error: '. $e->getMessage());
        }
        return redirect()->route('sales.show', $sale->id)->with($status, $message);
    }

    public function editOtherSale(Sale $sale)
    {
        $breadcrumbs = Breadcrumbs::generate('editOtherSale', $sale);
        $buyer = $this->buyerService->getBuyerById($sale->buyer->id);
        $saleItems = $this->itemService->getItems('sale','others');
        $items = $this->itemService->itemDetails($saleItems);
        $itemUnits = $this->itemUnitService->getItemUnits();
        $brands = $this->brandService->getBrands();
        $sale = $this->saleService->getSaleById($sale->id);
        $responseData = [
            'buyer' => $buyer,
            'items' => $items,
            'itemUnits' => $itemUnits,
            'brands' => $brands,
            'sale' =>   $sale,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle'=> __('pageTitle.custom.sale.editOther')
        ];
        return Inertia::render('Sale/Other/Edit', $responseData);
    }

    public function updateOtherSale(UpdateOtherSaleRequest $request, Sale $sale)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->saleService->updateSale($sale, $validatedData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ? __('message.custom.sale.otherUpdate.success') : __('message.custom.sale.otherUpdate.error');
        return redirect()->route('sales.show', $sale->id)->with($status, $message);
    }

    public function destroyOtherSale(Sale $sale)
    {
        $isDeleted = $this->saleService->deleteSale($sale);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ? __('message.custom.sale.otherDestroy.success') : __('message.custom.sale.store.error');
        return redirect()->route('sales.index')->with($status, $message);
    }

}
