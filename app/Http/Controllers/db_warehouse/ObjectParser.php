<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * \App\Http\Controllers\db_warehouse\ObjectParser::tbl_ws($object);
 */

class ObjectParser extends Controller
{
    public static function tbl_ws($object) {
        return [
            "ctrl_no"       => $object->ctrl_no,
            "entrydate"     => $object->entrydate,
            "post"          => $object->post,
            "branch"        => $object->branch,
            "ws_no"         => $object->ws_no,
            "invoice"       => $object->invoice,
            "remarks"       => $object->remarks,
            "entered"       => $object->entered,
            "customer"      => $object->customer,
            "mode"          => $object->mode,
            "department"    => $object->department,
            "picker_no"     => $object->picker_no,
            "pickstart"     => $object->pickstart,
            "pickpost"      => $object->pickpost,
            "pickstop"      => $object->pickstop,
            "pickername"    => $object->pickername,
            "dated"         => $object->dated,
            "time"          => $object->time,
            "postdate"      => $object->postdate,
            "posttime"      => $object->posttime
        ];
    }
}
