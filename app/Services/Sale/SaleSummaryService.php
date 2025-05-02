<?php

namespace App\Services\Sale;

use App\Models\Sale\SaleSummary;
use App\Services\Buyer\BuyerPaymentService;
use App\Services\Buyer\BuyerSummaryService;
use App\Services\Core\BaseModelService;
use App\Services\Item\ItemUnitService;
use Carbon\Carbon;

class SaleSummaryService extends BaseModelService
{
    protected ItemUnitService $itemUnitService;
    protected BuyerSummaryService $buyerSummaryService;
    protected BuyerPaymentService $buyerPaymentService;

    public function __construct(ItemUnitService $itemUnitService, BuyerSummaryService $buyerSummaryService, BuyerPaymentService $buyerPaymentService)
    {
        $this->itemUnitService = $itemUnitService;
        $this->buyerSummaryService = $buyerSummaryService;
        $this->buyerPaymentService = $buyerPaymentService;
    }

    public function model(): string
    {
        return SaleSummary::class;
    }

    public function firstOrCreateSaleSummary($sale, $saleItem)
    {
        $unitDisplayName = $this->itemUnitService->getItemUnitById($saleItem->item_id);
        $data = [
            'item_id' => $saleItem->item_id,
            'item_unit_id' => $saleItem->item_unit_id ?? null,
            'brand_id' => $saleItem->brand_id ?? null,
            'sale_item_id' => $saleItem->id,
            'unit_display_name' => $unitDisplayName->display_name ?? null,
            'fiscal_year_id' => $sale->fiscal_year_id,
            'sale_date' => $sale->sale_date,
            'month' => Carbon::now()->parse($sale->sale_date)->format('m')
        ];

        return $this->model()::firstOrCreate([
            'item_id' => $saleItem->item_id,
            'brand_id' => $saleItem->brand_id,
            'fiscal_year_id' => $sale->fiscal_year_id,
            'sale_date' => $sale->sale_date,
        ], $data);
    }

    public function addSaleItemToSummary($sale, $saleItem)
    {
        $saleSummary = $this->firstOrCreateSaleSummary($sale, $saleItem);
        $saleSummary->total_quantity += $saleItem->quantity;
        $saleSummary->total_amount += $saleItem->total_price;
        $saleSummary->total_box += $saleItem->total_box;
        $saleSummary->total_poly += $saleItem->total_poly;
        return $saleSummary->save();
    }

    public function removeSaleItemFromSummary($sale, $saleItem)
    {
        $saleSummary = $this->firstOrCreateSaleSummary($sale, $saleItem);
        $saleSummary->total_quantity -= $saleItem->quantity;
        $saleSummary->total_amount -= $saleItem->total_price;
        $saleSummary->total_box -= $saleItem->total_box;
        $saleSummary->total_poly -= $saleItem->total_poly;
        return $saleSummary->save();
    }
}
