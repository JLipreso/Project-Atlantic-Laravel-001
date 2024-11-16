<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * db_warehouse/TBLWSPaginateSearch?group=ws_no&keyword=1&bodega=1&page=1
 * \App\Http\Controllers\db_warehouse\TBLWSFetch::profile($ctrl_no);
 */

class TBLWSFetch extends Controller
{
    public static function dashboard(Request $request) {
        if($request['bodega'] == '1') {
            return TBLWSFetch::bodega1($request);
        }
        else {
            return TBLWSFetch::bodega23456($request);
        }
    }

    public static function bodega1($request) {
        $fiverestock        = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%5-RESTOCK%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $priosr             = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%PRIO-SR%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $threesr            = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%3-S/R%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $twodel             = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%2-DEL%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $fourpriority       = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%4-PRIORITY%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $onepup             = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%1-P/UP%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $empty              = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', ''],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $completion         = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%COMPLETION%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $motorpool          = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%MOTORPOOL%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        $sixshipment        = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%6-SHIPMENT%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0]
                                ])
                                ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
                                ->count();
        
        return [
            "fiverestock"   => $fiverestock,
            "priosr"        => $priosr,
            "threesr"       => $threesr,
            "twodel"        => $twodel,
            "fourpriority"  => $fourpriority,
            "onepup"        => $onepup,
            "empty"         => $empty,
            "completion"    => $completion,
            "motorpool"     => $motorpool,
            "sixshipment"   => $sixshipment
        ];
    }

    public static function bodega23456($request) {

        $fiverestock        = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%5-RESTOCK%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $priosr             = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%PRIO-SR%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $threesr            = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%3-S/R%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $twodel             = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%2-DEL%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $fourpriority       = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%4-PRIORITY%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $onepup             = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%1-P/UP%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $empty              = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', ''],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $completion         = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%COMPLETION%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $motorpool          = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%MOTORPOOL%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        $sixshipment        = DB::connection('db_warehouse')
                                ->table('tbl_ws')
                                ->where([
                                    ['mode', 'LIKE', '%6-SHIPMENT%'],
                                    ['post','=', 0],
                                    ['pickpost','=', 0],
                                    [DB::raw('RIGHT(department,1)'), $request['bodega']]
                                ])
                                ->count();
        
        return [
            "fiverestock"   => $fiverestock,
            "priosr"        => $priosr,
            "threesr"       => $threesr,
            "twodel"        => $twodel,
            "fourpriority"  => $fourpriority,
            "onepup"        => $onepup,
            "empty"         => $empty,
            "completion"    => $completion,
            "motorpool"     => $motorpool,
            "sixshipment"   => $sixshipment
        ];
    }

    public static function paginateSearch(Request $request) {
        if($request['bodega'] == '1') {
            return TBLWSFetch::paginateBodega1($request);
        }
        else {
            return TBLWSFetch::paginateBodega23456($request);
        }
    }

    public static function paginateBodega1($request) {
        if(($request['group'] == 'ws_no') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['ws_no', 'LIKE', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0]
            ])
            ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
            ->orderBy('ws_no', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'customer') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['customer', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0]
            ])
            ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
            ->orderBy('customer', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'invoice') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['invoice', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0]
            ])
            ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
            ->orderBy('invoice', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'branch') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['branch', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0]
            ])
            ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
            ->orderBy('branch', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'mode') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where()
            ->where([
                ['mode', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0]
            ])
            ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
            ->orderBy('mode', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'department') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['department', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0]
            ])
            ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
            ->orderBy('department', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'ctrl_no') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['ctrl_no', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0]
            ])
            ->whereNotIn(DB::raw('RIGHT(department,1)'), [2,3,4,5,6])
            ->orderBy('department', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('ctrl_no', 'desc')
            ->paginate(12)
            ->toArray();
        }

        $data           = $source['data'];
        $list           = [];

        foreach($data as  $index => $withdrawal) {
            $list[] = [
                "row_no" => $source['from'] + $index,
                ...\App\Http\Controllers\db_warehouse\ObjectParser::tbl_ws($withdrawal),
                "counts" => \App\Http\Controllers\db_warehouse\TBLWSDetailsFetch::countDetails($withdrawal->ctrl_no),
            ];
        }

        return \App\Http\Controllers\util_parser\Paginator::parse($source, $list);
    }

    public static function paginateBodega23456($request) {
        if(($request['group'] == 'ws_no') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['ws_no', 'LIKE', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('ws_no', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'customer') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['customer', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('customer', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'invoice') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['invoice', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('invoice', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'branch') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['branch', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('branch', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'mode') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where()
            ->where([
                ['mode', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('mode', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'department') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['department', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('department', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else if(($request['group'] == 'ctrl_no') && ($request['keyword'] !== '' )) {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['ctrl_no', 'like', $request['keyword'] . '%'],
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('department', 'asc')
            ->paginate(12)
            ->toArray();
        }
        else {
            $source = DB::connection('db_warehouse')->table('tbl_ws')
            ->where([
                ['post','=', 0],
                ['pickpost','=', 0],
                [DB::raw('RIGHT(department,1)'), $request['bodega']]
            ])
            ->orderBy('ctrl_no', 'desc')
            ->paginate(12)
            ->toArray();
        }

        $data           = $source['data'];
        $list           = [];

        foreach($data as  $index => $withdrawal) {
            $list[] = [
                "row_no" => $source['from'] + $index,
                ...\App\Http\Controllers\db_warehouse\ObjectParser::tbl_ws($withdrawal),
                "counts" => \App\Http\Controllers\db_warehouse\TBLWSDetailsFetch::countDetails($withdrawal->ctrl_no),
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

    public static function updatePickStart(Request $request) {
        $updated = DB::connection('db_warehouse')
            ->table('tbl_ws')
            ->where('ctrl_no', $request['ctrl_no'])
            ->update([
                'picker_no'     => $request['picker_no'],
                'pickstart'     => date('h:i:s'),
                'pickername'    => $request['pickername']
            ]);

        if($updated) {
            return [ "success" => true ];
        }
        else {
            return [ "success" => false ];
        }
    }
}
