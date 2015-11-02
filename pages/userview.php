<?php

require_once("../Personal/config.php");


require_once("../Lib/autoload.php");
require_once("function.menu.php");
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), "admins")) {
    header("Location:index.php?logout=1");
    exit(0);
}

$page = new page();
$page->setTitle("Dettagli utente");
$page->addStyle(UI_STYLE);
$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/user/userview.css");
$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");


$page->addStyle("../styles/mobile/orizontalmenu/orizontalmenu.css");

if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false){
    $page->addStyle("../styles/mobile/page/page.css");
    $page->addStyle("../styles/mobile/user/userview.css");
}else{
    $page->addStyle("../styles/page/page.css");
    $page->addStyle("../styles/user/userview.css");
}

$page->addJS(JQUERY);
$page->addJS("js/fastsearch.js");

$page->addMeta("initial-scale=1, maximum-scale=1", array("name"=>"viewport", "orientation"=>"landscape"));
$page->addMeta("initial-scale=0.8, maximum-scale=0.8", array("name"=>"viewport", "orientation"=>"portrait"));


$page->addCode(menu_convert(array("data"=>getOrrMenu(), "class"=>"admin_menu" )));  //eseguo la conversione ad html perchè non sto usando Smarty.

$page->addCode("<br /><br />");

$campidastampare = array("username" => "Nome Utente", "lastName" => "Cognome", "firstName" => "Nome", "email" => "E-Mail");
if (!isset($_GET['id']))
    die("id not found.");
$username = $_GET['id'];
if (!user::isUserExist($username))
    die("User $username does not exists...");
$user = new user();
$user->loadUser($username);
$userfields = $user->show();
//print_r($userfields);
$form = new form("send", "POST", "?", $page);

$table = new dataTable(array("Campo", "Valore"), array("datatable", "Dettagli", "datatable", "col"), "", $page);
foreach ($userfields as $key => $value) {
    if (array_key_exists($key, $campidastampare))
        $table->addRow(array($campidastampare[$key], $value));
}

$resultgroups = group::groupsOf($username);
//print_r($resultgroups[0]);
//foreach($resultgroups as $row)
//	$groups[$row['groupName']]=$row['groupName'];

$i = 0;

foreach ($resultgroups as $row) {
    if ($i != 0) {
        $table->addRow(array("", $row['groupName']));
    } else {
        $table->addRow(array("Gruppi:", $row['groupName']));
        $i++;
    }
}


$table->close();



$page->close();
?>
