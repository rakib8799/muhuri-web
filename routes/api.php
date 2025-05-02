<?php

use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\FiscalYearController;
use App\Http\Controllers\API\HealthCheckController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\SMSController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Tenant\TenantController;
use App\Http\Controllers\API\Subscription\SubscriptionController;
use App\Http\Controllers\API\Subscription\SubscriptionPlanController;
use App\Http\Controllers\API\Subscription\SubscriptionPlanFeatureController;
use App\Http\Controllers\API\JobPositionController;

Route::prefix('v1')->group(function () {
    Route::middleware(['api.key', 'throttle:api'])->group(function () {
        Route::prefix('muhuri-web')->group(function () {
            Route::prefix('tenant')->group(function () {
                Route::post('/migration', [TenantController::class, 'runMigrations']);
                Route::post('/run-seeders', [TenantController::class, 'runSeeders']);
                Route::post('/create-admin-user', [TenantController::class, 'createAdminUser']);
            });

            Route::prefix('sync')->group(function () {
                Route::post('/subscription-plan-features', [SubscriptionPlanFeatureController::class, 'syncSubscriptionPlanFeatures']);
                Route::post('/subscription-plans', [SubscriptionPlanController::class, 'syncSubscriptionPlans']);
                Route::post('/subscription', [SubscriptionController::class, 'syncSubscription']);
                Route::post('/items', [ItemController::class, 'syncItems']);
                Route::prefix('brands')->group(function(){
                    Route::post('/', [BrandController::class, 'syncBrands']);
                    Route::delete('/', [BrandController::class, 'brandDelete']);
                });
                Route::post('/fiscal-years', [FiscalYearController::class,'syncFiscalYears']);
                Route::post('/sms', [SMSController::class, 'addFreeSMS']);
                Route::prefix('payment-methods')->group(function(){
                    Route::post('/', [PaymentMethodController::class, 'syncPaymentMethods']);
                    Route::delete('/', [PaymentMethodController::class, 'paymentMethodDelete']);
                });
                Route::prefix('job-positions')->group(function(){
                    Route::post('/', [JobPositionController::class, 'syncJobPositions']);
                    Route::delete('/', [JobPositionController::class, 'jobPositionDelete']);
                });
            });
        });
    });

    // Location
    Route::get('/locations', [LocationController::class, 'locations']);
    Route::get('/divisions', [LocationController::class, 'division']);
    Route::get('/divisions/{division}/districts', [LocationController::class, 'districtByDivision']);
    Route::get('/districts/{district}/upazilas', [LocationController::class, 'upazilaByDistrict']);
    Route::get('/upazilas/{upazila}/unions', [LocationController::class, 'unionByUpazila']);

    Route::get('/health-check', [HealthCheckController::class, 'index']);
    Route::get('/health-check-test', [HealthCheckController::class, 'index']);
});

