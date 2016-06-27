<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

require_once("function.menuSmarty.php");
include ("orizontalmenu.php");

require_once("datediff.php");


$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}


//print_r($_POST);

$nophoto = NOPHOTO;

$usephoto = false;   //Indicherà se sono state usate le foto utente per la famiglia in questione.

$arr_style_config = array();
$arr_js_config = array();
//$arr_style_config[] = "../bootstrap/css/bootstrap.min.css";
$arr_style_config[] = "../styles/page/page.css";
//$arr_style_config[] = UI_STYLE;
$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";
$arr_style_config[] = "../styles/viewfamily/viewfamily.css";

$arr_js_config[] = JQUERYNEW;
$arr_js_config[] = "../Lib/Common/js/cf_handle.js";
$arr_js_config[] = "../Lib/Common/js/utility.js";
$arr_js_config[] = "js/fastsearch.js";
$arr_js_config[] = "js/viewfamily.js";

$menu = getOrrMenu();

$customer = new customer();

secur::addSlashes($_GET);

if (!isset($_GET["fid"]) || !is_numeric($_GET["fid"]))
    die("Errore ID!");

//recupero i valori dal db. 
$arr_val = $customer->getFromDb($_GET["fid"]);
//print_r($arr_val);
$id = $_GET["fid"];
$session->setSessionVar("fid", $id);

$array_born_date = $customer->date_convert($arr_val["borndate"]); //converto l' array di date dal formato restituito dalla query a quello che dovrò visualizzare

foreach ($arr_val["imagelink"] as $key => $value) {
    if ($value == "" || $value == NULL)
        $arr_val["imagelink"][$key] = $nophoto;
    else {
        $arr_val["imagelink"][$key] = "../Personal/PhotoPeople/".$value;
        $usephoto = true;
    }
}
$arr_res = array("img_lk" => $arr_val["imagelink"], "lname" => $arr_val["lastName"], "fname" => $arr_val["firstName"], "cf" => $arr_val["cf"], "born" => $array_born_date, "person_id" => $arr_val["person_id"], "rr" => $arr_val["rr"]);

$expireisee = $customer->date_convert($arr_val['expireisee']);
$block = new block();
$lastdistr = $block->getLastDistr($id);


//calcolo differenze tra date

$diff_isee = "";
$diff_distr = "";

if ($expireisee != ""){
    $interval = date_diff( date_create(date("Y-m-d")), date_create($arr_val['expireisee']));
    $diff_isee = $interval->format('%R%a');
    
    //echo "<br />Diff. ISEE Giorni: ".$diff_isee."<br /><br />";
}

if ($lastdistr != ""){
    $interval = date_diff( date_create(date("Y-m-d")), date_create(substr($lastdistr, 0, 10)));
    $diff_distr = $interval->format('%R%a');
    //echo "<br />Diff. DISTR Giorni: ".$diff_distr."<br /><br />";
    //Ordino data per visualizzare:
    $lastdistr = substr($lastdistr, 8, 2) . "/" . substr($lastdistr, 5, 2) . "/" . substr($lastdistr, 0, 4);
}




/*
echo "<br /><br />";
echo "diff distr: ". $diff_distr;
echo "<br /><br />";
echo "ultima distr: ".$lastdistr;
echo "<br /><br />";
*/




$msg = array();           //array per eventuali messaggi
$wmsg = array();           //array per eventuali messaggi di avviso
$emsg = array();           //array per eventuali messaggi messaggi che pregiudicano la distribuzione.
$class_color_msg = "green";      //colore Scheda n°.
//Messaggi
if ($lastdistr == "")
    $msg[] = "Ritira per la prima volta.";
else
    $msg[] = "Ultimo ritiro il " . $lastdistr;

//Warning
if ($arr_val['statoscheda'] == 'incomplete')
    $wmsg[] = "Scheda incompleta.";


$check_isee = false;
if (trim(strtoupper(config::getConfig('check_isee'))) != 'NO')
    $check_isee = true;

if ($check_isee && $diff_isee >0 && $diff_isee <= config::getConfig('notice_isee'))
    $wmsg[] = "Certificato in scadenza tra " . ($diff_isee *1) . " giorni.";

//Errori
if ($check_isee && $expireisee == "")
    $emsg[] = "Data di scadenza certificato non inserita.";

if ($check_isee && $expireisee != "" && $diff_isee <= 0)
    $emsg[] = "Certificato scaduto.";

if ($arr_val['statoscheda'] == "deleted")
    $emsg[] = "Scheda disattivata.";

$distance_distr_setted = intval(config::getConfig('distance_distrib'));


if ($lastdistr != "" && (-1 * $diff_distr) < intval($distance_distr_setted)){
    
    $emsg[] = "Non sono trascorsi i " . $distance_distr_setted . " giorni dal precedente ritiro.";
}
//echo "<br /><br />".$diff_distr."<br /><br />".$distance_distr_setted."<br /><br />";

if (count($wmsg)) {
    $class_color_msg = "yellow";
}

if (count($emsg)) {
    $class_color_msg = "red";
}

$objSmarty = new Smarty();

$objSmarty->assign("title", PAGETITLE);
$objSmarty->assign("shortcuticon", SHORTCUTICON);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('menu', $menu);
$objSmarty->assign('arr_res', $arr_res);
$objSmarty->assign('idfamily', $id);
$objSmarty->assign('class_color_msg', $class_color_msg);
$objSmarty->assign('msg', $msg);
$objSmarty->assign('wmsg', $wmsg);
$objSmarty->assign('emsg', $emsg);
$objSmarty->assign('note', $arr_val['note']);
$objSmarty->assign('usephoto', $usephoto);
$objSmarty->display('tpl/viewfamily.tpl');
?>
