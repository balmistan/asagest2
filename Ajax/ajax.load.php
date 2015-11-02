<?php
require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die("Accesso non autorizzato");
}

secur::addSlashes($_POST);

$load = new load();

$arr = $load->getLoad($_POST['date']);  //restituisce un array di array contenente gli id prodotti, le quantità, ecc.

$arr_res = array();

if (count($arr)) {
    $arr_res['id_insert'] = $arr[0]['id_insert'];
    $arr_res['numrif'] = $arr[0]['numrif'];
    $arr_res['products']=array();
    for ($i = 0; $i < count($arr); $i++) {
        $arr_res['products'][$i] = array();
        $arr_res['products'][$i]['id_product'] = $arr[$i]['id_product'];
        $arr_res['products'][$i]['carico'] = floatval($arr[$i]['carico']) * 1;   //rimuovo gli zeri non significativi
    }
}

echo json_encode($arr_res);
?>
