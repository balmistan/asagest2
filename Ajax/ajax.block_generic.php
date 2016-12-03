<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");
require_once '../pages/debug.php';

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die("Accesso non autorizzato");
}



$db = new db();

$post = filter_input_array(INPUT_POST);


switch ($post['req']) {

    case "get_giacze_agea":                     //Richiesta giacenze Agea
        $allegati = new allegatin(1);   //uso tabelle cumulative.
        $arr_giacze = $allegati->getArrLastGiacza();  //non indicando data si riferisce alla data attuale
        echo json_encode($arr_giacze);
        break;

    case "set_memory_list":
        $db->update("memorylist", array("strjson" => $post['jsonstr']), array(
            array("where", "memId", "=", $post['id_mem'])
        ));
        echo '[]';
        break;

    case "get_memory_list":
        $arr = $db->getRow("memorylist", "strjson", array(
            array("where", "memId", "=", $post['id_mem'])
        ));
        secur::stripSlashes($arr['strjson']);
        echo json_encode($arr['strjson']);
        break;

    case "get_memory_scorsa":
        echo json_encode(getMemoryScorsa());
        break;

    case "get_config":
        $key = $post['key'];
        if ($key == "default_date_blocksheet") {
            $resp = (isset($_SESSION["default_date_blocksheet"])) ? $_SESSION["default_date_blocksheet"] : date("d/m/Y");
        } else {
            $resp = config::getConfig($key, "internalconfig");
        }
        secur::stripSlashes($resp);
        echo json_encode($resp);
        break;

    case "set_config":
        $key = $post['key'];
        $str = $post['str'];

        if ($key == "default_date_blocksheet") {
            $_SESSION["default_date_blocksheet"] = $str;
        } else {
            config::setConfig($key, $str, "", "internalconfig");
        }
        echo "[]";
        break;

    case "check_date":          //da controllare
        $allegati = new allegatin(1);
        echo $allegati->checkDate($post['datacheck']);
        break;

    case "check_distr_exists":
        $block = new block();
        // Non possono essere effettuate due distribuzioni alla medesima famiglia nello stesso giorno.
        echo $block->checkDistrEff($post['datacheck']['idfamily'], $post['datacheck']['date']);
        break;

    case "remove_distr":
        $block = new block();    //lavora su banco alim e Agea contemporaneamente. Non serve specificare
        $allegati = new allegatin(1);  //Non importa scelgo tabelle cumulative oppure no. Il metodo agisce su entrambe.
        $allegati->removeBlockSheet($post['sheetId']);   //Rimuove da registri Agea (se sono presenti distribuzioni Agea)
        //Procedo con la rimozione dal blocchetto
        $issue = $block->removeBlockSheet($post['sheetId']);  //Rimuove da blocchetto
        echo json_encode(array(0 => $issue));  //Dovrei in teoria restituire l' esito
        break;

    case "get_products":
        $arr_product = array();
        $b_product = new product(1);   //seleziono prodotti banco alimentare
        $arr_product["banco"] = $b_product->getDisplayProduct();
        $a_product = new product(0);   //seleziono prodotti Agea
        $arr_product["agea"] = $a_product->getDisplayProduct();
        echo json_encode($arr_product);
        break;

    case "get_info_family":
        $customer = new customer();
        echo json_encode($customer->getInfoFamily($post["pid"]));
        break;
    case "get_nome_sede":
        $resp = config::getConfig("nomeSede", "config");
        secur::stripSlashes($resp);
        echo json_encode($resp);
        break;
    case "get_distr":
        $resp = array();
        echo json_encode($resp);
        break;

    default: echo "[]";
}//close switch

function getMemoryScorsa() {
    global $db;
    global $post;
    $arr_products = array();
    //$_POST['id_mem'] contiene in questo caso il familyid
    $arr_sheetId = $db->freeQuery("select max(sheetId) from blocksheet where personId in (select id_person from person where family_register_number=" . $post['id_mem'] . ")");

    if (count($arr_sheetId)) {
        $last_sheetId = $arr_sheetId[0]['max(sheetId)'];
    }
    $arr_agea = $db->freeQuery("select id_product, qty from distributedproduct where sheetId=" . $last_sheetId);

    for ($i = 0; $i < count($arr_agea); $i++) {
        $arr_products[$arr_agea[$i]['id_product']] = $arr_agea[$i]['qty'];
    }

    $arr_banco = $db->freeQuery("select id_product, qty from distributedproductbanco where sheetId=" . $last_sheetId);

    for ($i = 0; $i < count($arr_banco); $i++) {
        $arr_products["banco_" . $arr_banco[$i]['id_product']] = $arr_banco[$i]['qty'];
    }

    return json_encode($arr_products);
}

?>
