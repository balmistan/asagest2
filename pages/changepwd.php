<?php

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");
require_once("function.menu.php");
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}

$page = new page();
$page->setTitle(PAGETITLE);
$page->setIcon(SHORTCUTICON);

$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/changepwd/changepwd.css");
$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");
$page->addStyle(UI_STYLE);
$page->addJS(JQUERY);
$page->addJS(JQUERY_UI);



$page->addJS("js/fastsearch.js");

$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.

$user = new user();
$username = $user->getUserName($session->getUserId());
$user->loadUser($username);

secur::addSlashes($_POST);

if (isset($_POST['Salva'])) {
    //echo "<br /><br />";
    //echo $user->makePassword($_POST['oldpassword'], $user->getSalt());
    // echo $user->password;
    //echo "<br /><br />";
    /*
      if (!preg_match("(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$", $_POST['newpassword'])){
      $page->addText("Formato password non valido", array("align" => "center"));
      }
     */
    if (strlen($_POST['newpassword']) > 0 && $user->makePassword($_POST['oldpassword'], $user->getSalt()) === $user->password) {

        $user->password = $user->makePassword($_POST['newpassword']);
        $user->Update(1);
        $page->addText("Password aggiornata correttamente!", array("align" => "center", "class" => "msg"));
        //header("Location:home.php");
    } else {

        $page->addText("Password non corretta", array("align" => "center", "class" => "msg error"));
    }
}

//$user->Update(1);

$page->openDiv(array("class" => "sheet"));

$form = new form("userdata", "POST", "?", $page);

$form->addPassField("oldpassword", "Vecchia password:", 5, 0);
$form->addPassField("newpassword", "Nuova password:", 5, 1);
$form->addButton_2("submit", "Salva", array(), 0);



$page->addJSCode('<script>
            $(document).ready(function(){
             $(".sheet").css("cursor","handle");
	
			 $(".sheet").draggable({ containment: "window" });
			 
			  $("input:submit").button();         //aggancia lo style ui al pulsante di submit

 });

</script>');


$form->close();

$page->closeDiv();

$page->close();
?>
