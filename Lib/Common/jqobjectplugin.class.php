<?php
  /**
  * jqueryobjectplugin class
  * Permette di inserire codice jquery
  * @author NetMDM <1@3bsd.net>
  * @version 5.9
  * @package libss
  */

abstract class jqobjectplugin{

private $jquery_document_ready=FALSE;                      // Specifica se il blocco document.ready di jquery è già stato inserito 

private $pageScript="";                                    //contenitore per lo script js da inserire nella pagina

private $page;  

private $form;                                


public function __construct($page, $form=null){
    $this->page=$page;
    $this->form=$form;
    $this->addJQueryBlock();
  }// close __construct




 private function addJQueryBlock(){
    
    //se manca il blocco jquery lo inserisco
    if ($this->jquery_document_ready==false){
      $this->jquery_document_ready=true;
  
      $this->page->addJS(JQUERY);
      
      $this->pageScript.=" 
<script language=\"JavaScript\">    
    $(document).ready(function (){
           //ADD PLUGIN HERE
     });//close $(document).ready      
</script>\n";
    }
    
  } //close private function addJQueryBlock()    
      
   
  protected function addJqScript($script){
  	$this->replaceCommentWithScript("ADD PLUGIN HERE",$script);
  }
   
      
      
  private function replaceCommentWithScript($comment,$script){
    
   $this->pageScript=preg_replace("/\/\/$comment/","$script\n//$comment",$this->pageScript);
    
  }    
  
  
  
 public function close(){
         $this->page->addJSCode($this->pageScript); 
  }
 
 
 
}// close abstract class jqueryobjectplugin



?>

