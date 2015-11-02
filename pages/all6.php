<?php

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");
require_once('function.menuSmarty.php');
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "user"))) {
    header("Location:index?logout=1");
    exit(0);
}

secur::addSlashes($_GET);
secur::addSlashes($_POST);

$esportapdf = false;

if(isset($_POST['esportapdf']))
    $esportapdf = true;

$arr_dbget = array();

$tbl = 'allegaticonfig'.REFAGEA;

$arr_dbget['legalerappresentante'] = config::getConfig('legalerappres', $tbl);
$arr_dbget['datadinascita'] = config::getConfig('datanasc', $tbl);
$arr_dbget['luogodinascita'] = config::getConfig('luogonasc', $tbl);
$arr_dbget['nomesede'] = config::getConfig('sedecri', $tbl);
$arr_dbget['indirizzosede'] = config::getConfig('indirizzocri', $tbl);
$arr_dbget['corpo_all9'] = config::getConfig('corpo_all9', $tbl);
$arr_dbget['reg_ue'] = config::getConfig('reg_ue', $tbl);

secur::stripSlashes($arr_dbget);

if (isset($_GET["date"])) {
    $date = urldecode($_GET["date"]);
} else {
    $date = date("d/m/Y");
}

$dateq = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);

$datamin = $dateq . " 00:00:01";
$datamax = $dateq . " 23:59:59";

$block = new block();
$arr_out = $block->getForReport3($datamin, $datamax, "", true);      //Indico di considerare solo le distribuzioni Agea (per quanto concerne la num degli allegati 9)

//print_r($arr_out);

//Il numero di indigenti lo leggo dall' allegato 8 in quanto così non tiene conto di modifiche successive al numero dei componenti della famiglia.

$num_indig = $block->getNumIndig($dateq);

if ($num_indig != -1)
    $arr_out["serv_indigenti"] = $num_indig;
else
    $arr_out["serv_indigenti"] = 0;

$num_all_9 = $block->getNumAll9($dateq);

if ($num_all_9 != "")
    $num_all_9 = $num_all_9 + intval(config::getConfig('start_index_all9', $tbl)) - 1;

if(!$esportapdf){
$arr_style_config = array();
$arr_js_config = array();

$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";
$arr_style_config[] = UI_STYLE;
$arr_style_config[] = "../styles/allegati/allegato9.css";

$arr_js_config[] = JQUERY;
$arr_js_config[] = JQUERY_UI;
$arr_js_config[] = "../Lib/Common/js/datepicker/ui.datepicker-it.js";
$arr_js_config[] = "js/fastsearch.js";
$arr_js_config[] = "js/all9.js";

$menu = getOrrMenu();

//

$objSmarty = new Smarty();
$objSmarty->assign('arr_out', $arr_out);
$objSmarty->assign('arr_dbget', $arr_dbget);
$objSmarty->assign('date', $date);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('menu', $menu);
$objSmarty->assign('female', config::getConfig("sex", $tbl));
$objSmarty->assign('num_all_9', $num_all_9);
$objSmarty->display('tpl/all9.tpl');

}else{
    //Creo il file pdf
    require_once('../PDFMerger/fpdf/fpdf.php');

    $pdf = new All9Pdf("P", "cm", "A4", $arr_dbget, $num_all_9, $date, $arr_out);

    $pdf->addBody();
    
    $filename = "All.6 ". str_replace("/", "-", $date).".pdf";

   // $pdf->Output($filename, "I");
    
    $pdf->Output($filename, "D");
}