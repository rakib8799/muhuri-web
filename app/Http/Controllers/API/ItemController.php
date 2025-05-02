<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\Item\ItemService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function syncItems(Request $request)
    {
        $this->itemService->syncData($request);
        return response()->json(['message' => 'Items synced successfully']);
    }
}
