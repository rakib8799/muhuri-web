<?php

namespace App\Services\Purchase;

use App\Models\Purchase\PurchaseSummary;
use App\Services\Core\BaseModelService;
use App\Services\Item\ItemUnitService;
use Carbon\Carbon;

class PurchaseSummaryService extends BaseModelService
{
    protected ItemUnitService $itemUnitService;

    public function __construct(ItemUnitService $itemUnitService)
    {
        $this->itemUnitService = $itemUnitService;
    }

    public function model(): string
    {
        return PurchaseSummary::class;
    }

    public function firstOrCreatePurchaseSummary($purchase, $purchaseItem)
    {
        $unitDisplayName = $this->itemUnitService->getItemUnitById($purchaseItem->item_id);
        $data = [
            'item_id' => $purchaseItem->item_id,
            'item_unit_id' => $purchaseItem->item_unit_id ?? null,
            'brand_id' => $purchaseItem->brand_id ?? null,
            'purchase_item_id' => $purchaseItem->id,
            'unit_display_name' => $unitDisplayName->display_name ?? null,
            'fiscal_year_id' => $purchase->fiscal_year_id,
            'purchase_date' => $purchase->purchase_date,
            'month' => Carbon::now()->parse($purchase->purchase_date)->format('m')
        ];

        return $this->model()::firstOrCreate([
            'item_id' => $purchaseItem->item_id,
            'brand_id' => $purchaseItem->brand_id,
            'fiscal_year_id' => $purchase->fiscal_year_id,
            'purchase_date' => $purchase->purchase_date,
        ], $data);
    }

    public function addPurchaseItemToSummary($purchase, $purchaseItem)
    {
        $purchaseSummary = $this->firstOrCreatePurchaseSummary($purchase, $purchaseItem);
        $purchaseSummary->total_quantity += $purchaseItem->quantity;
        $purchaseSummary->total_amount += $purchaseItem->total_price;
        $purchaseSummary->total_box += $purchaseItem->total_box;
        $purchaseSummary->total_poly += $purchaseItem->total_poly;
        return $purchaseSummary->save();
    }

    public function removePurchaseItemFromSummary($purchase, $purchaseItem)
    {
        $purchaseSummary = $this->firstOrCreatePurchaseSummary($purchase, $purchaseItem);
        $purchaseSummary->total_quantity -= $purchaseItem->quantity;
        $purchaseSummary->total_amount -= $purchaseItem->total_price;
        $purchaseSummary->total_box -= $purchaseItem->total_box;
        $purchaseSummary->total_poly -= $purchaseItem->total_poly;
        return $purchaseSummary->save();
    }
}
