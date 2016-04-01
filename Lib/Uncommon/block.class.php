<?php

class block {

    private $db;
    private $product;
    private $person;
    private $bsheet_assoc_form_db;
    private $distribuited_assoc_form_db;
    private $product_table;
    private $bsheet_table;
    private $bancoalim;
    private $distributed_table;
    private $logger;

    public function __construct($bancoalim = false) {
        $session = new session();

        $this->logger = new logger("../elog/block.class.log", 1);
        if ($bancoalim) {
            $this->distributed_table = "distributedproductbanco";
            $this->product_table = "productbancoalim";
            $this->product = new product(1);
        } else {
            $this->distributed_table = "distributedproduct";
            $this->product_table = "product";
            $this->product = new product();
        }
        $this->bsheet_table = "blocksheet";
        $this->bancoalim = $bancoalim;
        $this->db = new db();
        $this->person = new Person();
        $this->array_init();
    }

    private function array_init() {     //form ->db
        $this->bsheet_assoc_form_db = array(
            "sheetId" => "sheetId",
            "person_id" => "personId",
            "dtime" => "dtime", //campo timestamp
            //"product_id" => "productId",
            "output" => "signature",
            "modifiable" => "modifiable",
            "num_indig" => "num_indig"
        );

        $this->distribuited_assoc_form_db = array(
            "product_id" => "id_product",
            "sheetId" => "sheetId",
            "qtytot" => "qty"
        );
    }

//close private function array_init()

    /**
     * Questo metodo torna un array con lo sheetId con cui ha salvato e il modifiable.
     * @param type $arr_post 
     * Restituisce un array.
     */
    //$arr_post contiene anche lo sheetId. se è una stringa vuota si tratta di nuovo inserimento altrimenti dovrò verificare se lo sheetId esiste già per poter dire se si tratta di nuovo inserimento o modifica.
    public function save($arr_post) {
       
        secur::addSlashes($arr_post);
        $sheetId = $arr_post['sheetId'];

        $update = false;
      if ($sheetId != "") {
            //Si tratta di update solo se la riga esiste già
            $arr1 = $this->db->getRow($this->bsheet_table, "sheetId", array(
                array("where", "sheetId", "=", $sheetId, true)
            ));
            
            if (count($arr1))
                $update = true;
        }else {
            //se lo sheetId è vuoto si tratta sicuramente di un nuovo inserimento e quindi update è già false 
            //ma devo verificare se c'è stata una cancellazione per cui lo sheetId dovrà essere quello della cancellazione appena avvenuta oppure no.
            if (config::getConfig("lastsheetidisdeleted", "internalconfig")) {
                $sheetId = config::getConfig("lastsheetid", "internalconfig");
                $arr_post['sheetId'] = $sheetId;
                config::setConfig("lastsheetidisdeleted", 0, "ultimo sheetid (blocchetto consegne) inserito è stato cancellato?", "internalconfig");
            }
      }

        if ($arr_post['sheetId'] == "")  //Questo perchè se dovrò effettuare un nuovo inserimento, lo sheetid dovrà essere inserito da mySql se non settato.
            unset($arr_post['sheetId']);


        $arr_save = array();

        if (isset($arr_post['date']) && $arr_post['date'] != "") {
            $arr_save['dtime'] = $this->convertData($arr_post['date']) . " 00:00:01"; //bisogna dare cmq un orario. Non posso conoscerlo perchè è un inserimento in differita. Indicherò quindi mezzanotte e 1 sec.
            $date = $arr_post['date'];   //Mi serve come valore di ritorno del metodo.
            unset($arr_post['date']);
        } else {
            //data e ora le assegnerà automaticamente Mysql. 
        }

        foreach ($this->bsheet_assoc_form_db as $key => $value) {
            if (array_key_exists($key, $arr_post))
                $arr_save[$value] = $arr_post[$key];
        }//close foreach
//L' aggiornamento del blocksheet non è previsto. Può solo essere cancellato mediante una funzione opportuna.
        if (!$update) { //nuovo inserimento
            //inserimento nuovo foglio
           $this->db->insert($this->bsheet_table, $arr_save);

            $sheetId = $this->db->getLastInsertedId();
            config::setConfig("lastsheetid", $sheetId, "ultimo sheetid (blocchetto consegne) inserito", "internalconfig"); //Questa variabile in caso di cancellazione contiene l' id appena rimosso
            
        }


        //A questo punto ho eseguito l' operazione di inserimento o aggiornamento e ho l' id utilizzato: $sheetId.
        //  Passo alla tabella dei prodotti distribuiti.
        //In caso di update bisogna effettuare un' operazione in più e cioè
        //vedere se in tabella sono già presenti dei prodotti che non compaiono in POST e cancellarli.
        $arr_output = array($this->bsheet_assoc_form_db['sheetId'] => $sheetId, "xxx" => $arr_save);

        if ($update) {
            //ottengo gli id dei prodotti in tabella
            $arr_res = $this->db->getRows($this->distributed_table, $this->distribuited_assoc_form_db['product_id'], array(
                array("where", $this->distribuited_assoc_form_db['sheetId'], "=", $sheetId, true)
            ));

            //controllo se qualcuno degli id presenti in tabella non è presente nell' array POST ed eventualmente lo cancello

            for ($i = 0; $i < count($arr_res); $i++) {
                if (!in_array($arr_res[$i][$this->distribuited_assoc_form_db['product_id']], $_POST['product_id'])) {
                    $this->deleteProduct($arr_res[$i][$this->distribuited_assoc_form_db['product_id']], $sheetId);
                }
            }
        }  
        $this->productAddOrUpdate($arr_post, $sheetId);

        $arr_output['xxx']['sheetId'] = $sheetId;
        $arr_output['xxx']['date'] = $date;

        return $arr_output;
    }

//close function



    private function productAddOrUpdate($arr_post, $sheetId) {
        //non uso la classe secur perchè ricevo i dati da save e lì è già stata usata.
      foreach ($arr_post['product_id'] as $i => $value) {
            $ispresent = $this->db->isPresent($this->distributed_table, array(
                $this->distribuited_assoc_form_db['product_id'] => $arr_post['product_id'][$i],
                $this->distribuited_assoc_form_db['sheetId'] => $sheetId
            ));
            if (!$ispresent) {
                $this->db->insert($this->distributed_table, array(
                    $this->distribuited_assoc_form_db['product_id'] => $arr_post['product_id'][$i],
                    $this->distribuited_assoc_form_db['sheetId'] => $sheetId,
                    $this->distribuited_assoc_form_db['qtytot'] => $arr_post['qtytot'][$i]
                ));

                $this->product->setNotModifiable($arr_post['product_id'][$i]); //rendo non modificabile nella tabella prodotti
            } else {
                $this->db->update($this->distributed_table, array(
                    $this->distribuited_assoc_form_db['qtytot'] => $arr_post['qtytot'][$i]
                        ), array(
                    array("where", $this->distribuited_assoc_form_db['product_id'], "=", $arr_post['product_id'][$i], true),
                    array("and", $this->distribuited_assoc_form_db['sheetId'], "=", $sheetId, true),
                    array("limit", "", 1, true)
                        //aggiungere il limit 1
                ));
            }
            
        }
    }

    private function deleteProduct($idproduct, $sheetId) {
        $this->db->delete($this->distributed_table, array(
            array("where", $this->distribuited_assoc_form_db['product_id'], "=", $idproduct, true),
            array("and", $this->distribuited_assoc_form_db['sheetId'], "=", $sheetId, true),
            array("limit", "", 1, true)
                //aggiungere il limit 1
        ));
    }

    public function getForReport(person $person, $familyid, $datamin, $datamax, $comune = "") {

        if ($familyid != "" && $familyid != "0") {

            $arr = $this->db->getRows(array($this->bsheet_table, "person"), array(
                $this->bsheet_assoc_form_db["sheetId"],
                $this->bsheet_assoc_form_db["person_id"],
                $this->bsheet_assoc_form_db["dtime"]
                    ), array(
                array("on", $this->bsheet_assoc_form_db["person_id"], "=", "id_person", true),
                array("where", "family_register_number", "=", $familyid, true),
                array("and", $this->bsheet_assoc_form_db["dtime"], ">=", $datamin),
                array("and", $this->bsheet_assoc_form_db["dtime"], "<=", $datamax)
            ));
        } else {


            $arr = $this->db->getRows($this->bsheet_table, array(
                $this->bsheet_assoc_form_db["sheetId"],
                $this->bsheet_assoc_form_db["person_id"],
                $this->bsheet_assoc_form_db["dtime"]
                    ), array(
                array("where", $this->bsheet_assoc_form_db["dtime"], ">=", $datamin),
                array("and", $this->bsheet_assoc_form_db["dtime"], "<=", $datamax)
            ));
            if ($comune != "") {
                //Rimuovo le righe che non devo visualizzare
                foreach ($arr as $key => $value) {
                    if (intval($this->getComuneFromPersonId($value[$this->bsheet_assoc_form_db["person_id"]])) != intval($comune))
                        unset($arr[$key]);
                }
            }
        }// close else

        $arr_2 = array();


        foreach ($arr as $key => $value) {
            //ottengo informazioni dal person_id

            $person_id = $arr[$key][$this->bsheet_assoc_form_db["person_id"]];

            $arr_2[$key] = array();

            $dtime = $arr[$key][$this->bsheet_assoc_form_db["dtime"]];
//Converto data in formato italiano e rimuovo l' ora.
            $dtime = substr($dtime, 8, 2) . "/" . substr($dtime, 5, 2) . "/" . substr($dtime, 0, 4);

            $arr_2[$key][] = $dtime;
            $arr_2[$key][] = $arr[$key][$this->bsheet_assoc_form_db["sheetId"]];
            $arr_2[$key][] = $person->getPersonNameById($person_id);
            $arr_2[$key][] = $person->getIdFamily($person_id);
        }


        return $arr_2;
    }

    public function getLastDistr($familyid) {
        
        $last_distr_date ="";

        //$logger = new logger("block.class.log", 1);
        //Puo succedere che sul blocchetto attuale non vi sono ancora distribuzioni ma su un precedente blocchetto si.  
        //Devo quindi estendere la ricerca:

        $ref = intval(REFYEAR);
       // $logger->rawLog($this->db->freeQuery("show tables like 'blocksheet".$ref."'"));
        while ( count($this->db->freeQuery("show tables like 'blocksheet".$ref."'", "getLastDistr")) ) {
          
            $arr = $this->db->getRow(array("blocksheet" . $ref, "person"), "MAX(" . $this->bsheet_assoc_form_db['dtime'] . ")", array(
                array("on", $this->bsheet_assoc_form_db["person_id"], "=", "id_person", true),
                array("where", "family_register_number", "=", $familyid, true)
            ));

            $last_distr_date = $arr['MAX(dtime)'];
            if ($last_distr_date != "")
                break;
            $ref --;
          // $logger->rawLog($this->db->freeQuery("show tables like 'blocksheet".$ref."'"));
        }
        return $last_distr_date;
    }

    public function getForModalForm($sheet_id) {

        $arr = $this->db->getRow(array("blocksheet", "person"), array(
            "dtime", "signature",
            "surname",
            "name",
            "num_indig"
                ), array(
            array("on", $this->bsheet_assoc_form_db["person_id"], "=", "id_person", true),
            array("where", $this->bsheet_assoc_form_db["sheetId"], "=", $sheet_id, true),
        ));

        $arr2 = $this->db->getRows(array($this->product_table, $this->distributed_table), array(
            "name_product", "measureunity", $this->product_table . ".id_product",
            $this->distribuited_assoc_form_db['qtytot']
                ), array(
            array("on", $this->product_table . ".id_product", "=", $this->distributed_table . "." . $this->distribuited_assoc_form_db['product_id'], true),
            array("where", $this->distribuited_assoc_form_db['sheetId'], "=", $sheet_id, true),
            array("and", $this->distribuited_assoc_form_db['qtytot'], "!=", "0.00", true)
        ));

        if (isset($arr['dtime']))     //in una delle 2 chiamate dtime non è definito. Verificare!  //?
        //sistemo la data in formato italiano rimuovendo l' ora
            $arr['dtime'] = substr($arr['dtime'], 8, 2) . "/" . substr($arr['dtime'], 5, 2) . "/" . substr($arr['dtime'], 0, 4);
        foreach ($arr2 as $key => $arrval) {                           //rimuovo gli zeri dopo la virgola
            $arr2[$key][$this->distribuited_assoc_form_db['qtytot']]*=1;
        }
        $arr['distributedproducts'] = $arr2;

        secur::stripSlashes($arr);

        return $arr;
    }

    /*
     * $datamin, $datamax sono di tipo datetime. Il metodo fornisce anche l' ultimo id allegato 9 nell' intervallo di date indicato.
     */

    public function getForReport3($datamin, $datamax, $comune = "", $onlyagea = false) {

        $arr = $this->db->getRows(array($this->distributed_table, $this->bsheet_table), array(
            $this->bsheet_table . ".personId",
            $this->distribuited_assoc_form_db['product_id'],
            $this->distribuited_assoc_form_db['qtytot']
                ), array(
            array("on", $this->distributed_table . ".sheetId", "=", $this->bsheet_table . ".sheetId", true),
            array("and", $this->bsheet_assoc_form_db["dtime"], ">=", $datamin),
            array("and", $this->bsheet_assoc_form_db["dtime"], "<=", $datamax),
            array("and", $this->distribuited_assoc_form_db['qtytot'], "!=", "0.00", true)
        ));

        if ($comune != "") {

            foreach ($arr as $key => $value) {

                if (intval($this->getComuneFromPersonId($value["personId"])) != intval($comune))
                    unset($arr[$key]);
            }
        }

        $arr_report = array();  //alla fine le chiavi saranno gli id dei prodotti

        foreach ($arr as $key => $value) {
            if (array_key_exists($arr[$key]['id_product'], $arr_report))
                $arr_report[$arr[$key]['id_product']]['qty'] += $arr[$key]['qty'];
            else {
                $arr_report[$arr[$key]['id_product']] = array();
                $arr_report[$arr[$key]['id_product']]['qty'] = $arr[$key]['qty'];
            }
        }

        //le chiavi sono gli id dei prodotti ma non sono ordinati per la visualizzazione corretta in allegato 9
        //Ottengo l' elenco degli id ordinati per l' allegato 9

        $ids = $this->product->getArrayIds(9);
        $arr_report2 = array();                   //sarà alla fine il nuovo array ordinato

        for ($i = 0; $i < count($ids); $i++) {
            if (array_key_exists($ids[$i], $arr_report)) {
                $arr_report2[$ids[$i]] = $arr_report[$ids[$i]];
            }
        }

        //su ogni prodotto distribuito eseguo una query per ottenere tutti i dati

        foreach ($arr_report2 as $key => $value) {
            $arr2 = $this->db->getRow($this->product_table, array("id_product", "name_product", "measureunity"), array(
                array("where", "id_product", "=", $key, true)
            ));

            $arr_report2[$key] = array_merge($arr_report2[$key], $arr2);
        }

        $arr_out['products'] = $arr_report2;

        //devo ottenere il numero di distribuzioni effettuate:

        $arr_x = $this->db->getRows(array($this->bsheet_table, "person"), array("family_register_number", "sheetId", "personId"), array(
            array("on", $this->bsheet_assoc_form_db["person_id"], "=", "id_person", true),
            array("where", $this->bsheet_assoc_form_db["dtime"], ">=", $datamin),
            array("and", $this->bsheet_assoc_form_db["dtime"], "<=", $datamax)
        ));

        if ($comune != "") {  // 2
            // 
            //Rimuovo elementi da array se necessario
            foreach ($arr_x as $key => $value) {

                if (intval($this->getComuneFromPersonId($value["personId"])) != intval($comune))
                    unset($arr_x[$key]);
            }
        }


        $arr_out["total_distr"] = count($arr_x);   //numero di distribuzioni effettuate
        //ottengo il numero di famiglie servite senza effettuare una nuova query.
        $arr_temp = array();

        foreach ($arr_x as $key => $value) {
            $arr_temp[$arr_x[$key]['family_register_number']] = "";
        }

        $arr_out["serv_family"] = count($arr_temp);

        //a questo punto gli indici di $arr_temp sono gli id famiglia non duplicati.
        //calcolo il numero di indigenti serviti

        $indig_tot = 0;
        if (!$onlyagea) {
            foreach ($arr_temp as $key => $value) {
                $idfamily = intval($key);
                $indig_tot+=$this->person->getNumPersons($idfamily);
            }
        } else { //Serve solo per allegato 9. La pagina report non tiene conto delle sole Agea ma delle distribuzioni complessive. Per all9 le due date coincidono
            $n_indig = $this->db->getRow("all8registercum" . REFAGEA, "numindig", array(
                array("where", "date", "=", substr($datamin, 0, 10)),
                array("and", "isload", "=", 0, true)
            ));

            if (isset($n_indig["numindig"]))
                $indig_tot = count($n_indig["numindig"]);
        }
        $arr_out["serv_indigenti"] = $indig_tot;

        if (count($arr_out['products'])) {   //verifico che siano stati distribuiti prodotti 
            // Occorre a questo punto individuare il numero progressivo da attribuire all' allegato.
            // Procedo contando le date sui fogli blocchetto inferiori a quella specificata con datamin.
            // Datamin quando chiediamo l' Allegato 9 è quella con orario inizio giornata.
            //
            //non si tratta di dati immessi dall' esterno e quindi posso eseguire una freeQuery:
            //    if ($onlyagea) {
            //$arr = $this->db->freeQuery("SELECT id_insert FROM all8registercum".REFYEAR. " where isload = 0" );
            //       } else {
            //       $arr = $this->db->freeQuery("SELECT DISTINCT dtime FROM blocksheet".REFYEAR." where dtime <= '" . $datamin . "'");
            //   }
            //Aggiungo l' informazione ad arr_out
            $arr_out["num_all9"] = count($this->db->freeQuery("SELECT id_insert FROM all8registercum" . REFAGEA . " where isload = 0", "getForReport3")) + 1;
        } else
            $arr_out["num_all9"] = '';            //non visualizzo il numero dell' allegato se non sono stati distribuiti prodotti
        $arr_out["nomeComuneSelezionato"] = $this->getComunebyId($comune);

        secur::stripSlashes($arr_out);

        return $arr_out;
    }

    public function getNumAll9($date) {
        $arr = $this->db->getRow("all8registercum" . REFAGEA, "numrif", array(
            array("where", "date", "=", $date),
            array("and", "isload", "=", 0, true)
        ));

        if (!count($arr))
            return '';

        return $arr['numrif'];
    }

    /**
     * Restituisce un array con l' id dell' ultimo foglio blocchetto salvato.
     * @param type $familyid se indicato restituisce in relazione alla famiglia indicata
     * @param type $lessthan se indicato, il metodo restituisce il primo sheetId trovato inferiore a $lessthan
     * @return type array() Restituisce un array  
     */
    public function getLastSheetId($familyid = "", $lessthan = "") {
        //    if($familyid!="") $arr_cond_1=array("where", )
        //implemento intanto solo la situazione che interessa adesso

        $arr_res = $this->db->getRow(array($this->bsheet_table, "person"), array(
            $this->bsheet_assoc_form_db['sheetId']
                ), array(
            array("on", $this->bsheet_assoc_form_db["person_id"], "=", "id_person", true),
            array('where', 'family_register_number', '=', $familyid, true)
                ), array(
            array("ORDERBY", $this->bsheet_assoc_form_db['sheetId']),
            array("ORDER", "DESC"),
        ));
        return $arr_res;
    }

    /**
     * Torna il numero di indigenti leggendolo dall' allegato 8. Se non presente torna -1
     * La data deve essere nel formato yyyy-mm-dd
     */
    public function getNumIndig($date) {

        $arr = $this->db->getRow("all8registercum" . REFAGEA, "numindig", array(
            array("where", "date", ">=", $date . " 00:00:00"),
            array("and", "date", "<=", $date . " 23:59:59")
                ), array(
            array("orderby", "id_insert"),
            array("order", "desc")
        ));
        if (count($arr) && is_numeric($arr["numindig"]))
            return $arr["numindig"];
        return -1;
    }

    private function convertData($data) {

        if (stripos($data, "/") > 0) {
            $arr = explode("/", $data);
            $str = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        } else {
            $arr = explode("-", $data);
            $str = $arr[2] . "/" . $arr[1] . "/" . $arr[0];
        }
        return $str;
    }

    /**
     * Testa che non sia già stata effettuata la distribuzione
     * @param return int  torna 1 se c'è già la distrib. In caso contrario 0 
     */
    public function checkDistrEff($idfamily, $date) {
        $date = $this->convertData($date);

        $datemin = $date . " 00:00:01";

        $datemax = $date . " 23:59:59";

        $arr = $this->person->getPersonsId($idfamily);

        $str_ids = implode(",", $arr);
        $str_query = "Select sheetId from blocksheet where dtime >= '" . $datemin . "' and dtime <= '" . $datemax . "' and personId in (" . $str_ids . ")";

        $arr = $this->db->freeQuery($str_query, "checkDistrEff");

        return count($arr);
    }

    /*
     * Rimuove da blocchetto e non da allegati
     */

    function removeBlockSheet($sheetId) {
        //verifico che lo sheedId esista e che sia l' ultimo inserito.
        
        $arr = $this->db->getRow("blocksheet", "MAX(sheetId)");
        
        //$this->logger->rawLog($sheetId);
        
        $arr = $this->db->getRow("blocksheet", "sheetId", array(), array(
            array("ORDERBY", "sheetId"),
            array("ORDER", "DESC"),
        )); 
        
        if (!count($arr) || $arr["sheetId"] != intval($sheetId))
            return 0;    //false sta ad indicare che la cancellazione non è stata effettuata.

        
 
//Procedo con la cancellazione:
        $this->db->delete("distributedproduct", array(
            array("where", "sheetId", "=", $sheetId, true)
        ));

        $this->db->delete("distributedproductbanco", array(
            array("where", "sheetId", "=", $sheetId, true)
        ));

        $this->db->delete("blocksheet", array(
            array("where", "sheetId", "=", $sheetId, true)
        ));
        // Indico nella tabella internal config che uno sheetId è stato rimosso. Quindi la nuova distribuzione dovrà acquisire questo sheetId
        config::setConfig("lastsheetidisdeleted", 1, "ultimo sheetid (blocchetto consegne) inserito è stato cancellato?", "internalconfig");
        config::setConfig("lastsheetid", $sheetId, "ultimo sheetid (blocchetto consegne) inserito", "internalconfig");

        $res = $this->db->getRow("blocksheet", "Max(sheetId)");

        config::setConfig("lastinsertedsheetid", $res["Max(sheetId)"], "ultimo sheetid presente", "internalconfig");

        return 1;
    }

    public function getFamilyIdFromBlocksheet($id_blocksheet) {
        //Effettuo 2 query separate. Se la prima non da risultati significa ad esempio che la pagina blocchetto è stata eliminata.
        $arr_pid = $this->db->getRow("blocksheet", "personId", array(
            array("where", "sheetId", "=", $id_blocksheet, true)
        ));
        if (count($arr_pid) == 0)
            return 0;
        $arr_fid = $this->db->getRow("person", "family_register_number", array(
            array("where", "id_person", "=", $arr_pid["personId"], true)
        ));
        return $arr_fid["family_register_number"];
    }

    public function getForPdfSheet($sheetid) {
        $result = array();
        $query1 = "SELECT dtime, num_indig, family_register_number, surname, name FROM " . $this->bsheet_table .
                " JOIN person ON " . $this->bsheet_table . ".personId=person.id_person where sheetId=" . $sheetid . " LIMIT 1";

        $arr_res_1 = $this->db->freeQuery($query1, "getForPdfSheet 1");

        if (count($arr_res_1) == 0)
            return $result;



        //Prelevo indirizzo di residenza

        $id_fam = $arr_res_1[0]["family_register_number"];

        $query2 = "SELECT address, nomeComune, provincia FROM family JOIN comuni ON family.district_id=comuni.idComune WHERE family_register_number=" . $id_fam . " LIMIT 1";

        $arr_res_2 = $this->db->freeQuery($query2, "getForPdfSheet 2");



        //Adesso prelevo i prodotti distribuiti Agea

        $query3 = "SELECT qty, name_product, measureunity FROM distributedproduct" .
                " JOIN product ON " .
                "distributedproduct.id_product=" . "product" . ".id_product" .
                " WHERE sheetId=" . $sheetid;

        $arr_res_3 = $this->db->freeQuery($query3, "getForPdfSheet 3");



        //Adesso prelevo i prodotti distribuiti Banco

        $query4 = "SELECT qty, name_product, measureunity FROM distributedproductbanco" . 
                " JOIN productbancoalim ON " .
                "distributedproductbanco.id_product=" . "productbancoalim.id_product" .
                " WHERE sheetId=" . $sheetid;

        $arr_res_4 = $this->db->freeQuery($query4, "getForPdfSheet 4");


        //Preparo array out

        $result = array(
            "date" => $this->convertData(substr($arr_res_1[0]["dtime"], 0, 10)),
            "num_indig" => $arr_res_1[0]["num_indig"],
            "id_fam" => $arr_res_1[0]["family_register_number"],
            "surname" => $arr_res_1[0]["surname"],
            "name" => $arr_res_1[0]["name"],
            "sheetid" => intval($sheetid) + intval(config::getConfig("start_index_blocksheet", "allegaticonfig" . REFAGEA)) - 1,
            "addr" => $arr_res_2[0]["address"],
            "com" => $arr_res_2[0]["nomeComune"],
            "prov" => $arr_res_2[0]["provincia"],
            "agea" => $arr_res_3,
            "banco" => $arr_res_4,
            "comit" => config::getConfig("sedecriabbr", "allegaticonfig" . REFAGEA)
        );


        secur::stripSlashes($result);

        return $result;
    }

    public function getComuneFromPersonId($PersonId) {
        $res = $this->db->getRow(array("person", "family"), array("district_id"), array(
            array("on", "person.family_register_number", "=", "family.family_register_number", true),
            array("where", "person.id_person", "=", intval($PersonId), true)
        ));
        if (count($res))
            return $res["district_id"];
        return "0";
    }

    public function getComunebyId($idComune) {

        secur::addSlashes($idComune);
        if ($idComune == "")
            return "";

        $res = $this->db->getRow("comuni", "nomeComune", array(
            array("where", "idComune", "=", $idComune, true)
        ));
        return $res["nomeComune"];
    }

}

//class close
?>
