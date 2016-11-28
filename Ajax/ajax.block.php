<?php
require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    die("Accesso non autorizzato");
}

secur::addSlashes($_POST);

if (!isset($_POST['output']))      //campo firma
    $_POST['output'] = "";

$logger = new logger("../elog/ajax.block.log", 0);

$logger->rawLog($_POST);

//Rimuovo da $_POST i prodotti le cui quantità sono nulle in modo da non salvare quantità distribuite nulle nel db.
$numelement = count($_POST['product_id']);  //Calcolo quì perchè durante il ciclo for le dimensioni dell' array varirano.

for ($i = 0; $i < $numelement; $i++) {
    if (floatval($_POST['qtytot'][$i]) == 0) {
        unset($_POST['product_id'][$i]);
        unset($_POST['qtytot'][$i]);
    }
}


//devo sapere se si tratta di un nuovo inserimento o aggiornamento perchè nel secondo caso, alla fine dovrò riaggiornare le righe successive del registro di carico e scarico

$sheetId = $_POST['sheetId'];

//Posso avere una situazione di aggiornamento in cui lo sheetId non è presente nella distribuione Agea (tabella distributedproduct...). 
//Questo accade se la distribuzione è solo Banco e io la modifico aggiungendovi prodotti Agea.
$allegati = new allegatin(1);
if ($sheetId == "" || ($sheetId != "" && !$allegati->isUsedBlockSheet($sheetId))) //Verifica se esiste una consegna (foglio blocchetto) con quello sheetId (Banco, Agea o entrambi)
    $riaggiorna = false;   //nuovo inserimento
else
    $riaggiorna = true;    //aggiornamento riga/ghe preesistenti sui registri Agea
  
//Se un id prodotto inizia con banco_ tale riga dovrà essere salvata in una differente tabella.
//$num_elem = count($_POST['product_id']);  //Calcolo quì perchè durante il ciclo for le dimensioni dell' array varirano.

$arr_banco = array();
$arr_banco['product_id'] = array();
$arr_banco['qtytot'] = array();

$arr_agea = array();
$arr_agea['product_id'] = array();
$arr_agea['qtytot'] = array();

foreach ($_POST['product_id'] as $i => $value) {
    if (strpos($value, "banco_") !== FALSE) {
        $arr_banco['product_id'][$i] = substr($_POST['product_id'][$i], 6);
        $arr_banco['qtytot'][$i] = $_POST['qtytot'][$i];
    } else {
        $arr_agea['product_id'][$i] = $_POST['product_id'][$i];
        $arr_agea['qtytot'][$i] = $_POST['qtytot'][$i];
    }
}


//In tabella distributedproduct salvo/aggiorno in ogni caso. E' quella che viene letta nelle tabelle delle pagine di report 1 e 2 
//Anche se non sono state effettuate distribuzioni Agea, lancio cmq questo metodo perchè ha anche il compito di inizializzare il blocksheet.
$block = new block(0);                    //Viveri Agea
//Aggiungo ulteriori parametri

$arr_agea['person_id'] = $_POST['person_id'];
$arr_agea['modifiable'] = $_POST['modifiable'];
$arr_agea['sheetId'] = $_POST['sheetId'];
$arr_agea['output'] = $_POST['output'];
$arr_agea['date'] = $_POST['date'];
$arr_agea['num_indig'] = $_POST['num_indig'];

$ret = $block->save($arr_agea);

//Lo sheetId utilizzato viene restituito all' interno di ret e lo assegno in arr_banco 
//------

if (count($arr_banco["product_id"])) {  //Se sono stati distribuiti prodotti del banco alimentare...
    //Aggiungo altri parametri da utilizzare:
    //inizializzazione array banco alimentare con ulteriori parametri
    $arr_banco['person_id'] = $_POST['person_id'];
    $arr_banco['modifiable'] = $_POST['modifiable'];
    $arr_banco['sheetId'] = $ret['xxx']['sheetId'];
    $arr_banco['output'] = $_POST['output'];
    $arr_banco['date'] = $_POST['date'];
    $arr_banco['num_indig'] = $_POST['num_indig'];
//fine inizializzazione

    $block = new block(1);
   $ret = $block->save($arr_banco);
}

//L' aggiornamento dei regitri Agea e della tabella distributedproduct dovrò effettuarlo solo se sono stati distribuiti prodotti Agea.
//Se non risultano prodotti Agea in $_POST deve essere applicata solo la modalità aggiornamento registri se lo sheetId !="".
//La modalità aggiornamento registri non agisce in ogni caso se lo sheetId non si trova già memorizzato sulle tabelle dei registri.

$usaallegati = true;

//$arr_agea contiene solo i dati da salvare in Agea. Verifico che non siano tutte quantità nulle.

$ageadistr = false;     // se ho in post una distribuzione Agea
foreach ($arr_agea['qtytot'] as $key => $value) {
    if (floatval($value) != 0) {
        $ageadistr = true;
        break;
    }
}//close foreach
//Se è un nuovo inserimento e non ho prodotti Agea:

if ($sheetId == "" && !$ageadistr)
    $usaallegati = false;

//Se è un aggiornamento e non ho prodotti Agea:

if ($sheetId != "" && !$ageadistr) {
    //è utile se sto facendo un aggiornamento e rispetto al precedente inserimento ho eliminato tutti i prodotti Agea.
//In pratica cancellerà tutti i prodotti agea corrispondenti allo sheetId indicato
    $allegati = new allegatin(1);  //non ha importanza se si sceglie cumulative oppure no
    //  ........  ..... ......  Da completare ....
}

//ho il personId attraverso il quale posso ottenere il numero di componenti
$personId = $ret['xxx']['personId'];

$person = new Person();

$ret['xxx']['numcomponents'] = $numindig = $person->getNumComponents($personId);

$arr_date = explode("/", $ret['xxx']['date']);

$date = $arr_date[2] . "-" . $arr_date[1] . "-" . $arr_date[0];

//devo individuare il numero dell' allegato 9 corrispondente
//prelevo i dati da visualizzare i allegato 9 i quali contengono anche il numero dell' allegato.
$datamin = $date . " 00:00:01";
$datamax = $date . " 23:59:59";

$block = new block(0); //Seleziono viveri Agea

$arr_out = $block->getForReport3($datamin, $datamax, "", 1);  // 1 indica di far riferimento ad Agea. Gli allegati contengono infatti i prodotti distribuiti con Agea.


if ($usaallegati) {
//////////////////Salvataggio su registro cumulativo//////////

    $arr_scarico = array();
    $arr_scarico['date'] = $date;
    //$arr_scarico['numrif'] = $arr_out['num_all9'];
    $arr_scarico['numindig'] = $_POST['num_indig'];
    $arr_scarico['products'] = array();
    foreach ($arr_out['products'] as $productId => $arrval) {
        $arr_scarico['products'][$productId] = floatval($arrval['qty']);
    }

    $allegati = new allegatin(1); //mi serve per la funzione riaggiorna. Non ha importanza se nel costruttore indico tabelle cumulative oppure no in quanto il metodo riaggiorna ne è indipendente.      

    if (!$riaggiorna) {                 //In caso di aggiornamento, questo verrà eseguito più avanti  //L' 1 indica che sto usando le tabelle cumulative
        $arr_giacze = $allegati->getArrLastGiacza();  //prima di fare qualsiasi inserimento

        $allegati->scarico($arr_scarico, $arr_giacze);
    } else {//caso aggiornamento
        $allegati->riaggiorna();
    }  
}// close if($usaallegati)

$session->setSessionVar("lastsaved", $session->getSessionVar("fid"));

///////// PREPARAZIONE DEL FILE PDF   ///////////////////////////////
////////////////////////////////////////////////////////////////////

echo json_encode($ret);
?>
