<?php

class product {

    private $db;
    private $tablename;
    private $tablekey;
    private $assoc_form_db;
    private $assoc_db_form;
    private $arr_modifiable = array();
    private $usebancoalim;

    public function __construct($bancoalim = false) {
        $this->usebancoalim = $bancoalim;
        $this->db = new db();
        $session = new session();
        if (!$bancoalim) {
            $this->tablename = "product";        //nome tabella
        } else {
            $this->tablename = "productbancoalim";
        }
        $this->tablekey = "id_product";     //chiave primaria
        $this->array_init();
    }

    private function array_init() {     //form ->db
        if ($this->usebancoalim)
            $this->assoc_form_db = array(
                "product_id" => "id_product",
                "product" => "name_product",
                "qtyforunity" => "quantity_for_unity",
                "qtyforunity2" => "quantity_for_unity_2",
                "qtyforunity3" => "quantity_for_unity_3",
                "measureunity" => "measureunity",
                "imagelink" => "imagelink",
                "modifiable" => "modifiable",
                "rr" => "showitem",
                "position_in_a8" => "position_in_a8"
            );
        else
            $this->assoc_form_db = array(
                "product_id" => "id_product",
                "product" => "name_product",
                "qtyforunity" => "quantity_for_unity",
                "qtyforunity2" => "quantity_for_unity_2",
                "qtyforunity3" => "quantity_for_unity_3",
                "measureunity" => "measureunity",
                "imagelink" => "imagelink",
                "modifiable" => "modifiable",
                "rr" => "showitem",
                "rr2" => "show_in_reg",
                "position_in_a8" => "position_in_a8"
            );
        $this->assoc_db_form = array_flip($this->assoc_form_db);
    }

    public function saveFromPost($arr_post) {
        /*
          echo "<br /><br /><br />";
          print_r($arr_post);
          echo "<br /><br /><br />";
         */
        secur::addSlashes($arr_post);

        unset($arr_post['Salva']);

        //cancello tutte le righe il cui id_product non è presente nei dati del post

        $arr_ids = $this->getArrayIds();

        foreach ($arr_ids as $value) {
            if (!isset($arr_post['product_id']) || !in_array($value, $arr_post['product_id']))
                $this->delProduct($value);
        }

        $newitem = false;
        //scorro gli elementi dell' array ricevuto in POST ed eseguo l' inserimento o l' update
        if (isset($arr_post['product_id'])) {
            for ($i = 0; $i < count($arr_post['product_id']); $i++) {
                if ($arr_post['product_id'][$i] == "") //se non ha l' ID si tratta di un nuovo inserimento
                    $newitem = true;
                //preparo un array con i valori.
                $arrkv = array();

                foreach ($arr_post as $key => $arr_values) {
                    $arrkv[$this->assoc_form_db[$key]] = $arr_values[$i];
                }


                $idproduct = $arrkv[$this->assoc_form_db['product_id']];
                unset($arrkv[$this->assoc_form_db['product_id']]);

                if ($newitem) {
                    $this->db->insert($this->tablename, $arrkv);
                } else {
                    $this->db->update($this->tablename, $arrkv, array(
                        array("where", $this->assoc_form_db['product_id'], "=", $idproduct, true)
                    ));
                }
            }//close for
        }// close if(isset($arr_post['product_id']))
    }

    public function getFromDb() {
        $arr = $this->db->getRows($this->tablename, "*", array(), array(
            array("orderby", "name_product", "asc")
        ));

        secur::stripSlashes($arr);

        $arr_out = array();

        for ($i = 0; $i < count($arr); $i++) {

            foreach ($arr[$i] as $key => $value) {
                $arr_out[$this->assoc_db_form[$key]][] = $value;
            }//close foreach
        }//close for
        if (isset($arr_out['qtyforunity'])) { //se la query ha restituito risultati...
            foreach ($arr_out['qtyforunity'] as $key => $value) {
                $arr_out['qtyforunity'][$key] = $value * 1; //rimuove gli zeri non significativi.
            }
            foreach ($arr_out['qtyforunity2'] as $key => $value) {
                $arr_out['qtyforunity2'][$key] = $value * 1; //rimuove gli zeri non significativi.
            }
            foreach ($arr_out['qtyforunity3'] as $key => $value) {
                $arr_out['qtyforunity3'][$key] = $value * 1; //rimuove gli zeri non significativi.
            }
        }

        return $arr_out;
    }

//close function

    public function getDisplayProduct($allegato_num = 0) {
        if($this->usebancoalim)
        $arr_val = array(
            $this->assoc_form_db['product_id'],
            $this->assoc_form_db['imagelink'],
            $this->assoc_form_db['product'],
            $this->assoc_form_db['qtyforunity'],
            $this->assoc_form_db['qtyforunity2'],
            $this->assoc_form_db['qtyforunity3'],
            $this->assoc_form_db['measureunity'],
            $this->assoc_form_db['position_in_a8'],
            $this->assoc_form_db['rr']
        );
    else {
        $arr_val = array(
            $this->assoc_form_db['product_id'],
            $this->assoc_form_db['imagelink'],
            $this->assoc_form_db['product'],
            $this->assoc_form_db['qtyforunity'],
            $this->assoc_form_db['qtyforunity2'],
            $this->assoc_form_db['qtyforunity3'],
            $this->assoc_form_db['measureunity'],
            $this->assoc_form_db['position_in_a8'],
            $this->assoc_form_db['rr'],
            $this->assoc_form_db['rr2']
        );
    }

        //setta l' ordine di visualizzazione in base all' allegato. 0: non ordina, 8: ordine di visualizz. per allegato 8, 9: ordine di vis. per allegato 9. 

        switch (intval($allegato_num)) {
            case 8 :
                $order = array("orderby", "position_in_a8", "asc");
                $arr = $this->db->getRows($this->tablename, $arr_val, array(
            array("where", "show_in_reg", "=", "on")
        ), array($order));
                break;
            case 9 :
            default:
                $order = array("orderby", "name_product", "asc");
                $arr = $this->db->getRows($this->tablename, $arr_val, array(), array($order));
                break;
        }



        //converto l' array nel formato visualizzabile da form
        $arr_out = array();
        for ($i = 0; $i < count($arr); $i++) {
            $arr_out[$i] = array();
            foreach ($arr[$i] as $key => $value) {
                if ($key == $this->assoc_form_db["qtyforunity"] || $key == $this->assoc_form_db["qtyforunity2"] || $key == $this->assoc_form_db["qtyforunity3"])
                    $value *= 1; //serve a rimuovere gli zeri non significativi.          
                $arr_out[$i][$this->assoc_db_form[$key]] = $value;
            }//close foreach
        }//close for

        secur::stripSlashes($arr_out);
        return $arr_out;
    }

    public function getArrayIds($allegato_num = 0) {


        //setta l' ordine di visualizzazione in base all' allegato. 0: non ordina, 8: ordine di visualizz. per allegato 8, 9: ordine di vis. per allegato 9. 

        switch (intval($allegato_num)) {
            case 8 :
                $order = array("orderby", $this->assoc_form_db['position_in_a8']);
                break;
            case 9 :
            default:
                $order = array("orderby", $this->assoc_form_db['product']);
                break;
        }

        $arr_ids = $this->db->getRows($this->tablename, $this->assoc_form_db['product_id'], array(), array($order));



        $arr_out = array();
        foreach ($arr_ids as $value) {
            $arr_out[] = $value[$this->assoc_form_db['product_id']];
        }
        return $arr_out;
    }

    private function delProduct($id_product) {
        $this->db->delete($this->tablename, array(
            array("where", $this->assoc_form_db['product_id'], "=", $id_product, true),
            array("AND", $this->assoc_form_db['modifiable'], "=", 1)  //mi assicuro che sia modificabile
        ));
    }

    /**
     * Restituisce un array contenente i link alle immagini che iniziano con $prefix
     * @param type $prefix 
     */
    public function getProductImage($prefix = "") {

        $arr_res = $this->db->getRows($this->tablename, $this->assoc_form_db['imagelink'], array(
            array("where", $this->assoc_form_db['imagelink'], "LIKE", $prefix . "%")
        ));

        $arrout = array();

        for ($i = 0; $i < count($arr_res); $i++) {
            $arrout[] = $arr_res[$i][$this->assoc_form_db['imagelink']];
        }

        return $arrout;
    }

    /**
     * Restituisce un array contenente i valori di product_id modificabili.
     * Modifiable 0 indica prodotto non modificabile
     */
    public function getModifiable() {
        $temp = $this->db->getRows($this->tablename, array($this->tablekey), array(
            array('where', $this->assoc_form_db['modifiable'], "=", 1)
        ));
        foreach ($temp as $value) {
            $this->arr_modifiable[] = $value[$this->tablekey];
        }
        return $this->arr_modifiable;
    }

    public function setNotModifiable($product_id) {
        $this->db->update($this->tablename, array($this->assoc_form_db['modifiable'] => 0), array(
            array("where", $this->assoc_form_db['product_id'], "=", $product_id, true)
        ));
    }

    /**
     * Questo metodo torna un array con i nomi dei prodotti.
     * Dovrà tornarli nell' ordine desiderato per l' allegato 8
     */
    public function getProductNamesAndId() {
        if($this->usebancoalim)
        $arr_result = $this->db->getRows($this->tablename, array($this->assoc_form_db['product_id'], $this->assoc_form_db['product']), array(), array(
            array("orderby", 'position_in_a8')   //default ordinamento per allegato 8      
        ));
        else $arr_result = $this->db->getRows($this->tablename, array($this->assoc_form_db['product_id'], $this->assoc_form_db['product']), array(
            array("where", $this->assoc_form_db['rr2'], "=", "on")
        ), array(
            array("orderby", 'position_in_a8')   //default ordinamento per allegato 8      
        ));

        $arr_out = array();
        foreach ($arr_result as $arr_values) {
            $idproduct = $arr_values[$this->assoc_form_db['product_id']];
            $nameproduct = $arr_values[$this->assoc_form_db['product']];
            $arr_out[$idproduct] = $nameproduct;
        }
        
        return $arr_out;
    }

    /**
     * Questo metodo torna un array con le unità di misura dei prodotti. Le chiavi saranno gli id dei prodotti
     * Dovrà tornarli nell' ordine desiderato per l' allegato 8
     */
    public function getProductUMis() {
        $arr_result = $this->db->getRows($this->tablename, array($this->assoc_form_db['product_id'], $this->assoc_form_db['measureunity']));
        $arr_out = array();
        foreach ($arr_result as $arr_values) {
            $idproduct = $arr_values[$this->assoc_form_db['product_id']];
            $umisproduct = $arr_values[$this->assoc_form_db['measureunity']];
            $arr_out[$idproduct] = $umisproduct;
        }
        return $arr_out;
    }

    /**
     *
     * @param type $arr Le chiavi indica la posizione mentre i valori gli id prodotto.
     */
    public function sortForAllegato8($arr) {
        //$logger=new logger("product.class.log", 1);
        //$logger->rawLog($arr);
        //$this->db->update("product", array("position_in_a8"=>'0'));
        foreach ($arr as $arrval) {
            $this->db->update("product", array("position_in_a8" => $arrval["pos"]), array(
                array("where", "id_product", "=", $arrval["id"], true)
            ));
        }
    }

}

//class close
?>