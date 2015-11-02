<?php

/*
 * Questa classe serve a gestire il carico su  allegato 8 detto anche Registro di carico e scarico e registro eventi.
 */

class load {

    private $db;
    private $product;  //istanza oggetto della classe product
    private $ArrLastGiacza;  //è un array contenente le giacenze per ciascun prodotto. Gli indici sono l' id prodotto.
    private $ArrAfterRows;
    private $ArrAfterRowsCum;
    private $ArrAfterProd;
    private $ArrAfterProdCum;

    public function __construct() {
        $this->ArrAfterRows = array();
        $this->ArrAfterRowsCum = array();
        $this->ArrAfterProd = array();
        $this->ArrAfterProdCum = array();
        $this->db = new db();
        $this->product = new product();
    }

//close __construct

    /**
     * Converte il formato data
     */
    private function convertData($data) {
        if (stripos($data, "/") > 0) {
            $arr = explode("/", $data);
            $str = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        } else if (stripos($data, "-") > 0) {
            $arr = explode("-", $data);
            $str = $arr[2] . "/" . $arr[1] . "/" . $arr[0];
        } else {
            $str = $date;
        }
        return $str;
    }

//close convertData

    /**
     * Restituisce l' elenco prodotti caricati in una determinata data da visualizzare sulla pagina del load.
     * @param type $data 
     */
    public function getLoad($date) {      //restituisce un array vuoto se alla data indicata non è presente alcun carico.
        $arr_qty = $this->db->getRows(array("all8registercum" . REFAGEA, "all8productcum" . REFAGEA), array("id_product", "carico", "all8productcum" . REFAGEA . ".id_insert", "numrif"), array(
            array("on", "all8registercum" . REFAGEA . ".id_insert", "=", "all8productcum" . REFAGEA . ".id_insert", true),
            array("where", "all8registercum" . REFAGEA . ".isload", "=", 1, true),
            array("and", "date", "=", $this->convertData($date))
        ));
        return $arr_qty;
    }

//close getLoad

    /**
     * Restituisce un array le cui righe contengono:
     * array("id_product"=>"", "name_product"=>"", "measureunity"=>"")
     */
    public function getProducts() {
        return $this->db->getRows("product", array("id_product", "name_product", "measureunity"));
    }

    /**
     * Restituisce l' ultimo contenuto non vuoto inserito nella casella num. indig. in fase di load. (in genere contiene data prog. distrib.)
     */
    public function getLastDataProg() {
        $arr = $this->db->getRow("all8registercum".REFAGEA, "numindig", array(
            array("where", "isload", "=", 1, true),
            array("and", "numindig", "!=", "")
                ), array(
            array("ORDERBY", "date"),
            array("ORDER", "DESC"),
                ), 0);
        if (isset($arr["numindig"])) {
            $retval = $arr["numindig"];
            secur::stripSlashes($retval);
        } else
            $retval = "";
        return $retval;
    }

//close getLastDataProg()
/*
    private function setLastGiacza($date, $cumulative = 0) {
        //Se il registro è vuoto, vengono prelevate da giacienze iniziali
        $arr_temp = $this->db->getRow("all8register", "id_insert");
        if (count($arr_temp) == 0) { //il registro è vuoto
            $arr_temp = $this->db->getRows("giacenzeiniziali", array("id_product", "giacza"));
        } else {
            //Giungo in questo else se il registro non è vuoto.
            //Se si tratta di registro cumulativo la giacenza è quella con data immediatamente inferiore a $date se esiste, altrimenti quella di giacenzeiniziali
            if ($cumulative) {
                //prelevo l' id con data immediatamente inferiore
                $arr_id = $this->db->getRow("all8registercum", "id_insert", array(
                    array("where", "date", "<", $date)
                        ), array(
                    array("ORDER", "DESC"),
                        ), 0);
                if (count($arr_id) == 0) {  //Se non ci sono distribuzioni che soddisfano...
                }
            } else { //caso !$cumulative
                //Se si tratta di registro non cumulativo la giacenza è quella sull' ultima riga.
            }
        }
//Setto ArrLastGiacza
        $this->ArrLastGiacza = array();
        for ($i = 0; $i < count($arr_temp); $i++) {
            if ($arr_temp[$i]["id_product"] == 0)
                continue; //Per un bug all' inizio la tabella potrebbe contenere un campo con id_product=0; In questo modo mi assicuro di ignorarlo.
            $this->ArrLastGiacza[$arr_temp[$i]["id_product"]] = $arr_temp[$i]["giacza"] * 1;      //Assegno rimuovendo gli zeri non significativi
        }
    }
*/
//close setLastGiacza

    private function myCheckdate($date) {
        $arr_date = explode("/", $date);

        if (count($arr_date) != 3)
            return false;

        return checkdate($arr_date[1], $arr_date[0], $arr_date[2]);
    }

    private function getPrecGiacze($insert_id) {
        if ($insert_id == "") {  //è un nuovo inserimento
            //é un nuovo inserimento quindi seleziono le giacenze sull' ultima riga
            $arr_giacze = $this->db->getRows("all8productcum" . REFAGEA, array("id_product", "giacza"), array(), array(
                array("ORDERBY", "id_insert"),
                array("ORDER", "DESC")
            ));
        } else {    //Aggiornamento carico precedentemente effettuato
            $arr_giacze = $this->db->getRows("all8productcum" . REFAGEA, array("id_product", "giacza"), array(
                array("where", "id_insert", "<", intval($arr['insert_id']), true)
                    ), array(
                array("ORDERBY", "id_insert"),
                array("ORDER", "DESC")
            ));
        }

        //In ogni caso se l' array ottenuto è vuoto le giacenze vengono lette da giacenze iniziali

        if (count($arr_giacze) == 0) {
            $arr_giacze = $this->db->getRows("giacenzeiniziali", array("id_product", "giacza"));
        }

        //A questo punto creo un array che ha come chiavi gli id dei prodotti e come valori le quantità:

        $arr_giacze_out = array();
        foreach ($arr_giacze as $arr_val) {
            $arr_giacze_out[$arr_val["id_product"]] = floatval($arr_val["giacza"]);
        }

        return $arr_giacze_out;
    }

// close function getPrecGiacze

    /**
     * 
     * @param type $arr Array ricevuto in post
     * Restituisce un array con le quantità da salvare nel formato id_product=>qty
     */
    private function getProductForSaving($arr) {
        $arr_out = array();
        foreach ($arr as $key => $value) {
            if (strstr($key, "load_") === FALSE)
                continue;
            $product_id = substr($key, 1 + strpos($key, "_"));
            $arr_out[$product_id] = floatval($value);
        }
        return $arr_out;
    }

    
    /**
     * Restituisce un array con le date in cui sono stati effettuati dei carichi
     * @param  $styledb
     */
    public function getDateLoads($styledb = false) {
        $res = $this->db->getRows("all8registercum" . REFAGEA, "date", array(
            array("where", "isload", "=", 1, true)
                ), array(
            array("orderby", "date"),
            array("order", "asc")
        ));
        $arr_out = array();

        if ($styledb) {
            foreach ($res as $key => $value) {
                array_push($arr_out, $value["date"]);
            }
        } else {
            foreach ($res as $key => $value) {
                array_push($arr_out, $this->convertData($value["date"]));
            }
        }
        return $arr_out;
    }

//function close
}

//class close
?>
