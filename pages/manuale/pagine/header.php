<?php
$arr_index = array(
    array("href"=>"introduzione.php", "title"=>"Introduzione"),
    //array("href"=>"suggerimenti.php", "title"=>"Suggerimenti"),
    array("href"=>"login.php", "title"=>"Login"),
    array("href"=>"home.php", "title"=>"Home"),
    array("href"=>"setupiniziale.php", "title"=>"Setup Iniziale"),
    array("href"=>"prodottiagea.php", "title"=>"Configurazione Prodotti Agea"),
    //array("href"=>"ordineprodotti.php", "title"=>"Ordine di visualizzazione Prodotti su allegato 8"),
    array("href"=>"prodottidonazioni.php", "title"=>"Configurazione Prodotti Donazioni"),
    array("href"=>"configurazionesistema.php", "title"=>"Configurazione Sistema"),
    array("href"=>"configurazioneregistri.php", "title"=>"Configurazione Registri Agea"),
    //array("href"=>"giacenzeiniziali.php", "title"=>"Giacenze iniziali Agea", "important"=>1),
    //array("href"=>"backup.php", "title"=>"Backup Sistema"),
    
    array("href"=>"nuovascheda.php", "title"=>"Nuova Scheda"),
    array("href"=>"modificascheda.php", "title"=>"Modifica Scheda"),
    array("href"=>"cercascheda.php", "title"=>"Cerca Scheda"),
    array("href"=>"ricercaavanzata.php", "title"=>"Ricerca avanzata persone"),
    array("href"=>"riepilogoscheda.php", "title"=>"Riepilogo Scheda"),
    
    array("href"=>"distribuzione.php", "title"=>"Distribuzione Viveri", "important"=>1),
  //  array("href"=>"carico.php", "title"=>"Carico Agea", "important"=>1),
    array("href"=>"gruppi.php", "title"=>"Gestione account"),
    //array("href"=>"", "title"=>"")
);



function showIndex(){
    global $arr_index;
    $pre = "";
    $str_html="<div class=\"menu8\">\n<ol>";
    
    for ($i=0; $i<count($arr_index); $i++){
      if(isset($arr_index[$i]['important']) && $arr_index[$i]['important']==1)
      	$str_html.="<li><a class=\"link_index\" href=\"".$pre.$arr_index[$i]['href']."\">".$arr_index[$i]['title']."</a><span class=\"red\">&nbsp;&nbsp;&nbsp;Importante!</span></li>\n"; 
      else
      	$str_html.="<li><a class=\"link_index\" href=\"".$pre.$arr_index[$i]['href']."\">".$arr_index[$i]['title']."</a></li>\n";  
    }
    $str_html.="</ol>\n</div>";
    echo $str_html;
}

function showMenu(){
    global $arr_index;
     $str_html="
            <table class=\"menuorizz\"><tr>\n";
     
     $str_html.="<td><a href=\"index.php\">Indice</a></td>";
     
     $key=getKey();
     
     if($key!=0){
         $str_html.="<td><a href=\"".$arr_index[$key-1]['href']."\">Precedente (".$arr_index[$key-1]['title'].")</a></td>\n";
     }
      
     if($key<count($arr_index)-1){
        $str_html.="<td><a href=\"".$arr_index[$key+1]['href']."\">Successiva (".$arr_index[$key+1]['title'].")</a></td>\n"; 
     }
     
     $str_html.="<td><a href=\"../../home.php\">Chiudi</a></td>\n			
            </tr></table>\n";
   
   echo $str_html;
}

function getLink(){
   return basename($_SERVER['SCRIPT_FILENAME']);
}

function getTitle(){
    global $arr_index;
    $key=getKey();
    return $arr_index[$key]['title'];
}

function getKey(){
    global $arr_index;
    $link=getLink();
    for ($i=0; $i<count($arr_index); $i++){
        if($arr_index[$i]['href']==$link) 
            return $i;
    }
    return -1;
}



?>
