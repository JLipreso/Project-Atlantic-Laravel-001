<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * db_warehouse/TBLLocatorHistoryCreateWithdrawal?stock_locator_id=3&stock_action=OUT&quantity=99&qty_old=500.00&ws_ctrl_no=182125&ws_detail_ctrl_no=1527881&created_by=140
 * \App\Http\Controllers\db_warehouse\TBLLocatorHistory::picking($ws_ctrl_no, $ws_detail_ctrl_no);
 * 
 */

class TBLLocatorHistory extends Controller
{
    public static function createWithdrawal(Request $request) {
        
        if(($request['stock_action'] == 'OUT') && (intval($request['qty_old']) < intval($request['quantity']))) {
            return [
                "success"   => false,
                "message"   => "Maximum valid quantity is " .$request['qty_old']
            ];
        }
        else if(($request['stock_action'] == 'IN') && (intval($request['quantity']) <= 0)) {
            return [
                "success"   => false,
                "message"   => "The quantity must be a valid number greater than zero (0)"
            ];
        }
        else {
            $created = DB::connection("db_warehouse")->table("tbl_stock_locator_history")
            ->updateOrInsert([
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
                    "message"   => "Saved successfuly"
                ];
            }
            else {
                return [
                    "success"   => false,
                    "message"   => "Fail to save, try again later."
                ];
            }
        }
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
                }
        
                $list[] = [
                    "stock_locator_id"  => $stock_locator_id,
                    "quantity"          => $quantity,
                    "deduct_quantity"   => $deduct_quantity
                ];
            }

            return $list;

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