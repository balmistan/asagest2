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
$page->setTitle("Cancellazione utente");
$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");
$page->addJS(JQUERY);



$page->addCode(menu_convert(array("data"=>getOrrMenu(), "class"=>"admin_menu" )));  //eseguo la conversione ad html perchè non sto usando Smarty.

if (!isset($_GET['id']) && !isset($_POST['username']))
    die("id not found...");


$username = (@$_GET['id'] == "") ? @$_POST['username'] : $_GET['id'];
if (!user::isUserExist($username))
    die("User $username does not exists...");

$user = new user();
$user->loadUser($username);
$user->delete();
header("Location: userlist.php");
//$page->addText("Utente cancellato completamente!", array("align" => "center"));

$page->close();
?>
