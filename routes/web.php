<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Buyer\BuyerPaymentController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Expense\ExpenseController;
use App\Http\Controllers\FiscalYearController;
use App\Http\Controllers\HR\EmployeeManagement\EmployeeController;
use App\Http\Controllers\HR\EmployeeManagement\EmployeeDocumentController;
use App\Http\Controllers\HR\JobPositionController;
use App\Http\Controllers\HR\Subscription\SubscriptionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Language\LanguageController;
use App\Http\Controllers\Language\LocalizationController;
use App\Http\Controllers\Payment\BkashPaymentController;
use App\Http\Controllers\Permission\RoleController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Purchase\PurchaseItemController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Sale\SaleController;
use App\Http\Controllers\Sale\SaleItemController;
use App\Http\Controllers\SMS\SMSController;
use App\Http\Controllers\SMS\SMSTemplateController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supplier\SupplierPaymentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\Language;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/localization/{locale}', [LocalizationController::class, 'localization'])->name('localization');
Route::get('/language-options', [LanguageController::class, 'getLanguageOptions'])->name('getLanguageOptions');

Route::middleware(Language::class)
    ->group(function () {
        Route::get('/', function () {
            return Inertia::render('Auth/Login', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
                'pageTitle' => __('pageTitle.custom.login'),
            ]);
        })->middleware('guest');

        Route::get('/signup-confirmation', function () {
            return Inertia::render('Auth/SignUpConfirmation', [
                'pageTitle' => __('pageTitle.custom.auth.signUpConfirmation')
            ]);
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

        Route::middleware('auth')->group(function () {
            // Alert route
            Route::get('/alert', function () {
                return Inertia::render('Error/Alert');
            })->name('alert');

            // Profile related routes
            Route::prefix('profile')->group(function() {
                Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
                Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
                Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
            });

            // Configuration related routes
            Route::prefix('configurations')->group(function() {
                Route::get('/', [ConfigurationController::class, 'details'])->name('configurations.details');
                Route::get('basic-info', [ConfigurationController::class, 'basicInfo'])->name('configurations.basicInfo');
                Route::get('contact-info', [ConfigurationController::class, 'contactInfo'])->name('configurations.contactInfo');
                Route::get('system-setting', [ConfigurationController::class, 'systemSettings'])->name('configurations.systemSetting');

                Route::patch('basic-info', [ConfigurationController::class, 'updateBasicInfo'])->name('configurations.updateBasicInfo');
                Route::patch('contact-info', [ConfigurationController::class, 'updateContactInfo'])->name('configurations.updateContactInfo');
                Route::patch('system-setting', [ConfigurationController::class, 'updateSystemSetting'])->name('configurations.updateSystemSetting');
            });

            // Roles related routes
            Route::resource('roles', RoleController::class);
            Route::post('assign-permission', [RoleController::class, 'assignPermissionToRole']);
            Route::delete('remove-assigned-permission', [RoleController::class, 'removePermissionFromRole']);
            Route::prefix('roles/{role}')->group(function() {
                Route::patch('change-status', [RoleController::class, 'changeStatus'])->name('roles.changeStatus');
                Route::delete('remove-user/{user}', [RoleController::class, 'removeUserFromRole'])->name('roles.removeUserFromRole');
            });

            // User related routes
            Route::resource('/users', UserController::class);
            Route::prefix('users/{user}')->group(function() {
                Route::patch('update-details', [UserController::class, 'updateDetails'])->name('users.updateDetails');
                Route::patch('update-mobile-number', [UserController::class, 'updateMobileNumber'])->name('users.updateMobileNumber');
                Route::patch('update-roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');
                Route::patch('update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
                Route::patch('change-status', [UserController::class, 'changeStatus'])->name('users.changeStatus');
            });

            // Job-position related routes
            Route::get('job-positions/get-job-positions', [JobPositionController::class, 'getJobPositions'])->name('job-positions.getJobPositions');
            Route::post('job-positions/storeJobPosition', [JobPositionController::class, 'storeJobPosition'])->name('job-positions.storeJobPosition');

            Route::resource('job-positions', JobPositionController::class);
            Route::patch('/job-positions/{job_position}/change-status', [JobPositionController::class, 'changeStatus'])->name('job-positions.changeStatus');

            // Employee related routes
            Route::resource('employees', EmployeeController::class);
            Route::prefix('employees/{employee}')->group(function() {
                Route::get('basic-info', [EmployeeController::class, 'basicInfo'])->name('employees.basicInfo');
                Route::patch('basic-info', [EmployeeController::class, 'updateBasicInfo'])->name('employees.updateBasicInfo');
                Route::get('additional-info', [EmployeeController::class, 'additionalInfo'])->name('employees.additionalInfo');
                Route::patch('additional-info', [EmployeeController::class, 'updateAdditionalInfo'])->name('employees.updateAdditionalInfo');
                Route::get('work-info', [EmployeeController::class, 'workInfo'])->name('employees.workInfo');
                Route::patch('work-info', [EmployeeController::class, 'updateWorkInfo'])->name('employees.updateWorkInfo');
                Route::get('departure-info', [EmployeeController::class, 'departureInfo'])->name('employees.departureInfo');
                Route::patch('process-employee-departure', [EmployeeController::class, 'processEmployeeDeparture'])->name('employees.processEmployeeDeparture');
                Route::patch('process-employee-rejoin', [EmployeeController::class, 'processEmployeeRejoin'])->name('employees.processEmployeeRejoin');
                Route::patch('change-status', [EmployeeController::class, 'changeStatus'])->name('employees.changeStatus');
            });

            // Subscription related routes
            Route::get('/subscriptions', [SubscriptionController::class, 'show'])->name('subscriptions.show');
            Route::resource('/subscriptions', SubscriptionController::class)->except(['index','create','store','show','destroy']);

            // Payment related routes
            Route::prefix('payment')->as('payment.')->group(function () {
                Route::prefix('bkash')->as('bkash.')->group(function () {
                    // Subscription Payment Routes
                    Route::post('/create-subscription-payment', [BkashPaymentController::class, 'createSubscriptionPayment'])->name('create.subscription');
                    Route::get('/subscription-callback', [BkashPaymentController::class, 'subscriptionCallback'])->name('callback.subscription');
                    Route::post('/create-sms-payment', [BkashPaymentController::class, 'createSmsPayment'])->name('create.sms');
                    Route::get('/sms-callback', [BkashPaymentController::class, 'smsCallback'])->name('callback.sms');

                    // SMS Payment Routes
                    Route::post('/create-sms-payment', [BkashPaymentController::class, 'createSmsPayment'])->name('create.sms');
                    Route::get('/sms-callback', [BkashPaymentController::class, 'smsCallback'])->name('callback.sms');
                });
            });
            Route::post('/expire-pending-invoices', [BkashPaymentController::class, 'expirePendingInvoices'])->name('expire.pending.invoices');

            //Item related routes.
            Route::prefix('/items')->name('items.')->group(function(){
                Route::get('/', [ItemController::class, 'index'])->name('index');
                Route::patch('/{item}/change-status', [ItemController::class, 'changeStatus'])->name('changeStatus');
                Route::post('/add-expense',[ItemController::class,'addExpense'])->name('addExpense');
            });

            //Fiscal Year Related Route
            Route::resource('fiscal-years',FiscalYearController::class)->except('destroy');

            //FiscalYear Related
            Route::prefix('fiscal-years')->name('fiscal-years.')->group(function() {
                Route::prefix('{fiscal_year}')->group(function() {
                    Route::patch('change-status', [FiscalYearController::class, 'changeStatus'])->name('changeStatus');
                    Route::patch('start-fiscal-year',[FiscalYearController::class,'startFiscalYear'])->name('startFiscalYear');
                    Route::patch('close-fiscal-year',[FiscalYearController::class,'closeFiscalYear'])->name('closeFiscalYear');
                });
            });

            //Buyer related route
            Route::resource('/buyers', BuyerController::class);
            Route::patch('/buyers/{buyer}/change-status', [BuyerController::class, 'changeStatus'])->name('buyers.changeStatus');
            Route::post('/buyers/send-due-reminder', [BuyerController::class, 'sendDueReminder'])->name('buyers.sendDueReminder');

            //BuyerPayment related route
            Route::resource('buyers.payments', BuyerPaymentController::class)->except('index', 'edit');
            Route::get('/buyers/{buyer}/payments/{payment}/invoice', [BuyerPaymentController::class,'show'])->name('buyers.payments.invoice');

            // Supplier related route
            Route::resource('/suppliers', SupplierController::class);
            Route::patch('/suppliers/{supplier}/change-status', [SupplierController::class, 'changeStatus'])->name('suppliers.changeStatus');
            Route::post('/suppliers/send-due-reminder', [SupplierController::class, 'sendDueReminder'])->name('suppliers.sendDueReminder');

            //SupplierPayment related route
            Route::resource('suppliers.payments', SupplierPaymentController::class)->except('index', 'edit');

            //Brand Related route
            Route::resource('/brands', BrandController::class);
            Route::patch('/brands/{brand}/change-status', [BrandController::class, 'changeStatus'])->name('brands.changeStatus');

            //Sale Related route
            Route::prefix('sales/others')->name('sales.others.')->group(function() {
                Route::get('/create', [SaleController::class, 'createOtherSale'])->name('create');
                Route::post('/store', [SaleController::class, 'storeOtherSale'])->name('store');
                Route::get('/{sale}/edit', [SaleController::class, 'editOtherSale'])->name('edit');
                Route::patch('/{sale}/saleItems/{saleItem}', [SaleItemController::class, 'updateOtherSaleItem'])->name('saleItems.update');
                Route::patch('/{sale}', [SaleController::class, 'updateOtherSale'])->name('update');
                Route::delete('/{sale}', [SaleController::class, 'destroyOtherSale'])->name('destroy');
                Route::post('/buyer', [BuyerController::class,'store'])->name('buyer.store');
            });
            Route::resource('/sales', SaleController::class);
            Route::resource('sales.saleItems', SaleItemController::class)->except('edit','show');
            Route::post('sales/buyer', [BuyerController::class,'store'])->name('sales.buyer.store');

            // Report Related routes
            Route::prefix('reports')->name('reports.')->group(function() {
                Route::get('/at-a-glance', [ReportController::class, 'getSummariesReport'])->name('atAGlance');
                Route::get('/buyer-due', [ReportController::class, 'getBuyerDue'])->name('buyerDue');
                Route::get('/sale', [ReportController::class, 'getSaleReport'])->name('sale');
            });

            // SMS related routes
            Route::get('/sms', [SMSController::class, 'index'])->name('sms.index');
            Route::get('/sms/purchase', [SMSController::class, 'smsPurchase'])->name('sms.purchase');
            Route::post('/sms/purchase', [SMSController::class, 'storeSMSPurchase'])->name('sms.storeSMSPurchase');
            Route::get('/sms/purchase-report', [SMSController::class, 'smsPurchaseReport'])->name('sms.purchaseReport');
            Route::get('/sms/sms-log', [SMSController::class, 'smsLog'])->name('sms.smsLog');
            Route::get('/sms/templates', [SMSTemplateController::class, 'index'])->name('sms.templates.index');
            Route::get('/sms/templates/create', [SMSTemplateController::class, 'create'])->name('sms.templates.create');
            Route::post('/sms/templates', [SMSTemplateController::class, 'store'])->name('sms.templates.store');
            Route::get('/sms/templates/{smsTemplate}/edit', [SMSTemplateController::class, 'edit'])->name('sms.templates.edit');
            Route::patch('/sms/templates/{smsTemplate}', [SMSTemplateController::class, 'update'])->name('sms.templates.update');
            Route::delete('/sms/templates/{smsTemplate}', [SMSTemplateController::class, 'destroy'])->name('sms.templates.destroy');
            Route::patch('/sms/templates/{smsTemplate}/change-status', [SMSTemplateController::class, 'changeStatus'])->name('sms.templates.changeStatus');

            Route::resource('expenses', ExpenseController::class);

            //Purchase related route
            Route::prefix('purchases/others')->name('purchases.others.')->group(function() {
                Route::get('/create', [PurchaseController::class, 'createOtherPurchase'])->name('create');
                Route::post('/', [PurchaseController::class, 'storeOtherPurchase'])->name('store');
                Route::get('/{purchase}/edit', [PurchaseController::class, 'editOtherPurchase'])->name('edit');
                Route::patch('/{purchase}/purchaseItems/{purchaseItem}', [PurchaseItemController::class, 'updateOtherPurchaseItem'])->name('purchaseItems.update');
                Route::patch('/{purchase}', [PurchaseController::class, 'updateOtherPurchase'])->name('update');
                Route::post('/supplier', [SupplierController::class,'store'])->name('supplier.store');
                Route::delete('/{purchase}', [PurchaseController::class, 'destroyOtherPurchase'])->name('destroy');
            });
            Route::resource('/purchases', PurchaseController::class);
            Route::resource('purchases.purchaseItems', PurchaseItemController::class)->except('edit','show');
            Route::post('purchases/supplier', [SupplierController::class,'store'])->name('purchases.supplier.store');
        });
    }
);

require __DIR__.'/auth.php';
