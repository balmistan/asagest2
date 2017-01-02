<?php

require_once("../Personal/config.php");

if (USEDEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

require_once("../Lib/autoload.php");
require_once("function.menuSmarty.php");
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins"))) {
    header("Location:index.php?logout=1");
    exit(0);
}

$arr_style_config = array();
$arr_js_config = array();



$arr_style_config[] = "../styles/page/page.css";
$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";
$arr_style_config[] = "../Plugin/gridster/src/jquery.gridster.css";
$arr_style_config[] = "../styles/products/sortproducts.css";

$arr_js_config[] = JQUERY;
//$arr_js_config[] = JQUERY_UI;
$arr_js_config[] = "../Plugin/gridster/src/jquery.collision.js";
$arr_js_config[] = "../Plugin/gridster/src/jquery.coords.js";
$arr_js_config[] = "../Plugin/gridster/src/jquery.draggable.js";
$arr_js_config[] = "../Plugin/gridster/src/jquery.collision.js";

$arr_js_config[] ="../Plugin/gridster/src/jquery.gridster.js";
$arr_js_config[] ="../Plugin/gridster/src/jquery.gridster.extras.js";
$arr_js_config[] ="../Plugin/gridster/src/utils.js";
$arr_js_config[] = "js/sortproducts.js";

$menu = getOrrMenu();


$product = new product();

$arr_products_8 = $product->getDisplayProduct(8);     //ottengo l' elenco prodotti da visualizzare per allegato 8

$maxcol = 3;     //number of elements for row

$arrout = array();
$row = -1;
$col = $maxcol;

foreach($arr_products_8 as $arrvalue){
    if($col == $maxcol){
        $col=0;
        $row++;
        $arrout[$row] = array();
    }
    
    $arrout[$row][$col] = $arrvalue;
    $col++;
}



//print_r($arrout);

$objSmarty = new Smarty();

$objSmarty->assign("title", PAGETITLE);
$objSmarty->assign("shortcuticon", SHORTCUTICON);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('arr_products_8', $arr_products_8);
$objSmarty->assign('arrout', $arrout);
$objSmarty->assign('menu', $menu);

$objSmarty->display('tpl/sortproducts.tpl');


?>