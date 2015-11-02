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



$assoc = array(//form -> db
    'legalerappres' => 'legalerappresentante',
    'luogonasc' => 'luogodinascita',
    'datanasc' => 'datadinascita',
    'sedecri' => 'nomesede',
    'sedecriabbr' => 'nomesedeabbr',
    'indirizzocri' => 'indirizzosede',
   // 'start_index_all8_nc' => 'start_index_all8_nc',
    'start_index_all8' => 'start_index_all8',
    'start_index_all9' => 'start_index_all9',
    'start_index_blocksheet' => 'start_index_blocksheet',
    'sex' => 'sex'
);

$cfg_tbl = 'allegaticonfig'.REFAGEA; 


$db = new db();

//salvo eventuali dati da POST

if (isset($_POST['Salva'])) {
    unset($_POST['Salva']);
    secur::addSlashes($_POST);



    foreach ($_POST as $key => $configValue) {
        if ($key == "sex")
            config::setConfig($key, intval($configValue), '', $cfg_tbl);
        else
            config::setConfig($key, $configValue, '', $cfg_tbl);
    }
}


$arr_res = config::getConfig(null, $cfg_tbl);

secur::stripSlashes($arr_res);

//creo l' array con i valori e l' array con i titoli

$arr_val = array();
$arr_title = array();

for ($i = 0; $i < count($arr_res); $i++) {
    $arr_val[$arr_res[$i]['configName']] = $arr_res[$i]['configValue'];
    $arr_title[$arr_res[$i]['configName']] = $arr_res[$i]['configTitle'];
}

//print_r($dbget);


$page = new page();
$page->setTitle(PAGETITLE);
$page->setIcon(SHORTCUTICON);

$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");
$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/allegati/info_all.css");
$page->addStyle(UI_STYLE);
$page->addJS(JQUERY);
$page->addJS(JQUERY_UI);
$page->addJS("js/fastsearch.js");
$page->addMeta("initial-scale=0.9, maximum-scale=0.9", array("name" => "viewport", "orientation" => "landscape"));
$page->addMeta("initial-scale=0.5, maximum-scale=0.5", array("name" => "viewport", "orientation" => "portrait"));

$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.
//Stampiamo la form
$formname = "conf_all9";
$method = "POST";
$action = "?";
$form = new form($formname, $method, $action, $page);
$form->addTextField_2("sedecri", "Sede CRI di:", array("value" => $arr_val['sedecri'], "title" => $arr_title['sedecri']));
$form->addTextField_2("sedecriabbr", "Nome Sede CRI Abbr.", array("value" => $arr_val['sedecriabbr'], "title" => $arr_title['sedecriabbr']), false);
$form->addTextField_2("indirizzocri", "Indirizzo:", array("value" => $arr_val['indirizzocri'], "title" => $arr_title['indirizzocri']));
$form->addTextField_2("legalerappres", "Legale Rappresentante:", array("value" => $arr_val['legalerappres'], "title" => $arr_title['legalerappres']));
$form->addTextField_2("luogonasc", "Nato a:", array("value" => $arr_val['luogonasc'], "title" => $arr_title['luogonasc']), false);
$form->addTextField_2("datanasc", "il:", array("value" => $arr_val['datanasc'], "title" => $arr_title['datanasc']));
$form->addRadioButton_2("sex", array(
    array("value" => "0", "label" => "Uomo"),
    array("value" => "1", "label" => "Donna")
), $arr_val['sex']);
//$form->addTextField_2("start_index_all8_nc", "Inizio numer. Reg. Eventi", array("value" => $arr_val['start_index_all8_nc'], "title" => $arr_title['start_index_all8_nc']));
//$form->addTextField_2("start_index_all8", "Inizio numer. All. 5:", array("value" => $arr_val['start_index_all8'], "title" => $arr_title['start_index_all8']));
//$form->addTextField_2("start_index_all9", "Inizio numer. All. 6:", array("value" => $arr_val['start_index_all9'], "title" => $arr_title['start_index_all9']),false);
//$form->addTextField_2("start_index_blocksheet", "Inizio numer. Blocchetto:", array("value" => $arr_val['start_index_blocksheet'], "title" => $arr_title['start_index_blocksheet']));
$form->addTextField_2("reg_ue", "Reg. (UE):", array("value" => $arr_val['reg_ue'], "title" => $arr_title['reg_ue']),true);
$form->addTextArea('corpo_all9', 'Descrizione su All. 6', array("value" => $arr_val['corpo_all9'], "title" => $arr_title['corpo_all9'], "rows" => "10", "cols" => "50"), true, array("", "2", ""));

$form->addButton_2("submit", "Salva", array(), 1);

$form->close();
//$page->addCode("<p>*Non modificare mai l' inizio numerazione Allegati una volta iniziata la loro compilazione.</p>");

$page->addJSCode("<script>
            $(document).ready(function(){
           
             $(\".sheet\").css(\"cursor\",\"handle\");
	
	     $(\".sheet\").draggable({ containment: \"window\" });
			 
	     $(\"input:submit\").button();         //aggancia lo style ui al pulsante di submit

                    });
</script>");




$page->close();

secur::addSlashes($_POST);
?>