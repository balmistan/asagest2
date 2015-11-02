<?php

//è la finestra modale che si apre sui report cliccando su una riga della tabella.

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
   die("Accesso non autorizzato");
}

secur::addSlashes($_GET);

$block=new block();

$arr=$block->getForModalForm($_GET['sid']);

$block2=new block(1);  //per banco alimentare

$arr2=$block2->getForModalForm($_GET['sid']);

$arr_style_config = array();
$arr_js_config = array();

$arr_style_config[] = "../styles/report/modalreport.css";
$arr_js_config[] = JQUERY;

$arr_js_config[] = "../Plugin/signature-pad/jquery.signaturepad.js";
$arr_js_config[] = "../Plugin/signature-pad/build/json2.min.js";
$arr_js_config[] = "js/modalreport.js";

//sdoppio gli array
$distr=array();
$distr['agea']=$arr['distributedproducts'];
$distr['banco']=$arr2['distributedproducts'];
unset($arr['distributedproducts']);

$arr['signature'] = urlencode(stripslashes($arr['signature']));

$codcons =  intval($_GET['sid']) + config::getConfig("start_index_blocksheet", "allegaticonfig".REFAGEA) - 1;

$objSmarty = new Smarty();
//print_r($arr);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('arr', $arr);
$objSmarty->assign('distr', $distr);
$objSmarty->assign('codcons', $codcons);

$objSmarty->display('tpl/modalreport.tpl');

?>
