<?php

use App\Http\Controllers\Administration\BankController;
use App\Http\Controllers\Users\DepartmentController;
use App\Http\Controllers\Administration\ProviderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\Payments\PurchaseRequestController;
use App\Http\Controllers\Payments\PurchaseRequestObservationController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    //departments
    Route::apiResource('department', DepartmentController::class);
    Route::get('/select/department', [DepartmentController::class, 'select']);
    //banks
    Route::apiResource('bank', BankController::class);
    Route::get('/select/bank', [BankController::class, 'select']);

    //providers
    Route::apiResource('/provider', ProviderController::class);
    Route::get('/excel/provider/export', [ProviderController::class, 'export']);
    Route::get('/select/provider', [ProviderController::class, 'select']);

    //files
    Route::apiResource('/file', FileController::class);
    Route::get('/download/{file}', [FileController::class, 'download']);
    Route::delete('/{model_id}/{model_type}/destroy/files', [FileController::class, 'destroyByModel']);

    //purchaseRequest
    Route::apiResource('/purchaseRequest', PurchaseRequestController::class);
    Route::apiResource('/purchaseRequestObservation', PurchaseRequestObservationController::class);
    Route::get('/pdf/purchaseRequest/export/{purchaseRequest}', [PurchaseRequestController::class, 'exportPDF']);
    Route::get('/pending/details/purchaseRequest', [PurchaseRequestController::class, 'showPendingPaymentDetails']);
    Route::put('/reject/purchaseRequest/{purchaseRequest}', [PurchaseRequestController::class, 'reject']);
    Route::put('/approve/purchaseRequest/{purchaseRequest}', [PurchaseRequestController::class, 'approve']);
    Route::get('/pending/purchaseRequest', [PurchaseRequestController::class, 'getPendingPayments']);
    Route::get('/balance/purchaseRequestDetail/{id}', [PurchaseRequestController::class, 'getBalancePayments']);

    //users
    Route::apiResource('/user', UserController::class);
    Route::get('/auth/user', [AuthController::class, 'getUser']);
});

//modules
Route::apiResource('/module', ModuleController::class);
Route::post('/assign/module', [ModuleController::class, 'assign']);
