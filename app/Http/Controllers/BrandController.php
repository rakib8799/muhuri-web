<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\Brand;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\BrandService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Http\Requests\Brand\CreateBrandRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BrandController extends Controller implements HasMiddleware
{
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-brand', only: ['index']),
            new Middleware('permission:can-create-brand', only: ['create','store']),
            new Middleware('permission:can-edit-brand', only: ['edit','update']),
            new Middleware('permission:can-delete-brand', only: ['destroy']),
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('brands');
        $brands = $this->brandService->getBrands();
        $responseData = [
            'brands' => $brands,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.brand.index')
        ];

        return Inertia::render('Brand/Index', $responseData);
    }

    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addBrand');
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.brand.create')
        ];
        return Inertia::render('Brand/Create', $responseData);
    }

    public function store(CreateBrandRequest $request)
    {
        $validatedData = $request->validated();
        $isCreated = $this->brandService->create($validatedData);
        $status = $isCreated ? 'success' : 'error';
        $message = $isCreated ? __('message.custom.brand.store.success') : __('message.custom.brand.store.error');
        return redirect()->route('brands.index')->with($status, $message);
    }

    public function edit(Brand $brand)
    {
        $breadcrumbs = Breadcrumbs::generate('editBrand', $brand);
        $responseData = [
            'brand' => $brand,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.brand.edit')
        ];

        return Inertia::render('Brand/Create', $responseData);
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->brandService->update($brand, $validatedData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ?  __('message.custom.brand.update.success') : __('message.custom.brand.update.error');
        return redirect()->route('brands.index')->with($status, $message);
    }

    public function destroy(Brand $brand)
    {
        $isDeleted = $this->brandService->delete($brand->id);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ?  __('message.custom.brand.destroy.success') : __('message.custom.brand.destroy.error');
        return redirect()->route('brands.index')->with($status, $message);
    }

    public function changeStatus(Request $request, Brand $brand)
    {
        $brand = $this->brandService->changeStatus($brand, $request->is_active);
        $status =  $brand ? 'success' : 'error';
        $message = $brand ? ($brand->is_active ? 'Brand is activated' : 'Brand status is deactivated') : 'Brand status could not be changed';
        return redirect()->back()->with($status, $message);
    }
}
