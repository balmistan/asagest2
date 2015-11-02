<?php
require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die('Accesso non permesso');
}

//secur::addSlashes($_POST);

$allegati = new allegatin(1);

switch ($_POST['checktype']) {
   //case "match_giacze":
        //echo $allegati->testMatchGiacze();
       // echo 1;
       // break;
    case "check_date":
        echo $allegati->checkDate($_POST['datecheck']);
        break;
    case "check_distr_exists":
        $block = new block();
        // Non possono essere effettuate due distribuzioni alla medesima famiglia nello stesso giorno.
        echo $block->checkDistrEff($_POST['datecheck']['idfamily'], $_POST['datecheck']['date']);
        break;
    default:
        break;
}
?>
