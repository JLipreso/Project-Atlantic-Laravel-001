<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * \App\Http\Controllers\db_warehouse\ObjectParser::tbl_stocks($object);
 * \App\Http\Controllers\db_warehouse\ObjectParser::tbl_ws($object);
 */

class ObjectParser extends Controller
{
    public static function tbl_stocks($object) {
        return [
            "ctrl_no"       => $object->ctrl_no ,
            "active"        => $object->active,
            "item_no"       => $object->item_no,
            "itemcode"      => $object->itemcode,
            "barcode"       => $object->barcode,
            "d_desc"        => json_decode(json_encode($object->d_desc, JSON_INVALID_UTF8_SUBSTITUTE)),
            "unit"          => $object->unit,
            "total"         => $object->total,
            "locator"       => $object->locator,
            "primar_loc"    => $object->primar_loc,
            "second_loc"    => $object->second_loc,
            "ws_total"      => floatval($object->ws_total),
        ];
    }

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
