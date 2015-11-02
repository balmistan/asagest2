<?php
/*
 * verifica la presenza del codice fiscale nella tabella db con un idfamily differente da quello con cui sto salvando. 
 * Restituisce un array con le chiavi ispresent e idfam.
 * is present con 1 oppure 0 indica se è prese
 *  
 */

require_once("../Personal/config.php");             //sono in libss questi file
require_once("../Lib/autoload.php");

$person = new Person();
$retarray=array("familyid"=>$person->testIfCfIsPresent($_POST["cf"], $_POST["familyid"]));
 
echo json_encode($retarray);
?>
