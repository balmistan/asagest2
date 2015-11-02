<?php

/**
 * jquerymultielement class extend jquery class
 *
 * This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
 * @author Carmelo San Giovanni <admin@barmes.org>
 * @version 4.0
 * @copyright Copyright (c) 2009 Carmelo San Giovanni
 * @package libss
 */
class jquerymultielement extends jquery {

    private $form;

    public function __construct($page, $form = null) {
        parent::__construct($page);
        $this->form = $form;
    }

    /**
     * Aggiunta di elementi multipli attraverso id.
     * Visualizza su una riga della tabella form il contenuto html passato come argomento più pulsanti di aggiunta e rimozione elemento
     * @param string $element_id id dell' elemento(textarea, textbox o selectbox che si vuole rendere multiplo)
     * @param array $values è un array di array in cui il primo elemento è l' array contenente i value di default. Gli altri elementi non sono ancora utilizzati
     * @param boolean $debug se è true colora gli elementi in modo da rendersi conto in che posizione vengono aggiunti
     * */
    public function addMultielemById($element_id, $values = array(), $debug = false) {

        if ((!is_array($values)) || (!is_array($values[0])))
            die("addMultielemById: $values deve essere un array di array");
     
            for ($i = 0; $i < count($values); $i++)                 
                foreach ($values[$i] as $key => $val) {
                    $values[$i][$key] = addslashes($val);
                }
       
	 
        $jslinks = array(
            JQUERY,
            LIBSSDIR . LIBNAME . "/Common/js/jquery.multielem.js"
        );

        //$this->page->addJS(JQUERY);"
        //$this->page->addJS(LIBSSDIR.LIBNAME."/Common/js/jquery.multielem.js"); 

        $str_json = json_encode($values);
	

        $this->addJQueryPlugin(" 
           var widget1 = new MyWidget('" . $element_id . "'); 
           widget1.setvalue(null,'" . $str_json . "')     
     ", $jslinks);

        if ($debug)
            $this->addJQueryPlugin("widget1.useDebug()");


        $dim_arr = 0;

        if (count($values))
            $dim_arr = ((count($values, COUNT_RECURSIVE) - count($values)) / count($values));

        //aggiungo le varie righe

        for ($i = 1; $i < $dim_arr; $i++)
            $this->addJQueryPlugin(" 
         //alert('" . count($values, COUNT_RECURSIVE) . "')
         widget1.addRowDown('col2_" . ($i - 1) . "','" . json_encode($values) . "')
      ");
        ///////////////////////////////////////////////////////    Utilizza i pulsanti
        /*   $this->form->addIntoTableForm('<tr id="row_0">'.
          //POS1: in questa posizione verrà reinserito mediante js l' elemento rimosso
          '<td id="col1_0"><br /><input type="button" class="btnup" value="&#9651" title="Aggiungi sopra" /></td>'.
          '<td id="col2_0"><br /><input type="button" class="btndown" value="&#9661" title="Aggiungi sotto" /></td>'.
          '<td class="rem" id="remb_0"><br /><input type="button" class="btnremove" value="X" title="Rimuovi riga" /></td>'.
          '</tr>');
         */
        ///////////////////////////////////////////////////////    Utilizza le icone

        $this->form->addIntoTableForm('<tr id="row_0">' .
                //POS1: in questa posizione verrà reinserito mediante js l' elemento rimosso 
                '<td id="col1_0"><br /><img src="../Lib/Common/icons/freccia_up.png" class="btnup" alt="&#9651" title="Aggiungi sopra" /></td>' .
                '<td id="col2_0"><br /><img src="../Lib/Common/icons/freccia_dw.png" class="btndown" alt="&#9661" title="Aggiungi sotto" /></td>' .
                '<td class="rem" id="remb_0"><br /><img src="../Lib/Common/icons/button_cancel.png" class="btnremove" alt="X" title="Rimuovi riga" /></td>' .
                '</tr>');
    }

// close function addMultielemById()

    /**
     * Aggiunta di elementi multipli attraverso id. Questo metodo minimizza il numero di pulsanti utilizzati.
     * Visualizza su una riga della tabella form il contenuto html passato come argomento più pulsanti di aggiunta e rimozione elemento
     * @param string $element_id id dell' elemento(textarea, textbox o selectbox che si vuole rendere multiplo)
     * @param array $values è un array di array in cui il primo elemento è l' array contenente i value di default. Gli altri elementi non sono ancora utilizzati
     * @param boolean $debug se è true colora gli elementi in modo da rendersi conto in che posizione vengono aggiunti
     * */
    public function addSimpleMultielemById($element_id, $values = array(), $debug = false) {

        if ((!is_array($values)) || (!is_array($values[0])))
            die("addMultielemById: $values deve essere un array di array");
        page::addJS(JQUERY);
        page::addJS(LIBSSDIR . LIBNAME . "/js/jquery.simple_multielem.js");


        $this->addJQueryPlugin("
  
      var " . $element_id . "mysw=new MySimpleWidget('" . $element_id . "','" . json_encode($values) . "') 
 
         ");
    }

// close function addSimpleMultielemById()

    public function close() {
        parent::close();
    }

}

//class close
?>
