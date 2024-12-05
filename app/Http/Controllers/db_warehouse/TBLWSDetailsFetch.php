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
        $source = DB::connection('db_warehouse')
            ->table('tbl_wsdetail')
            ->where('ctrl_no', $ctrl_no)
            ->orderby('rec_no', 'asc')
            ->get();
            
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
                    "stock"     => \App\Http\Controllers\db_warehouse\ObjectParser::tbl_stocks($stock),
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

    public static function inputReleaseDone(Request $request) {
        $items  = DB::connection('db_warehouse')->table('tbl_wsdetail')->where("ctrl_no", $request['ctrl_no'])->get();
        $sum    = DB::connection('db_warehouse')
                    ->table('tbl_stock_locator_history')
                    ->where([
                        ['ws_ctrl_no', $request['ctrl_no']],
                        ['ws_detail_ctrl_no', $request['ws_detail_ctrl_no']],
                    ])
                    ->sum('quantity');
        if(count($items) == 0) {
            return [
                "success"   => false,
                "message"   => "No record found"
            ];
        }
        else {
            foreach($items as $item) {
                if(floatval($item['rel_unit']) < 0) {
                    return [
                        "success"   => false,
                        "message"   => "Error: Input quantity cannot be less than zero. Please enter a valid value."
                    ];
                    break;
                }
                else if(floatval($item['rel_unit']) > floatval($item['qty_unit'])) {
                    return [
                        "success"   => false,
                        "message"   => "Error: Input quantity exceeds locator quantity. Please verify and adjust the values."
                    ];
                    break;
                }
                else if(($sum > floatval($item['qty_unit'])) && ($request['mode'] !== '5-RESTOCK')) {
                    return [
                        "success"   => false,
                        "message"   => "Error: Release quantity cannot exceed the requested quantity"
                    ];
                    break;
                }
                else {
                    return [
                        "success"   => true,
                        "message"   => "Done"
                    ];
                }
            }
        }
    }
}
