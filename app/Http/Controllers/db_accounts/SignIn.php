<?php

namespace App\Http\Controllers\db_accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * db_accounts/signIn?username=[STR]&password=[STR]&oModuleid=[STR]
 * 
 */

class SignIn extends Controller
{
    public static function signin(Request $request) {
        $source = DB::connection("db_accounts")->table("tbl_user")
        ->where([
            ["oUsername", $request['username']],
            ["oPassword", $request['password']],
            ["oActive", 1]
        ])
        ->get();

        if(count($source) == 1) {

            $access_source = DB::connection("db_accounts")->table('tbl_access')
            ->select('oMain')
            ->where([
                ['oUserid', $source[0]->oUserid],
                ['oModuleid', $request['oModuleid']]
            ])
            ->get();

            $access = 0;
            if(count($access_source) > 0) {
                if($access_source[0]->oMain > 0) {
                    return [
                        "success"   => true,
                        "message"   => "Successfully sign-in",
                        "profile"   => $source[0]
                    ];
                }
                else {
                    return [
                        "success"   => false,
                        "message"   => "Access denied",
                        "profile"   => []
                    ];
                }
            }
            else {
                return [
                    "success"   => false,
                    "message"   => "Access denied",
                    "profile"   => []
                ];
            }
        }
        else {
            return [
                "success"   => false,
                "message"   => "Incorrect username or password",
                "profile"   => []
            ];
        }
    }
}
