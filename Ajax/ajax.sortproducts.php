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

$arr_in = json_decode(file_get_contents('php://input'), true);

$product = new product();

$product->sortForAllegato8($arr_in);



//$logger = new logger("debug_ajaxsortproduct.txt",1);

//$logger->rawLog($arr_in);


?>