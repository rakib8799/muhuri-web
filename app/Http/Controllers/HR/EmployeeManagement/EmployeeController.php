<?php

namespace App\Http\Controllers\HR\EmployeeManagement;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\EmployeeManagement\CreateEmployeeRequest;
use App\Http\Requests\HR\EmployeeManagement\EmployeeDepartureRequest;
use App\Http\Requests\HR\EmployeeManagement\UpdateEmployeeAdditionalInformationRequest;
use App\Http\Requests\HR\EmployeeManagement\UpdateEmployeeBasicInformationRequest;
use App\Http\Requests\HR\EmployeeManagement\UpdateEmployeeWorkInformationRequest;
use App\Models\HR\Employee;
use App\Services\Core\HelperService;
use App\Services\CountryService;
use App\Services\HR\DepartureReasonService;
use App\Services\HR\Employee\EmployeeService;
use App\Services\HR\JobPositionService;
use App\Services\User\UserService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller implements HasMiddleware
{
    protected EmployeeService $employeeService;
    protected CountryService $countryService;
    protected UserService $userService;
    protected DepartureReasonService $departureReasonService;
    protected JobPositionService $jobPositionService;

    public function __construct(EmployeeService $employeeService, CountryService $countryService, UserService $userService, DepartureReasonService $departureReasonService, JobPositionService $jobPositionService)
    {
        $this->employeeService = $employeeService;
        $this->countryService = $countryService;
        $this->userService = $userService;
        $this->departureReasonService = $departureReasonService;
        $this->jobPositionService = $jobPositionService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-employee', only: ['create', 'store']),
            new middleware('permission:can-edit-employee', only: ['basicInfo', 'updateBasicInfo', 'additionalInfo', 'updateAdditionalInfo', 'workInfo', 'updateWorkInfo', 'departureInfo', 'processEmployeeDeparture', 'processEmployeeRejoin', 'changeStatus']),
            new middleware('permission:can-delete-employee', only: ['destroy']),
            new Middleware('permission:can-view-employee', only: ['index']),
            new Middleware('permission:can-view-details-employee', only: ['show']),
            new middleware('employee.departed', only: ['basicInfo', 'updateBasicInfo', 'additionalInfo', 'updateAdditionalInfo', 'workInfo', 'updateWorkInfo', 'processEmployeeDeparture', 'changeStatus'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $breadcrumbs = Breadcrumbs::generate('employees');
        $employees = $this->employeeService->getEmployees();
        $jobPositions = $this->jobPositionService->getJobPositions();
        $user = auth()->user();
        $responseData = [
            'employees' => $employees,
            'jobPositions' => $jobPositions,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.index.employee'),
        ];
        return Inertia::render('Employee/Index', $responseData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addEmployee');
        $bloodGroups = HelperService::getBloodGroups();
        $genders = HelperService::getGenders();
        $maritalStatus = HelperService::getMaritalStatus();
        $responseData = [
            'bloodGroups' => $bloodGroups,
            'genders' => $genders,
            'maritalStatus' => $maritalStatus,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.create'),
        ];
        return Inertia::render('Employee/Create', $responseData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request)
    {
        $validatedData = $request->validated();
        $employee = $this->employeeService->createEmployee($validatedData);
        $status = $employee ? Constants::SUCCESS : Constants::ERROR;
        $message = $employee ? __('message.custom.employeeManagement.employee.store.success') : __('message.custom.employeeManagement.employee.attendanceRequest.store.error');
        return Redirect::route('employees.show', ['employee' => $employee])->with($status, $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $breadcrumbs = Breadcrumbs::generate('employeeDetails', $employee);
        $employee = $this->employeeService->getEmployeeDetails($employee);
        $jobPositions = $this->jobPositionService->getJobPositions();
        $departureReasons = $this->departureReasonService->getDepartureReasons();
        $responseData = [
            'employee' => $employee,
            'jobPositions' => $jobPositions,
            'departureReasons' => $departureReasons,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.show'),
        ];
        return Inertia::render('Employee/Show', $responseData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $isDeleted = $this->employeeService->deleteEmployee($employee);
        $message = $isDeleted ? __('message.custom.employeeManagement.employee.destroy.success') : __('message.custom.employeeManagement.employee.attendanceRequest.destroy.error');
        return Redirect::route('employees.index')->with($isDeleted ? Constants::SUCCESS : Constants::ERROR, $message);
    }

    /**
     * Edit Basic Info
     */
    public function basicInfo(Employee $employee)
    {
        $breadcrumbs = Breadcrumbs::generate('basicInfo', $employee);
        $bloodGroups = HelperService::getBloodGroups();
        $genders = HelperService::getGenders();
        $maritalStatus = HelperService::getMaritalStatus();
        $departureReasons = $this->departureReasonService->getDepartureReasons();
        $responseData = [
            'employee' => $employee,
            'bloodGroups' => $bloodGroups,
            'genders' => $genders,
            'maritalStatus' => $maritalStatus,
            'departureReasons' => $departureReasons,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.basicInfo'),
        ];
        return Inertia::render('Employee/BasicInfo', $responseData);
    }

    /**
     * Update Basic Information.
     */
    public function updateBasicInfo(UpdateEmployeeBasicInformationRequest $request, Employee $employee)
    {
        $validatedData = $request->validated();
        $employee = $this->employeeService->updateEmployee($employee, $validatedData);
        $status = $employee ? Constants::SUCCESS : Constants::ERROR;
        $message = $employee ? __('message.custom.employeeManagement.employee.update.basicInfo.success') : __('message.custom.employeeManagement.employee.attendanceRequest.update.basicInfo.error');
        return Redirect::route('employees.show', ['employee' => $employee])->with($status, $message);
    }

    /**
     * Edit Additional Information
     */
    public function additionalInfo(Employee $employee)
    {
        $breadcrumbs = Breadcrumbs::generate('additionalInfo', $employee);
        $users = $this->userService->getUsers();
        $countries = $this->countryService->getCountries();
        $departureReasons = $this->departureReasonService->getDepartureReasons();
        $responseData = [
            'employee' => $employee,
            'users' => $users,
            'countries' => $countries,
            'departureReasons' => $departureReasons,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.additionalInfo'),
        ];
        return Inertia::render('Employee/AdditionalInfo', $responseData);
    }

    /**
     * Update Additional Information
     */
    public function updateAdditionalInfo(UpdateEmployeeAdditionalInformationRequest $request, Employee $employee)
    {
        $validatedData = $request->validated();
        $employee = $this->employeeService->updateEmployee($employee, $validatedData);
        $status = $employee ? Constants::SUCCESS : Constants::ERROR;
        $message = $employee ? __('message.custom.employeeManagement.employee.update.additionalInfo.success') : __('message.custom.employeeManagement.employee.attendanceRequest.update.additionalInfo.error');
        return Redirect::route('employees.show', ['employee' => $employee])->with($status, $message);
    }

    /**
     * Edit Work Information
     */
    public function workInfo(Employee $employee)
    {
        $breadcrumbs = Breadcrumbs::generate('workInfo', $employee);
        $jobPositions = $this->jobPositionService->getJobPositions(true);
        $departureReasons = $this->departureReasonService->getDepartureReasons();
        $responseData = [
            'jobPositions' => $jobPositions,
            'employee' => $employee,
            'departureReasons' => $departureReasons,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.workInfo'),
        ];
        return Inertia::render('Employee/WorkInfo', $responseData);
    }

    /**
     * Update Work Information
     */
    public function updateWorkInfo(UpdateEmployeeWorkInformationRequest $request, Employee $employee)
    {
        $validatedData = $request->validated();
        $employee = $this->employeeService->updateEmployee($employee, $validatedData);
        $status = $employee ? Constants::SUCCESS : Constants::ERROR;
        $message = $employee ? __('message.custom.employeeManagement.employee.update.workInfo.success') : __('message.custom.employeeManagement.employee.attendanceRequest.update.workInfo.error');
        return Redirect::route('employees.show', ['employee' => $employee])->with($status, $message);
    }

    /**
     * Edit Departure Information
     */
    public function departureInfo(Employee $employee)
    {
        $breadcrumbs = Breadcrumbs::generate('departureInfo', $employee);
        $departureReasons = $this->departureReasonService->getDepartureReasons();
        $responseData = [
            'departureReasons' => $departureReasons,
            'employee' => $employee,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.departureInfo')
        ];
        return Inertia::render('Employee/DepartureInfo', $responseData);
    }

    /**
     * Process Employee Departure
     */
    public function processEmployeeDeparture(EmployeeDepartureRequest $request, Employee $employee)
    {
        $validatedData = $request->validated();
        $employee = $this->employeeService->handleDeparture($employee, $validatedData);
        $status = $employee ? Constants::SUCCESS : Constants::ERROR;
        $message = $employee ? __('message.custom.employeeManagement.employee.update.departureInfo.success') : __('message.custom.employeeManagement.employee.attendanceRequest.update.departureInfo.error');
        return Redirect::back()->with($status, $message);
    }

    /**
     * Process Employee Rejoin
     */
    public function processEmployeeRejoin(Request $request, Employee $employee)
    {
        $validatedData = $request->all();
        $isUpdated = $this->employeeService->handleRejoin($employee, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.employeeManagement.employee.update.rejoinInfo.success') : __('message.custom.employeeManagement.employee.attendanceRequest.update.rejoinInfo.error');
        return Redirect::back()->with($status, $message);
    }

    /**
     * Change Employee Status
     */
    public function changeStatus(Request $request, Employee $employee)
    {
        $employee = $this->employeeService->changeStatus($employee, $request->is_active);
        $status = $employee ? Constants::SUCCESS : Constants::ERROR;
        $message = $employee ? ($employee->is_active ? __('message.custom.employeeManagement.employee.changeStatus.activate') : __('message.custom.employeeManagement.employee.changeStatus.deactivate')) : __('message.custom.employeeManagement.employee.changeStatus.error');
        return Redirect::back()->with($status, $message);
    }
}
