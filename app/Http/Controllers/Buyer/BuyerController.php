<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Requests\Buyer\CreateBuyerRequest;
use App\Http\Requests\Buyer\UpdateBuyerRequest;
use App\Models\Buyer\Buyer;
use App\Models\Sale\Sale;
use App\Services\BrandService;
use App\Services\Buyer\BuyerService;
use App\Services\Buyer\BuyerSummaryService;
use App\Services\Core\HelperService;
use App\Services\Item\ItemService;
use App\Services\LocationService;
use App\Services\Sale\SaleItemService;
use App\Services\Sale\SaleService;
use App\Services\SMS\SMSService;
use App\Services\SummaryService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\Buyer\BuyerPaymentService;

class BuyerController extends Controller implements HasMiddleware
{
    private BuyerService $buyerService;
    private LocationService $locationService;
    private BuyerPaymentService $buyerPaymentService;
    private BuyerSummaryService $buyerSummaryService;
    private SaleService $saleService;
    private SaleItemService $saleItemService;
    private ItemService $itemService;
    private BrandService $brandService;
    private SummaryService $summaryService;
    private SMSService $smsService;


    public function __construct(
        BuyerService $buyerService,
        LocationService $locationService,
        BuyerPaymentService $buyerPaymentService,
        BuyerSummaryService $buyerSummaryService,
        SaleService $saleService,
        SaleItemService $saleItemService,
        ItemService $itemService,
        BrandService $brandService,
        SummaryService $summaryService,
        SMSService $smsService
    )
    {
        $this->buyerService = $buyerService;
        $this->locationService = $locationService;
        $this->buyerPaymentService = $buyerPaymentService;
        $this->buyerSummaryService = $buyerSummaryService;
        $this->saleService = $saleService;
        $this->saleItemService = $saleItemService;
        $this->itemService = $itemService;
        $this->brandService = $brandService;
        $this->summaryService = $summaryService;
        $this->smsService = $smsService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-buyer', only: ['index']),
            new Middleware('permission:can-view-details-buyer', only: ['show','buyerInvoiceTransaction']),
            new middleware('permission:can-create-buyer', only: ['create','store','storeBuyer']),
            new middleware('permission:can-edit-buyer', only: ['edit','update','changeStatus']),
            new middleware('permission:can-delete-buyer', only: ['destroy'])
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('buyers');
        $buyers = $this->buyerService->getBuyers();
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'buyers' => $buyers,
            'pageTitle' => __('pageTitle.custom.buyer.index')
        ];
        return Inertia::render('Buyer/Index', $responseData);
    }

    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addBuyers');
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.buyer.create')
        ];
        return Inertia::render('Buyer/Create',$responseData);
    }

    public function store(CreateBuyerRequest $request)
    {
        $validatedData = $request->validated();
        $buyer = $this->buyerService->create($validatedData);
        $status = $buyer ? 'success' : 'error';
        $message = $buyer ? __('message.custom.buyer.store.success') : __('message.custom.buyer.store.error');
        if ($request->routeIs('sales.buyer.store')) {
            return redirect()->route('sales.create', ['buyer' => $buyer->id])->with($status, $message);
        } else if ($request->routeIs('sales.others.buyer.store')) {
            return redirect()->route('sales.others.create', ['buyer' => $buyer->id])->with($status, $message);
        }
        return redirect()->route('buyers.index')->with($status, $message);
    }

    public function show(Buyer $buyer)
    {
        $breadcrumbs = Breadcrumbs::generate('buyerDetails', $buyer);
        $address = HelperService::getAddress($buyer);
        $paymentMethods = HelperService::getPaymentMethodEnum();
        $buyerPayments = $this->buyerPaymentService->getPayments($buyer);
        $buyerSummary = $this->buyerSummaryService->getBuyerSummary($buyer);
        $sales = $this->saleService->getSales($buyer);

        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'buyer' => $buyer,
            'address' => $address,
            'paymentMethods' => $paymentMethods,
            'buyerPayments' => $buyerPayments,
            'buyerSummary' => $buyerSummary,
            'sales' => $sales,
            'pageTitle' => __('pageTitle.custom.buyer.show')
        ];

        return Inertia::render('Buyer/Show', $responseData);
    }

    public function edit(Buyer $buyer)
    {
        $breadcrumbs = Breadcrumbs::generate('editBuyer', $buyer);
        $responseData = [
            'buyer' => $buyer,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.buyer.edit')
        ];

        return Inertia::render('Buyer/Create', $responseData);
    }

    public function update(UpdateBuyerRequest $request, Buyer $buyer)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->buyerService->update($buyer, $validatedData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ? __('message.custom.buyer.update.success') : __('message.custom.buyer.update.error');
        return redirect()->route('buyers.index')->with($status, $message);
    }

    public function destroy(Buyer $buyer)
    {
        $isDeleted = $this->buyerService->deleteBuyer($buyer);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ? __('message.custom.buyer.destroy.success') : __('message.custom.buyer.destroy.error');
        return redirect()->route('buyers.index')->with($status, $message);
    }

    public function changeStatus(Buyer $buyer, Request $request)
    {
        $buyer = $this->buyerService->changeStatus($buyer, $request->is_active);
        $status = $buyer ? 'success' : 'error';
        $message = $buyer ? ($buyer->is_active? __('message.custom.buyer.changeStatus.activate') : __('message.custom.buyer.changeStatus.deactivate')) : __('message.custom.buyer.changeStatus.error');
        return redirect()->back()->with($status, $message);
    }

    // sendDueReminder json resonse
    public function sendDueReminder(Request $request)
    {
        $buyers = $this->buyerService->getBuyerDue();
        foreach ($buyers as $buyer) {
            $this->smsService->sendDueReminder($buyer);
        }

        $status = $buyers ? 'success' : 'error';
        $message = $buyers ? __('message.custom.buyer.sendDueReminder.success') : __('message.custom.buyer.sendDueReminder.error');
        return response()->json(['message' => $message], $buyers ? 200 : 400);
    }
}
