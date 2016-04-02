<?php



function __autoload($class_name) {
    $usepath = "../";
if(!is_dir($usepath . "Lib"))
        $usepath = "";
    
    $link1 = $usepath . "Lib/Common/" . $class_name . '.class.php';
    $link2 = $usepath . "Lib/Uncommon/" . $class_name . '.class.php';
    $link3 = $usepath . "Lib/Smarty/" . $class_name . '.class.php';
    $link4 = $usepath . "Lib/LibMenu/" . $class_name . '.class.php';
    if (substr($class_name, 0, 15) == "Smarty_Internal") {
        $classname = $usepath . "Lib/sysplugins/" . strtolower($class_name);
        require_once $classname . ".php";
    } else if (file_exists($link1))
        require_once($link1);
    else if (file_exists($link2))
        require_once($link2);
    else if (file_exists($link3))
        require_once($link3);
else if (file_exists($link4))
        require_once($link4);
    else
        die('file ' . $class_name . '.....php non trovato!');
}



define ("REFAGEA", config::getConfig("progagea", "config"));

?>
