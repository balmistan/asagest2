<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");
require_once 'function.menuSmarty.php';        //plugin per menù Smarty
include ("orizontalmenu.php");         //contiene la funzione che genera il menù

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}


$title = PAGETITLE . " - Ricerca Avanzata";
$shortcuticon = SHORTCUTICON;


//configurazioni per l' inclusione dei link nella pagina
$arr_style_config = array();
$arr_js_config = array();

$arr_style_config[] = UI_STYLENEW;
$arr_style_config[] = JQTABLES . "/media/css/jquery.dataTables.min.css";
$arr_style_config[] = "../styles/page/page.css";
//$arr_style_config[] = "../styles/page/TableTools_JUI.css";
$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";
$arr_style_config[] = "../styles/page/jquery.multiselect.css";
$arr_style_config[] = JQALERT . "/jquery.alerts.css";

$arr_style_config[] = "../styles/advancedsearch/advancedsearch.css";

$arr_js_config[] = JQUERYNEW;
$arr_js_config[] = JQUERY_UINEW;
$arr_js_config[] = JQTABLES . "/media/js/jquery.dataTables.min.js";
$arr_js_config[] = JQPLUGINS . "/datatable/ZeroClipboard.js";
$arr_js_config[] = JQTABLES . "/extensions/TableTools/js/dataTables.tableTools.min.js";
$arr_js_config[] = JQPLUGINS . "/datatable/jqDataTableSingleRowSelect.js";
$arr_js_config[] = JQALERT . "/jquery.alerts.js";
$arr_js_config[] = "js/jquery.multiselect.min.js";
$arr_js_config[] = "js/fastsearch.js";
$arr_js_config[] = "js/advancedsearch.js";


$menu = getOrrMenu();

$arr_age = array();

for ($age = 1; $age < 101; $age++)
    $arr_age[] = $age;

$db=new db();

$arr_temp_district=$db->freeQuery("select distinct district_id, nomeComune from family inner join comuni on district_id = idComune order by nomeComune asc");

secur::stripSlashes($arr_temp_district);

$arr_district=array();

for($i=0; $i<count($arr_temp_district); $i++){
   $arr_district[$arr_temp_district[$i]['district_id']] = $arr_temp_district[$i]['nomeComune']; 
}

$objSmarty = new Smarty();

$objSmarty->assign("title", $title);
$objSmarty->assign("shortcuticon", $shortcuticon);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('menu', $menu);
$objSmarty->assign('arr_age', $arr_age);
$objSmarty->assign('arr_district', $arr_district);

$objSmarty->display('tpl/advancedsearch.tpl');
?>