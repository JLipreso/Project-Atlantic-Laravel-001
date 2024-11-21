<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * \App\Http\Controllers\db_warehouse\TBLStocksUpdate::updateWSTotal($item_no);
 */

class TBLStocksUpdate extends Controller
{
    public static function updateWSTotal($item_no) {
        $total = DB::connection('db_warehouse')
            ->table('tbl_stocks_locator')
            ->where([
                ['item_no', $item_no],
                ['quantity', '>', 0]
            ])
            ->sum('quantity');
        $update = DB::connection('db_warehouse')
            ->table('tbl_stocks')
            ->where('item_no', $item_no)
            ->update([
                "ws_total" => $total
            ]);

        return $update;
    }
}
