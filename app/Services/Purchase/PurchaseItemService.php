<?php

namespace App\Services\Purchase;

use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseItem;
use App\Services\BrandService;
use App\Services\Item\ItemService;
use App\Services\SummaryService;
use App\Services\Supplier\SupplierSummaryService;
use Illuminate\Support\Facades\DB;
use App\Services\Item\ItemUnitService;
use App\Services\Core\BaseModelService;

class PurchaseItemService extends BaseModelService
{
    protected ItemUnitService $itemUnitService;
    protected BrandService $brandService;
    protected PurchaseSummaryService $purchaseSummaryService;
    protected ItemService $itemService;
    protected SupplierSummaryService $supplierSummaryService;
    protected SummaryService $summaryService;

    public function __construct(ItemUnitService $itemUnitService, BrandService $brandService, PurchaseSummaryService $purchaseSummaryService, ItemService $itemService, SupplierSummaryService $supplierSummaryService, SummaryService $summaryService)
    {
        $this->itemUnitService = $itemUnitService;
        $this->brandService = $brandService;
        $this->purchaseSummaryService = $purchaseSummaryService;
        $this->itemService = $itemService;
        $this->supplierSummaryService = $supplierSummaryService;
        $this->summaryService = $summaryService;
    }

    public function model(): string
    {
        return PurchaseItem::class;
    }

    public function addPurchaseItemToSummaries($purchase, $purchaseItem)
    {
        $this->summaryService->increaseTotalPurchase($purchase);
        $this->supplierSummaryService->increaseTotalTransaction($purchase->supplier, $purchase);
        $this->purchaseSummaryService->addPurchaseItemToSummary($purchase, $purchaseItem);
    }

    public function removePurchaseItemFromSummaries($purchase, $purchaseItem)
    {
        $this->summaryService->decreaseTotalPurchase($purchase);
        $this->supplierSummaryService->decreaseTotalTransaction($purchase->supplier, $purchase);
        $this->purchaseSummaryService->removePurchaseItemFromSummary($purchase, $purchaseItem);
    }

    public function createPurchaseItems(array $items, int $purchaseId)
    {
        $createdPurchaseItems = [];
        foreach ($items as $purchaseItem) {
            $itemUnit = $this->itemUnitService->getItemUnitById($purchaseItem['item_id']);
            $item = $this->itemService->getItemById($purchaseItem['item_id']);
            $brandId = $purchaseItem['brand_id'] ?? null;
            $brand = $brandId ? $this->brandService->getBrandById($brandId) : null;
            $brandName = $brand ? $brand->name : null;
            if ($brandId === 0) {
                $brandId = null;
            }

            $purchaseItemsData = [
                'purchase_id' => $purchaseId,
                'item_id' => $item->id,
                'item_name' => $item->name,
                'brand_id' => $brandId,
                'brand_name' => $brandName,
                'quantity' => $purchaseItem['quantity'] ?? 0,
                'total_box' => $purchaseItem['total_box'] ?? 0,
                'total_poly' => $purchaseItem['total_poly'] ?? 0,
                'mir' => $purchaseItem['mir'] ?? 0,
                'unit_price' => $purchaseItem['unit_price'] ?? 0,
                'total_price' => round($purchaseItem['total_price']),
                'unit_name' => $itemUnit->name ?? null,
                'unit_value' => $itemUnit->value ?? null,
                'unit_display_name' => $itemUnit?->display_name ?? null,
                'item_unit_id' => $itemUnit->id ?? null,
                'note' => $purchaseItem['note'] ?? ''

            ];

            $createdPurchaseItems[] = $this->create($purchaseItemsData);
        }
        return $createdPurchaseItems;
    }

    public function updatePurchaseItem($purchase, $purchaseItem, array $data)
    {
        $itemUnit = $this->itemUnitService->getItemUnitById($data['item_id']);
        $item = $this->itemService->getItemById($data['item_id']);
        $brandId = $data['brand_id'] ?? null;
        $brand = $brandId ? $this->brandService->getBrandById($brandId) : null;
        $brandName = $brand ? $brand->name : null;

        if ($brandId === 0) {
            $brandId = null;
        }

        return DB::transaction(function () use ($purchase, $purchaseItem, $data, $item, $brandId, $brandName, $itemUnit) {
            $this->removePurchaseItemFromSummaries($purchase, $purchaseItem);
            $this->decreasePurchaseGrandTotal($purchase, $purchaseItem);

            $purchaseItem->purchase_id = $purchase->id;
            $purchaseItem->item_id = $item->id;
            $purchaseItem->item_name = $item->name;
            $purchaseItem->brand_id = $brandId;
            $purchaseItem->brand_name = $brandName;
            $purchaseItem->total_box = ($data['total_box'] ?? 0);
            $purchaseItem->total_poly = ($data['total_poly'] ?? 0);
            $purchaseItem->mir = ($data['mir'] ?? 0);
            $purchaseItem->quantity = $data['quantity'] ?? 0;
            $purchaseItem->unit_price = $data['unit_price'] ?? 0;
            $purchaseItem->total_price = round($data['total_price']);
            $purchaseItem->note = $data['note'] ?? '';
            $purchaseItem->unit_name = $itemUnit->name ?? null;
            $purchaseItem->unit_value = $itemUnit->value ?? null;
            $purchaseItem->unit_display_name = $itemUnit->display_name ?? null;
            $purchaseItem->item_unit_id = $itemUnit->id ?? null;
            $purchaseItem->save();

            $this->increasePurchaseGrandTotal($purchase, $purchaseItem);
            $this->addPurchaseItemToSummaries($purchase, $purchaseItem);

            return $purchaseItem;
        });
    }

    public function increasePurchaseGrandTotal($purchase, $purchaseItem)
    {
        $purchase->sub_total += $purchaseItem->total_price;
        $purchase->grand_total = $purchase->sub_total - $purchase->discount;
        $purchase->save();
    }

    public function decreasePurchaseGrandTotal($purchase, $purchaseItem)
    {
        $purchase->sub_total -= $purchaseItem->total_price;
        $purchase->grand_total = $purchase->sub_total - $purchase->discount;
        $purchase->save();
    }

    public function getPurchaseItems(Purchase $purchase)
    {
        return $this->model()::with('purchase')->where('purchase_id', $purchase->id)->get();
    }
}
