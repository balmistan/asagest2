<?php

abstract class DataBoundObject {

   protected $ID;
   protected $db;
   protected $strTableName;
   protected $arRelationMap;
   protected $blForDeletion;
   protected $blIsLoaded;
   protected $arModifiedRelations;
   protected $dbKeyId;
   abstract protected function DefineTableName();
   abstract protected function DefineRelationMap();
   abstract protected function DefineKeyId();

   public function __construct(db $db, $id = NULL) {
      $this->strTableName = $this->DefineTableName();
      $this->arRelationMap = $this->DefineRelationMap();
	  $this->DbKeyId = $this->DefineKeyId();
      $this->db = $db;
      $this->blIsLoaded = false;
      if (isset($id)) {
         $this->ID = $id;
      };
      $this->arModifiedRelations = array();
   }

 

   public function __call($strFunction, $arArguments){
		$strMethodMember=substr($strFunction, 3);
		if(is_array($strMethodMember)) die("DataBoundObject::__call: error function not applicable on attribute array.");
		
		$strMethodType=substr($strFunction, 0, 3);
		
		switch($strMethodType){
			
			case "set":
				return($this->SetAccessor($strMethodMember, $arArguments[0]));
				break;
			case "get":
				return($this->GetAccessor($strMethodMember));
				break;
			default: echo $strMethodType; die("DataBoundObject::__call error: Unknown method type!");
		};
	}
	

  private function SetAccessor($strMember, $strNewValue){
  	if(property_exists($this, $strMember) && $strMember!="AppendedChildren"){
  		//if(is_numeric($strNewValue)) eval('$this->'.$strMember.'='.$strNewValue.';');
		//else eval('$this->'.$strMember.'="'.$strNewValue.'";');
                eval('$this->'.$strMember.'="'.$strNewValue.'";');
		
  	}else die("DataBoundObject::SetAccessor error: ".$strMember." attribute not exists in this class!");
  }



  private function GetAccessor($strMember){
  	if(property_exists($this, $strMember) && $strMember!="AppendedChildren"){
  		eval('$strRetVal = $this->'.$strMember.';');
  		return $strRetVal;
  	}else die("DataBoundObject::GetAccessor error: ".$strMember." attribute not exists in this class!");
  }

   
  
   
   
 public function getLastId(){
			return $this->db->getMaxId($this->strTableName,$this->DbKeyId);
		}  
   
}//class close

?>