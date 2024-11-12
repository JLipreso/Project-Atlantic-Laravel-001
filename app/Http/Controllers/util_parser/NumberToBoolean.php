<?php

namespace App\Http\Controllers\util_parser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * \App\Http\Controllers\util_parser\NumberToBoolean::convert($number);
 */

class NumberToBoolean extends Controller
{
    public static function convert($number) {
        if($number == 0) {
            return [
                "number"    => $number,
                "boolean"   => false
            ];
        }
        else if($number == 1) {
            return [
                "number"    => $number,
                "boolean"   => true
            ];
        }
        else {
            return [
                "number"    => $number,
                "boolean"   => false
            ];
        }
    }
}
