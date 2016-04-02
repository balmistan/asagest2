<?php
require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die("Accesso non autorizzato");
}

secur::addSlashes($_POST);

$db = new db();

switch ($_POST['req']) {

    case "get_giacze_agea":                     //Richiesta giacenze Agea
        $allegati = new allegatin(1);   //uso tabelle cumulative.
        $arr_giacze = $allegati->getArrLastGiacza();  //non indicando data si riferisce alla data attuale
        echo json_encode($arr_giacze);
        break;

    case "set_memory_list":
        $db->update("memorylist", array("strjson" => $_POST['jsonstr']), array(
            array("where", "memId", "=", $_POST['id_mem'])
        ));
        echo '[]';
        break;

    case "get_memory_list":
        $arr = $db->getRow("memorylist", "strjson", array(
            array("where", "memId", "=", $_POST['id_mem'])
        ));
        secur::stripSlashes($arr['strjson']);
        echo json_encode($arr['strjson']);
        break;

    case "get_memory_scorsa":
        echo json_encode(getMemoryScorsa());
        break;

    case "get_config":
        $id = $_POST['key'];
        $resp = config::getConfig($id, "internalconfig");
        secur::stripSlashes($resp);
        echo json_encode($resp);
        break;

    case "set_config":
        $id = $_POST['key'];
        $str = $_POST['str'];
        config::setConfig($id, $str, "", "internalconfig");
        echo "[]";
        break;

    case "check_date":          //da controllare
        $allegati = new allegatin(1);
        echo $allegati->checkDate($_POST['datacheck']);
        break;

    case "check_distr_exists":
        $block = new block();
        // Non possono essere effettuate due distribuzioni alla medesima famiglia nello stesso giorno.
        echo $block->checkDistrEff($_POST['datacheck']['idfamily'], $_POST['datacheck']['date']);
        break;

    case "remove_distr":
        $block = new block();    //lavora su banco alim e Agea contemporaneamente. Non serve specificare
        $allegati = new allegatin(1);  //Non importa scelgo tabelle cumulative oppure no. Il metodo agisce su entrambe.
        $allegati->removeBlockSheet($_POST['sheetId']);   //Rimuove da registri Agea (se sono presenti distribuzioni Agea)
        //Procedo con la rimozione dal blocchetto
        $issue=$block->removeBlockSheet($_POST['sheetId']);  //Rimuove da blocchetto
        echo json_encode(array(0 => $issue));  //Dovrei in teoria restituire l' esito
        break;

    case "get_products":
        $arr_product = array();
        $product = new product(1);   //seleziono prodotti banco alimentare
        $arr_product["banco"] = $product->getDisplayProduct();
        $product = new product(0);   //seleziono prodotti Agea
        $arr_product["agea"] = $product->getDisplayProduct();
        echo json_encode($arr_product);
        break;

    case "get_info_family":
        $customer = new customer();
        echo json_encode($customer->getInfoFamily($_POST["pid"]));
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
    $arr_products = array();
    //$_POST['id_mem'] contiene in questo caso il familyid
    $arr_sheetId = $db->freeQuery("select max(sheetId) from blocksheet where personId in (select id_person from person where family_register_number=" . $_POST['id_mem'] . ")");

    if (count($arr_sheetId))
        $last_sheetId = $arr_sheetId[0]['max(sheetId)'];

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
