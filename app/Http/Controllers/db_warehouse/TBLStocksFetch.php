<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * db_warehouse/TBLStocksFetchPaginateSearch?group=ctrl_no&keyword=1&page=1
 * 
 * \App\Http\Controllers\db_warehouse\TBLStocksFetch::fetchByItemNo($item_no);
 * db_warehouse/TBLStocksFetchFetchByItemNo?item_no=[STR]&bodega=[STR]
 */

class TBLStocksFetch extends Controller
{
    public static function paginateSearch(Request $request) {
        if($request['group'] == 'ctrl_no') {
            return DB::connection('db_warehouse')->table('tbl_stocks')
            ->where('ctrl_no', 'like', $request['keyword'] . '%')
            ->orderBy('ctrl_no', 'asc')
            ->paginate(12);
        }
        else if($request['group'] == 'item_no') {
            return DB::connection('db_warehouse')->table('tbl_stocks')
            ->where('item_no', 'like', $request['keyword'] . '%')
            ->orderBy('item_no', 'asc')
            ->paginate(12);
        }
        else if($request['group'] == 'itemcode') {
            return DB::connection('db_warehouse')->table('tbl_stocks')
            ->where('itemcode', 'like', $request['keyword'] . '%')
            ->orderBy('itemcode', 'asc')
            ->paginate(12);
        }
        else if($request['group'] == 'barcode') {
            return DB::connection('db_warehouse')->table('tbl_stocks')
            ->where('barcode', 'like', $request['keyword'] . '%')
            ->orderBy('barcode', 'asc')
            ->paginate(12);
        }
        else if($request['group'] == 'd_desc') {
            return DB::connection('db_warehouse')->table('tbl_stocks')
            ->where('d_desc', 'like', $request['keyword'] . '%')
            ->orderBy('d_desc', 'asc')
            ->paginate(12);
        }
        else if($request['group'] == 'scan') {
            return DB::connection('db_warehouse')->table('tbl_stocks')
            ->where( function ($query) use ($request) {
                $query
                    ->where('itemcode', 'LIKE', $request['keyword'])
                    ->orWhere('barcode', 'LIKE', $request['keyword']);
            })
            ->orderBy('d_desc', 'asc')
            ->paginate(12);
        }
        else {
            return DB::connection('db_warehouse')->table('tbl_stocks')
            ->orderBy('itemcode', 'asc')
            ->paginate(12);
        }
    }

    public static function fetchByItemNo(Request $request) {

        $source = DB::connection('db_warehouse')->table('tbl_stocks')
        ->where('item_no', $request['item_no'])
        ->get();

        if(count($source) > 0) {

            $extract = DB::connection('db_warehouse')
            ->table('tbl_stocks')
            ->select(DB::raw("RIGHT(LEFT(locator,2),1) AS ExLoc"))
            ->where("item_no", $source[0]->item_no)
            ->limit(50)
            ->get();

            $permitted = false;
            if(count($extract) > 0) {
                $extracted = $extract[0]->ExLoc;
                if($request['bodega'] === $extract[0]->ExLoc) {
                    $permitted = true;
                }
                else {
                    if(str_contains($source[0]->locator, 'P8') && $request['bodega'] == '1') {
                        $permitted = true;
                    }
                }
            }

            return [
                "success"       => true,
                "header"        => $source[0],
                "locators"      => \App\Http\Controllers\db_warehouse\TBLStocksLocator::fetchLocators($source[0]->item_no),
                "photos"        => [],
                "permissions"   => [
                    "add_locator"   => $permitted,
                    "quantity"      => $permitted,
                    "actual"        => $permitted,
                    "receive"       => $permitted
                ],
                "references"    => [
                    "locator"       => $source[0]->locator,
                    "locator_ext"   => $extracted,
                    "bodega"        => $request['bodega'],
                    "item_no"       => $request['item_no']
                ]
            ];
        }
        else {
            return [
                "success"   => false,
                "header"    => [],
                "locators"  => [],
                "photos"    => []
            ];
        }
    }

    public static function test(Request $request) {
        return DB::connection('db_warehouse')
        ->table('tbl_stocks')
        ->select(DB::raw("RIGHT(LEFT(locator,2),1)"))
        ->limit(50)
        ->get();
    }

    

}
