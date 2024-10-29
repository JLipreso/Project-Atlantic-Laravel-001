<?php

namespace App\Http\Controllers\util_generator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * \App\Http\Controllers\util_generator\OTPCode::create();
 */

class OTPCode extends Controller
{
    public static function create() {
        return rand(100000,999999);
    }
}
