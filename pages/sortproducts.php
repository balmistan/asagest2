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
$arr_style_config[] = "../styles/products/sortproducts.css";

$arr_js_config[] = JQUERY;
$arr_js_config[] = JQUERY_UI;
$arr_js_config[] = "js/fastsearch.js";
$arr_js_config[] = "js/sortproducts.js";

$menu = getOrrMenu();


$product = new product();

$arr_products_8 = $product->getDisplayProduct(8);     //ottengo l' elenco prodotti da visualizzare per allegato 8

$objSmarty = new Smarty();

$objSmarty->assign("title", PAGETITLE);
$objSmarty->assign("shortcuticon", SHORTCUTICON);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('arr_products_8', $arr_products_8);
$objSmarty->assign('menu', $menu);

$objSmarty->display('tpl/sortproducts.tpl');


?>