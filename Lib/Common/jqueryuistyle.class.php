<?php

/* Class jqueryuistyle
 * @version 0.7
 * @copyright Copyright (c) 2011 Carmelo San Giovanni
 * @package libss
 */

class jqueryuistyle extends jquery{


public function __construct($page, $form=null){
	parent::__construct($page);	
}	


public function useJqueryButton($selector){
    $jslinks=array(
  		JQUERY,
  		JQUERY_UI
        );
    $csslink=array(UI_STYLE);
   
    $this->addJQueryPlugin(" 
             $(\"".$selector."\").button();
      
       ", $jslinks, $csslink); 
}


}//class close

?>