<?php
/**
* jquery object class
*
* This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
* @author Carmelo San Giovanni <admin@barmes.org>
* @version 6.0
* @copyright Copyright (c) 2011 Carmelo San Giovanni
* @package libss
*/
class jqueryobject {
	
	
	/**
	 * Costrutture di class jqueryobject
	 */
	public function __construct(){
	
	if(!page::$is_present_doc_ready){
		 page::$is_present_doc_ready=true;
		 page::addJS(JQUERY);
		 page::addJQueryCode('//ADD PLUGIN HERE');
	}
  }
  
	
	/**
	 * Restituisce un array contenente i nomi dei plugin inseriti
	 * @return array
	 */
	
	//public static $initialized=false;
	 
	public function getPlugins(){
		
		return page::plugins;
	}
	
	
	/** Permette di inserire codice jquery nel blocco $(document).ready 
  * @param string $script è il codice che si vuole aggiungere nel blocco $(document).ready 
  */
  public function addJQueryPlugin($script){
      
    
    $this->replaceCommentWithScript("ADD PLUGIN HERE",$script);
   
  }
	
	

 
 
 /** Permette di sostituire un commento con uno script
  * @param string $comment è il commento che si desidera rimpiazzare con lo script $script
  * @param string $script è lo script 
  */ 
  protected function replaceCommentWithScript($comment,$script){
    
   $this->pageScript=preg_replace("/\/\/$comment/","$script\n//$comment",page::$pageScript);
    
  }   // private function replaceCommentWithScript close 
  
  /**
   * Aggiunge il nome plugin all' elenco delle plugin inserite nella pagina
   * @param string $pluginame è il nome della plugin
   */
  public function addPlugin($pluginame){
		
        	page::$plugins[$pluginame]=true;
     	
	}
	
 /**
   * Verifica se una plugin con lo stesso nome è già stata inserit nella pagina
   * @param string $pluginame è il nome della plugin
  *  @return boolean true se la plugin è presente. In caso contrario torna false
   */	
public function isPresentPlugin($pluginame){
	    if(array_key_exists($pluginame,page::$plugins)) return page::$plugins[$pluginame];
		return false;
    	
  	}


 
  
  public function close(){

      return;
  
  }
  

}//class close



?>