<?php

require_once("planmenuitem.class.php");

class planmenu{
	
private $ArrIndex=0;

private $ObjArr=array();

private $Html="";

private $PathImages;

private $NumItemsForRow;



public function __construct($NumItemsForRow=1,$PathImages=""){
	
	$this->NumItemsForRow=$NumItemsForRow;
	$this->PathImages=$PathImages;
}


 /**
   * Aggiunge una opzione al menù
   * @param array $arr è un array associativo contenente le seguenti opzioni:
  * Nomi chiavi ammesse:
  *  Class: classe associata all' immagine
  *  Id: ID associato all' immagine
  *  Link: link alla pagina su cui puntare (facoltativo) default considera "#"
  * SrcImage: Link immagine da aggiungere al menù
  * Title: Suggerimento al passaggio del mouse (è facoltativo).
   */
public function addItem($arr){
	$obj=new planmenuitem($arr); 
	$this->ObjArr[$this->ArrIndex++]=$obj;	
}


private function prepareHTML(){
$total_icons=count($this->ObjArr);
$this->Html.="<table border=0>";
$j=0;
while($j<$total_icons){
$this->Html.="<tr>\n";
for($i=0; $i<$this->NumItemsForRow && $j<$total_icons; $i++){
	$obj=$this->ObjArr[$j];
	if($obj->getClass()!="") $class=" class=\"".$obj->getClass()."\"";
	else $class="";
	
	if($obj->getId()!="") $id=" id=\"".$obj->getId()."\"";
	else $id="";
	
	if($obj->getTitle()!="") $title=" title=\"".$obj->getTitle()."\"";
	else $title="";
	
	if($obj->getLink()!="") $link=" onclick='javascript:location.href = \"".$obj->getLink()."\"';";
	else $link="";
	
    $this->Html.="<td><img src=\"".$this->PathImages.$obj->getSrcImage()."\" alt=\"\"".$class.$id.$title.$link." /></td>\n";	
    $j++;
    }	
$this->Html.="</tr>\n";
}//close while	
$this->Html.="</table>"; 
}//close private function prepareHTML()


public function close(){
	
	$this->prepareHTML();
	return $this->Html;
	
}


	
}//close class


?>