<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FiscalYear\FiscalYearManagementService;
use App\Services\FiscalYear\FiscalYearService;
use Inertia\Inertia;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use App\Models\FiscalYear;
use App\Http\Requests\FiscalYear\CreateFiscalYearRequest;
use App\Http\Requests\FiscalYear\UpdateFiscalYearRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class FiscalYearController extends Controller implements HasMiddleware
{
    public FiscalYearService $fiscalYearService;
    public FiscalYearManagementService $fiscalYearManagementService;

    public function __construct(FiscalYearService $fiscalYearService, FiscalYearManagementService $fiscalYearManagementService)
    {
        $this->fiscalYearService = $fiscalYearService;
        $this->fiscalYearManagementService = $fiscalYearManagementService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('check-fiscal-year.isEditable', only: ['edit', 'update']),
            new Middleware('permission:can-view-fiscal-year', only: ['index']),
            new Middleware('permission:can-create-fiscal-year', only: ['create', 'store']),
            new middleware('permission:can-edit-fiscal-year', only: ['edit', 'update', 'changeStatus', 'closeFiscalYear', 'startFiscalYear']),
            new Middleware('permission:can-view-details-fiscal-year', only: ['show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('fiscalYears');
        $fiscalYears = $this->fiscalYearService->all();
        $responseData = [
            'fiscalYears' => $fiscalYears,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.fiscalYear.index')
        ];
        return Inertia::render('FiscalYear/Index',$responseData);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(): Response
    {
        $breadcrumbs = Breadcrumbs::generate('addFiscalYear');
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.fiscalYear.create')
        ];

        return Inertia::render('FiscalYear/Create', $responseData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFiscalYearRequest $request): RedirectResponse
    {
        $validateData = $request->validated();
        $fiscalYear =  $this->fiscalYearService->create($validateData);
        $status = $fiscalYear ? 'success' : 'error';
        $message = $fiscalYear ?  __('message.custom.fiscalYear.store.success') : __('message.custom.fiscalYear.store.error');
        return Redirect::route('fiscal-years.index')->with($status, $message);
    }

    public function show(FiscalYear $fiscalYear)
    {
        $breadcrumbs = Breadcrumbs::generate('fiscalYearDetails', $fiscalYear);
        $responseData = [
            'fiscalYear' => $fiscalYear,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.fiscalYear.show')
        ];

        return Inertia::render('FiscalYear/Show', $responseData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FiscalYear $fiscalYear): Response
    {
        $breadcrumbs = Breadcrumbs::generate('editFiscalYear', $fiscalYear);
        $responseData = [
            'fiscalYear' => $fiscalYear,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.fiscalYear.edit')
        ];

        return Inertia::render('FiscalYear/Create', $responseData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFiscalYearRequest $request, FiscalYear $fiscalYear)
    {
        $validateData = $request->validated();
        $isUpdated = $this->fiscalYearService->update($fiscalYear, $validateData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ? __('message.custom.fiscalYear.update.success') : __('message.custom.fiscalYear.update.error');
        return Redirect::route('fiscal-years.index')->with($status, $message);
    }

    public function changeStatus(Request $request, FiscalYear $fiscalYear): RedirectResponse
    {
        $fiscalYear = $this->fiscalYearService->changeStatus($fiscalYear, $request->is_active);
        $status = $fiscalYear ? 'success' : 'error';
        $message = $fiscalYear ? ($fiscalYear->is_active ? __('message.custom.fiscalYear.changeStatus.activate') : __('message.custom.fiscalYear.changeStatus.deactivate')) : __('message.custom.fiscalYear.changeStatus.error');
        return redirect()->route('fiscal-years.index')->with($status, $message);
    }

    public function closeFiscalYear(FiscalYear $fiscalYear): RedirectResponse
    {
        if($fiscalYear->status != 'running'){
            $status = 'error';
            $message = 'Fiscal year is not running. So it can not be closed';
        }else{
            $fiscalYear = $this->fiscalYearManagementService->closeFiscalYear($fiscalYear);
            $status = $fiscalYear ? 'success' : 'error';
            $message = $fiscalYear ?  __('message.custom.fiscalYear.closeFiscalYear.success') : __('message.custom.fiscalYear.closeFiscalYear.error');
        }
        return redirect()->route('fiscal-years.index')->with($status, $message);
    }

    public function startFiscalYear(FiscalYear $fiscalYear): RedirectResponse
    {
        $runningFiscalYear = $this->fiscalYearService->getRunningFiscalYear();
        if($runningFiscalYear){
            $status = 'error';
            $message = "Another fiscal year is running, so it can't be started";
        }else{
            $fiscalYear = $this->fiscalYearManagementService->startFiscalYear($fiscalYear);
            $status = $fiscalYear ? 'success' : 'error';
            $message = $fiscalYear ?  __('message.custom.fiscalYear.startFiscalYear.success') : __('message.custom.fiscalYear.startFiscalYear.error');
        }
        return redirect()->route('fiscal-years.index')->with($status, $message);
    }
}
