<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");
require_once('../PDFMerger/fpdf/fpdf.php');

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "user"))) {
    header("Location:index?logout=1");
    exit(0);
}

secur::addSlashes($_GET);

if (isset($_GET["id"])) {

    $block = new block();

    $arr_out = $block->getForPdfSheet($_GET["id"]);

//print_r($arr_out);

    $pdf = new BlockPdf($arr_out);

    
   // $pdf->Header();

    $pdf->Body();

    //$fname = "foglio_".$pdf->getViewSheetId() .".pdf";
    
    $fname = "foglio.pdf";

    $pdf->Output($fname, "I");
    //$pdf->Output($fname, "F");
   //$pdf->Output($fname);

}
?>