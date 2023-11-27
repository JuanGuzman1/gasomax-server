<?php

use App\Http\Controllers\Administration\BankController;
use App\Http\Controllers\Administration\ProviderController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//banks
Route::apiResource('bank', BankController::class);

//providers
Route::apiResource('/provider', ProviderController::class);
Route::get('/excel/provider/export', [ProviderController::class, 'export']);

//files
Route::apiResource('/file', FileController::class);
Route::get('/download/{file}', [FileController::class, 'download']);
Route::delete('/{model_id}/{model_type}/destroy/files', [FileController::class, 'destroyByModel']);
