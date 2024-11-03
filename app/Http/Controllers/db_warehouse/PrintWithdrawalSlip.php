<?php

namespace App\Http\Controllers\db_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class PrintWithdrawalSlip extends Controller
{
    public static function print(Request $request) {

        $base_height        = 120;
        $product_counts     = 2;
        $page_height        = $base_height + ($product_counts * 26);
        
        $fpdf = new \Sarabitcom\Fpdf\FpdfCode39('P', 'mm', [80, $page_height]);
        $fpdf->AddPage();
        $fpdf->SetLeftMargin(2);

        /** Variables */
        $parameters = [
            "border"            => 0,
            "width_full"        => 80,
            "width_half"        => 37.5,
            "mode"              => "3-S/R",
            "printed"           => [
                "date"              => "10/20/2024",
                "time"              => "09:30:24"
            ],
            "header"            => [
                "ctrl_no"           => "1234",
                "date"              => "10/10/2024",
                "branch"            => "MDIY",
                "ws_no"             => "23556",
            ]
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
        for($i = 0; $i < 2; $i++) {
            $fpdf->Cell(18.7, 5, "ITEMCODE", $border, 0);
            $fpdf->Cell(18.7, 5, "UNIT", $border, 0);
            $fpdf->Cell(18.7, 5, "LOCATOR", $border, 0);
            $fpdf->Cell(18.7, 5, "BRAND", $border, 1);
            $fpdf->MultiCell(75, 12, "This method allows printing text with line breaks.", $border);
            $fpdf->Cell(25, 5, "REQ QTY", $border, 0);
            $fpdf->Cell(25, 5, "REL QTY", $border, 0);
            $fpdf->Cell(25, 5, "PACK", $border, 0);
            $fpdf->Ln();
            $fpdf->Ln();
        }
        $fpdf->Cell(75, 5, "PICKED BY: USERNAME", $parameters['border'], 0);
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

        $fpdf->MultiCell(75, 5, "This method allows printing text with line breaks. They can be automatic", $parameters['border']);
        

    }
}
