<?php
require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die('Accesso non permesso');
}

/*Recupero la stringa ricevuta in POST.*/
$arr = stripslashes($_POST["str"]);          //str è un array json
$product = new product();
$product->sortForAllegato8(json_decode($arr));
?>
