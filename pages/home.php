<?php

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");
require_once 'function.menuSmarty.php';        //plugin per menù Smarty
include ("orizontalmenu.php");

$session = new session();

$userId = $session->getUserId();

$username = user::getUserName($userId);

if (!accesslimited::isInAutorizedGroups($username, array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}

$db = new db();
$arr_access = $db->getRows("sessions", array("lastDate", "ip_address"), array(
    array("where", "userId", "=", $userId, true)
        ), array(
    array("ORDERBY", "lastDate"),
    array("ORDER", "DESC"),
    array("LIMIT", 2)
        ));

if (count($arr_access) < 2) {
    $msg_last_date_login = "Primo accesso";
} else {
    $msg_last_date_login = "Precedente accesso effettuato in data " . DataOra::isoDatetimeToDate($arr_access[1]['lastDate']) . " alle ore " . DataOra::isoDatetimeToTime($arr_access[1]['lastDate']) . " IP: " . $arr_access[1]['ip_address'];
}


/*
  echo"<br /><br /><br />";
  var_dump(ini_get('memory_limit'));
  var_dump(ini_get('post_max_size'));
  var_dump(ini_get('upload_max_filesize'));
  echo"<br /><br /><br />";
 */


//configurazioni per l' inclusione dei link nella pagina
$arr_style_config = array();

$arr_js_config = array();

/*
  $block = new block();
  $block->getSheetCode();
 */


$arr_style_config[] = "../styles/page/page.css";
$arr_style_config[] = "../styles/home/home.css";
$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";


$arr_js_config[] = JQUERYNEW;
$arr_js_config[] = "js/fastsearch.js";

$path_icons = "../styles/home/icons/";  //percorso icone menù grande a centro pagina.

$menu = getOrrMenu();

$objSmarty = new Smarty();

$objSmarty->assign("title", PAGETITLE . " - Home");
$objSmarty->assign("shortcuticon", SHORTCUTICON);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('path_icons', $path_icons);
$objSmarty->assign('username', $username);
$objSmarty->assign('msg_last_date_login', $msg_last_date_login);

$objSmarty->assign('menu', $menu);


$objSmarty->display('tpl/home.tpl');
?>
	
