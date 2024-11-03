<?php

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

Route::group(['prefix' => 'db_accounts'], function () {
    Route::get('signIn', [App\Http\Controllers\db_accounts\SignIn::class, 'signin']);
});

Route::group(['prefix' => 'db_warehouse'], function () {

    Route::get('PrintWithdrawalSlip', [App\Http\Controllers\db_warehouse\PrintWithdrawalSlip::class, 'print']);
    Route::get('TBLLocatorFetchSearch/{keyword}', [App\Http\Controllers\db_warehouse\TBLLocatorFetch::class, 'search']);
    Route::get('TBLStocksFetchPaginateSearch', [App\Http\Controllers\db_warehouse\TBLStocksFetch::class, 'paginateSearch']);
    Route::get('TBLStocksFetchFetchByItemNo', [App\Http\Controllers\db_warehouse\TBLStocksFetch::class, 'fetchByItemNo']);
    Route::get('TBLStocksFetchScanBarcodeItemCode', [App\Http\Controllers\db_warehouse\TBLStocksFetch::class, 'scanBarcodeItemCode']);
    Route::get('TBLStocksLocatorCreate', [App\Http\Controllers\db_warehouse\TBLStocksLocator::class, 'create']);
    Route::get('TBLStocksLocatorUpdateActual', [App\Http\Controllers\db_warehouse\TBLStocksLocator::class, 'updateActual']);
    Route::get('TBLStocksLocatorUpdateReceive', [App\Http\Controllers\db_warehouse\TBLStocksLocator::class, 'updateReceive']);
    Route::get('TBLLocatorHistoryCreateWithdrawal', [App\Http\Controllers\db_warehouse\TBLLocatorHistory::class, 'createWithdrawal']);
    Route::get('TBLLocatorHistoryPostWithdrawal', [App\Http\Controllers\db_warehouse\TBLLocatorHistory::class, 'postWithdrawal']);
    Route::get('TBLWSPaginateSearch', [App\Http\Controllers\db_warehouse\TBLWSFetch::class, 'paginateSearch']);
    Route::get('TBLWSFetchUpdatePickStart', [App\Http\Controllers\db_warehouse\TBLWSFetch::class, 'updatePickStart']);
    Route::get('TBLWSProfile/{ctrl_no}', [App\Http\Controllers\db_warehouse\TBLWSFetch::class, 'profile']);
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE';
});
