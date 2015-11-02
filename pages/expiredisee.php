<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

require_once("function.menu.php");
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins"))) {
    header("Location:index?logout=1");
    exit(0);
}

$page = new page();
$page->setTitle("ASAGesT");
$page->setIcon(SHORTCUTICON);
$page->addStyle("../styles/page/page.css");

$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");
$page->addStyle("../styles/user/userlist.css"); //Uso lo stesso css di userlist perchèla tabella è la stessa.

$page->addJS(JQUERY);
$page->addJS("js/fastsearch.js");
//$page->addJS("../Lib/Common/js/datatable/jquery.dataTables.min.js");
//$page->addJS("../Lib/Common/js/datatable/ZeroClipboard.js");

$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.

$db = new db();

$res = $db->freeQuery("SELECT family_register_number, expirydate_isee FROM `family` WHERE state <> 'deleted' AND (expirydate_isee = '0000-00-00' OR expirydate_isee <= DATE(NOW())) ORDER BY expirydate_isee");

$page->addCode("<br /><br />");

//$namecols = array("family_register_number" => "Num. scheda", "expirydate_isee" => "Scadenza Isee");

$table = new dataTable(array("Num. scheda", "Scadenza Isee"), array("datatable", "Dettagli", "datatable", "col"), "Modifica", $page);
for ($i = 0; $i < count($res); $i++) {

    if ($res[$i]["expirydate_isee"] == "0000-00-00")
        $res[$i]["expirydate_isee"] = "-";
    else {
        $dt = $res[$i]["expirydate_isee"];
        $res[$i]["expirydate_isee"] = substr($dt, 8, 2) . "/" . substr($dt, 5, 2) . "/" . substr($dt, 0, 4);
    }

    $table->addRow($res[$i], $table->makeAction(intval($res[$i]["family_register_number"]), array("edit")));
}
$table->close();

unset($db);
unset($table);


secur::addSlashes($_GET);
if ((isset($_GET['action']))) {
    $action = $_GET['action'];
    $familyid = $_GET['id'];
    switch ($action) {
        case "edit": header("Location: addmodfamily?fid=$familyid");
            break;
        default:
            break;
    }
}


$page->close();
?>