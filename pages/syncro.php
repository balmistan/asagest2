<?php

//Verificare che la tabella family abbia num_indig

//Verificare che la tabella blocksheet abbia num_indig


//Aggiorno valori num_indig in family:

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");

$db = new db();

$arr = $db->getRows("family", "family_register_number");

for($i=0; $i<count($arr); $i++){
    $arr_ids = $db->getRows("person", "id_person", array(
        array("where", "family_register_number", "=", $arr[$i]["family_register_number"], true)
    ));
    $db->update("family", array("num_indig" => count($arr_ids)), array(
        array("where", "family_register_number", "=", $arr[$i]["family_register_number"], true)
    ));
}

//Adesso aggiorno num_indig in blocksheet:

$arr = $db->getRows("all8register", array("numindig", "sheet_id"));

for($i=0; $i<count($arr); $i++){
    $db->update("blocksheet", array("num_indig" => $arr[$i]["numindig"]), array(
        array("where", "sheetId", "=", $arr[$i]["sheet_id"], true)
    ));
}



echo "Esito OK!";






?>