<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("../Personal/config.php");
require_once("../Lib/Common/config.class.php");
require_once("../Lib/Common/secur.class.php");
require_once("../Lib/Common/logger.class.php");
require_once("../Lib/Common/user.class.php");
require_once("../Lib/Common/session.class.php");
require_once("../Lib/Common/accesslimited.class.php");
require_once("../Lib/Common/group.class.php");
require_once("../Lib/Uncommon/allegatin.class.php");
require_once("../Lib/Uncommon/product.class.php");
require_once("../Lib/Uncommon/excelconvert.class.php");
require_once '../Lib/PHPExcel/Classes/PHPExcel.php';
require_once '../Lib/PHPExcel/Classes/PHPExcel/IOFactory.php';

require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins"))) {
    header("Location:index?logout=1");
    exit(0);
}
$nome_struttura = config::getConfig('sedecriabbr', 'allegaticonfig' . REFAGEA);
$reg_ue = config::getConfig('reg_ue', 'allegaticonfig' . REFAGEA);

secur::stripSlashes($nome_struttura);
secur::stripSlashes($reg_ue);


$alleg = new allegatin(1);     //L' 1 indica che mi sto riferendo alle tabelle cumulative

$pagen = $alleg->getNumPages();   //Numero di pagine dell'allegato
//echo "<br /><br />";
//echo $pagen;
//echo "<br /><br />";

$arr_products = $alleg->getNameProductForTable();     //ottengo l' elenco prodotti da visualizzare nell' ordine relativo all' allegato 5.
//Ottengo le unità di misura per i vari prodotti
$product = new product();
$arr_umis = $product->getProductUMis();

/*
  foreach ($arr_products as $value) {
  print_r($value);
  echo "<br /><br />";
  }
  echo "<br /><br />";
  //  print_r($arr_umis);
 */

$total_num_register = count($arr_products);    //Numero totale di registri  ($arr_products è un array di array. Ogni array indica i prodotti per un singolo registro)
//echo $total_num_register;
//I registri sono mappati:
//1° registro si chiamerà registro a, il secondo registro b, e così via.

$arr_reg_mapping = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o");

//Il template da utilizzare dipende dal numero di registri e dal numero di pagine di ciascun registro.
//
$template = "../template_xls/" . $arr_reg_mapping[$total_num_register - 1] . "/template_" . $arr_reg_mapping[$total_num_register - 1] . $pagen . ".xls";
//Creo file excel:
//echo $template;
//echo $pagen;
if (1) {
    
        $excel = new excelconvert("../Personal/Documents/Allegato_3.xls", $template);
        $excel->setHeader($nome_struttura, "dei prodotti alimentari assegnati ai sensi del Reg. (UE) " . $reg_ue);
        $excel->setProductsName($arr_products, $arr_umis);  //Compila righe nomi prodotti di tutte le tabelle
    
    for ($pn = 1; $pn <= $pagen; $pn++) { //leggo i risultati per le varie pagine
        $session->setSessionVar("pagen", $pn);         //IMPORTANTE !  Dopo aver settato la pagina visualizzata, aggiorno la variabile di sessione in modo che la classe allegati legga i dati per la pagina settata.
        $arr_view = $alleg->getRowsView();
        $excel->addPageContent($arr_view, $pn - 1); //L' array parte sempre da 0, solo che in sessione setto 1 per avere la prima pagina (quella con indice 0)

        foreach ($arr_view as $value) {
            print_r($value);
            echo "<br /><br />";
        }
    }
   
  
    
        if (isset($_GET["dwn"])){
            $excel->Output("Allegato_5.xls");
            
        }else {
            $excel->Save("../Personal/Allegato_5.xlsx");
        }
   
}


//$excel->Output("../Personal/Documents/Allegato_5.xlsx");
?>