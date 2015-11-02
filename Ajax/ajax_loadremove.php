<?php
require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die('Accesso non permesso');
}

$allegati = new allegatin(1);

secur::addSlashes($_POST);

$allegati->removeLoad($_POST["idload"]);

echo '[]';
?>
