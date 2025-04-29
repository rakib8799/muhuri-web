<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\CreateExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Models\Expense\Expense;
use App\Services\Core\HelperService;
use App\Services\Expense\ExpenseService;
use App\Services\Item\ItemService;
use App\Services\SummaryService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class ExpenseController extends Controller implements HasMiddleware
{
    private ExpenseService $expenseService;
    private SummaryService $summaryService;
    private HelperService $helperService;

    private ItemService $itemService;

    public function __construct(
        ExpenseService $expenseService,
        SummaryService $summaryService,
        HelperService $helperService,
        ItemService $itemService
    )
    {
        $this->expenseService = $expenseService;
        $this->summaryService = $summaryService;
        $this->helperService = $helperService;
        $this->itemService = $itemService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-expense', only: ['index']),
            new Middleware('permission:can-create-expense', only: ['create', 'store']),
            new Middleware('permission:can-edit-expense', only: ['edit', 'update']),
            new Middleware('permission:can-delete-expense', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $breadcrumbs = Breadcrumbs::generate('expensesList');
        $queryParams = $request->query();
        $expenses = $this->expenseService->getExpenses(
            $queryParams['item_id'] ?? null,
            $queryParams['year'] ?? null,
            $queryParams['from_date'] ?? null,
            $queryParams['to_date'] ?? null,
            page: $queryParams['page'] ?? 1
        );
        $responseDate =  [
            'expenses' => $expenses,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.expense.index')
        ];

        return Inertia::render('Expense/Index', $responseDate);
    }

    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addExpense');
        $items = $this->itemService->getItems('expense');
        $responseDate = [
            'breadcrumbs' => $breadcrumbs,
            'items' => $items,
            'pageTitle' => __('pageTitle.custom.expense.create')
        ];
        return Inertia::render('Expense/Create', $responseDate);
    }

    public function store(CreateExpenseRequest $request)
    {
        $this->expenseService->addExpense($request->validated());
        return redirect()->route('expenses.index')->with('success', __('message.custom.expense.store.success'));
    }

    public function edit(Expense $expense)
    {
        $breadcrumbs = Breadcrumbs::generate('editExpense', $expense);
        $items = $this->itemService->getItems('expense');
        $responseData = [
            'expense' => $expense,
            'breadcrumbs' => $breadcrumbs,
            'items' => $items,
            'pageTitle' => __('pageTitle.custom.expense.edit')
        ];
        return Inertia::render('Expense/Create', $responseData);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        if (!$expense->is_editable) {
            return redirect()->back()->with('error', trans('validation.expense_invalid'));
        }

        $this->expenseService->updateExpense( $expense, $request->validated());
        return redirect()->route('expenses.index')->with('success', __('message.custom.expense.update.success'));
    }

    public function destroy(Expense $expense)
    {
        if (!$expense->is_editable) {
            return redirect()->back()->with('error', trans('validation.expense_invalid'));
        }

        $this->expenseService->deleteExpense($expense);
        return redirect()->route('expenses.index')->with('success', __('message.custom.expense.destroy.success'));
    }
}
