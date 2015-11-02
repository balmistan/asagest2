<?php

/*
 * Include sia le newlibssmin sia le classi specifiche per l' applicazione  presenti in app_class
 * 
 */

$lib_path="newlibssmin";


$path="";
for($i=count(array_pop(explode("/",dirname($_SERVER['PHP_SELF'])))); $i>0; $i--) $path .= "../";

define("LIBSSDIR",$path.$lib_path);

function __autoload($class_name) {

	$link1=LIBSSDIR."/".$class_name . '.class.php';
	$link2=LIBSSDIR."/app_class/".$class_name . '.class.php';
    if (file_exists($link1)) require_once($link1);
	else require_once($link2);
}


?>
