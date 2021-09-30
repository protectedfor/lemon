<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrganizationsController;
use App\Http\Controllers\Api\PartnersController;
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
    Route::put('organization', [OrganizationsController::class, 'update']);

    Route::resource('partners', PartnersController::class);
});

Route::post('login', [AuthController::class, 'signin'])->name('login');
Route::post('login/verify/phone', [AuthController::class, 'verifyPhone']);

