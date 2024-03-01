<?php

use App\Http\Controllers\Administration\BankController;
use App\Http\Controllers\Users\DepartmentController;
use App\Http\Controllers\Administration\ProviderController;
use App\Http\Controllers\Administration\UnitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\Payments\PurchaseRequestController;
use App\Http\Controllers\Payments\PurchaseRequestObservationController;
use App\Http\Controllers\Payments\QuoteConceptController;
use App\Http\Controllers\Payments\QuoteController;
use App\Http\Controllers\Payments\QuoteFileController;
use App\Http\Controllers\Payments\QuoteObservationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Users\RoleController;
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

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //roles
    Route::apiResource('role', RoleController::class);
    Route::get('/select/role', [RoleController::class, 'select']);
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
    Route::get('/select/account/provider/{id}', [ProviderController::class, 'selectAccountsByProvider']);

    //files
    Route::apiResource('/file', FileController::class);
    Route::get('/download/{file}', [FileController::class, 'download']);
    Route::delete('/{model_id}/{model_type}/destroy/files', [FileController::class, 'destroyByModel']);

    //quoteConcepts
    Route::get('/select/charge', [QuoteConceptController::class, 'getCharges']);
    Route::get('/select/concept/charge', [QuoteConceptController::class, 'getConceptsByCharge']);

    //units
    Route::get('/select/line', [UnitController::class, 'getLines']);
    Route::get('/select/unit/line', [UnitController::class, 'getUnitsByLine']);

    //quotes
    Route::apiResource('/quote', QuoteController::class);
    Route::apiResource('/quoteObservation', QuoteObservationController::class);
    Route::post('/send/pay/quote/{quote}', [QuoteController::class, 'sendPay']);

    //quoteFiles
    Route::apiResource('/quoteFile', QuoteFileController::class);
    Route::get('/quoteFileDownload/{file}', [QuoteFileController::class, 'download']);
    Route::delete('/{quote_id}/destroy/quoteFiles', [QuoteFileController::class, 'destroyByQuote']);

    //purchaseRequest
    Route::apiResource('/purchaseRequest', PurchaseRequestController::class);
    Route::apiResource('/purchaseRequestObservation', PurchaseRequestObservationController::class);
    Route::get('/pdf/purchaseRequest/export/{purchaseRequest}', [PurchaseRequestController::class, 'exportPDF']);
    Route::get('/pending/details/purchaseRequest', [PurchaseRequestController::class, 'showPendingPaymentDetails']);
    Route::put('/reject/purchaseRequest/{purchaseRequest}', [PurchaseRequestController::class, 'reject']);
    Route::put('/pay/purchaseRequest/{purchaseRequest}', [PurchaseRequestController::class, 'pay']);
    Route::get('/pending/purchaseRequest', [PurchaseRequestController::class, 'getPendingPayments']);
    Route::get('/balance/purchaseRequestDetail/{id}', [PurchaseRequestController::class, 'getBalancePayments']);

    //users
    Route::apiResource('/user', UserController::class);
    Route::get('/auth/user', [AuthController::class, 'getUser']);

    //modules
    Route::apiResource('/module', ModuleController::class);
    Route::post('/assign/module', [ModuleController::class, 'assign']);

    //permissions
    Route::apiResource('/permission', PermissionController::class);
    Route::post('/assign/permission', [PermissionController::class, 'assign']);
});
