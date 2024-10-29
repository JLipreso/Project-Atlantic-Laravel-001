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
}
