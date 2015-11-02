<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");
require_once("function.menuSmarty.php");
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}

secur::addSlashes($_GET);

$person = new Person();

$person_id = $_GET['pid'];

$msg = "";
$sheet_id = "";

$num_indig = $person->getNumComponents($person_id);

$otherinfo = "N° Comp: " . $num_indig;
$dataemiss=date("d/m/Y");       //viene sovrascritta se si tratta di modifica della consegna già effettuata.
$arr_last_quantity = array();   //array che conterrà le quantità di prodotto distribuite. Le chiavi saranno l' id prodotto. 
$arr_last_quantity_banco = array();
$arrx = array();  //conterrà tutti i dati da visualizzare letti con la funzione loadPrevDistr
$link_last_distr = "block.php?pid=" . $person_id . "&last=1";
$linktext = "Visualizza ultima distribuzione effettuata";

//codice per rimuovere la possibilità di salvare 2 volte ricaricando la pagina.
$block = new block();

$lastsheetidsaved = config::getConfig("lastsheetid", "internalconfig"); //Ultimo

$lastsaved = $block->getFamilyIdFromBlocksheet($lastsheetidsaved);  //id famiglia a cui è stata effettuata l' ultima distribuzione.
//$lastsaved = $session->getSessionVar("lastsaved", 0);
if (/*($lastsaved != 0 && $lastsaved == $session->getSessionVar("fid")) || */ (isset($_GET['last']) && $_GET['last'] == 1)) {
    if (loadPrevDistr($session->getSessionVar("fid"))) {

        $msg = "Ultima distribuzione effettuata:";
        $link_last_distr = "block.php?pid=" . $person_id . "&last=0";
        if (!(($lastsaved != 0 && $lastsaved == $session->getSessionVar("fid"))))
            $linktext = "Effettua una nuova distribuzione";
        else
            $linktext = "";
    }
}

function loadPrevDistr($fid) {
    global $person;
    global $person_id;
    global $otherinfo;
    global $sheet_id;
    global $arr_last_quantity;
    global $arr_last_quantity_banco;
    global $arrx;
    global $dataemiss;
    $block = new block();
    //Ottengo le informazioni base sull' ultima distribuzione effettuata.
    $arr_info = $block->getLastSheetId($fid);
    
    if (isset($arr_info['sheetId'])) {            //se esiste una precedente distribuzione...
        $arrx = $block->getForModalForm($arr_info['sheetId']);
        
        $start_index = config::getConfig("start_index_blocksheet", "allegaticonfig".REFAGEA);

        $sheet_id = intval($arr_info['sheetId']); //traslo valore su db rispetto al valore di pagina iniziale impostato nelle configurazioni.
        $dataemiss=substr($arrx['dtime'], 0, 10);
        
        $otherinfo = "[" . ($sheet_id + intval($start_index) - 1) . "]" .
                "&nbsp;&nbsp;&nbsp;" . $dataemiss .
                "&nbsp;&nbsp;&nbsp;N° Comp: " . $arrx["num_indig"];

        foreach ($arrx['distributedproducts'] as $arr_values) {
            $arr_last_quantity[$arr_values['id_product']] = $arr_values['qty'];
        }
        
        //Ottengo dati banco alimentare
        $block = new block(1);
        $arrx_banco = $block->getForModalForm($arr_info['sheetId']);
        
        foreach ($arrx_banco['distributedproducts'] as $arr_values) {
            $arr_last_quantity_banco[$arr_values['id_product']] = $arr_values['qty'];
        }
   // print_r($arrx_banco);    
        return 1;
    }
    return 0;
}

if (isset($arrx['surname']))
    $person_name = $arrx['surname'] . " " . $arrx['name'];
else
    $person_name = $person->getPersonNameById($person_id);

$arr_style_config = array();
$arr_js_config = array();

$arr_style_config[] = UI_STYLENEW;
$arr_style_config[] = "../bootstrap/css/bootstrap.min.css";
$arr_style_config[] = "../styles/page/page.css";
//$arr_style_config[] = "../Plugin/signature-pad/build/jquery.signaturepad.min.css";
$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";
$arr_style_config[] = JQALERT . "/jquery.alerts.css";
$arr_style_config[] = "../styles/block/block.css";

$arr_js_config[] = JQUERYNEW;
$arr_js_config[] = JQUERY_UINEW;
$arr_js_config[] = JQPLUGINS . "/datepicker/ui.datepicker-it.js";
//$arr_js_config[] = "../Plugin/signature-pad/jquery.signaturepad.js";
$arr_js_config[] = "../bootstrap/js/bootstrap.min.js";
$arr_js_config[] = "js/fastsearch.js";
$arr_js_config[] = JQALERT . "/jquery.alerts.js";
$arr_js_config[] = "js/block.js";
//$arr_js_config[] = "../Plugin/signature-pad/build/json2.min.js";
$menu = getOrrMenu();

$product = new product();

$arr_products = $product->getDisplayProduct();     //ottengo l' elenco prodotti da visualizzare

//duplico elementi se sono indicate diverse quantità per lo stesso prodotto

$len=count($arr_products);  //assegno quì perchè durante il ciclo for variano le dimensioni dell' array

for($i=0; $i<$len; $i++){
    if(floatval($arr_products[$i]["qtyforunity2"])!=0){
        $arr_products[$i."a"] = $arr_products[$i];
        $arr_products[$i."a"]["qtyforunity"] = $arr_products[$i]["qtyforunity2"];
    }
    if(floatval($arr_products[$i]["qtyforunity3"])!=0){
        $arr_products[$i."b"] = $arr_products[$i];
        $arr_products[$i."b"]["qtyforunity"] = $arr_products[$i]["qtyforunity3"];
    }
}

asort($arr_products);   //ordino in modo da avere vicini i prodotti identici. ordino mantenendo le chiavi.

//Aggiungo qtytot 
foreach ($arr_products as $key => $arr_val) {

    if (!isset($arr_last_quantity[$arr_val['product_id']]))
        $arr_products[$key]['qtytot'] = 0;
    else
        $arr_products[$key]['qtytot'] = $arr_last_quantity[$arr_val['product_id']];
}

//Faccio la stessa cosa coi prodotti del banco alimentare
$product = new product(1);  //indico che deve usare la tabella con i prodotti del banco alimentare.
$arr_products_banco = $product->getDisplayProduct();     //ottengo l' elenco prodotti da visualizzare

//duplico elementi se sono indicate diverse quantità per lo stesso prodotto
$len=count($arr_products_banco);  //assegno quì perchè durante il ciclo for variano le dimensioni dell' array

for($i=0; $i<$len; $i++){
    if(floatval($arr_products_banco[$i]["qtyforunity2"])!=0){
        $arr_products_banco[$i."a"] = $arr_products_banco[$i];
        $arr_products_banco[$i."a"]["qtyforunity"] = $arr_products_banco[$i]["qtyforunity2"];
    }
    if(floatval($arr_products_banco[$i]["qtyforunity3"])!=0){
        $arr_products_banco[$i."b"] = $arr_products_banco[$i];
        $arr_products_banco[$i."b"]["qtyforunity"] = $arr_products_banco[$i]["qtyforunity3"];
    }   
}

asort($arr_products_banco);   //ordino in modo da avere vicini i prodotti identici. ordino mantenendo le chiavi.

//Aggiungo qtytot 
foreach ($arr_products_banco as $key => $arr_val) {

    if (!isset($arr_last_quantity_banco[$arr_val['product_id']]))
        $arr_products_banco[$key]['qtytot'] = 0;
    else
        $arr_products_banco[$key]['qtytot'] = $arr_last_quantity_banco[$arr_val['product_id']];
}

//Indica se mostrare l' area firma
if(strtoupper(config::getConfig("signature_block"))=="NO"){
    $signature_block=0;
}else{
    $signature_block=1;
}

if (strtoupper(config::getConfig("init_registri")) == "NO")
    $init_registri = 0;
else
    $init_registri = 1;

$family_id = $session->getSessionVar("fid");
$family=new Family();
$arr_address = $family->getAddress($family_id);
$address_prov = "";
if($arr_address['provincia']!="")
    $address_prov = "(".$arr_address['provincia'].")";
$address = $arr_address['address']." ".$arr_address['nomeComune']." ".$address_prov;
$nome_struttura = config::getConfig('sedecriabbr', 'allegaticonfig'.REFAGEA);

secur::stripSlashes($nome_struttura);

$objSmarty = new Smarty();
$objSmarty->assign('nome_struttura', $nome_struttura);
$objSmarty->assign('init_registri', $init_registri);
$objSmarty->assign("title", PAGETITLE);
$objSmarty->assign("shortcuticon", SHORTCUTICON);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('menu', $menu);
$objSmarty->assign('arr_products', $arr_products);
$objSmarty->assign('arr_products_banco', $arr_products_banco);
$objSmarty->assign('family_id', $family_id);
$objSmarty->assign('person_id', $person_id);
$objSmarty->assign('person_name', $person_name);
$objSmarty->assign('address', $address);
$objSmarty->assign('sheet_id', $sheet_id);
$objSmarty->assign('otherinfo', $otherinfo);
$objSmarty->assign('link_last_distr', $link_last_distr);
$objSmarty->assign('linktext', $linktext);
$objSmarty->assign('signature_block', $signature_block);
$objSmarty->assign('dataemiss', $dataemiss);
$objSmarty->assign('msg', $msg);
$objSmarty->assign('num_indig', $num_indig);
$objSmarty->assign('config_start_blocksheet', config::getConfig("start_index_blocksheet", "allegaticonfig".REFAGEA));
$objSmarty->display('tpl/block.tpl');
?>
