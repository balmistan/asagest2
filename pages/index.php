<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

$prefix = "";

secur::addSlashes($_GET);
secur::addSlashes($_POST);

$session = new session();
$session->setSessionVar("prefix", $prefix);

$protected_page = "home";

$page = new page();
$page->setTitle(PAGETITLE . " - Login");

$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/login/login.css");

$page->addMeta("initial-scale=1, maximum-scale=1", array("name" => "viewport"));


$page->addStyle(UI_STYLE);

$page->addJS(JQUERY);
$page->addJS(JQUERY_UI);
$page->addJS("js/pagelogin.js");

if(date("Y")<"2013")
    die("Data di sistema errata. Settare la data e riprovare!");

if (isset($_GET['logout'])) {
    redirect("index.php");
    $session = new session();
    $session->close("logout");
    unset($session);
}
$session = new session();

if (($session->getUserId() != -1) && ($session->getUserId() != "")) {
    redirect($protected_page);
}

if (!isset($_POST['username'])) {

    $page->openDiv(array("class" => "sheet"));

    if (user::getUserName($session->getUserId()) == "guest") {
        $formname = "login";
        $method = "POST";
        $action = "?";
        $form = new formlist($formname, $method, $action, $page);
        $form->addTextField_2("username", "Username:", array("maxlength" => 20, "autocomplete" => "off"));
        $form->addPassField("password", "Password:", 5, 0);
        $form->addHideField("sede", $prefix);
        if (user::isUserExist($session->getSessionVar("lastusername")) && accesslimited::useCaptcha(user::getUserId($session->getSessionVar("lastusername"))))
            $form->addCaptcha("Codice visualizzato:");   

        $form->addButton_2("submit", "Accedi", array(), 0);

        $form->close();
        $page->closeDiv();
    }
} else {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (user::isUserExist($username)) {
        $user = new user();
        $user->loadUser($username);
        $session = new session();
        $session->setSessionVar("lastusername", $username);

        $access_danied = true;

        if ($user->password == $user->checkPassword($password)) { //test password
            $access_danied = false;
            if (accesslimited::useCaptcha($user->getUserId($username))) { //se viene usato il captcha
                $inputval = "";
                if (isset($_POST["captcha"]))
                    $inputval = $_POST["captcha"];
                if (!accesslimited::checkCaptcha($inputval))
                    $access_danied = true;
            };
        }// close if ($user->password == $user->checkPassword($password))
        if (!$access_danied) {
            $session->setUserId($user->getUserId($username));                          
            accesslimited::resetCaptcha($user->getUserId($username));
            redirect($protected_page);
            $session->setSessionVar("prefix", $prefix);
            exit;
        } else {
            accesslimited::LoginFailed($user->getUserId($username), 3);
        }
    }// close if(user::isUserExist($username))
      redirect($_SERVER['REQUEST_URI']);
}

$page->close();

function redirect($link){
   echo'
<script type="text/javascript">
window.location = "'.$link.'"
</script>';
}

?>

