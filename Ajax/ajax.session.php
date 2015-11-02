<?php
require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die("Accesso non autorizzato");
}

secur::addSlashes($_POST);

$retval = array("");

if (isset($_POST["SESS_OPTION"])) {

    switch ($_POST["SESS_OPTION"]) {
        case "SETVAL":
            if (is_array($_POST["SESS_OPTION_NAME"])) {
                foreach ($_POST["SESS_OPTION_NAME"] as $key => $value) {
                    $_SESSION[$key] = $value;
                }
            } else
                $_SESSION[$_POST["SESS_OPTION_NAME"]] = $_POST["SESS_OPTION_VALUE"];

            break;

        case "GETVAL":
            if (is_array($_POST["SESS_OPTION_NAME"])) {
                $retval = array(); //Reinizializzo come array vuoto
                foreach ($_POST["SESS_OPTION_NAME"] as $key => $value) {
                    if (isset($_SESSION[$key]))     //Ottiene i valori dalla sessione. Ma se la variabile di sessione non è inizializzata, la inizializza con i valori di default passati come argomento.
                        $retval[$key] = $_SESSION[$key];
                    else{
                        $_SESSION[$key] = $value;
                        $retval[$key] = $value;
                    }
                }
            } else
            if (isset($_SESSION[$_POST["SESS_OPTION_NAME"]]))
                $retval = array($_SESSION[$_POST["SESS_OPTION_NAME"]]);
            break;

        default:
            break;
    }
}

echo json_encode($retval);
?>
