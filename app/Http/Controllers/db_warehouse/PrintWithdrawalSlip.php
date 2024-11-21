<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * db_warehouse/PrintWithdrawalSlip?ctrl_no=173917
 */

class PrintWithdrawalSlip extends Controller
{
    public static function print(Request $request) {

        $profile            = \App\Http\Controllers\db_warehouse\TBLWSFetch::profile($request['ctrl_no']);

        return $profile;

        $base_height        = 120;
        $product_counts     = count($profile['child']);
        $page_height        = $base_height + ($product_counts * 26);
        
        $fpdf = new \Sarabitcom\Fpdf\FpdfCode39('P', 'mm', [80, $page_height]);
        $fpdf->AddPage();
        $fpdf->SetMargins(2, 1, 2);

        $parameters = [
            "border"            => 0,
            "width_full"        => 80,
            "width_half"        => 37.5,
            "mode"              => $profile['header']->mode,
            "pickername"        => $profile['header']->pickername,
            "printed"           => [
                "date"              => date('M/d/Y'),
                "time"              => date('h:i:s')
            ],
            "header"            => [
                "ctrl_no"           => $profile['header']->ctrl_no,
                "date"              => $profile['header']->dated,
                "branch"            => $profile['header']->branch,
                "ws_no"             => $profile['header']->ws_no,
                "remarks"           => $profile['header']->remarks
            ],
            "child"             => $profile['child']
        ];

        $fpdf->Code39(30, 31.5, "1234", 1.5, 14);

        PrintWithdrawalSlip::slipHeader($fpdf, $parameters);
        PrintWithdrawalSlip::products($fpdf, $parameters);

        $fpdf->SetAutoPageBreak(true);
        $fpdf->Output();
        exit;
    }

    public static function products($fpdf, $parameters) {

        $fpdf->SetFont('Arial', '', 8);
        $border = 1;
        $fpdf->setY(87);

        foreach($parameters['child'] as $product) {
            $fpdf->Cell(18.7, 5, $product['item']->itemcode, $border, 0);
            $fpdf->Cell(18.7, 5, $product['item']->unit, $border, 0);
            $fpdf->Cell(18.7, 5, $product['stock']->locator, $border, 0);
            $fpdf->Cell(18.7, 5, "BRAND", $border, 1);
            $fpdf->MultiCell(75, 12, $product['stock']->d_desc, $border);
            $fpdf->Cell(25, 5, "REQ QTY : " . $product['item']->qty_unit, $border, 0);
            $fpdf->Cell(25, 5, "REL QTY : " . $product['item']->rel_unit, $border, 0);
            $fpdf->Cell(25, 5, "PACK", $border, 0);
            $fpdf->Ln();
            $fpdf->Ln();
        }

        $fpdf->Cell(75, 5, "PICKED BY: " . $parameters['pickername'], $parameters['border'], 0);
    }

    public static function slipHeader($fpdf, $parameters) {
        
        $fpdf->Ln();
        $fpdf->SetFont('Arial', 'B', 16);
        $fpdf->Cell(75, 9, 'MINGLA PICK SLIP', $parameters['border'], 1, 'C');

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->Cell(75, 6, 'MODE: ' . $parameters['mode'], $parameters['border'], 1, 'C');
        $fpdf->Ln();

        $print_date_width = 30;
        $fpdf->SetFont('Arial', '', 9);
        $fpdf->Cell($print_date_width, 5, 'PRINTED ON:', $parameters['border'], 0);
        $fpdf->Ln();
        $fpdf->Cell($print_date_width, 5, $parameters['printed']['date'], $parameters['border'], 1);
        $fpdf->Cell($print_date_width, 5, $parameters['printed']['time'], $parameters['border'], 1);
        
        $fpdf->Line(0, 49, $parameters['width_full'], 49);

        $fpdf->Ln();
        
        $fpdf->Cell($parameters['width_half'], 5, "CTRL #:" . $parameters['header']['ctrl_no'], $parameters['border'], 0);
        $fpdf->Cell($parameters['width_half'], 5, "DATE: " . $parameters['header']['date'], $parameters['border'], 0);
        $fpdf->Ln();
        $fpdf->Cell($parameters['width_half'], 5, "BRANCH:" . $parameters['header']['branch'], $parameters['border'], 0);
        $fpdf->Cell($parameters['width_half'], 5, "WS #: " . $parameters['header']['ws_no'], $parameters['border'], 0);

        $fpdf->Line(0, 63, $parameters['width_full'], 63);
        
        $fpdf->Ln();
        $fpdf->Ln();
        $fpdf->Cell(75, 5, "REMARKS:", $parameters['border'], 0);
        $fpdf->Ln();

        $fpdf->MultiCell(75, 5, $parameters['header']['remarks'], $parameters['border']);
        

    }
}
