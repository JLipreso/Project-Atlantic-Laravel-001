<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * db_warehouse/TBLWSPaginateSearch?group=ws_no&keyword=1&page=1
 */

class TBLWSFetch extends Controller
{
    public static function paginateSearch(Request $request) {
        if($request['group'] == 'ws_no') {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where('ws_no', 'like', $request['keyword'] . '%')
            ->whereNotNull('postdate')
            ->orderBy('ws_no', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if($request['group'] == 'customer') {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where('customer', 'like', $request['keyword'] . '%')
            ->whereNotNull('postdate')
            ->orderBy('customer', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if($request['group'] == 'invoice') {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where('invoice', 'like', $request['keyword'] . '%')
            ->whereNotNull('postdate')
            ->orderBy('invoice', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if($request['group'] == 'branch') {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where('branch', 'like', $request['keyword'] . '%')
            ->whereNotNull('postdate')
            ->orderBy('branch', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if($request['group'] == 'mode') {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where('mode', 'like', $request['keyword'] . '%')
            ->whereNotNull('postdate')
            ->orderBy('mode', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if($request['group'] == 'department') {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where('department', 'like', $request['keyword'] . '%')
            ->whereNotNull('postdate')
            ->orderBy('department', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->orderBy('ctrl_no', 'desc')
            ->whereNotNull('postdate')
            ->paginate(12)
            ->toArray();
        }

            $data           = $source['data'];
            $list           = [];

            foreach($data as  $index => $withdrawal) {
                $list[] = [
                    "row_no" => $source['from'] + $index,
                    ...\App\Http\Controllers\db_warehouse\ObjectParser::tbl_ws($withdrawal),
                    "counts" => \App\Http\Controllers\db_warehouse\TBLWSDetailsFetch::countDetails($withdrawal->ctrl_no)
                ];
            }

            return \App\Http\Controllers\util_parser\Paginator::parse($source, $list);
    }

    public static function profile($ctrl_no) {
        $source = DB::connection('db_warehouse')->table('tbl_ws')->where('ctrl_no', $ctrl_no)->get();
        if(count($source) > 0) {
            return [
                "success"   => true,
                "header"    => $source[0],
                "child"     => \App\Http\Controllers\db_warehouse\TBLWSDetailsFetch::fetchDetails($ctrl_no)
            ];
        }
        else {
            return [
                "success"   => false,
                "header"    => [],
                "child"     => []
            ];
        }
    }
}
