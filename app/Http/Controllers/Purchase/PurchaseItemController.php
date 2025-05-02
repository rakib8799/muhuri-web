<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\UpdateOtherPurchaseItemRequest;
use App\Http\Requests\Purchase\UpdatePurchaseItemRequest;
use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseItem;
use App\Services\Core\HelperService;
use App\Services\Purchase\PurchaseItemService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PurchaseItemController extends Controller implements HasMiddleware
{
    protected PurchaseItemService $purchaseItemService;

    public function __construct(PurchaseItemService $purchaseItemService)
    {
        $this->purchaseItemService = $purchaseItemService;
    }

    public static function middleware(): array
    {
        return[
            new middleware('permission:can-edit-purchase-item', only: ['update']),
            new middleware('permission:can-edit-other-purchase-item', only: ['updateOtherPurchaseItem']),
        ];
    }

    public function index(Purchase $purchase)
    {
        return response()->json($purchase->purchaseItems);
    }

    public function update(UpdatePurchaseItemRequest $request, Purchase $purchase, PurchaseItem $purchaseItem)
    {
        HelperService::checkPurchaseItemAccess($purchase, $purchaseItem);
        $validatedData = $request->validated();
        $updated = $this->purchaseItemService->updatePurchaseItem($purchase, $purchaseItem, $validatedData);
        if ($updated) {
            return response()->json($purchase->purchaseItems);
        }
        return response()->json(['error' => 'Purchase Item could not be updated'], 400);
    }

    public function updateOtherPurchaseItem(UpdateOtherPurchaseItemRequest $request, Purchase $purchase, PurchaseItem $purchaseItem)
    {
        HelperService::checkPurchaseItemAccess($purchase, $purchaseItem);
        $validatedData = $request->validated();
        $updated = $this->purchaseItemService->updatePurchaseItem($purchase, $purchaseItem, $validatedData);
        if ($updated) {
            return response()->json($purchase->purchaseItems);
        }
        return response()->json(['error' => 'Purchase Item could not be updated'], 400);
    }
}
