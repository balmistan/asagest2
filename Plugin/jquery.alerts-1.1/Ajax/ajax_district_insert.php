<?php

/**
 * Script per la selezione dei comuni e province
 * Copyright NetMDM 2010
 */

require_once("../Lib/config.php");             //sono in libss questi file
require_once("../Lib/autoload.php");

	
$tabellacomuni="comuni";


$db=new db();
if(isset($_POST["id"]) && $_POST["id"]=="district"){
$searchstring=$_POST["value"];
$result_array=$db->getRows($tabellacomuni,"nomeComune",
      array(
            array("WHERE","provincia","=","ME"),
            array("AND","nomeComune","LIKE","$searchstring%"),                
          ),array(
              array("orderby","idComune")
              )
   );
  
    // $result_array=array_reverse($result_array);

   
   

                                       //////  BLOCCO RISERVATO  ///////

  ///////////////////////////////////chiusura blocco (da quì in poi il codice non dovrà essere modificato)/////////////////////////////////////////////////////////////////////


$json_arr=json_encode($result_array);       //mi da la stringa json corrispondente all' array $result_array


//$result_array è una matrice. Ne determino dunnque le dimensioni


$rows = count($result_array,0);
if($rows!=0){   //se l' array non è vuoto

$cols = (count($result_array,1)/$rows)-1;


//preparo la parte da aggiungere alla stringa json
//$cols=0 significa array vuoto ma devo comunque aggiungere l' id che tornerà indietro alla stringa json

//tolgo la parentesi quadra finale
$json_arr=substr ( $json_arr , 0, (strlen($json_arr) -1) );

$json_arr.=',{"responseid":"'.$_POST['id'].'"';

for($ecnt=1; $ecnt<$cols; $ecnt++) $json_arr.=',"'.$ecnt.'":""';

$json_arr.='}';

//aggiungo adesso il nome  delle colonne restituite da mysql

$namecols=array_keys($result_array[0]);

$json_arr.=',{';

foreach($namecols as $k=>$v){
  
  $json_arr.='"'.$k.'":"'.$v.'",';
}
//Tolgo la virgola finale e chiudo la parentesi graffa e quadra

$json_arr=substr ( $json_arr , 0, (strlen($json_arr) -1) );

$json_arr.='}]';

}// close if($rows!=0)

 echo $json_arr; 

}//close if(isset($_POST["id"]) && $_POST["id"]=="district")


?>
