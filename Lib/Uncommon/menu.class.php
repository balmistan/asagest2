<?php
//Questa classe permette di creare la lista html per il menù

require_once("menuitem.class.php");

class menu{
	
private $arrayindex=0;

private $objarr=array();   //conterrà le istanze di menuitem corrispondenti alle voci del menù.

private $html="";           //conterrà l' html da mandare in output per la visualizzazione del menù.

private $classcontainer=""; //il menù andrà inserito in un div con attributo class = $classcontainer


public function __construct($classcontainer=""){
	$this->classcontainer=$classcontainer;
}


 /**
   * Aggiunge una opzione al menù
   * @param array $arr è un array associativo contenente le seguenti opzioni:
  * Nomi chiavi ammesse:
   * Text: testo da visualizzare sul pulsante
  *  Class: classe associata al pulsante.
  *  Link: link alla pagina su cui puntare (facoltativo) default considera "#"
  * Index: indice univoco associato al pulsante per poter aggiungere figli (facoltativo se non si ha si ha la necessità di aggiungere un sottomenù
  * AppendToIndex: indica di agganciarsi ad un opzione in cui è stato specificato Index (è facoltativo).
   */
public function addItem($arr){	
	
	if(!array_key_exists("Index",$arr)) $arr["Index"]=$this->arrayindex++;  //se non ricevo la key ne assegno una numerica io.
	
    if(array_key_exists("Index",$this->objarr)) die("class menu::addItem valore di index già utilizzato"); //prima di utilizzare una key mi assicuro che no ne esista  già una con lo stesso nome.
	if(isset($arr["AppendToIndex"]) && !array_key_exists($arr["AppendToIndex"],$this->objarr)) die("class menu::addItem valore di AppendToIndex non corrispondente ad alcun indice utilizzato");
	$obj=new menuitem($arr); 
	$this->objarr[$arr["Index"]]=$obj;
}	// close public function addItem
	
private function prepareObjectArray(){
	//scorro l' array $this->objarr settando su ciascun oggetto gli attributi children
	foreach($this->objarr as $key=>$object){
		//echo "-".$object->getAppendToIndex()."<br />";
		
		if(($appind=$object->getAppendToIndex()) != ""){
		
		$object->setIntegrated(true);               //indico che l' oggetto è stato incluso come figlio di un altro.	
		
		 $objparent=$this->objarr[$appind];
			$objparent->appendChild($object);	
			//echo $objparent->getIndex();
		}
	}
}// close private function prepareObjectArray()


private function prepareHTML(){
	$first=true;
		
	foreach($this->objarr as $key=>$val){
		$a="";
	    if($val->getAClass()!="") $a.=" class=\"".$val->getAClass()."\"";
	    if($val->getAId()!="") $a.=" id=\"".$val->getAId()."\"";
	    $ul="";
	    if($val->getUlClass()!="") $ul.=" class=\"".$val->getUlClass()."\"";
	    if($val->getUlId()!="") $ul.=" id=\"".$val->getUlId()."\"";
	    $li="";
	    if($val->getLiClass()!="") $li.=" class=\"".$val->getLiClass()."\"";
	    if($val->getLiId()!="") $li.=" id=\"".$val->getLiId()."\"";
	
		if(!$val->getIntegrated()){       //se l' oggetto non è stato integrato come figlio di qualche altro oggetto
	      if($first){
	      $this->html.="<ul>\n<li".$li."><a".$a." href=\"".$val->getLink()."\">".$val->getText()."</a></li>\n</ul>";
	      $first=false;
		  }else{
		  	$this->html=$this->replace_last_occurence($this->html, "</ul>", 
		  	"<li".$li."><a".$a." href=\"".$val->getLink()."\">".$val->getText()."</a></li>\n</ul>");
		  }
		  	
		  $this->prepareRecursiveHTML($val);
		}//close if(!$val->getIntegrated())
	}//close foreach
	
}

private function prepareRecursiveHTML($obj){  //metodo ricorsivo di supporto a prepareHTML()
	$myarr=$obj->getAppendedChildrenArray();
	for($i=0; $i<count($myarr); $i++){
		$a="";
	    if($myarr[$i]->getAClass()!="") $a.=" class=\"".$myarr[$i]->getAClass()."\"";
	    if($myarr[$i]->getAId()!="") $a.=" id=\"".$myarr[$i]->getAId()."\"";
	    $ul="";
	    if($myarr[$i]->getUlClass()!="") $ul.=" class=\"".$myarr[$i]->getUlClass()."\"";
	    if($myarr[$i]->getUlId()!="") $ul.=" id=\"".$myarr[$i]->getUlId()."\"";
	    $li="";
	    if($myarr[$i]->getLiClass()!="") $li.=" class=\"".$myarr[$i]->getLiClass()."\"";
	    if($myarr[$i]->getLiId()!="") $li.=" id=\"".$myarr[$i]->getLiId()."\"";
	
		if($i==0)$this->html = $this->replace_last_occurence($this->html, "</a>", 
		"</a>\n<ul>\n<li".$li."><a".$a." href=\"".$myarr[0]->getLink()."\">".$myarr[0]->getText()."</a></li>\n</ul>");
		
		else $this->html = $this->replace_last_occurence($this->html, "</a></li>\n</ul>", 
		"</a></li>\n<li".$li."><a".$a." href=\"".$myarr[$i]->getLink()."\">".$myarr[$i]->getText()."</a></li>\n</ul>");

        if(count($myarr[$i]->getAppendedChildrenArray())) $this->prepareRecursiveHTML($myarr[$i]);
	}
		
}



public function close(){
	$this->prepareObjectArray();
	$this->prepareHTML();
	return "<div class=\"".$this->classcontainer."\">\n". $this->html."</div>";
}



private function replace_last_occurence($string, $search, $replace){
	if($pos=strrpos($string, $search)) $string=substr_replace($string,$replace,$pos,strlen($search));
	return $string;
	}



}//close class menu

?>