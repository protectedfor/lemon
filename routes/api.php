<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceTransactionController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\OrganizationPartnerController;
use App\Http\Controllers\Api\OrganizationPartnerInvoiceController;
use App\Http\Controllers\Api\OrganizationInvoiceController;
use App\Http\Controllers\Api\OrganizationReportController;
use App\Http\Controllers\Api\SliderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'signout']);
    Route::get('options/invoice', [OrganizationPartnerInvoiceController::class, 'options']);

    Route::get('organizations/search', [OrganizationController::class, 'search']);
    Route::apiResource('organizations', OrganizationController::class)->shallow();
    Route::apiResource('organizations.partners', OrganizationPartnerController::class)->shallow();
    Route::apiResource('organizations.reports', OrganizationReportController::class)->shallow();
    Route::apiResource('organizations.invoices', OrganizationInvoiceController::class)->shallow();
    Route::apiResource('organizations.partners.invoices', OrganizationPartnerInvoiceController::class)->shallow();
    Route::apiResource('invoices.transactions', InvoiceTransactionController::class)->shallow();
});

Route::get('sliders', [SliderController::class, 'index']);

Route::post('login', [AuthController::class, 'signin'])->name('login');
Route::post('login/verify/phone', [AuthController::class, 'verifyPhone']);

