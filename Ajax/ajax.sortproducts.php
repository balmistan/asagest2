<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die('Accesso non permesso');
}


/*Recupero la stringa ricevuta in POST.*/

$arr = stripslashes($_POST["str"]);          //str è un array json

$product = new product();

//$logger = new logger("debug_ajaxsortproduct.log");

//$logger->rawLog($arr);


$product->sortForAllegato8(json_decode($arr));


//$logger = new logger("debug_ajaxsortproduct.txt");

//$logger->rawLog($arr);


?>