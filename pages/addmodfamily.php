<?php

require_once("../Personal/config.php");

require_once("../Lib/autoload.php");
require_once("function.menu.php");
include ("orizontalmenu.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}

secur::addSlashes($_GET);

if (isset($_GET["fid"]) && !is_numeric($_GET["fid"]))
    die("Errore ID!");

$nophoto = NOPHOTO;

$page = new page();
$page->setTitle(PAGETITLE);
$page->setIcon(SHORTCUTICON);
$is_mobile = strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile');

    $page->addStyle("../styles/page/page.css");
    $page->addStyle("../Plugin/jcrop/css/jquery.Jcrop.css");
    $page->addStyle("../styles/addmodfamily/addmodfamily.css");


$page->addStyle("../styles/orizontalmenu/orizontalmenu.css");
$page->addStyle(JQALERT . "/jquery.alerts.css");

$page->addJS(JQUERY);
$page->addJS(JQUERY_UI);
$page->addJS("js/fastsearch.js");
$page->addJS("../Lib/Common/js/cf_handle.js");

$page->addJS("../Lib/Uncommon/js/checkbox_test.js");
$page->addJS("../Lib/Common/js/utility.js");
if (!$is_mobile) {
    $page->addJS("../Plugin/jpegcam/webcam.js");
    $page->addJS("../Plugin/jcrop/js/jquery.Jcrop.min.js");
    $page->addJS("js/mywebcam.js");
}
$page->addJS(JQALERT . "/jquery.alerts.js");

$page->addJS("js/addmodfamily.js");

$page->addMeta("initial-scale=0.8, maximum-scale=0.8", array("name" => "viewport"));
$page->addCode(menu_convert(array("data" => getOrrMenu(), "class" => "admin_menu")));  //eseguo la conversione ad html perchè non sto usando Smarty.
$page->addcode("<br />");
$page->openTag("center");
$page->openDiv(array("class" => "sheet"));


$form = new form("add_family", "POST", "?", $page);
//imposto elementi multipli in testa al form 
$jquery = new jquerymultielement($page, $form);

//ottengo l' elenco dei comuni per popolare la selectbox/////////////////

$db = new db();

$result_array = $db->getRows("comuni", array("nomeComune", "provincia", "idComune"), array(
    array("WHERE", "provincia", "=", config::getConfig('criprovince'))
        ), array(
    array("orderby", "nomeComune")
        )
);


//Blocco aggiuntivo da rimuovere al successivo aggiornamento:
/*
$arr_temp = $db->getRows("family", array("district_id", "district"));


for ($i = 0; $i < count($arr_temp); $i++) {

    if ($arr_temp[$i]['district_id'] == 0) {

        $arr_id = $db->getRow("comuni", "idComune", array(
            array("where", "nomeComune", "=", $arr_temp[$i]['district'])
        ));
        if (count($arr_id)) {
            $db->update("family", array("district_id" => $arr_id["idComune"]), array(
                array("where", "district", "=", $arr_temp[$i]['district']),
                    //Aggiungere il limit 1
            ));
        }
    }
}
//Fine blocco aggiuntivo
*/
$optionslist = array();
$optionslist[0] = "Non indicato";
for ($i = 0; $i < count($result_array); $i++) {
    $optionslist[$result_array[$i]['idComune']] = $result_array[$i]['nomeComune'] . " (" . $result_array[$i]['provincia'] . ")";
}

//////////////////////////////////////////////////////////////////////

$jqstyle = new jqueryuistyle($page);
$jqstyle->useJqueryButton("#Salva");
$jqstyle->useJqueryButton("#Annulla");

$customer = new customer();

$id = 0;  //E' l' id della scheda famiglia. In caso di nuovo inserimento, non sono io a deciderlo ma mi verrà restituito dal metodo customer::saveFromPost($_POST).

if (isset($_GET["fid"])) {   //recupero i valori dal db. Ricevo l' id family con GET nel caso in cui vengo reindirizzato dalla pagina risultati ricerca
    $arr_val = $customer->getFromDb($_GET["fid"]);
    $id = $_GET["fid"];
    $array_born_date = $customer->date_convert($arr_val["borndate"]); //converto l' array di date dal formato restituito dalla query a quello che dovrò visualizzare
    //rendo la riga contenente la textbox con id="idtex" multipla inizializzando i vari campi
    $jquery->addMultielemById("idtext", array($arr_val["person_id"], $arr_val["imagelink"], $arr_val["lastName"], $arr_val["firstName"], $arr_val["cf"], $array_born_date, $arr_val["rr"]));
} else {
    if (isset($_POST["lastName"])) {                      //se ricevo dati in post, dovrò salvarli sul database
        //Svuoto dati di default ricerca scheda da sessione visto che i dati vengono aggiornati
        config::setConfig("lastdateupdate", date("Y-m-d h:m:s", time()), "Orario ultima modifica tabella family", "internalconfig");
       // if(isset($_SESSION["default_result"]))
       //     unset($_SESSION["default_result"]);
        $id = $_POST["id"] = intval($_POST["id"]);

        // header("Location: addmodcustomer.php");


        $_POST["expireisee"] = $customer->date_convert($_POST["expireisee"]);  //converto le date dal formato visualizzato nel campo di testo a quello da passare al database
        $_POST["borndate"] = $customer->date_convert($_POST["borndate"]);

        $id = $customer->saveFromPost($_POST);    //salva su db i dati ricevuti in POST e torna l' id family con cui li ha salvati.

        if ($id) {
            //Se l' id restituito è diverso da zero significa che il salvataggio è andato a buon fine e posso visualizzare il messaggio.

            $page->addCode('<script>
                $(function () {
                  $("#saved_msg").show();
                  $("#saved_msg").css("visibility","visible").fadeIn().fadeOut(2000);
                }); // close $(function ()
                </script>');
        }

        $arr_val = $customer->getFromDb($id);        //Come verifica che i dati sono stati salvati correttamente, li rileggo dal db e li visualizzo nella form.
        //print_r($arr_val);

        $array_born_date = $customer->date_convert($arr_val["borndate"]); //converto l' array di date
        $jquery->addMultielemById("idtext", array($arr_val["person_id"], $arr_val["imagelink"], $arr_val["lastName"], $arr_val["firstName"], $arr_val["cf"], $array_born_date, $arr_val["rr"]));
    } //close if(isset($_POST["Invia"]))
    else {   //se non ho ricevuto dati in POST
        $arr_val = $customer->getFromDb();
        $jquery->addMultielemById("idtext", array(array()));
    }
}// close else if isset $_GET[fid]	
$page->openDiv(array("class" => "sheet_title"));

if ($id == 0)
    $page->addText("Nuova Scheda");
else {
    $page->addText("Scheda n° " . $id . "<img id=\"cart\" src=\"../styles/page/images/back.png\" alt=\"Torna alla pagina pecedente\" />");
}
$page->closeDiv();

$form->addHideField("id", $id, array("id" => "idf"));      // id scheda famiglia
$form->addIntoTableForm("<tr><td><input type=\"hidden\" name=\"person_id[]\" value=\"\" class = \"mtf_textfield hfield_person_id\" /><div class=\"photodiv\"><img src=\"" . $nophoto . "\" alt=\"\" /></div><input type=\"hidden\" name=\"imagelink[]\" value=\"\" class = \"mtf_textfield hfield_imglink\" /></td></tr>");
$form->addTextField_2("lastName[]", "Cognome<span class=\"red\">*</span>:", array('autocomplete' => 'off', 'id' => 'idtext', "class" => "mtf_textfield lastname_tbox", 'value' => '', 'onblur' => 'return first_upper_case(event, this)'), false);
$form->addTextField_2("firstName[]", "Nome:", array('autocomplete' => 'off', 'id' => 'idtext2', "class" => "mtf_textfield firstname_tbox", 'value' => '', 'onblur' => 'return first_upper_case(event, this)'), false);
$form->addTextField_2("cf[]", "C.F:", array('autocomplete' => 'off', 'id' => 'cf', "class" => "mtf_textfield cf_tbox", 'maxlength' => '16', 'value' => ''), false);
$form->addTextField_2("borndate[]", "Data nascita:", array('placeholder'=>' gg/mm/aaaa','autocomplete' => 'off','id' => 'borndate', "class" => "mtf_textfield date_tbox borndate_tbox", 'value' => ''), false);
$form->addDiv("", "", array("class" => "sexeta"));
//$form->addRadioButton_2("sex[]", array("M","F", "?"), "", false);
$form->addRisultSearch1("R.R.", false, "Indica se mostrare il nome di questa persona nella fase di ricerca. Selezionare solo se è chi viene a ritirare i viveri");

$form->addTextField_2("address", "Indirizzo:", array('autocomplete' => 'off', 'id' => 'address', "class" => "address_tbox", 'value' => $arr_val["address"]), true, array("1", "", ""));

$form->addSelectBox_2("district_id", "Comune", $optionslist, array("title" => "L' elenco comuni è quello della provincia la cui sigla è stata indicata in Configurazioni -> Sistema."), $arr_val["district_id"], false);
//$form->addSel
//$form->addTextField_2("district", "Comune:", array('id' => 'district', "class" => "district_tbox", 'value' => $arr_val["district"]), false);
$form->addTextField_2("telephone", "Tel:", array('autocomplete' => 'off', 'id' => 'telephone', "class" => "tel_tbox", 'value' => $arr_val["telephone"]), false);
$form->addVSpace("30");
$form->addTextArea("note", "Note", array("align" => "top", "rows" => 2, "cols" => 45, "style" => "resize:none;", "value" => $arr_val["note"]), true, $colspan = array("1", "2", "2"));

$form->addTextField_2("expireisee", "Scad. certificato:", array('placeholder'=>' gg/mm/aaaa', 'title' => 'E\' possibile indicare la scadenza del certificato che attesta che la famiglia necessita i viveri. Settando opportunamente un opzione di configurazione in Sistema è possibile visualizzare le notifiche.', 'class' => 'date_tbox', 'value' => $customer->date_convert($arr_val["expireisee"])), false, array("", "", ""), "#expireisee");
$form->addVSpace("30");
$jquery->close();
//<div id=\"saved_msg\">Dati salvati!</div>
$form->addDiv("", "Dati salvati!", array("id" => "saved_msg"));


$form->addRadioButton("Stato della scheda", array("completa", "incompleta", "disattivata"), setradiobutton($arr_val["statoscheda"]), 0);
$form->addVSpace("30");

$form->addButton_2("button", "Salva", array(), 3, true);
$form->addButton_2("button", "Annulla", array(), 0, false, 2);

$myjquery = new myjquery($page);    //serve per l' autocompletamento dei comuni
$myjquery->addAutocompletePlugin("district", "../Ajax/ajax_district_insert.php", array("autoFocus" => "true"));
//$myjquery->AutocompleteOptionViewer("district", array("nomeComune", " (","provincia", ")"));
//mi serve solo per aggiungere del codice jquery nel $(document).ready
$myjquery->addJQueryPlugin("  
    $(\".cf_tbox\").each(function(){ // per ogni riga leggo il cf e la data di nascita inserite
        var cf = $(this).val();
        var objbdate = getElementObjByCfObj($(this), \"borndate_tbox\");
        var born_date = $(objbdate).val();    
        var sexeta_obj=getElementObjByCfObj($(this), \"sexeta\");               //ottengo l' oggetto div con class sexeta      
        var sexeta_label_obj=$(sexeta_obj).parent().find(\"label\");            //ottengo l' oggetto label corrispondente

        if(cf!=''){
           var sex=getSexByCf(cf);
           if(sex=='M'){
             $(sexeta_obj).addClass('bck_blue')
           }else if(sex=='F'){
             $(sexeta_obj).addClass('bck_pink')
           }
           $(sexeta_label_obj).html(\"Età\");
         }//close if(cf!='')
         
        if(born_date!=''){
          var eta=getEta(born_date);
          $(sexeta_obj).html(eta)
        }//close if(borndate!='')

 }); //close $(\".cf_tbox\").each(function()

$(\".cf_tbox\").live(\"focusout\", function(){
     $(this).val($(this).val().toUpperCase())
 });
 
 $(\".cf_tbox\").live(\"keyup\", function(){
     cf_handle($(this))
 }); //close $(\".cf_tbox\").live(\"keyup\", function()
 
 $(\".cf_tbox\").live(\"blur\", function(){
     cf_handle($(this))
 }); //close $(\".cf_tbox\").live(\"blur\", function()
 
 
 function cf_handle(obj){
    if($(obj).val().length==16){ 
         var sugg_date=getSuggDateByCF($(obj).val());
         if(sugg_date){
         
           var objdatefield=getElementObjByCfObj($(obj), \"borndate_tbox\");
                 objdatefield.attr(\"default_date\",sugg_date);
                 objdatefield.val(sugg_date);
        
         }//close  if(sugg_date) 
         
     }// close if($(obj).val().length==16)
 }//close function cf_handle(obj)

function getElementObjByCfObj(cf_obj, class_of_element_searched){
  var parent_elem=$(cf_obj).parent().parent();  
           var objelem=null;
           $(parent_elem).find(\"input,div\").each(function(){
             if($(this).hasClass(class_of_element_searched)) {
                 objelem = $(this);
               }      
         }) //close $(parent_elem).find(\"input\").each(function()
         return objelem;
}

$(\"#cart\").click(function(){
    window.location.href = \"viewfamily.php?fid=" . $id . "\";
})
           
");

if (!$is_mobile)
    $myjquery->addJQueryPlugin(" 
    //GESTIONE WEBCAM
            $.mywebcam({'save_folder': '../Personal/PhotoPeople', 'nophoto': '" . $nophoto . "', 'title': 'Foto carta d\' identità'});
");

$form->close();

$page->closeDiv();              //close openDiv(array("class"=>"sheet")

$page->closeTag("center");

$page->openDiv(array("id" => "content_webcam"));
$page->closeDiv();
$page->addCode('<p>Le righe aggiunte vengono utilizzate dal programma per il conteggio degli indigenti. Se non si dispone dei nominativi degli altri componenti della famiglia aggiungere comunque le righe ed inserire una x sul cognome.</p>');
if (!$is_mobile)
    $page->addCode('<p>(Per inserire la foto cliccare su "NO FOTO")</p>');
$page->close();

function setradiobutton($option) {

    if ($option == "" || $option == "incomplete")
        $tmp = array(
            array("name" => "statoscheda", "value" => "complete"),
            array("name" => "statoscheda", "value" => "incomplete", "checked" => "checked"),
            array("name" => "statoscheda", "value" => "deleted")
        );
    else if ($option == "complete")
        $tmp = array(
            array("name" => "statoscheda", "value" => "complete", "checked" => "checked"),
            array("name" => "statoscheda", "value" => "incomplete"),
            array("name" => "statoscheda", "value" => "deleted")
        );

    else if ($option == "deleted")
        $tmp = array(
            array("name" => "statoscheda", "value" => "complete"),
            array("name" => "statoscheda", "value" => "incomplete"),
            array("name" => "statoscheda", "value" => "deleted", "checked" => "checked")
        );
    else
        $tmp = array(
            array("name" => "statoscheda", "value" => "complete"),
            array("name" => "statoscheda", "value" => "incomplete"),
            array("name" => "statoscheda", "value" => "deleted")
        );
    return $tmp;
}

?>
