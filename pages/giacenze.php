<?php

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");
require_once('function.menuSmarty.php');
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index.php?logout=1");
    exit(0);
}

$arr_style_config = array();
$arr_js_config = array();

$arr_style_config[] = UI_STYLE;
$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";
$arr_style_config[] = "../styles/page/page.css";
$arr_style_config[] = "../styles/giacenze/giacenze.css";

$arr_js_config[] = JQUERY;
$arr_js_config[] = "js/fastsearch.js";
$arr_js_config[] = "js/giacenze.js";

$menu = getOrrMenu();


$giac_iniziale = new giacenzainiziale();

//Gestione degli eventuali dati ricevuti in POST:

if(isset($_POST["Salva"])){
    secur::addSlashes($_POST);
    $giac_iniziale->saveGiacenze($_POST);
    
    //print_r($_POST);
}

$arr_products = $giac_iniziale->getGiacenzeIniziali();



/////////////////////////////////////////////////////////////////////////////////////////

$objSmarty = new Smarty();
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('arr_products', $arr_products);
$objSmarty->assign('menu', $menu);

$objSmarty->display('tpl/giacenze.tpl');

?>