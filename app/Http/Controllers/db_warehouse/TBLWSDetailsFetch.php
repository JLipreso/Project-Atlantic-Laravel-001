<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * \App\Http\Controllers\db_warehouse\TBLWSDetailsFetch::fetchDetails($ws_no);
 * \App\Http\Controllers\db_warehouse\TBLWSDetailsFetch::countDetails($ctrl_no);
 */

class TBLWSDetailsFetch extends Controller
{
    public static function fetchDetails($ctrl_no) {
        $source = DB::connection('db_warehouse')->table('tbl_wsdetail')->where('ctrl_no', $ctrl_no)->get();
        if(count($source) > 0) {
            $list = [];
            foreach($source as $item) {
                $stock      = DB::connection('db_warehouse')
                                ->table('tbl_stocks')
                                ->where('item_no', $item->item_no)
                                ->get()[0];
                $locators   = DB::connection('db_warehouse')
                                ->table('tbl_stocks_locator')
                                ->where([
                                    ['item_no', $item->item_no],
                                    ['quantity', '>', 0]
                                ])
                                ->orderBy('locator', 'asc')
                                ->get();
                $list[]     = [
                    "item"      => $item,
                    "stock"     => $stock,
                    "locators"  => $locators,
                    "picking"   => \App\Http\Controllers\db_warehouse\TBLLocatorHistory::picking($ctrl_no, $item->recnum)
                ];
            }
            return $list;
        }
        else {
            return [];
        }
    }

    public static function countDetails($ctrl_no) {
        return DB::connection('db_warehouse')->table('tbl_wsdetail')->where('ctrl_no', $ctrl_no)->count();
    }
}
