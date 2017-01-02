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
$page->setTitle(PAGETITLE . " - Configurazioni");
$page->setIcon(SHORTCUTICON);
$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/settings/settings.css");
$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");

$page->addJS(JQUERY);

$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.

$page->addCode("<br /><br /><br /><br />");
if (isset($_POST['Salva'])) {
    foreach ($_POST as $key => $value) {
        if ((config::getConfig($key) !== null) && (config::isEditable($key))) {
            config::setConfig($key, $value);
        }
    }
}

/*
if (isset($_GET["err"]))
    $page->addCode("<p class=\"red\">Anno di riferimento non valido. Correggere per proseguire!</p>");
*/
$form = new form("configuration", "POST", "?", $page);
$configuration = config::getConfig();

//Ottengo elenco 

$form->addText("<p class=\"promem\"> Ricordarsi di salvare, terminate le modifiche</p>","",2);




foreach ($configuration as $confrow) {
/*
    if ($confrow['configName'] == "yearref") {
        $form->addSelectBox_2("yearref", "Blocchetto consegne", $opt['disponibleblocks'], array(), $confrow['configValue'],0);
        continue;
    }
   */ 
    if ($confrow['configName'] == "progagea") {
        $form->addSelectBox_2("progagea", "Progr. Agea", $opt['progagea'], array(), $confrow['configValue']);
        continue;
    }

    if ($confrow['configEditable'])
        $form->addTextField($confrow['configName'], (strlen($confrow['configDescription']) > 0) ? $confrow['configDescription'] : $confrow['configName'], array("size" => "15", "value" => $confrow['configValue'], "title" => $confrow['configTitle']));
}
$form->addVSpace(20);
$form->addButton_2("reset", "Annulla", array(), 0, 1);
$form->addButton_2("submit", "Salva", array(), 0, 0);

$page->addCode($form->getContent());
$page->close();
?>
