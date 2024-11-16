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
    Route::get('verifyPostcode', [App\Http\Controllers\db_accounts\Postcode::class, 'verify']);
    Route::get('signIn', [App\Http\Controllers\db_accounts\SignIn::class, 'signin']);
});

Route::group(['prefix' => 'db_warehouse'], function () {
    Route::get('TBLStocksUpdateWSTotal/{item_no}', [App\Http\Controllers\db_warehouse\TBLStocksUpdate::class, 'updateWSTotal']);
    Route::get('TBLWSFetchDashboard', [App\Http\Controllers\db_warehouse\TBLWSFetch::class, 'dashboard']);
    Route::get('PrintWithdrawalSlip', [App\Http\Controllers\db_warehouse\PrintWithdrawalSlip::class, 'print']);
    Route::get('TBLLocatorFetchSearch/{keyword}', [App\Http\Controllers\db_warehouse\TBLLocatorFetch::class, 'search']);
    Route::get('TBLLocatorFetchLocationWithProduct/{ctrl_no}', [App\Http\Controllers\db_warehouse\TBLLocatorFetch::class, 'fetchLocationWithProduct']);
    Route::get('TBLLocatorFetchReport', [App\Http\Controllers\db_warehouse\TBLLocatorFetch::class, 'report']);
    Route::get('TBLStocksFetchPaginateSearch', [App\Http\Controllers\db_warehouse\TBLStocksFetch::class, 'paginateSearch']);
    Route::get('TBLStocksFetchFetchByItemNo', [App\Http\Controllers\db_warehouse\TBLStocksFetch::class, 'fetchByItemNo']);
    Route::get('TBLStocksFetchScanBarcodeItemCode', [App\Http\Controllers\db_warehouse\TBLStocksFetch::class, 'scanBarcodeItemCode']);
    Route::get('TBLStocksLocatorCreate', [App\Http\Controllers\db_warehouse\TBLStocksLocator::class, 'create']);
    Route::get('TBLStocksLocatorUpdateActual', [App\Http\Controllers\db_warehouse\TBLStocksLocator::class, 'updateActual']);
    Route::get('TBLStocksLocatorUpdateReceive', [App\Http\Controllers\db_warehouse\TBLStocksLocator::class, 'updateReceive']);
    Route::get('TBLStocksLocatorFetchLocatorStock/{ctrl_no}', [App\Http\Controllers\db_warehouse\TBLStocksLocator::class, 'fetchLocatorStock']);
    Route::get('TBLLocatorHistoryCreateWithdrawal', [App\Http\Controllers\db_warehouse\TBLLocatorHistory::class, 'createWithdrawal']);
    Route::get('TBLLocatorHistoryDeleteWithdrawal', [App\Http\Controllers\db_warehouse\TBLLocatorHistory::class, 'deleteWithdrawal']);
    Route::get('TBLLocatorHistoryFetchReleaseSum', [App\Http\Controllers\db_warehouse\TBLLocatorHistory::class, 'fetchReleaseSum']);
    Route::get('TBLLocatorHistoryCancelReleaseItem', [App\Http\Controllers\db_warehouse\TBLLocatorHistory::class, 'cancelReleaseItem']);
    Route::get('TBLLocatorHistoryPostWithdrawal', [App\Http\Controllers\db_warehouse\TBLLocatorHistory::class, 'postWithdrawal']);
    Route::get('TBLWSPaginateSearch', [App\Http\Controllers\db_warehouse\TBLWSFetch::class, 'paginateSearch']);
    Route::get('TBLWSFetchUpdatePickStart', [App\Http\Controllers\db_warehouse\TBLWSFetch::class, 'updatePickStart']);
    Route::get('TBLWSProfile/{ctrl_no}', [App\Http\Controllers\db_warehouse\TBLWSFetch::class, 'profile']);
});

Route::group(['prefix' => 'util_generator'], function () {
    Route::get('randomStockLocator', [App\Http\Controllers\util_generator\RandomStockLocator::class, 'generate']);
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE';
});
