<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 
 */

class TBLLocatorFetch extends Controller
{
    public static function search($keyword) {
        return DB::connection('db_warehouse')->table('tbl_locator')->where('locator', 'like', $keyword.'%')->orderBy('locator','asc')->get();
    }

    public static function fetchLocationWithProduct($ctrl_no) {

        $locator = DB::connection('db_warehouse')
                    ->table('tbl_stocks_locator')
                    ->where('ctrl_no', $ctrl_no)
                    ->get();

        $product = DB::connection('db_warehouse')
                    ->table('tbl_stocks')
                    ->where('item_no', $locator[0]->item_no)
                    ->get();
        
        $reasons    = DB::connection('db_warehouse')
                    ->table('tbl_wms_reason')
                    ->orderBy('reason', 'asc')
                    ->get();

        return [
            "locator"   => $locator[0],
            "product"   => $product[0],
            "reasons"   => $reasons
        ];
    }

    public static function report(Request $request) {
        $created = DB::connection('db_warehouse')
                ->table('tbl_wms_report')
                ->insert([
                    "item_no"           => $request['item_no'],
                    "itemcode"          => $request['itemcode'],
                    "locator"           => $request['locator'],
                    "reason"            => $request['reason'],
                    "remarks"           => $request['remarks'],
                    "created_at"        => date('Y-m-d h:i:s'),
                    "created_by"        => $request['created_by'],
                    "created_by_name"   => $request['created_by_name'],
                ]);
        
        if($created) {
            return [ "success" => true ];
        }
        else {
            return [ "success" => false ];
        }
    }
}
