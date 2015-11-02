<?php
  /**
  * jquery class
  * Permette di inserire codice jquery
  * @author NetMDM <1@3bsd.net>
  * @version 5.9
  * @package libss
  */


class jquery{
  protected $page; 
  private $html="";   //contenuto pagina
  private $jquery_document_ready=false;
 
  
  public function __construct($page){
   // $page->addJs(JQUERY);
	$this->page=$page;

  }
  
  
  private function isPresentDocumentReadyBlock(){
  	if(strstr($this->html, "$(document).ready")==false) return false;
	return true;
  }
  
          
   private function addJQueryBlock(){
    
    //se manca il blocco jquery lo inserisco
    if ($this->jquery_document_ready==false){
       $this->jquery_document_ready=true;
	  
      $this->html=preg_replace("/<\/head>/","<script language=\"JavaScript\">
        $(document).ready(function(){
                               //ADD PLUGIN HERE
          });//close $(document).ready
    </script>\n</head>", $this->html);
    }
    
  } //close private function addJQueryBlock()    
      
 
 
  /** Permette di inserire codice jquery nel blocco $(document).ready se esso è presente. In caso contrario lo inserisce
  * @param string $script è il codice che si vuole inserire.
  */
  public function addJQueryPlugin($script, $JSlinks=array(), $CSSlinks=array()){  
  	$this->html=$this->page->getHTML();
	$this->jquery_document_ready=$this->isPresentDocumentReadyBlock();		
    $this->addJQueryBlock();   //inserisce il blocco $(document.ready) se non presente e i principali link js per JQUERY 
    $this->replaceCommentWithScript("ADD PLUGIN HERE",$script);   
    $this->page->setHTML($this->html);
	foreach($JSlinks as $key=>$value)  $this->page->addJs($value);
	foreach($CSSlinks as $key=>$value)  $this->page->addStyle($value);
  }
  
  protected function replaceCommentWithScript($comment,$script){   
   $this->html=preg_replace("/\/\/$comment/","$script\n//$comment",$this->html);   
  }    
 
  public function close(){
   
  }
  

  }// close class
  
  
?>