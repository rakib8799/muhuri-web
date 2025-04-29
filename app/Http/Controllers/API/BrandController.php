<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function syncBrands(Request $request)
    {
        $this->brandService->syncData($request);
        return response()->json(['message' => 'Brands synced successfully']);
    }

    public function brandDelete(Request $request)
    {
        $brand = $request->json()->all();
        $this->brandService->syncBrandDelete($brand);
        return response()->json(['message' => 'Brands deleted successfully']);
    }
}
