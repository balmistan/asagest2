<?php

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    echo "NOLOGGED";
    exit(0);
}

secur::addSlashes($_POST);

$smart=$_POST['imagename'];
$full = $_POST['save_folder']."/".$smart;

$image = new image($full);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    $image = new image($full);
    $image->crop($_POST['w'], $_POST['h'], $_POST['x'], $_POST['y']);
    $image->resizeIN(64,  64, 1);
    
 
    $image->save();
    
    echo $smart;
   
}

?>
