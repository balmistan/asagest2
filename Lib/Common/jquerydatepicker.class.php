<?php

/**
*jquerydatepicker class extend jquery class
*
* This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
* @author Carmelo San Giovanni <admin@barmes.org>
* @version 4.0
* @copyright Copyright (c) 2009 Carmelo San Giovanni
* @package libss
*/

class jquerydatepicker extends jquery{
private $form;	

public function __construct($page, $form=null){
	parent::__construct($page);
	$this->form=$form;
}	
	
/**
   * Metodo per l' inserimento di un campo data utilizzante il datepicker 
   * @param string $name è sia il name che l' id della textbox.
   * @param string $label è l' etichetta che apparirà accanto alla textbox
   * @param array $properties[optional] che permette di impostare ulteriori attributi della textbox come ad esempio size, ecc. 
   * @param bool $debug [optional] attiva il debug per la textbox inserita
  */                
  
  public function addDateField($name,$label,$properties=array(),$datepicker_options=array(), $newrow=true,$colspan=array("","",""), $jquery_selector=".datepicker"){
    
     $this->addJQueryDatePicker($datepicker_options, $jquery_selector);  
                                            
     if($this->form)$this->form->addTextField_2($name,$label,$properties,$newrow,$colspan); 
     else die("addDateField error: jquerydatepicher necessita l' oggetto form come parametro del costruttore per utilizzare questo metodo");   

  }//public function addDateField close
  
 
  private function addJQueryDatePicker($datepicker_options=array(), $jquery_selector=".datepicker"){
  		$jslinks=array(
  		JQUERY,
  		JQUERY_UI,
  		LIBSSDIR.LIBNAME."/Common/js/datepicker/ui.datepicker-it.js",
  		LIBSSDIR.LIBNAME."/Common/js/datepicker/mydatepicker.jquery.js"
  		);
		
	    $csslink=array(UI_STYLE);
  
            $dp_opt="";
            if(count($datepicker_options)) {
                 $dp_opt.= "{";                                    //converto da array php ad array js
                foreach($datepicker_options as $key=>$value) $dp_opt.= $key.": ".$value.",";    
                 $dp_opt=  substr($dp_opt, 0, strlen($dp_opt)-1);           //rimuovo la virgola finale
                  $dp_opt.= "}";    //chiudo la parentesi }
            }
	$this->addJQueryPlugin(" 
            $(\"".$jquery_selector."\").live(\"mouseover\", function(){
                $(this).mydatepicker(".$dp_opt."); 
                    var default_date=$(this).attr(\"default_date\");
                    if(default_date != undefined && default_date != \"\")
                        $(this).datepicker( \"option\", \"defaultDate\",  $(this).attr(\"default_date\") ); 
              });  
              
         $(\"".$jquery_selector."\").live(\"mousedown\", function(){
             if($(this).is(\":focus\")) {
                  $(this).blur().focus();
                }
              });
       ", $jslinks, $csslink); 

  }

}//class close


?>