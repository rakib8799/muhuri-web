<?php

namespace App\Services\Expense;

use App\Models\Expense\Expense;
use App\Models\Item\Item;
use App\Services\Core\BaseModelService;
use App\Services\FiscalYear\FiscalYearService;
use App\Services\Core\HelperService;
use App\Services\Item\ItemService;
use App\Services\SummaryService;
use App\Services\Supplier\SupplierSummaryService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExpenseService extends BaseModelService
{
    private ItemService $itemService;
    private FiscalYearService $fiscalYearService;
    private ExpenseSummaryService $expenseSummaryService;
    protected SummaryService $summaryService;


    public function __construct(
        ItemService $itemService,
        FiscalYearService $fiscalYearService,
        ExpenseSummaryService $expenseSummaryService,
        SummaryService $summaryService
    ) {
        $this->itemService = $itemService;
        $this->fiscalYearService = $fiscalYearService;
        $this->expenseSummaryService = $expenseSummaryService;
        $this->summaryService = $summaryService;
    }


    public function model(): string
    {
        return Expense::class;
    }

    public function createExpense($data): Expense
    {
        return $this->create($data);
    }

    public function addExpense($data): Expense
    {
        $item = $this->itemService->getByIdOrDefault($data['item_id'] ?? null, 'expense-others');
        $fiscalYear = $this->fiscalYearService->getFiscalYearByDate($data['expense_date']);
        $data['item_id'] = $item->id;
        $data['item_name'] = $item->name;
        $data['fiscal_year_id'] = $fiscalYear->id;
        $data['fiscal_year'] = $fiscalYear->fiscal_year;

        return DB::transaction(function () use ($data) {
            $expense = $this->createExpense($data);
            $this->summaryService->addExpenseToSummary( $expense);
            $this->expenseSummaryService->addExpenseToSummary($expense->item()->first(), $expense);
            return $expense;
        });
    }

    private function getExpensesQuery()
    {
        return Expense::with([
            'item' => function ($itemQuery) {
                $itemQuery->select('id', 'name', 'type', 'parent_id');
            }
        ]);
    }

    public function getExpenses(
        $itemId = null,
        $year = null,
        $fromDate = null,
        $toDate = null,
        $orderBy = 'expense_date',
        $direction = 'desc',
        $page = 1
    ) {
        if (!$year) {
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }
        $workspaceName = app('workspaceName');
        $cacheKey = "{$workspaceName}-expenses-{$itemId}-{$year}-{$fromDate}-{$toDate}-{$orderBy}-{$direction}-{$page}";
        $cacheTag = "{$workspaceName}-expense";
        if (Cache::tags($cacheTag)->has($cacheKey)) {
            return Cache::tags($cacheTag)->get($cacheKey);
        }
        $query = $this->getExpensesQuery()
            ->where('fiscal_year', $year)
            ->when($itemId, function ($query) use ($itemId) {
                return $query->where('item_id', $itemId);
            })
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->whereDate('expense_date', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->whereDate('expense_date', '<=', $toDate);
            });

        $totalExpense = $query->sum('amount');

        $expenses = $query->orderBy($orderBy, $direction)
            ->orderBy('id', $direction)->get();
//            ->paginate(10, ['*'], 'page', $page);
//        $expenses = $expenses->toArray();
//        $expenses['total_expense'] = $totalExpense;

        Cache::tags($cacheTag)->put($cacheKey, $expenses);
        return $expenses;
    }

    public function getExpense($expenseAbleType, $expenseAbleId)
    {
        return $this->getExpensesQuery()
            ->where('expenseable_type', $expenseAbleType)
            ->where('expenseable_id', $expenseAbleId)
            ->first();
    }

    public function getById($id)
    {
        return $this->getExpensesQuery()
            ->where('id', $id)
            ->first();
    }

    public function updateExpense($expense, $data)
    {
        if (!$expense->is_editable) {
            return $expense;
        }
        $item = $this->itemService->getByIdOrDefault($data['item_id'] ?? $expense->item_id, 'expense-others');
        $fiscalYear = $this->fiscalYearService->getFiscalYearByDate($data['expense_date']);
        return DB::transaction(function () use ($expense, $data, $item, $fiscalYear) {
            $this->summaryService->removeExpenseFromSummary( $expense);
            $this->expenseSummaryService->removeExpenseFromSummary($expense->item()->first(), $expense);

            $expense->expense_date = $data['expense_date'];
            $expense->item_id = $item->id;
            $expense->item_name = $item->name;
            $expense->amount = (int)$data['amount'];
            $expense->fiscal_year_id = $fiscalYear->id;
            $expense->fiscal_year = $fiscalYear->fiscal_year;
            $expense->note = $data['note'] ?? $expense->note;
            $expense->save();
            $this->summaryService->addExpenseToSummary($expense);
            $this->expenseSummaryService->addExpenseToSummary($expense->item()->first(), $expense);

            $expense->refresh();
            $expense['item'] = $expense->item()->first();
            return $expense;
        });
    }

    public function getExpenseDataFromObject($object, $expenseAbleType): array
    {
        return [
            'amount' => $object->amount,
            'expenseable_id' => $object->id,
            'expenseable_type' => $expenseAbleType,
            'note' => $object->note,
            'expense_date' => $object->payment_date,
        ];
    }

    public function addPaymentToExpense($payment, $expenseAbleType, $itemSlug): void
    {
        $item = Item::where('slug', $itemSlug)->first();
        $data = $this->getExpenseDataFromObject($payment, $expenseAbleType);
        $data['item_id'] = $item->id;
        $data['is_editable'] = false;
        $paymentMethod = HelperService::getPaymentMethodDisplayName($payment->payment_method);
        $data['note'] = $payment->landOwner()->first()->name; // TODO: need to make this dynamic for all payment types
        $this->addExpense($data);
    }

    public function addSupplierPaymentToExpense($supplierPayment, $expenseAbleType): void
    {
        $supplier = $supplierPayment->supplier()->first();
        $item = Item::where('slug', 'supplier-payment-expense')->first();
        $data = $this->getExpenseDataFromObject($supplierPayment, $expenseAbleType);
        $data['item_id'] = $item->id;
        $data['is_editable'] = false;
        if (!$supplier->is_default) {
            $data['note'] = HelperService::getPaymentNote($supplierPayment);
        }
        $this->addExpense($data);
    }

    public function updatePaymentToExpense($payment, $expenseAbleType, $note = null)
    {
        $expense = $this->getExpense($expenseAbleType, $payment->id);
        if ($expense) {
            $expense->is_editable = true;
            $updatedData = $this->getExpenseDataFromObject($payment, $expenseAbleType);
            $updatedData['note'] = $note ?? $updatedData['note'];
            $this->updateExpense($expense, $updatedData);
        }

        return $expense;
    }

    public function deletePaymentFromExpense($payment, $expenseAbleType)
    {
        $expense = $this->getExpense($expenseAbleType, $payment->id);
        if ($expense) {
            $expense->is_editable = true;
            $this->deleteExpense($expense);
        }

        return $expense;
    }

    public function deleteExpense($expense)
    {
        if (!$expense->is_editable) {
            return $expense;
        }
        return DB::transaction(function () use ($expense) {
            $this->summaryService->removeExpenseFromSummary($expense);
            $this->expenseSummaryService->removeExpenseFromSummary($expense->item()->first(), $expense);
            $expense->delete();
            $expense->deleted_by = auth()->user()->id;
            $expense->save();
            return $expense;
        });
    }
}
