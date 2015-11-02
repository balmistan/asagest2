<?php

/**
 * jquery class
 * Permette di inserire codice jquery
 * @author NetMDM <1@3bsd.net>
 * @version 5.9
 * @package libss
 */
class myjquery {

    protected $page;
    private $html = "";   //contenuto pagina
    private $jquery_document_ready = false;

    public function __construct(page $page) {
        // $page->addJs(JQUERY);
        $this->page = $page;
    }

    private function isPresentDocumentReadyBlock() {
        if (strstr($this->html, "$(document).ready") == false)
            return false;
        return true;
    }

    private function addJQueryBlock() {

        //se manca il blocco jquery lo inserisco
        if ($this->jquery_document_ready == false) {
            $this->jquery_document_ready = true;

            $this->html = preg_replace("/<\/head>/", "<script language=\"JavaScript\">
        $(document).ready(function(){
                               //ADD PLUGIN HERE
          });//close $(document).ready
    </script>\n</head>", $this->html);
        }
    }

//close private function addJQueryBlock()    

    /** Permette di inserire codice jquery nel blocco $(document).ready se esso è presente. In caso contrario lo inserisce
     * @param string $script è il codice che si vuole inserire.
     */
    public function addJQueryPlugin($script, $JSlinks = array(), $CSSlinks = array()) {

        $this->html = $this->page->getHTML();

        $this->jquery_document_ready = $this->isPresentDocumentReadyBlock();
        $this->addJQueryBlock();   //inserisce il blocco $(document.ready) se non presente e i principali link js per JQUERY 
        $this->replaceCommentWithScript("ADD PLUGIN HERE", $script);
        $this->page->setHTML($this->html);

        foreach ($JSlinks as $key => $value)
            $this->page->addJS($value);
        foreach ($CSSlinks as $key => $value)
            $this->page->addStyle($value);
    }

    protected function replaceCommentWithScript($comment, $script) {
        $this->html = preg_replace("/\/\/$comment/", "$script\n//$comment", $this->html);
    }

    /**
     * Metodo per agganciare il plugin ad una textbox
     * @param string $id contiene l' id del campo di autocompletamento.
     * @param string $ajaxpage è il link alla pagina di interrogazione Ajax lato server
     */
    public function addAutocompletePlugin($id, $ajaxpage, $autocomplete_prop = array()) {
        
        $autocomplete_block = "
       var arr_out=Array();    //creo  l' array per le opzioni da mostrare
       var id_sel='';          //è l' id che il server torna in risposta insieme all' elenco opzioni.
       var arr_keys=Array();   //conterrà i nomi delle colonne restituite da Mysql.
            
    
         
     $('#" . $id . "').autocomplete({\n";

        if (count($autocomplete_prop) != 0)
            foreach ($autocomplete_prop as $key => $value)
                $autocomplete_block.="\t\t" . $key . ":" . $value . ",\n";
        
        $autocomplete_block.="
            
          source: function(request, response) {
      
                 ins_val=request.term.toLowerCase();  //La stringa digitata nella textbox
                 if(ins_val!=''){                            //se è stata scritta qualcosa, la invio al Server.
                    var datastr='id=".$id."&value='+$('#".$id."').val();
                   //alert(datastr)
                        $.ajax({
                            url: '" . $ajaxpage . "',
                            type: 'post',
                            data: datastr,     
                            success: function(data) {  
                                         //alert(data);
                                         result=JSON.parse(data);                            
                                         $(\"#debug\").val(data);
                                         if(result.length!=0){                                         
                                           arr_keys=result.pop(); //estraggo dall' array l' ultimo inserimento                                                                    //che contiene i nomi delle colonne mysql.   
                                           var  id_sel_arr=Array();                         
                                           id_sel_arr=result.pop();//array che contiene l' id textbox sulla 
                                           //quale è stata digitata la stringa di input e alla quale associare le opzioni.                                     
                                           id_sel=id_sel_arr['responseid'];
                                           arr_out=Array();    //reinizializzo l' array per le opzioni
                                            
                                           ////////////////////////////////////////////////////
                                          //               Add code optionWiever " . $id . "     //
                                          ////////////////////////////////////////////////////
                                                           
                                         // PREPARO L'ARRAY CON LE OPZIONI DA MANDARE IN OUTPUT
                                                        
                                         //ADD OPTIONVIEWER HERE " . $id . '                               
                                         
                                          //Blocco default
                                         if(key_choose==undefined){   // blocco default per visualizza opzioni
                                           var key_choose=arr_keys[0];       
                                           //creo un array con i risultati della prima colonna 
                                           for(var j=0; j<result.length; j++) arr_out.push(result[j][key_choose]); 
                                           } //chiusura blocco  if(key_choose==undefined)
                                         //Fine blocco default
                                         
                                         response(arr_out);     //visualizzo le opzioni
                                           }//close if(result.length!=0)
    
                                     },  //close success
                                   error:function(xhr,err){
              
                                        alert("Server "+err+": "+xhr.status);
                                        }
                            
                                 }); //close $.ajax  
              
                            }//close  if(ins_val!=\'\')
          
                         },// close source:
           //define select handler
            select: function(e, ui) {
                          //recupero l\' id dell\' opzione selezionata
                
                         opt_selected_id=$.inArray(ui.item.value, arr_out); 
                         //alert(opt_selected_id);
                         
                          
                                          ////////////////////////////////////////////////////
                                          //               Add code onselect ' . $id . "        //
                                          ////////////////////////////////////////////////////
                                          
                                      //ADD CODE ONSELECT " . $id . "                                            
            }

            });  //chiusura codice per autocomplete     
   ";

        $this->addJQueryPlugin($autocomplete_block, array(JQUERY, JQUERY_UI), array(UI_STYLE));
    }

// close method

    /**
     * Metodo per la formattazione delle stringhe visualizzate tra le opzioni. 
     * Normalmente le opzioni mostrate sono quelle relative alla prima colonna mysql ottenuta con la query.
     * Questo metodo permette di modificare il suddetto comportamento.
     * @param string $id_textbox è l' id della textbox su cui attivare la regola.
     * @param array $format_str si tratta di un array di stringhe che permette di impostare il formato di visualizzazione 
     * delle opzioni. I valori in array corrispondenti ad indice dispari devono essere sempre  nomi di colonne mysql restituite dalla query.
     * o in alternativa degli indici. "0" è la prima colonna, "1" è la seconda e così via.
     * @category Metodi per settare le opzioni avanzate di autocomplete.
     */
    public function AutocompleteOptionViewer($id_textbox, $format_str = array()) {

        //preparo la stringa con la formattazione opzione

        $opzstr = "";

        for ($i = 0; $i < count($format_str); $i++) {

            if ($i % 2) { //se indice dispari si tratta di una colonna mysql o suo indice
                if (is_numeric($format_str[$i]))
                    $opzstr.="result[j][arr_keys[" . $format_str[$i] . "]]+";
                else
                    $opzstr.="result[j]['" . $format_str[$i] . "']+";
            }//close if($i%2)
            else if ($format_str[$i] != "")
                $opzstr.= "'" . $format_str[$i] . "'+";
        }
        // rimuovo il + finale

        $opzstr = substr($opzstr, 0, (strlen($opzstr) - 1));
        $optionviewercode = '                                                 
             if(id_sel=="' . $id_textbox . '") {
                var key_choose="";            //definisco key_choose in modo da non eseguire il blocco di default per questa textbox          
                for(var j=0; j<result.length; j++) arr_out.push(' . $opzstr . ');    
             }
             ';

        //aggiungo il codice al posto di //ADD OPTIONVIEWER HERE

        $html=$this->page->getHTML();
        $comment = "ADD OPTIONVIEWER HERE " . $id_textbox;
        $html = preg_replace("/\/\/$comment/", "$optionviewercode\n//$comment", $html);
        $this->page->setHTML($html);
    }

//public function AutocompleteOptionViewer close

    /**
     *  Metodo per la formattazione della stringa che verrà inserita nella textbox. 
     * Normalmente selezionata un' opzione questa verrà inserita nella textbox così com' è. 
     * Utilizzando questo metodo è possibile modificare tale comportamento.</b>
     * @param string $id_textbox è l' id della textbox su cui avviene la selezione. Il metodo non deve essere applicato
     * due volte sulla stessa textbox. 
     * @param array $id_out_textbox è un array contenente gli id delle textbox che dovranno mostrare l' opzione selezionata.
     * @param array $format_str è un array bidimensionale in cui ciascuna riga contiene la formattazione per l' opzione da 
     * visualizzare in ciascuna textbox con id indicato in $id_out_textbox.
     * Per specificare la formattazione, essendo ciascuna riga $format_str un array, andrà usata la seguente convenzione:
     * Agli indici dispari andranno  sempre  associati nomi di colonne mysql restituite dalla query mentre agli indici pari delle stringhe anche vuote.
     * @category Metodi per settare le opzioni avanzate di autocomplete.
     */
    public function AutocompleteOptionInserter($id_textbox, $id_out_textbox = array(), $format_arr_str = array(array())) {
        //controlli preliminari 
        if ((!is_array($id_out_textbox)) || (!is_array($format_arr_str)))
            die("FORM::AutocompleteOptionInserter need  filled array");
        if (count($id_out_textbox) == 0 || count($format_arr_str) == 0 || count($id_out_textbox) != count($format_arr_str))
            die("FORM::AutocompleteOptionInserter incompatible array");
        //apro il blocco if
        $codeonselect = '
           if(id_sel=="' . $id_textbox . '"){       
              ';
        //preparo il codice da inserire nel blocco if
        $str2 = "";
        foreach ($id_out_textbox as $kout => $vout) {  //per ogni textbox specificata preparo la stringa di formattazione
            $str = "";
            for ($i = 0; $i < count($format_arr_str[$kout]); $i++) {
                if ($i % 2) { //se indice dispari si tratta di una colonna mysql o suo indice
                    $str.="result[opt_selected_id]['" . $format_arr_str[$kout][$i] . "']+";
                }//close if($i%2)
                else if ($format_arr_str[$kout][$i] != "")
                    $str.= "'" . $format_arr_str[$kout][$i] . "'+";
            }// close for($i=0; $i<count($format_str[$kout]); $i++)
            // rimuovo il + finale   
            $str = substr($str, 0, (strlen($str) - 1));
            $str2.= '$("#' . $id_out_textbox[$kout] . '").val(' . $str . '); ';
        }    // chiusura foreach  

        $codeonselect.=$str2 . '
                return false;
           }   
    ';

        $html=$this->page->getHTML();
        $comment = "ADD CODE ONSELECT " . $id_textbox;
        $html = preg_replace("/\/\/$comment/", "$codeonselect\n//$comment", $html);
        $this->page->setHTML($html);
    }

//public function AutocompleteOptionInserter close

    public function close() {
        
    }

}

// close class
?>
