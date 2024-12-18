<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * db_warehouse/TBLStocksLocatorCreate?
 * db_warehouse/TBLStocksLocatorUpdateActual?
 * 
 * \App\Http\Controllers\db_warehouse\TBLStocksLocator::fetchLocators($item_no);
 * 
 */

class TBLStocksLocator extends Controller
{
    public static function create(Request $request) {

        if($request['locator'] == '') {
            return [
                "success"   => false,
                "message"   => "Locator is required"
            ];
        }
        else if(floatval($request['quantity']) <= 0) {
            return [
                "success"   => false,
                "message"   => "Please provide quantity greater than zero."
            ];
        }
        else if(TBLStocksLocator::isExist($request['item_no'], $request['locator'])) {
            return [
                "success"   => false,
                "message"   => "Locator already exist"
            ];
        }
        else {
            $created = DB::connection('db_warehouse')->table('tbl_stocks_locator')->insert([
                "item_no"           => $request['item_no'],
                "itemcode"          => $request['itemcode'],
                "locator"           => strtoupper($request['locator']),
                "quantity"          => $request['quantity'],
                "created_at"        => date('Y-m-d h:i:s'),
                "created_by"        => $request['created_by'],
                "created_by_name"   => $request['created_by_name']
            ]);

            if($created) {
                \App\Http\Controllers\db_warehouse\TBLStocksUpdate::updateWSTotal($request['item_no']);
                return [
                    "success"   => true,
                    "message"   => "Successfully created"
                ];
            }
            else {
                return [
                    "success"   => false,
                    "message"   => "Fail to create locator"
                ];
            }
        }
    }

    public static function isExist($item_no, $locator) {
        $count = DB::connection('db_warehouse')
        ->table('tbl_stocks_locator')
        ->where([
            ['item_no', $item_no],
            ['locator', $locator],
            ['quantity', '>', 0]
        ])
        ->count();
        
        if($count > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function fetchLocators($item_no) {
        return DB::connection('db_warehouse')
        ->table('tbl_stocks_locator')
        ->where([
            ['item_no', $item_no],
            ['quantity', '>', 0]
        ])
        ->orderBy('locator','asc')
        ->get();
    }

    public static function updateActual(Request $request) {
        $updated = DB::connection('db_warehouse')
        ->table('tbl_stocks_locator')
        ->where('id', $request['id'])
        ->update([
            "quantity" => $request['quantity']
        ]);
        if($updated) {
            return [
                "success"   => true,
                "message"   => "Actual updated successfully."
            ];
        }
        else {
            return [
                "success"   => false,
                "message"   => "Fail to update, try again later."
            ];
        }
    }

    public static function updateReceive(Request $request) {
        $updated = DB::connection('db_warehouse')
        ->table('tbl_stocks_locator')
        ->where('id', $request['id'])
        ->increment('quantity', intval($request['quantity']));
        if($updated) {
            \App\Http\Controllers\db_warehouse\TBLStocksUpdate::updateWSTotal($request['item_no']);
            return [
                "success"   => true,
                "message"   => "Quantity updated successfully."
            ];
        }
        else {
            return [
                "success"   => false,
                "message"   => "Fail to update, try again later."
            ];
        }
    }

    public static function fetchLocatorStock($ctrl_no) {
        return [
            "header"    => [],
            "item_info" => []      
        ];
    }

    public static function isMyItem(Request $request) {

        $item_no    = $request['item_no'];
        $bodega     = $request['bodega'];

        $source = DB::connection('db_warehouse')
            ->table('tbl_stocks')
            ->select('locator')
            ->where('item_no', $item_no)
            ->get();

        if(count($source) > 0) {

            $locator    = $source[0]->locator;
            $length     = strlen($locator);
            $is_mine    = false;

            if(($length > 2) && (substr($locator,0,2) == 'P'. $bodega )) {
                $is_mine = true;
            }
            else if(($length == 0) && ($bodega == '1')) {
                $is_mine = true;
            }
            else {
                $is_mine = false;
            }

            return [
                "locator"   => $locator,
                "length"    => $length,
                "is_mine"   => $is_mine
            ];
        }
        else {
            return [
                "locator"   => null,
                "length"    => 0,
                "is_mine"   => false
            ];
        }
    }
}
