<?php

namespace App\Http\Controllers;

use App\Http\Requests\Item\CreateItemRequest;
use App\Models\Item\Item;
use App\Services\Core\HelperService;
use App\Services\Item\ItemService;
use Illuminate\Http\Request;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class ItemController extends Controller implements HasMiddleware
{
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-item', only: ['index']),
            new middleware('permission:can-create-item', only: ['addExpense']),
            new middleware('permission:can-edit-item', only: ['changeStatus']),
        ];
    }

    public function index(Request $request)
    {
        $type = $request->query('type','sale');
        $breadcrumbs = Breadcrumbs::generate('items', $type);
        $itemsByType = $this->itemService->getItems($type);
        $items = $this->itemService->itemDetails($itemsByType);
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'items' => $items,
            'type' => $type,
            'pageTitle' => __('pageTitle.custom.item.index')
        ];
        return Inertia::render('Items/Index', $responseData);
    }

    public function changeStatus(Item $item, Request $request): RedirectResponse
    {
        $item = $this->itemService->changeStatus($item, $request->is_active);
        $status =  $item ? 'success' : 'error';
        $message = $item ? ($item->is_active ? 'Item is activated' : 'Item status is deactivated') : 'Item status could not be changed';
        return redirect()->back()->with($status, $message);
    }

    public function addExpense(CreateItemRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = HelperService::generateUniqueSlugFromTranslatedName($validated['name'], Item::class);
        $expense = $this->itemService->create($validated);
        $status =  $expense ? 'success' : 'error';
        $message = $expense ?  __('message.custom.expense.store.success') : __('message.custom.expense.store.error');
        return redirect()->back()->with($status, $message);
    }
}
