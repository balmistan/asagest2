<?php

require_once '../Personal/config.php';
require_once '../Lib/autoload.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    echo json_encode("danied");
} else {

    $person = new Person();
    $block = new block();

    $arr_out = array();

    if ($_POST['datamin'] == "")
        $_POST['datamin'] = "1999-03-12 00:00:00";
    else {
        $dm = $_POST['datamin'];
        $_POST['datamin'] = substr($dm, 6, 4) . "-" . substr($dm, 3, 2) . "-" . substr($dm, 0, 2) . " 00:00:00";
    }

    if ($_POST['datamax'] == "")
        $_POST['datamax'] = date(Y - m - d);
    else {
        $dm = $_POST['datamax'];
        $_POST['datamax'] = substr($dm, 6, 4) . "-" . substr($dm, 3, 2) . "-" . substr($dm, 0, 2) . " 23:59:59";
    }

    if (isset($_POST['comune']))
        $comune = $_POST['comune'];
    else
        $comune = "";                 //indica selezione tutti i comuni

    switch ($_POST['tabselected']) {

        case 'tabs-1':
            getReport_1();
            break;
        case 'tabs-2':
            getReport_2();
            break;
        case 'tabs-3':
            getReport_3();
            break;

        default:
            break;
    }//close switch

    echo json_encode($arr_out);
}

/////////////////  FUNCTIONS //////////////////////////////

/**
 * Obiettivo di questa funzione è ottenere l' array dati da usare per la datatable.
 * @global type $_POST
 * @global array $arr_out 
 */
function getReport_1() {
    global $_POST;
    global $arr_out;
    global $block;        //classe che gestisce il blocchetto consegne.
    global $person;       //classe che gestisce la tabella persone.

    $arr_out = $block->getForReport($person, $_POST['familyid'], $_POST['datamin'], $_POST['datamax']);
    return;
}

function getReport_2() {
    global $_POST;
    global $arr_out;
    global $block;        //classe che gestisce il blocchetto consegne.
    global $person;       //classe che gestisce la tabella persone.
    global $comune;

    $arr_out = $block->getForReport($person, "", $_POST['datamin'], $_POST['datamax'], $comune);
    return;
}

function getReport_3() {
    global $_POST;
    global $arr_out;
    global $block;
    global $comune;

    $arr_out['agea'] = $block->getForReport3($_POST['datamin'], $_POST['datamax'], $comune);
    $block = new block(1);
    $arr_out['banco'] = $block->getForReport3($_POST['datamin'], $_POST['datamax'], $comune);
}

?>
