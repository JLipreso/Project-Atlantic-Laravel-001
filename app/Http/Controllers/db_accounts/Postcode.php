<?php

namespace App\Http\Controllers\db_accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * db_accounts/verifyPostcode?postocde=3022&moduleid=22
 */

class Postcode extends Controller
{
    public static function verify(Request $request) {
        $supervisor = DB::connection('db_accounts')
            ->table('tbl_user')
            ->select('oUserid')
            ->where([
                ['oPostcode', $request['postocde']],
                ['oActive', 1]
            ])
            ->get();

        if(count($supervisor) > 0) {
            $isSupervisor = DB::connection('db_accounts')
                ->table('tbl_access')
                ->where([
                    ['oUserid', $supervisor[0]->oUserid],
                    ['oModuleid', $request['moduleid']]
                ])
                ->where( function ($query) {
                    return $query
                        ->where('oSupervisor', 1)
                        ->orWhere('oManager', 1);
                })
                ->count();
            if($isSupervisor > 0) {
                return [ "success" => true ];
            }
            else {
                return [ "success" => false ];
            }
        }
        else {
            return [ "success" => false ];
        }
    }
}
