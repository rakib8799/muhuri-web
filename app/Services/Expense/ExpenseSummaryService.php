<?php

namespace App\Services\Expense;

use App\Models\Expense\ExpenseSummary;
use App\Models\Item\Item;
use App\Services\Core\BaseModelService;
use App\Services\FiscalYear\FiscalYearService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ExpenseSummaryService extends BaseModelService
{
    private FiscalYearService $fiscalYearService;

    public function __construct(FiscalYearService $fiscalYearService)
    {
        $this->fiscalYearService = $fiscalYearService;
    }

    public function model(): string
    {
        return ExpenseSummary::class;
    }

    public function firstOrCreateExpenseSummary($item, $expense)
    {
        $data = [
            'item_id' => $item->id,
            'month' => Carbon::parse($expense->expense_date)->format('m'),
            'fiscal_year_id' => $expense->fiscal_year_id,
            'fiscal_year' => $expense->fiscal_year,
        ];

        return $this->model()::firstOrCreate([
            'item_id' => $item->id,
            'fiscal_year_id' => $expense->fiscal_year_id,
            'expense_date' => $expense->expense_date,
        ], $data);
    }


    public function getExpenseSummary(Item $item, $year = null, $lunarPhaseId = null, $month = null)
    {
        if (!$year) {
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }

        $cacheKey = "item-summary-{$item->id}-{$lunarPhaseId}-{$month}-{$year}";
        $cacheTag = "item-summary-{$item->id}";
        if (Cache::tags($cacheTag)->has($cacheKey)) {
            return Cache::tags($cacheTag)->get($cacheKey);
        }

        $query = $this->model()::where('item_id', $item->id)
            ->where('fiscal_year', $year);

        if ($lunarPhaseId !== null) {
            $query->where('lunar_phase_id', $lunarPhaseId);
        }

        if ($month !== null) {
            $query->whereMonth('month', $month);
        }

        $expenseSummaries = $query->get();
        $totalTransaction = $expenseSummaries->sum('total_transaction');
        $totalPayment = $expenseSummaries->sum('total_payment');

        $expenseSummary = [
            'total_quantity' => $totalTransaction,
            'total_amount' => $totalPayment,
        ];

        Cache::tags($cacheTag)->put($cacheKey, $expenseSummary);
        return $expenseSummary;
    }

    public function addExpenseToSummary(Item $item, $expense)
    {
        $expenseSummary = $this->firstOrCreateExpenseSummary($item, $expense);
        $expenseSummary->total_amount += $expense->amount;
        return $expenseSummary->save();
    }

    public function removeExpenseFromSummary(Item $item, $expense)
    {
        $expenseSummary = $this->firstOrCreateExpenseSummary($item, $expense);
        $expenseSummary->total_amount -= $expense->amount;
        return $expenseSummary->save();
    }

    public function getExpeseReport(
        $year = null,
        $month = null,
        $lunarPhase = null,
        $fromDate = null,
        $toDate = null,
        $page = 1
    ) {
        if (!$year) {
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }
        $expenseSummaries = $this->model()::where('fiscal_year', $year);

        if ($month) {
            $expenseSummaries->where('month', $month);
        }

        if ($lunarPhase) {
            $expenseSummaries->where('lunar_phase_id', $lunarPhase);
        }

        if ($fromDate && $toDate) {
            $expenseSummaries->whereRaw(
                "? <= month AND month <= ?",
                [date('n', strtotime($fromDate)), date('n', strtotime($toDate))]
            );
        }

        $expenseSummaries = $expenseSummaries->get();
        $itemIds = $expenseSummaries->pluck('item_id')->unique();
        $items = Item::whereIn('id', $itemIds)->get();
        $result = [
            "total_expense" => 0,
            "parent_items" => [],
        ];

        $itemAmounts = [];

        foreach ($expenseSummaries as $summary) {
            $itemId = $summary->item_id;
            $amount = $summary->total_amount;
            $itemAmounts[$itemId] = $itemAmounts[$itemId] ?? 0;
            $itemAmounts[$itemId] += $amount;
        }

        $itemMap = [];
        foreach ($items as $item) {
            $parentId = $item->parent_id;
            $item->amount = $itemAmounts[$item->id] ?? 0;

            $newItem = [
                'id' => $item->id,
                'name' => $item->name,
                'amount' => $item->amount,
                'slug' => $item->slug,
            ];

            if ($parentId) {
                if (!isset($itemMap[$parentId])) {
                    $itemMap[$parentId] = [
                        'id' => $parentId,
                        'name' => $item->parent->name,
                        'slug' => $item->parent->slug,
                        'child_items' => [],
                        'amount' => 0,
                        'percent' => 0,
                    ];
                }

                $itemMap[$parentId]['child_items'][] = $newItem;
                $itemMap[$parentId]['amount'] += $item->amount;
            } else {
                $itemMap[$item->id] = array_merge($newItem, [
                    'percent' => 0,
                    'child_items' => [$newItem],
                ]);
            }
        }

        $result["total_expense"] = 0;
        foreach ($itemMap as $item) {
            $result["total_expense"] += $item["amount"];
        }

        foreach ($itemMap as &$item) {
            $item["percent"] = $result["total_expense"] ? ($item["amount"] / $result["total_expense"]) * 100 : 0;
            $item["percent"] = round($item["percent"], 2);
        }

        $result['parent_items'] = array_values($itemMap);

        return $result;
    }
}
