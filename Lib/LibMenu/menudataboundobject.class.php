<?php
class menudataboundobject{
    
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