<?php
require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

secur::addSlashes($_GET);

$location=new location();

if(isset($_GET['getlocation'])){
    return $location->getLocation();
}

if(isset($_GET['setlocation'])){
    $location->getLocation($_GET['value']);
    return 'adf';
}
?>
