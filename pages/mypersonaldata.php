<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");
require_once("function.menu.php");
include ("orizontalmenu.php");

$session = new session();

$username = user::getUserName($session->getUserId());

if (!accesslimited::isInAutorizedGroups($username, array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}


$campidamodificare = array("lastName" => "Cognome", "firstName" => "Nome", "email" => "E-Mail");


$page = new page();
$page->setTitle("Dati personali");
$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/user/mypersonaldata.css");
$page->addStyle("../styles/page/jquery.multiselect.css");
$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");

$page->addStyle(UI_STYLE);
$page->addJS(JQUERY);
$page->addJS(JQUERY_UI);
$page->addJS("js/fastsearch.js");
$page->addJS("js/jquery.multiselect.min.js");



$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.
//Salviamo i campi utente qualora ci siano modifiche


$user = new user();
$user->loadUser($username);

//$campidamodificare = array("lastName" => "Cognome", "firstName" => "Nome", "email" => "E-Mail");


if (isset($_POST['Salva'])) {

    foreach ($campidamodificare as $nomecampo => $valore)
        $user->$nomecampo = $_POST[$nomecampo];

    $user->Update();
}//close if (isset($_POST['Salva']))


$page->openDiv(array("class" => "sheet"));

$form = new form("userdata", "POST", "?", $page);
$newline = true;

$userfields = $user->show();

foreach ($campidamodificare as $name => $label) {

    $form->addTextField_2($name, $label, array('value' => $userfields[$name]), $newline);
    $newline = !$newline;
}//close foreach

$form->addHideField("username", $username, array("id" => "username"), false);

$form->addText("<label>username: </label>" . $username);

$form->addButton_2("submit", "Salva", array(), 1);
$form->close();


$page->closeDiv(); //chiusura div con classe sheet

$page->addJSCode("<script>
            $(document).ready(function(){
            
             $(\".sheet\").css(\"cursor\",\"handle\");
	
	     $(\".sheet\").draggable({ containment: \"window\" });
			 
	     $(\"input:submit\").button();         //aggancia lo style ui al pulsante di submit

                    });

</script>");


$page->close();
?>