<?php

/* Questa classe serve a gestire l' allegato 8 detto anche Registro di carico e scarico.
 * La gestione è abbastanza complessa in quanto ciascuna pagina del registro è composta da più
 * tabelle. Questo perchè ciascuna pagina può contenere al massimo 4 nominativi di prodotto.
 */

class allegatin {

    private $db;
    private $product;  //istanza oggetto della classe product
    private $ArrProductNames;   //array contenente l' elenco dei nomi dei prodotti e degli id prodotto.
    private $NumTables;  //numero di tabelle per ciascuna pagina del registro. Ogni tabella può contenere al max 4 tipologie di prodotto.
    private $NumPages;   //numero di pagine attualmente riempite del registro.
    private $ArrLastGiacza;  //è un array contenente le giacenze per ciascun prodotto. Gli indici sono l' id prodotto.
    private $Session;
    private $Table1;
    private $Table2;
    private $TabelleCumulative;
    private $NextRow;
    private $RegCum;
    private $ProdCum;
    private $Logger;

    public function __construct($tabelle_cumulative = false) {
        $this->Logger = new logger("../elog/allegatin.class.log", 0);
        $this->RegCum = "all8registercum" . REFAGEA;
        $this->ProdCum = "all8productcum" . REFAGEA;

        $this->TabelleCumulative = $tabelle_cumulative;
        if ($tabelle_cumulative) {
            $this->Table1 = "all8registercum" . REFAGEA;
            $this->Table2 = "all8productcum" . REFAGEA;
        } else {
            $this->Table1 = "all8register_allegatinclass" . REFAGEA;
            $this->Table2 = "all8product_allegatinclass" . REFAGEA;
        }
        $this->db = new db();
        $this->Session = new session();
        $this->product = new product();
        $this->setArrProductNames();
        $this->setNumTables();            //è importante l' ordine in cui avvengono le varie inizializzazioni.
        $this->setPages();               //setta NumPages e LastPage. Da chiamare in fase di inizializzazione e anche dopo aver aggiunto una nuova riga alla tabella 1 db        
    }

    /**
     * setta NumPages e LastPage 
     */
    private function setPages() {

        //Se la tabella è vuota ho una sola pagina:
        //       $arr_a = $this->db->getRow($this->Table1, "id_insert");
        //       if (!count($arr_a)) {
        //           $this->NumPages = $start_num_page;
        //       } else {
//Ottengo il numero di righe complessivamente presenti nella tabella
        $arr_res = $this->db->getRows($this->Table1, "numrif");                 //una colonna qualsiasi
        $numrows = count($arr_res);

//ottengo il numero di pagine:
        $quoz_int = intval($numrows / 25);
        $resto = $numrows % 25;

        $this->NumPages = $quoz_int;  //----

        if ($resto != 0 || $this->NumPages == 0)
            $this->NumPages++;
        //     }
//Setto LastPage che normalmente coincide con NumPages
        $this->LastPage = $this->NumPages;

//se il resto=0 dovrò incrementare lastpage in quanto significa che le pagine sono tutte piene e dovrà quindi puntare ad una nuova pagina.
//LastPage in pratica indica il valore di page con cui effettuare un nuovo inserimento. Mentre l' update interessa la page in uso, l' insert no. Esso segue il lastpage in quanto la pagina in uso potrebbe già essere piena.
    }

    public function getStartNumPage() {
        if ($this->TabelleCumulative) {
            $start = intval(config::getConfig('start_index_all8', 'allegaticonfig' . REFAGEA));
        } else {
            $start = intval(config::getConfig('start_index_all8_nc', 'allegaticonfig' . REFAGEA));
        }
        return $start;
    }

    private function setArrProductNames() {
        $arr_temp = $this->product->getProductNamesAndId();

        $arr_umis = $this->product->getProductUMis();

        /* Voglio ottenere un array del tipo:
          array(
          array('idproduct'=>'', 'nameproduct'=>'', 'umis'=>''),
          array('idproduct'=>'', 'nameproduct'=>'', 'umis'=>'')
          )
         */
        $arr_out = array();

        foreach ($arr_temp as $key => $value) {
            $arr_out[] = array('idproduct' => $key, 'nameproduct' => $value, 'umis' => $arr_umis[$key]);
        }

        $this->ArrProductNames = $arr_out;
    }

    private function setNumTables() {
        $length = count($this->ArrProductNames);

        $this->NumTables = intval($length / 3);
        if ($length % 3)
            $this->NumTables++;
    }

    /**
     * Restituisce il nome dei prodotti da visualizzare nell' head della tabella.
     * Restituisce un array del tipo:
     * array(
     * [0]=>array(
     *  [0]array('idproduct'=>'', 'nameproduct'=>''),
     *  [1]array('idproduct'=>'', 'nameproduct'=>''),
     *  [2]array('idproduct'=>'', 'nameproduct'=>''),

     *   ),
     * [1]=>array(
     *  [0]array('idproduct'=>'', 'nameproduct'=>''),
     *  [1]array('idproduct'=>'', 'nameproduct'=>''),
     *  [2]array('idproduct'=>'', 'nameproduct'=>''),

     *   )
     * )
     * 
     */
    public function getNameProductForTable() {

        $arr_out = array();

//rendo l' array divisibile per 3 aggiungendo gli element mancanti

        $num = count($this->ArrProductNames) % 3;

        $num = 3 - $num;                  //numero di elementi vuoti da aggiungere.
        for ($i = 0; $i < $num; $i++) {
            array_push($this->ArrProductNames, array('idproduct' => '0', 'nameproduct' => '', 'umis' => ''));   //colonne vuote in più.
        }

        for ($i = 0; $i < $this->NumTables; $i++) {

            $startindex = $i * 3;

            $subarray = array_slice($this->ArrProductNames, $startindex, 3);
//aggiungo ad $arr_out il gruppo di 4 elementi
            $arr_out[] = $subarray;
//$arr_out[] alla fine dei cicli conterrà i gruppi di 4 elementi (intestazioni) da visualizzare su ciascuna tabella.
        }
        secur::stripSlashes($arr_out);
        return $arr_out;
    }

    public function getNumTables() {
        return $this->NumTables;
    }

    public function getNextRow() {
        return $this->NextRow;
    }

    /**
     * Questa funzione cancella tutte le righe ricevute in POST che contengono un campo data vuoto. Serve a ridurre la quantità di risultati da salvare. 
     * @return type array
     */
    public function delEmptyRows($arr) {
// creo un array temporaneo che conterrà gli indici da rimuovere.
        $arr_temp = array();

        foreach ($arr['data'] as $key => $value) {
            if ($value == "")
                $arr_temp[] = $key;
        }

//adesso $arr_temp contiene gli indici da rimuovere. Scorro L' array $arr per rimuovere.

        foreach ($arr as $key => $arr_value) {
            for ($i = 0; $i < count($arr_temp); $i++) {
                unset($arr[$key][$arr_temp[$i]]);
            }
        }
        return $arr;
    }

    /**
     * Esplora le righe, effettua alcune validazioni 
     * @return type 
     */
    public function validateRow($arr) {
        foreach ($arr['data'] as $key => $value) {
            
        }
    }

    /*
      public function test() {
      return($this->ArrProductNames);
      }
     */

    private function convertData($date) {

        if (stripos($date, "/") > 0) {
            $arr = explode("/", $date);
            $str = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        } else if (stripos($date, "-") > 0) {
            $arr = explode("-", $date);
            $str = $arr[2] . "/" . $arr[1] . "/" . $arr[0];
        } else {
            $str = $date;
        }

        return $str;
    }

    /**
     * Metodo che restituisce una riga visualizzabile in tabella 
     */
    public function getRowsView() {
        $pagenum = $this->Session->getSessionVar("pagen");

        //----  $pagenum = $pagenum - $this->getStartNumPage();       //In quanto le pagine precedenti a startnumpage non esisono quindi le escludo dalla query
        $arr_ret = array();
        for ($i = 0; $i < 25; $i++) {
            $arr_ret[$i] = $this->setDefault();   //setta riga con campi tutti vuoti
//effettuo la query per leggere i valori relativi alla riga $i dalla prima tabella
            //$limit1 = (intval($pagenum) - $this->getStartNumPage()) * 25 + $i;
            $limit1 = (intval($pagenum) - 1) * 25 + $i;
            /*
              $arr_out = $this->db->getRows($this->Table1, array("date", "numrif", "numindig", "id_insert", "isload"), array(), array(
              array("limit", $limit1, "1")
              ));
             */
            if (0) {
                $arr_out = $this->db->getRows($this->Table1, array("date", "numrif", "numindig", "id_insert", "isload"), array(), array(
                    array("limit", $limit1, "1")
                ));
            } else {
                $arr_out = $this->db->freeQuery("SELECT date, numrif, numindig, id_insert, isload FROM " . $this->Table1 . " ORDER BY DATE ASC, isload DESC LIMIT " . $limit1.", 1");
            }
            $id_insert = -1;   //valore non valido
            if (count($arr_out)) { //se la query ha restituito qualcosa
                $arr_ret[$i] = array("date" => $this->convertData($arr_out[0]['date']), "numrif" => $arr_out[0]['numrif'], "numindig" => $arr_out[0]['numindig']);
                $id_insert = $arr_out[0]['id_insert'];
                if ($arr_out[0]['isload'] == 0) { //Se non si tratta di carico...
                    if ($this->TabelleCumulative) { //se si tratta di tabelle cumulative il numrif visualizzato dovrà essere aumentato dell' index start di allegato 9.
                        //-------
                    } else { //se si tratta di tabelle non cumulative il numrif visualizzato dovrà essere aumentato dell' index start di blocksheet.
                        //-------
                    }
                }
            }

//effettuo la query per ottenere i dati dalla seconda tabella
            $arr_out = $this->db->getRows($this->Table2, "*", array(
                array("where", "id_insert", "=", $id_insert, true),
            ));

            //ottengo gli id di tutti i prodotti esistenti compatibili con la visualizzazione in allegato 3. Sul db vengono infatti memorizzati solo i prodotti distribuiti mentre sull' allegato 8 devono apparire tutti

            $temp = $this->db->getRows("product", "id_product", array(
                array("where", "show_in_reg", "=", "on")
            ));

            $arr_id1 = array();  //conterrà gli id dei prodotti da visualizzare in allegato 3

            foreach ($temp as $key => $value) {
                array_push($arr_id1, $value["id_product"]);
            }

            $arr_id2 = array();  //conterrà gli id dei prodotti disponibili in tabella

            foreach ($arr_out as $key => $value) {
                array_push($arr_id2, $value["id_product"]);
            }




            //Aggiungo ad arr_out le righe mancanti

            foreach ($arr_id1 as $value) {
                if (!in_array($value, $arr_id2))
                    $arr_out[] = array(
                        "id_product" => $value,
                        "carico" => 0,
                        "scarico" => 0,
                        "giacza" => 0
                    );
            }


            for ($k = 0; $k < count($arr_out); $k++) {
                $arr_ret[$i][0] = array("carico" => "", "scarico" => "", "giacza" => "");      //setto colonne vuote alla fine ultima tabella

                $carico = (($this->setValue($arr_out[$k]['carico']) * 1) != 0) ? $this->setValue($arr_out[$k]['carico']) : "";

                $scarico = (($this->setValue($arr_out[$k]['scarico']) * 1) != 0) ? $this->setValue($arr_out[$k]['scarico']) : "";

                $giacza = ($arr_ret[$i]["date"] == "") ? "" : $this->setValue($arr_out[$k]['giacza']);  //placeholder


                $arr_ret[$i][$arr_out[$k]['id_product']] = array("carico" => $carico, "scarico" => $scarico, "giacza" => $giacza);
            }
        }
        return $arr_ret;
    }

    /**
     * setta i valori di default di ritorno da getRowsView 
     */
    private function setDefault() {
        $arr_out = array(
            'date' => '',
            'numrif' => '',
            'numindig' => ''
        );

        for ($i = 0; $i < count($this->ArrProductNames); $i++) {

            $arr_out[$this->ArrProductNames[$i]['idproduct']] = array("carico" => "", "scarico" => "", "giacza" => "");
        }

        return $arr_out;
    }

    private function setValue($val) {
        if ($val != "")
            $val = floatval($val) * 1;   //toglie zeri dopo la virgola
        return $val;
    }

    public function getNumPages() {

        return $this->NumPages;
    }

    public function getLastPage() {
        return intval($this->LastPage);
    }


    /**
     * Restituisce l' ultimo contenuto non vuoto inserito nella casella num. indig. in fase di load. (in genere contiene data prog. distrib.)
     */
    public function getLastDataProg() {
        $arr = $this->db->getRow($this->Table1, "numindig", array(
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

    /**
     * Inizializza e aggiorna l' array $ArrLastGiacza e restituisce lo stesso array, con le giacenze presenti.
     */
    public function getArrLastGiacza() {

        //Se il registro è vuoto leggo le giacenze dalla tabella delle giacenze iniziali

        $arr_temp = $this->db->freeQuery("select id_product, giacza from all8productcum" . REFAGEA . " where id_insert = (select  MAX(id_insert) from all8productcum" . REFAGEA . ")", "getArrLastGiacza()");


        //Creo un array che ha come chiavi gli id e come valore le giacenze
        $this->ArrLastGiacza = array();

        if (count($arr_temp) != 0) {

            for ($i = 0; $i < count($arr_temp); $i++) {
                $this->ArrLastGiacza[$arr_temp[$i]["id_product"]] = floatval($arr_temp[$i]["giacza"]);
            }
        } else {
            $arr_temp = $this->db->getRows("product", array("id_product"));

            for ($i = 0; $i < count($arr_temp); $i++) {
                $this->ArrLastGiacza[$arr_temp[$i]["id_product"]] = 0;
            }
        }

        return $this->ArrLastGiacza;
    }

//close function

    /**
     * Restituisce l' elenco prodotti caricati in una determinata data da visualizzare sulla pagina del load.
     * @param type $data 
     */
    public function getLoad($date) {      //restituisce un array vuoto se alla data indicata non è presente alcun carico.
// $arr_date = explode("/", $date);
// $date = $arr_date[2] . "-" . $arr_date[1] . "-" . $arr_date[0];
        $arr_qty = $this->db->getRows(array("all8registercum", "all8productcum" . REFAGEA), array("id_product", "carico", "all8productcum" . REFAGEA . ".id_insert", "numrif"), array(
            array("on", "all8registercum" . REFAGEA . ".id_insert", "=", "all8productcum" . REFAGEA . ".id_insert", true),
            array("where", "all8registercum" . REFAGEA . ".isload", "=", 1, true),
            array("and", "date", "=", $this->convertData($date))
        ));

        return $arr_qty;
    }

    /**
     * Gestisce il carico prodotti 
     */
    public function saveLoad($arr, $arrLastGza = array()) {
//$arrLastGza non utilizzato in caso di aggiornamento di un vecchio load
//effettuo il salvataggio sulla tabella 1
        $date = $this->convertData($arr['date']);

        //Verifico se si tratta di nuovo inserimento o aggiornamento:
        $test = $this->db->getRow($this->RegCum, "id_insert", array(
            array("where", "date", "=", $date),
            array("and", "isload", "=", 1, true)
        ));


        if (!count($test)) {                     //nuovo inserimento
            $this->db->insert($this->RegCum, array(
                "date" => $date,
                "numrif" => ($arr['numrif']),
                "isload" => 1, //rappresenta isload che dovrà essere settato in caso di carico
                "numindig" => $arr['numindig']
            ));

//prelevo l' id con cui ho salvato che mi servirà ad inserirlo nella tabella 2

            $arr_retval = $this->db->getRow($this->RegCum, "id_insert", array(), array(
                array("ORDERBY", "id_insert"),
                array("ORDER", "DESC")
            ));

            $id_insert = $arr_retval["id_insert"];

//salvo in tabella 2

            foreach ($arr as $key => $value) {

                if (substr($key, 0, 4) == "load") {

                    $carico = floatval(trim($value));

                    // if ($carico==0)
                    //     continue;

                    $arr_save = array();
                    $idproduct = substr($key, 5);


                    $arr_save['id_insert'] = $id_insert;
                    $arr_save['id_product'] = $idproduct;
                    $arr_save['carico'] = $carico;
                    $arr_save['scarico'] = 0;
                    $arr_save['giacza'] = 0;        //verrà settata alla fine da un' apposita funzione
                    $arr_save['isload'] = 1;

                    $this->db->insert($this->ProdCum, $arr_save);
                }
            }

//aggiorno info inserimento
            $this->setPages();   //setta pagina e riga successiva da usare.
///////// Dopo aver effettuato l' inserimento verifico che non sia presente già uno scarico con la medesima data. In caso affermativo devo invertire l' ordine sull' allegato 8 cumulativo in modo che il carico preceda lo scarico.
        if(0){
            $temp_arr_scarico = $this->db->getRow($this->RegCum, array("id_insert", "numrif", "numindig", "isload"/*                     * ***, "pagenum", "rownum" */), array(
                array("where", "date", "=", $date),
                array("and", "isload", "=", 0, true)
            ));

            if (isset($temp_arr_scarico['id_insert'])) {  //inverto l'ordine
//attualmente lo scarico è al primo posto.  
//attualmente il load è al secondo posto.  
                $temp_arr_load = $this->db->getRow($this->RegCum, array("id_insert", "numrif", "numindig", "isload" /*                         * ***,"pagenum", "rownum" */), array(
                    array("where", "date", "=", $date),
                    array("and", "isload", "=", 1, true)
                ));

                $id_insert_scarico = $temp_arr_scarico['id_insert'];   //siamo prima dello scambio

                unset($temp_arr_scarico['id_insert']);

                $id_insert_load = $temp_arr_load['id_insert'];

                unset($temp_arr_load['id_insert']);

//effettuo lo scambio
//ASSEGNO
                //PRIMO REGISTRO
                $this->db->update($this->RegCum, array("id_insert" => 10000001), array(
                    array("where", "id_insert", "=", $id_insert_load, true)
                ));

                $this->db->update($this->RegCum, array("id_insert" => 10000002), array(
                    array("where", "id_insert", "=", $id_insert_scarico, true)
                ));

                //SECONDO REGISTRO
                $this->db->update($this->ProdCum, array("id_insert" => 10000001), array(
                    array("where", "id_insert", "=", $id_insert_load, true)
                ));

                $this->db->update($this->ProdCum, array("id_insert" => 10000002), array(
                    array("where", "id_insert", "=", $id_insert_scarico, true)
                ));

//SCAMBIO
                //PRIMO REGISTRO
                $this->db->update($this->RegCum, array("id_insert" => $id_insert_scarico), array(
                    array("where", "id_insert", "=", 10000001, true)
                ));

                $this->db->update($this->RegCum, array("id_insert" => $id_insert_load), array(
                    array("where", "id_insert", "=", 10000002, true)
                ));

                ////SECONDO REGISTRO
                $this->db->update($this->ProdCum, array("id_insert" => $id_insert_scarico), array(
                    array("where", "id_insert", "=", 10000001, true)
                ));

                $this->db->update($this->ProdCum, array("id_insert" => $id_insert_load), array(
                    array("where", "id_insert", "=", 10000002, true)
                ));
            }//close if ...
        } 
//////////////////////////////////AGGIORNAMENTO///////////////////////////////////
        }//close if($arr['insert_id']=="")
        else {    //caso aggiornamento
//Aggiorno la prima tabella
            $this->db->update($this->RegCum, array(
                "date" => $date,
                "numrif" => ($arr['numrif']),
                "numindig" => $arr['numindig']
                    ), array(
                array("where", "id_insert", "=", intval($arr['insert_id']), true)
            ));

//Aggiorno la seconda tabella
            foreach ($arr as $key => $value) {

                if (substr($key, 0, 4) == "load") {
                    $arr_save = array();
                    $idproduct = substr($key, 5);

                    $carico = floatval(trim($value));

                    $arr_save['id_product'] = $idproduct;
                    $arr_save['carico'] = $carico;

//prelevo il vecchio carico. Per la giacza non mi preoccupo perchè verranno ricalcolate tutte alla fine con riaggiorna()

                    $arr_old = $this->db->getRow($this->ProdCum, array("carico"), array(
                        array("where", "id_insert", "=", intval($arr['insert_id']), true),
                        array("and", "id_product", "=", intval($idproduct))
                    ));

                    if (count($arr_old)) { //Può verificarsi la situazione in cui il vecchio carico non è presente. E' il caso di un nuovo prodotto aggiunto.
//Se il vecchio carico esiste:
                        $oldcarico = $arr_old["carico"];

                        $this->db->update($this->ProdCum, $arr_save, array(
                            array("where", "id_insert", "=", intval($arr['insert_id']), true),
                            array("and", "id_product", "=", intval($idproduct))
                        ));
                    } else { // Se manca la riga per quel prodotto, la aggiungo:
                        $arr_save["id_product"] = intval($idproduct);
                        $arr_save["id_insert"] = intval($arr['insert_id']);
                        $this->db->insert($this->ProdCum, $arr_save);
                    }
                }
            }
        }

        //Adesso mi tocca aggiornare le giacenze sul registro mediante il metodo riaggiorna.

        $this->riaggiorna();
    }

//close function saveLoad

    /*
     * Verifica che su qualcuno dei registri non sia già stata inserita una data superiore a quella in cui si sta effettuando la distribuzione.
     */

    public function checkDate($date) {

        $date = $this->convertData($date);

        /*$arr_date = $this->db->getRow("all8registercum" . REFAGEA, "date", array(
            array("where", "date", ">", $date)
        ));*/
        
        $arr_date = $this->db->getRow("blocksheet", "dtime", array(
            array("where", "DATE(dtime)", ">", $date)
        ));
        
        //return json_encode($arr_date);

        if (count($arr_date) == 0)
            return 0;

        return -5;
    }

    /**
     * 
     * @param type $sheet_id
     * @return type torna 0 se quel valore di sheet_id non è presente, altrimenti 1
     */
    public function isUsedBlockSheet($sheet_id) {
        $arr = $this->db->getRow("blocksheet", "sheetId", array(
            array("where", "sheetId", "=", $sheet_id, true)
        ));
        return count($arr);
    }

    /**
     * Rimuove da registri Agea solamente e non dal blocchetto
     * @param type $sheetId
     * @return int
     */
    public function removeBlockSheet($sheetId) {

        //Verifico se sono presenti prodotti Agea distribuiti con quello sheetid

        $test = $this->db->getRow("distributedproduct", "Id", array(
            array("where", "sheetId", "=", intval($sheetId), true)
        ));
//$this->Logger->rawLog($test);
        if (!count($test))
            return 0;

        //prelevo data distribuzione e numero indigenti da blocksheet

        $res = $this->db->getRow("blocksheet", array("dtime", "num_indig"), array(
            array("where", "sheetId", "=", $sheetId, true)
        ));

        $date = substr($res['dtime'], 0, 10);
        $num_indig_remove = intval($res["num_indig"]);
        //conto il numero di distribuzioni effettuate in quella data:

        $distr = $this->db->getRows("blocksheet", "sheetId", array(
            array("where", "dtime", "like", $date . "%")
        ));

        //Ottengo l' id_insert dei registri Agea

        $res = $this->db->getRow($this->RegCum, array("id_insert", "numindig"), array(
            array("where", "date", "=", $date),
            array("and", "isload", "=", 0, true)
        ));

        $num_indig = intval($res["numindig"]);
        $id_insert = intval($res["id_insert"]);


        //Se è una sola distribuzione elimino la riga dai registri

        if (count($distr) == 1) {
//$this->Logger->rawLog("una distribuzione");
            $this->db->delete($this->ProdCum, array(
                array("where", "id_insert", "=", $id_insert, true)
            ));

            $this->db->delete($this->RegCum, array(
                array("where", "date", "=", $date),
                array("and", "isload", "=", 0, true)
            ));
            //Siccome ad essere eliminata è sempre l' ultima distribuzione, non è necessario ggiornare i numeri allegato 9
        } else {//in caso di più distribuzioni correggo lo scarico
            //Prelevo elenco dei prodotti distribuiti:
            // $this->Logger->rawLog("più distribuzioni");
            $res = $this->db->getRows("distributedproduct", array("id_product", "qty"), array(
                array("where", "sheetId", "=", $sheetId, true)
            ));

            //Creo un array in cui le chiavi sono gli id dei prodotti e i valori le quantità distribuite

            $distributed = array();

            foreach ($res as $key => $value) {
                $distributed[$res[$key]["id_product"]] = $res[$key]["qty"];
            }

            //Prelevo righe da aggiornare

            $res = $this->db->getRows($this->ProdCum, array("id_product", "scarico"), array(
                array("where", "id_insert", "=", $id_insert, true)
            ));

            //Aggiorno righe:
            foreach ($res as $key => $value) {

                $this->db->update($this->ProdCum, array(
                    "scarico" => floatval($res[$key]["scarico"]) - floatval($distributed[$res[$key]["id_product"]])
                        ), array(
                    array("where", "id_insert", "=", $id_insert, true),
                    array("and", "id_product", "=", $res[$key]["id_product"], true)
                ));
            }


            // $this->Logger->rawLog($num_indig);
//$this->Logger->rawLog($id_insert);
            /*
              //Aggiorno numindig
              $this->db->update($this->RegCum, array(
              "numindig" => ($num_indig - $num_indig_remove)
              ), array("where", "id_insert", "=", $id_insert, true));       //Agg. limit 1
             */
            //Aggiorno numindig
            $this->db->freeQuery("update " . $this->RegCum . " set numindig=" . ($num_indig - $num_indig_remove) . " where id_insert=" . $id_insert . " limit 1");
        }
    }

    /**
     * @param type $date è la data a partire dalla quale iniziare ad aggiornare.
     */
    public function riaggiorna() {
        return;
    }

//function close

    /**
     * Scarico cumulativo
     * @param type $arr 
     */
    public function scarico($arr, $arr_giacze) {


        //Determino il numero di riferimento base per l' allegato 9
        $temp = $this->db->getRow($this->Table1, "COUNT(id_insert)", array(
            array("where", "isload", "=", 0, true),
            array("and", "date", "<=", $arr['date'])
        ));



        // $logger->rawLog($temp["COUNT(id_insert)"]);
//Devo valutare ogni volta se è il caso di inserire una nuova riga o aggiornarne il contenuto.
//Se nella stessa giornata è stata effettuata una distribuzione devo solo aggiornare i dati.
//Effettuo la verifica mediante la seguente query:

        $arr_ret = $this->db->getRow($this->Table1, "id_insert", array(
            array("where", "date", "=", $arr['date']),
            array("and", "isload", "=", "0", true)
        ));


//Prima di salvare effettuerò un controllo sulle dimensioni di $arr_ret se 0, si tratta di nuovo inserimento, in caso contrario di update e conoscerò già i valori  id
        if (count($arr_ret) == 0) { //NUOVO INSERIMENTO
            $arr_save = array(
                "date" => $arr["date"],
                "numrif" => $temp["COUNT(id_insert)"] + 1,
                "numindig" => $arr["numindig"],
                "isload" => 0
            );


            $this->db->insert($this->Table1, $arr_save);
//leggo l' id_insert appena creato
            $id_insert = $this->db->getLastInsertedId();
//salvataggio su seconda tabella:
            foreach ($arr["products"] as $idproduct => $qty) {
                $this->db->insert($this->Table2, array(
                    "id_insert" => $id_insert,
                    "id_product" => intval($idproduct),
                    "scarico" => $qty,
                    "isload" => 0
                ));
            }
        } else { //AGGIORNAMENTO
            //leggo il numero di indigienti già presente
            $res = $this->db->getRow($this->Table1, array("numindig", "id_insert"), array(
                array("where", "date", "=", $arr['date']),
                array("and", "isload", "=", "0", true)
            ));
            /*             * ***
              $arr_update = array(
              "numindig" => count($arr["numindig"]) + $res["numindig"]
              );
             * *** */
            $arr_update = array(
                "numindig" => intval($arr["numindig"]) + intval($res["numindig"])
            );

            $this->db->update($this->Table1, $arr_update, array(
                array("where", "date", "=", $arr['date']),
                array("and", "isload", "=", "0", true)
            ));
            //Leggo i prodotti distribuiti dalla seconda tabella:
            $res2 = $this->db->getRows($this->Table2, array("id_product", "scarico"), array(
                array("where", "id_insert", "=", $res["id_insert"])
            ));
            //creo un array in cui le chiavi sono l' id prodotto e i valori le quantità:
            $arr_assoc = array();
            foreach ($res2 as $key => $value) {
                $arr_assoc[$value["id_product"]] = $value["scarico"];
            }
            //Se il prodotto esiste già in tabella aggiorno la quantità, altrimenti aggiungo riga.
            foreach ($arr["products"] as $idproduct => $qty) {
                if (array_key_exists($idproduct, $arr_assoc)) {
                    //////                $this->Logger->rawLog("qty: ".$qty." ass: ".$arr_assoc[$idproduct]);
                    /////  $this->db->freeQuery("update " . $this->Table2 . " set scarico = " . ($qty + $arr_assoc[$idproduct]) . " where id_product = " . $idproduct . " and id_insert=" . $res["id_insert"] . " limit 1", "scarico");
                    $this->db->freeQuery("update " . $this->Table2 . " set scarico = " . ($qty) . " where id_product = " . $idproduct . " and id_insert=" . $res["id_insert"] . " limit 1", "scarico");
                } else {
                    $this->db->insert($this->Table2, array(
                        "id_insert" => $res["id_insert"],
                        "id_product" => $idproduct,
                        "scarico" => $arr["products"][$idproduct],
                        "isload" => 0
                    ));
                }
            }
        }
        $this->riaggiorna();
    }

//close function

    /*
     * Restituisce tutte le date in cui sono compilati gli allegati 9
     */
    public function getDateForAll9() {
        $arr_tmp = $this->db->getRows("all8registercum" . REFAGEA, "date", array(
            array("where", "isload", "=", 0, true)
        ));
        $arr_out = array();
        for ($i = 0; $i < count($arr_tmp); $i++) {
            array_push($arr_out, $arr_tmp[$i]["date"]);
        }
        return $arr_out;
    }

    /*
     * Restituisce tutte le date in cui sono state effettuate distribuzioni
     */

    public function getDateForReport() {
        $arr_tmp = $this->db->getRows("blocksheet", "dtime");
        $arr_out = array();
        for ($i = 0; $i < count($arr_tmp); $i++) {
            array_push($arr_out, substr($arr_tmp[$i]["dtime"], 0, 10));
        }
        return $arr_out;
    }

    public function removeLoad($idload) {
        $this->db->delete($this->ProdCum, array(
            array("where", "id_insert", "=", $idload, true),
            array("and", "isload", "=", 1, true)
        ));

        $this->db->delete($this->RegCum, array(
            array("where", "id_insert", "=", $idload, true),
            array("and", "isload", "=", 1, true)
        ));

        $this->riaggiorna();   //Riaggiorno le varie giacenze
    }

    //class close
}

?>
