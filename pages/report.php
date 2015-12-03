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

secur::addSlashes($_GET);

if (isset($_GET["mode"]) && !is_numeric($_GET["mode"]))
    die("Errore mode!");

$default_selected_tabs = "tabs-2";

if (isset($_GET["mode"])) {
    switch ($_GET["mode"]) {
        case 1:
            $default_selected_tabs = "tabs-1";
            break;
        case 2:
            $default_selected_tabs = "tabs-2";
            break;
        case 3:
            $default_selected_tabs = "tabs-3";
            break;
        default:
            break;
    }
}


if (isset($_SESSION["reportidcomune"])) {
    $idselectedcom = $_SESSION["reportidcomune"];
} else
    $idselectedcom = "";


$title = PAGETITLE . " - Report Consegne";
$shortcuticon = SHORTCUTICON;


//configurazioni per l' inclusione dei link nella pagina
$arr_style_config = array();
$arr_js_config = array();

$arr_style_config[] = UI_STYLENEW;
$arr_style_config[] = JQTABLES . "/media/css/jquery.dataTables.min.css";


$arr_style_config[] = "../styles/page/datatable.css";  //stile datatable aggiuntivo
$arr_style_config[] = "../styles/page/page.css";
//$arr_style_config[] = "../styles/page/TableTools_JUI.css";
$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";

$arr_style_config[] = "../styles/report/report.css";

$arr_js_config[] = JQUERYNEW;
$arr_js_config[] = JQUERY_UINEW;
$arr_js_config[] = JQTABLES . "/media/js/jquery.dataTables.min.js";
$arr_js_config[] = JQPLUGINS . "/datatable/ZeroClipboard.js";
//$arr_js_config[] = JQPLUGINS . "/datatable/TableTools.js";
//$arr_js_config[] = JQPLUGINS . "/dataTables.jqueryui.min.js";//
$arr_js_config[] = JQPLUGINS . "/datepicker/ui.datepicker-it.js";
$arr_js_config[] = "js/fastsearch.js";
$arr_js_config[] = "js/report.js";


$menu = getOrrMenu();
if (isset($_SESSION["reportdatamin"])) {
    $start_date = $_SESSION["reportdatamin"];
} else
    $start_date = "01/01/" . REFYEAR;

if (isset($_SESSION["reportdatamax"])) {
    $end_date = $_SESSION["reportdatamax"];
} else
    $end_date = "31/12/" . REFYEAR;



$family_id = isset($_SESSION['fid']) ? $_SESSION['fid'] : "0";

//effettuo una query per riempire la selectbox dei comuni

$db = new db();

$arr_temp_district = $db->freeQuery("select distinct district_id, nomeComune from family inner join comuni on district_id = idComune order by nomeComune asc");

secur::stripSlashes($arr_temp_district);

$arr_district = array();

for ($i = 0; $i < count($arr_temp_district); $i++) {
    $arr_district[$arr_temp_district[$i]['district_id']] = $arr_temp_district[$i]['nomeComune'];
}


$objSmarty = new Smarty();

$objSmarty->assign("title", $title);
$objSmarty->assign("shortcuticon", $shortcuticon);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('menu', $menu);
$objSmarty->assign('idselectedcom', $idselectedcom);
$objSmarty->assign('arr_district', $arr_district);
$objSmarty->assign('start_date', $start_date);
$objSmarty->assign('end_date', $end_date);
$objSmarty->assign('family_id', $family_id);
$objSmarty->assign('refyear', REFYEAR);
$objSmarty->assign('default_selected_tabs', $default_selected_tabs);
$objSmarty->assign('config_start_blocksheet', config::getConfig("start_index_blocksheet", "allegaticonfig" . REFAGEA));
$objSmarty->display('tpl/report.tpl');
?>