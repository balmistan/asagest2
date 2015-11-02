<?php

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}


header('Content-type: image/jpeg');

secur::addSlashes($_GET);
$f=$_GET['url'];
if (is_file($f)) {
    $im = @ImageCreateFromJPEG($f);
    if (!$im) {
        readfile($f);
    } else {
        @ImageJPEG($im);
    }
}
?>
