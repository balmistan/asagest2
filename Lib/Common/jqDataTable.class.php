<?php

class jqDataTable extends jquery{

	
	public function __construct($page){
	
	    parent::__construct($page);
     }	
	
	public function singleRowSelectable($table_id, $options=array()){
		$this->rowSelectable($table_id, $options);	
                //$this->page->addJs(JQPLUGINS."/datatable/Scroller.js");
                $this->page->addJs(JQPLUGINS."/datatable/jqDataTableSingleRowSelect.js");
	}
	
	public function multiRowSelectable($table_id, $options=array()){
		$this->rowSelectable($table_id, $options);	
                //$this->page->addJs(JQPLUGINS."/datatable/Scroller.js");
                $this->page->addJs(JQPLUGINS."/datatable/jqDataTableMultiRowSelect.js");
	}
	
	
	
	
	private function rowSelectable($table_id, $datatable_options){
		$jslinks=array(
		//JQUERY,
		//JQTABLES."/media/js/jquery.dataTables.min.js"
		);
		
		$csslinks=array(
		UI_STYLE,
		//JQTABLES."/media/css/demo_table_jui.css"
		);
                
                $dt_opt="";
                if(count($datatable_options)) {
                 $dt_opt.= "{";                                    //converto da array php ad array js
                 foreach($datatable_options as $key=>$value) $dt_opt.= $key.": ".$value.",";    
                 $dt_opt=  substr($dt_opt, 0, strlen($dt_opt)-1);           //rimuovo la virgola finale
                 $dt_opt.= "}";    //chiudo la parentesi }
               }// close if
            
		$this->addJQueryPlugin("
		$('#".$table_id."').jqDataTable(".$dt_opt.");
		", $jslinks, $csslinks);	
	}
        
    
	
}//close 




?>