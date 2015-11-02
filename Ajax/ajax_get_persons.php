<?php
require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

//Dato un idfamily restituisce l' elenco delle persone relative a quella scheda
if (isset($_GET["fid"])){
$fid = addslashes($_GET["fid"]);
$customer = new customer();
$arr_val = $customer->getFromDb($_GET["fid"]); 
}
?>
