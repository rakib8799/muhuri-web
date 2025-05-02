<?php

namespace App\Services\Sale;

use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use App\Services\BrandService;
use App\Services\Buyer\BuyerSummaryService;
use App\Services\Item\ItemService;
use App\Services\SummaryService;
use Illuminate\Support\Facades\DB;
use App\Services\Item\ItemUnitService;
use App\Services\Core\BaseModelService;

class SaleItemService extends BaseModelService
{
    protected ItemUnitService $itemUnitService;
    protected BrandService $brandService;
    protected SaleSummaryService $saleSummaryService;
    protected ItemService $itemService;
    protected BuyerSummaryService $buyerSummaryService;
    protected SummaryService $summaryService;

    public function __construct(ItemUnitService $itemUnitService, BrandService $brandService, SaleSummaryService $saleSummaryService, ItemService $itemService, BuyerSummaryService $buyerSummaryService, SummaryService $summaryService)
    {
        $this->itemUnitService = $itemUnitService;
        $this->brandService = $brandService;
        $this->saleSummaryService = $saleSummaryService;
        $this->itemService = $itemService;
        $this->buyerSummaryService = $buyerSummaryService;
        $this->summaryService = $summaryService;
    }

    public function model(): string
    {
        return SaleItem::class;
    }

    public function addSaleItemToSummaries($sale, $saleItem)
    {
        $this->summaryService->increaseTotalSale($sale);
        $this->buyerSummaryService->increaseTotalTransaction($sale->buyer, $sale);
        $this->saleSummaryService->addSaleItemToSummary($sale, $saleItem);
    }

    public function removeSaleItemFromSummaries($sale, $saleItem)
    {
        $this->summaryService->decreaseTotalSale($sale);
        $this->buyerSummaryService->decreaseTotalTransaction($sale->buyer, $sale);
        $this->saleSummaryService->removeSaleItemFromSummary($sale, $saleItem);
    }

    public function createSaleItems(array $items, int $saleId)
    {
        $createdSaleItems = [];
        foreach ($items as $saleItem) {
            $itemUnit = $this->itemUnitService->getItemUnitById($saleItem['item_id']);
            $item = $this->itemService->getItemById($saleItem['item_id']);
            $brandId = $saleItem['brand_id'] ?? null;
            $brand = $brandId ? $this->brandService->getBrandById($brandId) : null;
            $brandName = $brand ? $brand->name : null;
            if ($brandId === 0) {
                $brandId = null;
            }

             $totalPrice = round($saleItem['total_price']);
             $unitPrice = $saleItem['unit_price'] ?? $totalPrice;

            $saleItemsData = [
                'sale_id' => $saleId,
                'item_id' => $item->id,
                'item_name' => $item->name,
                'brand_id' => $brandId,
                'brand_name' => $brandName,
                'quantity' => $saleItem['quantity'] ?? 1,
                'total_box' => $saleItem['total_box'] ?? 0,
                'total_poly' => $saleItem['total_poly'] ?? 0,
                'mir' => $saleItem['mir'] ?? 0,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'unit_name' => $itemUnit->name ?? null,
                'unit_value' => $itemUnit->value ?? null,
                'unit_display_name' => $itemUnit?->display_name ?? null,
                'item_unit_id' => $itemUnit->id ?? null,
                'note' => $saleItem['note'] ?? ''

            ];
            $createdSaleItems[] = $this->create($saleItemsData);
        }

        return $createdSaleItems;
    }

    public function updateSaleItem($sale, $saleItem, array $data)
    {
        $itemUnit = $this->itemUnitService->getItemUnitById($data['item_id']);
        $item = $this->itemService->getItemById($data['item_id']);
        $brandId = $data['brand_id'] ?? null;
        $brand = $brandId ? $this->brandService->getBrandById($brandId) : null;
        $brandName = $brand ? $brand->name : null;

        if ($brandId === 0) {
            $brandId = null;
        }

        return DB::transaction(function () use ($sale, $saleItem, $data, $item, $brandId, $brandName, $itemUnit) {
            $this->removeSaleItemFromSummaries($sale, $saleItem);
            $this->decreaseSaleGrandTotal($sale, $saleItem);

            $saleItem->sale_id = $sale->id;
            $saleItem->item_id = $item->id;
            $saleItem->item_name = $item->name;
            $saleItem->brand_id = $brandId;
            $saleItem->brand_name = $brandName;
            $saleItem->total_box = ($data['total_box'] ?? 0);
            $saleItem->total_poly = ($data['total_poly'] ?? 0);
            $saleItem->mir = ($data['mir'] ?? 0);
            $saleItem->quantity = $data['quantity'] ?? 0;
            $saleItem->unit_price = $data['unit_price'] ?? 0;
            $saleItem->total_price = round($data['total_price']);
            $saleItem->note = $data['note'] ?? '';
            $saleItem->unit_name = $itemUnit->name ?? null;
            $saleItem->unit_value = $itemUnit->value ?? null;
            $saleItem->unit_display_name = $itemUnit->display_name ?? null;
            $saleItem->item_unit_id = $itemUnit->id ?? null;
            $saleItem->save();

            $this->increaseSaleGrandTotal($sale, $saleItem);
            $this->addSaleItemToSummaries($sale, $saleItem);

            return $saleItem;
        });
    }

    public function increaseSaleGrandTotal($sale, $saleItem)
    {
        $sale->sub_total += $saleItem->total_price;
        $sale->grand_total = $sale->sub_total - $sale->discount;
        $sale->save();
    }

    public function decreaseSaleGrandTotal($sale, $saleItem)
    {
        $sale->sub_total -= $saleItem->total_price;
        $sale->grand_total = $sale->sub_total - $sale->discount;
        $sale->save();
    }

    public function getSaleItems(Sale $sale)
    {
        return $this->model()::with('sale')->where('sale_id', $sale->id)->get();
    }
}
