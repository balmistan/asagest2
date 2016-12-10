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
$arr_style_config[] = "../styles/load/load.css";

$arr_js_config[] = JQUERY;
$arr_js_config[] = JQUERY_UI;
$arr_js_config[] = JQPLUGINS . "/datepicker/ui.datepicker-it.js";
$arr_js_config[] = "js/load.js";

$menu = getOrrMenu();

$allegati = new allegatin(1);




$arr = $allegati->getNameProductForTable();     //ottengo l' elenco prodotti da visualizzare nell' ordine relativo all' allegato 8.
$product = new product();
$arr_umis = $product->getProductUMis();


//creo un array contenente nome prodotti, id prodotti e unità di misura 

$arr_products = array();
$count = 0;
for ($j = 0; $j < count($arr); $j++) {
    for ($i = 0; $i < count($arr[$j]); $i++) {
        if ($arr[$j][$i]["idproduct"] == 0) {
            break;              //le colonne vuote senza prodotti sono alla fine della tabella e provocano l' uscita dal ciclo.
        }
        $arr_products[$count++] = array(
            "idproduct" => $arr[$j][$i]["idproduct"],
            "nameproduct" => $arr[$j][$i]["nameproduct"],
            "umis" => $arr_umis[$arr[$j][$i]["idproduct"]]
        );
    }
}

$date = date("d/m/Y");



/////////////////////////////// Gestione dei dati in POST  //////////////////////////////
secur::addSlashes($_POST);
if (isset($_POST['Salva'])) {
    unset($_POST['Salva']);
    $date = $_POST['date'];
    //salvo i dati ricevuti in POST ed effettuo un redirect ad allegato 8 attraverso il quale posso verificare il corretto inserimento.

    $allegati->saveLoad($_POST);
}

$load = new load();

/////////////////////////////////////////////////////////////////////////////////////////

$objSmarty = new Smarty();
$objSmarty->assign("title", PAGETITLE . " - Carico");
$objSmarty->assign("shortcuticon", SHORTCUTICON);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('arr_products', $arr_products);
$objSmarty->assign('dateins', $date);
$objSmarty->assign('prog', $allegati->getLastDataProg());
$objSmarty->assign('menu', $menu);
$objSmarty->assign('prog_agea', $opt['progagea'][REFAGEA]);
$objSmarty->assign('dateloads', $load->getDateLoads());
$objSmarty->display('tpl/load.tpl');
?>