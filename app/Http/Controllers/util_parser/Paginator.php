<?php

namespace App\Http\Controllers\util_parser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * \App\Http\Controllers\util_parser\Paginator::parse($source, $list);
 */

class Paginator extends Controller
{
    public static function parse($source, $list) {

        $last_page                  = $source['last_page'];
        $page_links                 = [];

        for($page = 1; $page < ($last_page + 1); $page++) {
            $page_links[]   = [
                "page_no"       => $page,
                "page_label"    => "Page " . $page . " of " . $last_page
            ];
        }

        return [
            "current_page"      => $source['current_page'],
            "data"              => $list,
            "from"              => $source['from'],
            "last_page"         => $source['last_page'],
            "per_page"          => $source['per_page'],
            "to"                => $source['to'],
            "total"             => $source['total'],
            "page_links"        => $page_links
        ];
    }
}
