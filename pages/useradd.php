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
$page->setTitle("Utenti");

$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");
$page->addStyle("../styles/page/jquery.multiselect.css");


$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/user/useradd.css");


$page->addMeta("initial-scale=0.9, maximum-scale=0.9", array("name" => "viewport", "orientation" => "landscape"));
$page->addMeta("initial-scale=0.5, maximum-scale=0.5", array("name" => "viewport", "orientation" => "portrait"));

$page->addStyle(UI_STYLE);
$page->addJS(JQUERY);
$page->addJS(JQUERY_UI);
$page->addJS("js/fastsearch.js");
$page->addJS("js/jquery.multiselect.min.js");


$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.


if ((!isset($_POST['username']))) {
    //Se non ci sono dati in post accediamo al db per prendere la lista dei gruppi
    $db = db::getInstance();
    $resultarray = $db->getRows($session->getSessionVar('prefix') . $opt['mysql']['grouptable'], array("groupName", "groupDescription"));

    foreach ($resultarray as $row)
        $groups[$row['groupName']] = $row['groupName'];

    $page->openDiv(array("class" => "sheet"));
    //Stampiamo il form
    $formname = "useradd";
    $method = "POST";
    $action = "?";
    $form = new form($formname, $method, $action, $page);
    $form->addTextField_2("lastName", "Cognome:", array());
    $form->addTextField_2("firstName", "Nome:", array(), false);
    $form->addTextField_2("username", "Username:", array());
    $form->addTextField_2("email", "E-Mail:", array(), false);
    $form->addPassField("password", "Password:", 5, 1);
    $form->addMultiSelectBox("group[]", "Gruppi di appartenenza --->", $groups, array(), "users");
    $form->addIntoTableForm('<tr><td><input type="checkbox" checked="checked" name="active" value="1"  />Account attivo</td></tr>');
    $form->addButton_2("submit", "Salva", array(), 1);
    $form->addRule("firstName", array("required", true), "Il campo nome dev'essere compilato!");
    $form->addRule("lastName", array("required", true), "Il campo cognome dev'essere compilato!");
    //$form->addRule("password",array("minlength",5),"Password troppo corta, premi il tasto Indietro e riesegui l'inserimento");
    $form->addRule("email", array("validmail", true), "E' necessaria una e-mail valida!");
    $form->addRule("username", array("required", true), "L'username dev'essere specificato!");

    $form->close();

    $page->closeDiv();
} else {                 //Se ho dati in POST
    secur::addSlashes($_POST);
    $permittedFields = array("firstName", "lastName", "email", "active");
    //Altrimenti l'utente ha già premuto il pulsante Salva
    //verifichiamo il numero di caratteri che ha impostato per la password
    //if (strlen($_POST['password'])<5) die ("Password troppo corta, premi il tasto Indietro e riesegui l'inserimento");
    //verifichiamo che il nome utente non sia già presente nel db  
    if (user::isUserExist($_POST['username']))
        die("L' username indicato e' gia' presente...");
    //e che il gruppo selezionato esista
    //$requestGroup=$_POST['group'];

    $validator = new validator("post");
    $validator->addRule("firstName", array("required", true), "Il campo nome dev'essere compilato!");
    $validator->addRule("lastName", array("required", true), "Il campo cognome dev'essere compilato!");
    //$validator->addRule("email", array("validmail", true), "E' necessaria una e-mail valida!");
    $validator->addRule("username", array("required", true), "L'username dev'essere specificato!");
    $validator->addRule("password", array("minlength", 5), "Password troppo corta, premi il tasto Indietro e riesegui l'inserimento");
    $validation = $validator->validate();
    if ($validation !== true) {
        $page->critical($validation);
        exit();
    }
    //a questo punto possiamo creare l'utente ed assegnargli gli attributi  
    $user = new user();
    $user->create($_POST['username']); //param "active" default false

    if (isset($_POST["active"])) {
        $_POST["active"] = true;
    }

    foreach ($_POST as $key => $value) {
        if (in_array($key, $permittedFields)) {
            $user->$key = $value;
            //echo $key." = ".$value."<br />";
        }
    }
    //  echo "<br /><br />";
    $user->password = $user->makePassword($_POST['password']);


    $user->Update();
    /*
      echo "<br /><br />";
      print_r($_POST);
      echo "<br /><br />";
     */
    //Assegnamo i gruppi di appartenenza:

    for ($i = 0; $i < count($_POST['group']); $i++) {
        if (!group::isGroupExist($_POST['group'][$i][0]))
            die("USERADD -> group " . $_POST['group'][$i][0] . " does not exist!");
    }


    //possiamo aggiungerlo al gruppo/i selezionati
    $group = new group();
    for ($i = 0; $i < count($_POST['group']); $i++) {
        $group->loadGroup($_POST['group'][$i][0]);  //oppure tramite l'username che tanto è UNIQUE
        $group->addUser($_POST['username']);
    }
    //$page->addText("Utente " . $_POST['username'] . " creato...");
    header("Location: userlist.php");
}


$page->addJSCode("<script>
            $(document).ready(function(){
            
             $(\"#email\").val(\"\");
            
             $(\".sheet\").css(\"cursor\",\"handle\");
	
	     $(\".sheet\").draggable({ containment: \"window\" });
			 
	     $(\"input:submit\").button();         //aggancia lo style ui al pulsante di submit

             $( \"select\" ).multiselect({
                                            header: false,
                                            multiple: false,
                                            minWidth:170,
                                            height:120,
                                            selectedList: 2       
                            });
              $('input:password').val('');              
              $('input:password').attr('autocomplete','off');
                    });

</script>");



$page->close();
?>
