<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");
require_once("function.menu.php");
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), "admins")) {
    header("Location:index?logout=1");
    exit(0);
}

$page = new page();
$page->setTitle("Lista utenti");
$page->addStyle(UI_STYLE);
$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/user/userlist.css");
$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");


$page->addStyle("../styles/mobile/orizontalmenu/orizontalmenu.css");

if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) {
    $page->addStyle("../styles/mobile/page/page.css");
    $page->addStyle("../styles/mobile/user/userlist.css");
} else {
    $page->addStyle("../styles/page/page.css");
    $page->addStyle("../styles/user/userlist.css");
}

$page->addJS(JQUERY);


$page->addMeta("initial-scale=0.9, maximum-scale=0.9", array("name" => "viewport", "orientation" => "landscape"));
$page->addMeta("initial-scale=0.5, maximum-scale=0.5", array("name" => "viewport", "orientation" => "portrait"));


$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.

$db = db::getInstance();

$results = $db->getRows(
        array($session->getSessionVar('prefix') . $opt['mysql']['usertable'], $session->getSessionVar('prefix') . $opt['mysql']['userinfotable']), array("username", "firstName", "lastName", "email", "active"), array(
    array("ON", $session->getSessionVar('prefix') . $opt['mysql']['usertable'] . ".userId", "=", $session->getSessionVar('prefix') . $opt['mysql']['userinfotable'] . ".userId", true)
        ), array());


for ($i = 0; $i < count($results); $i++) {   //ciclo per ogni username (Ogni riga tabella è associata univocamente ad un username).

    $arr_group_type = $db->getRows("groupusers", "groupName", array(
        array("where", "username", "=", $results[$i]["username"])
    ));
    
    $groupapp="";
    for($j=0; $j<count($arr_group_type); $j++){
        $groupapp .= $arr_group_type[$j]["groupName"]. " ";
    }
    
    $results[$i]["group"] = $groupapp;
    
    if($results[$i]["active"])
        $results[$i]["active"] = "ON";
    else $results[$i]["active"] = "OFF";
    
}//close for

$page->addCode("<br /><br />");

$table = new dataTable(array("Username", "Nome", "Cognome", "E-Mail", "Stato", "Gruppi"), array("datatable", "Dettagli", "datatable", "col"), "Opzioni", $page);

for ($i = 0; $i < count($results); $i++) {
    $table->addRow($results[$i], $table->makeAction($results[$i]['username']));
}

unset($db);
$table->close();
unset($table);


if ((isset($_GET['action']))) {
    $action = $_GET['action'];
    $user = $_GET['id'];
    switch ($action) {
        case "view": header("Location: userview.php?id=$user");
            break;
        case "edit": header("Location: useredit.php?id=$user");
            break;
        case "delete": header("Location: userdel.php?id=$user");
            break;
    }
}

$page->close();
?>
