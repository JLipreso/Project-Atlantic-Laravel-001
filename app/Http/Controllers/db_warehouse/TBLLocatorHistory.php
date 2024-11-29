<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * db_warehouse/TBLLocatorHistoryCreateWithdrawal?stock_locator_id=3&stock_action=OUT&quantity=99&qty_old=500.00&ws_ctrl_no=182125&ws_detail_ctrl_no=1527881&created_by=140
 * \App\Http\Controllers\db_warehouse\TBLLocatorHistory::picking($ws_ctrl_no, $ws_detail_ctrl_no);
 * 
 * db_warehouse/TBLLocatorHistoryFetchReleaseSum?ws_ctrl_no=[INT]&ws_detail_ctrl_no=[INT]
 * 
 */

class TBLLocatorHistory extends Controller
{
    public static function createWithdrawal(Request $request) {
        $created = DB::connection("db_warehouse")->table("tbl_stock_locator_history")
            ->updateOrInsert(
            [
                "item_no"           => $request['item_no'],
                "itemcode"          => $request['itemcode'],
                "stock_action"      => $request['stock_action'],
                "stock_locator_id"  => $request['stock_locator_id'],
                "ws_ctrl_no"        => $request['ws_ctrl_no'],
                "ws_detail_ctrl_no" => $request['ws_detail_ctrl_no'],
            ],
            [
                "quantity"          => $request['quantity'],
                "qty_old"           => $request['qty_old'],
                "created_by"        => $request['created_by'],
                "created_at"        => date('Y-m-d h:i:s')
            ]
        );

        if($created) {
            return [
                "success"   => true,
                "message"   => "Saved successfully"
            ];
        }
        else {
            return [
                "success"   => false,
                "message"   => "Fail to save, try again later."
            ];
        }
    }

    public static function deleteWithdrawal(Request $request) {
        $deleted = DB::connection("db_warehouse")->table("tbl_stock_locator_history")
        ->where([
            ["stock_locator_id", $request['stock_locator_id']],
            ["ws_ctrl_no", $request['ws_ctrl_no']],
            ["ws_detail_ctrl_no", $request['ws_detail_ctrl_no']],
        ])
        ->delete();
        if($deleted) {
            return [ "success" => true ];
        }
        else {
            return [ "success" => false ];
        }
    }

    public static function cancelReleaseItem(Request $request) {
        $deleted = DB::connection("db_warehouse")->table("tbl_stock_locator_history")
        ->where([
            ["ws_ctrl_no", $request['ws_ctrl_no']],
            ["ws_detail_ctrl_no", $request['ws_detail_ctrl_no']],
        ])
        ->delete();
        if($deleted) {
            return [ "success" => true ];
        }
        else {
            return [ "success" => false ];
        }
    }

    public static function fetchReleaseSum(Request $request) {
        $sum = DB::connection('db_warehouse')
                ->table('tbl_stock_locator_history')
                ->where([
                    ['ws_ctrl_no', $request['ws_ctrl_no']],
                    ['ws_detail_ctrl_no', $request['ws_detail_ctrl_no']],
                    ['posted', 0]
                ])
                ->sum('quantity');
        return [
            "sum" => floatval($sum)
        ];
    }

    public static function postWithdrawal(Request $request) {
        $source = DB::connection('db_warehouse')
                ->table('tbl_stock_locator_history')
                ->where([
                    ['ws_ctrl_no', $request['ws_ctrl_no']],
                    ['posted', 0]
                ])
                ->get();
        
        if(count($source) > 0) {
            $list = [];
            foreach($source as $index => $record) {
                $stock_locator_id   = $record->stock_locator_id;
                $quantity           = floatval($record->quantity);
                $deduct_quantity    = DB::connection('db_warehouse')
                    ->table('tbl_stocks_locator')
                    ->where('id', $stock_locator_id)
                    ->decrement('quantity', $quantity);
                
                if($deduct_quantity) {
                    DB::connection('db_warehouse')
                    ->table('tbl_stock_locator_history')
                    ->where('id', $record->id)
                    ->update([
                        "posted"        => 1,
                        "posted_date"   => date('Y-m-d h:i:s')
                    ]);

                    $sum = DB::connection('db_warehouse')
                    ->table('tbl_stock_locator_history')
                    ->where([
                        ['ws_ctrl_no', $record->ws_ctrl_no],
                        ['ws_detail_ctrl_no', $record->ws_detail_ctrl_no]
                    ])
                    ->sum('quantity');

                    DB::connection('db_warehouse')
                    ->table('tbl_wsdetail')
                    ->where('recnum', $record->ws_detail_ctrl_no)
                    ->update([
                        'rel_unit' => $sum
                    ]);

                    /** Update item total stock */
                    \App\Http\Controllers\db_warehouse\TBLStocksUpdate::updateWSTotal($record->item_no);
                }
        
                $list[] = [
                    "stock_locator_id"  => $stock_locator_id,
                    "quantity"          => $quantity,
                    "deduct_quantity"   => $deduct_quantity
                ];
            }

            $post_header = DB::connection('db_warehouse')
                ->table('tbl_ws')
                ->where('ctrl_no', $request['ws_ctrl_no'])
                ->update([
                    'pickstop'      => date('h:i:s'),
                    'pickpost'      => 1
                ]);

            return [
                "success"   => true,
                "message"   => "Posted successfully",
                "data"      => $list
            ];

        }
        else {
            return [
                "success"   => false,
                "message"   => "No records to post, save quantity first!"
            ];
        }
    }

    public static function picking($ws_ctrl_no, $ws_detail_ctrl_no) {
        $source = DB::connection('db_warehouse')
        ->table('tbl_stock_locator_history')
        ->where([
            ['ws_ctrl_no', $ws_ctrl_no],
            ['ws_detail_ctrl_no', $ws_detail_ctrl_no],
            ['posted', 0]
        ])
        ->get();
        
        $rel_qty = 0;
        foreach($source as $picked) {
            $rel_qty = $rel_qty + floatval($picked->quantity);
        }

        return [
            "source"    => $source,
            "rel_qty"   => $rel_qty
        ];
    }
}
