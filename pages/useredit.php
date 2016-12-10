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

secur::addSlashes($_GET);
secur::addSlashes($_POST);




$page = new page();
$page->setTitle("Modifica utente");
$page->addStyle("../styles/page/page.css");
$page->addStyle("../styles/user/useredit.css");
$page->addStyle("../styles/page/jquery.multiselect.css");
$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");

$page->addStyle("../styles/mobile/page/page.css");
$page->addStyle("../styles/mobile/orizontalmenu/orizontalmenu.css");
$page->addStyle("../styles/mobile/user/useredit.css");

$page->addStyle(UI_STYLE);
$page->addJS(JQUERY);
$page->addJS(JQUERY_UI);

$page->addJS("js/jquery.multiselect.min.js");


$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.


if (!isset($_GET['id']) && !isset($_POST['username']))
    die("id not found...");

if (isset($_GET["passw"]) && $_GET["passw"] == "on")
    $ischecked = true;
else
    $ischecked = false;



$username = (@$_GET['id'] == "") ? @$_POST['username'] : $_GET['id'];
if (!user::isUserExist($username)) 
    die("User does not exists...");
//Salviamo i campi utente qualora ci siano modifiche


$user = new user();
$user->loadUser($username);

$campidamodificare = array("lastName" => "Cognome", "firstName" => "Nome", "email" => "E-Mail", "password" => "Password", "active"=>"");


if (isset($_POST['Salva'])) {

    //$campiperaggiornamento = array();
    if(!isset($_POST["active"]))
        $_POST["active"]=0;    //Se la checkbox non è settata non viene inviato il value in POST
    foreach ($campidamodificare as $nomecampo => $valore) {
        if ($nomecampo != "password") {
            $user->$nomecampo = $_POST[$nomecampo];
            
            //echo $nomecampo." = ".$_POST[$nomecampo]."<br />";
        }
    }//close foreach

    if (isset($_POST['password']) && strlen($_POST['password']) > 0)
        $user->password = $user->makePassword($_POST['password']);

//if(isset($_POST["active"]))
    
    
    
    $user->Update();
    
    $group = new group();
    
    $group->updateGroupsOf($username, $_POST['group']); 
   
}





$page->openDiv(array("class" => "sheet"));





$form = new form("userdata", "POST", "?", $page);
$newline = true;

$userfields = $user->show();

foreach ($campidamodificare as $name => $label) {
    
     if($name == "active"){
        if(intval($userfields[$name])==1)
            $form->addIntoTableForm('<tr><td><input type="checkbox" checked="checked" name="active" value="1"  />Account attivo</td></tr>');
        else 
            $form->addIntoTableForm('<tr><td><input type="checkbox" name="active" value="1"  />Account attivo</td></tr>');
       }
       else if ($name != "password") {
        $form->addTextField_2($name, $label, array('value' => $userfields[$name]), $newline);
        $newline = !$newline;
    }
}//close foreach

if (!$ischecked)
    $form->addText('<input type="checkbox" name="showfield" id="showfield" value="1" />Modifica la password');
else {
    $form->addText('<input type="checkbox" checked="checked" name="showfield" id="showfield" value="1" />Modifica la password');
    $form->addPassField("password", "Password:", 5, 1);
}




$form->addHideField("username", $username, array("id" => "username"), false);

$form->addText("<label>username: </label>" . $username);




//richiediamo la lista dei gruppi a cui appartiene l'utente e generiamo l'array associativo dei gruppi
$resultgroups = group::groupsOf($username);
$groups = array();
$allgroups = array();
foreach ($resultgroups as $row)
    $groups[$row['groupName']] = $row['groupName'];


//generiamo il secondo array con tutti i gruppi a cui l'utente non appartiene contenuti al suo interno
$resultgroups = group::getGroups();

foreach ($resultgroups as $row) {
    $allgroups[$row['groupName']] = $row['groupName'];
}


$form->addMultiSelectBox("group[]", "Gruppi di appartenenza --->", $allgroups, array(), $groups);

$form->addButton_2("submit", "Salva", array(), 1);
$form->close();

/*
echo "<br /><br />";
      print_r($_POST);
      echo "<br /><br />";
*/

$page->closeDiv(); //chiusura div con classe sheet

$page->addJSCode("<script>
            $(document).ready(function(){
            
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
              $('#showfield').click(function(){
          
                    if($(this).is(':checked')) 
                        $(window).attr(\"location\",\"useredit.php?id=" . $username . "&passw=on\");           
                    else $(window).attr(\"location\",\"useredit.php?id=" . $username . "&passw=off\");
})

                    });

</script>");


$page->close();
?>
