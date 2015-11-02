<?php
require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die('Accesso non permesso');
}

$allegati = new allegatin(1);
if ($_POST["requestfrom"] == "all9")
    $arr_out = $allegati->getDateForAll9();
else if ($_POST["requestfrom"] == "report")
    $arr_out = $allegati->getDateForReport();

echo json_encode($arr_out);
?>
