<?php

namespace App\Http\Controllers\util_generator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * util_generator/randomStockLocator?page=1
 */

class RandomStockLocator extends Controller
{
    public static function generate(Request $request) {
        $source = DB::connection('db_warehouse')
                ->table('tbl_wsdetail')
                ->select('item_no', 'itemcode')
                ->orderBy('item_no', 'asc')
                ->distinct('item_no')
                ->paginate(150)
                ->toArray();

        $data   = $source['data'];
        $list   = [];

        foreach($data as  $index => $stock) {
            $list[] = [
                "row_no"        => $source['from'] + $index,
                "item_no"       => $stock->item_no,     
                "itemcode"      => $stock->itemcode,  
                "create"        => RandomStockLocator::create($stock->item_no, $stock->itemcode)        
            ];
        }
        return \App\Http\Controllers\util_parser\Paginator::parse($source, $list);
    }

    public static function create($item_no, $itemcode) {
        $created = DB::connection('db_warehouse')->table('tbl_stocks_locator')->insert([
            "item_no"           => $item_no,
            "itemcode"          => $itemcode,
            "locator"           => "P1-" . rand(100,999),
            "quantity"          => 567,
            "created_at"        => date('Y-m-d h:i:s'),
            "created_by"        => 140,
            "created_by_name"   => "TESTUSER1"
        ]);
    }
}
