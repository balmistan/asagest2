<?php
/*l' item è la voce menù. Ogni istanza di oggetto di questa classe identifica una voce nel menù*/
class menuitem{
	private $Text="";         //voce che apparirà come opzione nel menù
	
	private $AId="";          //id associato al tag <a> relativo alla voce menù
	private $UlId="";         //id associato al tag <ul>
	private $LiId="";         //id associato al tag <li>
	private $AClass="";       //attributo class associato al tag <a>
	private $UlClass="";      //attributo class associato al tag <ul>
	private $LiClass="";      //attributo class associato al tag <li>
	
	private $Link="#";        //link al quale si viene reindirizzati cliccando su una voce del menù
	private $Index="";        //identificativo associato alla voce menù. Permette di aggiungere nuove voci al menù appendendole in cascata.
	private $AppendToIndex=""; //indica l' index della voce menù alla quale appendere in cascata questo item
	
        //i seguenti attributi vengono settati automaticamente dalla classe
        private $Integrated=false;    //se un oggetto è stato integrato come figlio di un altro 
	private $AppendedChildren=array();     // array contenente oggetti di tipo menuitem

	
	
	public function __construct($arr){
      foreach($arr as $key=>$value){
      	eval('$this->set'.$key.'("'.$value.'");');
      }
  }	
	
	public function appendChild(menuitem $objchild){		
		$this->AppendedChildren[]= $objchild;	
		//echo count($this->AppendedChildren);
		//echo "<br />";
		//echo $this->Index;
	}
	
	public function getAppendedChildrenArray(){
		
		return $this->AppendedChildren;
	}
	
 public function __call($strFunction, $arArguments){
		$strMethodMember=substr($strFunction, 3);
		if($strMethodMember=="AppendedChildren") die("menuitem::__call: error function not applicable on AppendedChildren attribute.");
		
		$strMethodType=substr($strFunction, 0, 3);
		
		switch($strMethodType){
			case "set":
				return($this->SetAccessor($strMethodMember, $arArguments[0]));
				break;
			case "get":
				return($this->GetAccessor($strMethodMember));
				break;
			default: die("menuitem::__call error: Unknown method type!");
		};
	}
	

  private function SetAccessor($strMember, $strNewValue){
  	if(property_exists($this, $strMember) && $strMember!="AppendedChildren"){
  		if(is_numeric($strNewValue)) eval('$this->'.$strMember.'='.$strNewValue.';');
		else eval('$this->'.$strMember.'="'.$strNewValue.'";');
		
  	}else die("menuitem::SetAccessor error: ".$strMember." attribute not exists in this class!");
  }



  private function GetAccessor($strMember){
  	if(property_exists($this, $strMember) && $strMember!="AppendedChildren"){
  		eval('$strRetVal = $this->'.$strMember.';');
  		return $strRetVal;
  	}else die("menuitem::GetAccessor error: ".$strMember." attribute not exists in this class!");
  }



}

?>