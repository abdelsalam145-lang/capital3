<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;

/*
 | مسارات API. محمية بـ auth:sanctum (تخدم الويب والجوال معاً).
 */
// تسجيل الدخول (عام)
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('me',      [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);


    // ===== وحدة الطلبات =====
    Route::get('requests',                      [MaintenanceRequestController::class, 'index']);
    Route::post('requests',                     [MaintenanceRequestController::class, 'store']);
    Route::get('requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'show']);
    Route::post('requests/{maintenanceRequest}/assign',   [MaintenanceRequestController::class, 'assign']);
    Route::post('requests/{maintenanceRequest}/cancel',   [MaintenanceRequestController::class, 'cancel']);
    Route::post('requests/{maintenanceRequest}/start',    [MaintenanceRequestController::class, 'start']);
    Route::post('requests/{maintenanceRequest}/complete', [MaintenanceRequestController::class, 'complete']);
    Route::post('requests/{maintenanceRequest}/rate',     [RatingController::class, 'store']);

    // ===== وحدة العقود =====
    Route::get('contracts',              [ContractController::class, 'index']);
    Route::post('contracts',             [ContractController::class, 'store']);
    Route::get('contracts/{contract}',   [ContractController::class, 'show']);
    Route::patch('contracts/{contract}', [ContractController::class, 'update']);
    Route::post('contracts/{contract}/generate-invoice', [InvoiceController::class, 'generateFromContract']);

    // ===== وحدة الفواتير =====
    Route::get('invoices',                  [InvoiceController::class, 'index']);
    Route::post('invoices',                 [InvoiceController::class, 'store']);
    Route::get('invoices/{invoice}',        [InvoiceController::class, 'show']);
    Route::post('invoices/{invoice}/issue', [InvoiceController::class, 'issue']);

    // ===== وحدة المدفوعات =====
    Route::post('invoices/{invoice}/pay-manual', [PaymentController::class, 'recordManual']);
    Route::post('invoices/{invoice}/pay-online', [PaymentController::class, 'payOnline']);

    // ===== وحدة المخزون وقطع الغيار =====
    Route::get('warehouses',  [\App\Http\Controllers\WarehouseController::class, 'index']);
    Route::post('warehouses', [\App\Http\Controllers\WarehouseController::class, 'store']);

    Route::get('parts',                  [\App\Http\Controllers\PartController::class, 'index']);
    Route::post('parts',                 [\App\Http\Controllers\PartController::class, 'store']);
    Route::get('parts/{part}',           [\App\Http\Controllers\PartController::class, 'show']);
    Route::patch('parts/{part}',         [\App\Http\Controllers\PartController::class, 'update']);
    Route::get('parts/{part}/movements', [\App\Http\Controllers\PartController::class, 'movements']);
    Route::post('parts/{part}/receive',  [\App\Http\Controllers\PartController::class, 'receive']);
    Route::post('parts/{part}/adjust',   [\App\Http\Controllers\PartController::class, 'adjust']);
    Route::post('parts/{part}/transfer', [\App\Http\Controllers\PartController::class, 'transfer']);

    // القطع المستخدمة في الطلبات + فوترتها
    Route::get('requests/{maintenanceRequest}/parts',  [\App\Http\Controllers\PartUsageController::class, 'index']);
    Route::post('requests/{maintenanceRequest}/parts', [\App\Http\Controllers\PartUsageController::class, 'store']);
    Route::post('requests/{maintenanceRequest}/parts/bill', [\App\Http\Controllers\PartUsageController::class, 'bill']);

    // ===== لوحة التقارير والمؤشرات =====
    Route::get('dashboard',          [\App\Http\Controllers\DashboardController::class, 'index']);
    Route::get('dashboard/overview', [\App\Http\Controllers\DashboardController::class, 'overview']);
});

// عودة بوابة الدفع / webhook (قد لا تتطلب مصادقة حسب المزوّد)
Route::match(['get', 'post'], 'payments/callback', [PaymentController::class, 'callback'])
    ->name('payments.callback');
