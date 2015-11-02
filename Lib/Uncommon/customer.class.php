<?php

/**
 * form class
 * this class can make a form
 * @author NetMDM <1@3bsd.net>
 * @version 5.9
 * @package libss
 */
class customer {

    private $FamilyObj;    //oggetto della classe family
    private $PersonObj;    //oggetto della classe person
    private $FamilyId;
    private $db;
    private $FAssocFormClassFamilyDb = array(
        array("id", "FamilyId", "family_register_number"),
        array("address", "Address", "address"),
        //array("district", "District", "district"),
        array("district_id", "DistrictId", "district_id"),
        array("telephone", "PhoneNumber", "phonenumber"),
        array("note", "Note", "note"),
        array("expireisee", "DateExpiryIsee", "expirydate_isee"),
        array("statoscheda", "StateInsc", "state"),
        array("num_indig", "NumIndig", "num_indig")
    );
    //
    private $PAssocFormClassPersonDb = array(
        array("person_id", "IdPerson", "id_person"),
        array("imagelink", "ImageLink", "imagelink"),
        array("position", "Position", "vPosition"),
        array("id", "FamilyId", "family_register_number"),
        array("lastName", "Surname", "surname"),
        array("firstName", "Name", "name"),
        array("cf", "Cf", "fiscal_code"),
        array("borndate", "BornDate", "born_date"),
        array("rr", "IncludeInResultSearch", "search_result")
    );
    private $FAssocFormFamilyClass = array();
    private $FAssocFamilyClassDb = array();
    private $FAssocFormDb = array();
    private $FAssocDbForm = array();
    private $PAssocFormPersonClass = array();
    private $PAssocPersonClassDb = array();
    private $PAssocDbForm = array();
    private $PAssocFormDb = array();
    private $PPersonClassForm = array();

    private function init_array() {
        for ($i = 0; $i < count($this->FAssocFormClassFamilyDb); $i++) {
            $this->FAssocFormFamilyClass[$this->FAssocFormClassFamilyDb[$i][0]] = $this->FAssocFormClassFamilyDb[$i][1];
            $this->FAssocFamilyClassDb[$this->FAssocFormClassFamilyDb[$i][1]] = $this->FAssocFormClassFamilyDb[$i][2];
            $FAssocFormDb[$this->FAssocFormClassFamilyDb[$i][0]] = $this->FAssocFormClassFamilyDb[$i][2];
            $this->FAssocDbForm[$this->FAssocFormClassFamilyDb[$i][2]] = $this->FAssocFormClassFamilyDb[$i][0];
        }

        for ($i = 0; $i < count($this->PAssocFormClassPersonDb); $i++) {
            $this->PAssocFormPersonClass[$this->PAssocFormClassPersonDb[$i][0]] = $this->PAssocFormClassPersonDb[$i][1];
            $this->PAssocPersonClassDb[$this->PAssocFormClassPersonDb[$i][1]] = $this->PAssocFormClassPersonDb[$i][2];
            $this->PAssocDbForm[$this->PAssocFormClassPersonDb[$i][2]] = $this->PAssocFormClassPersonDb[$i][0];
            $this->PAssocFormDb[$this->PAssocFormClassPersonDb[$i][0]] = $this->PAssocFormClassPersonDb[$i][2];
            $this->PPersonClassForm[$this->PAssocFormClassPersonDb[$i][1]] = $this->PAssocFormClassPersonDb[$i][0];
        }
        //print_r($this->AssocFormFamilyClass);	
    }

    public function __construct() {
        $this->init_array();
        $this->FamilyObj = new Family();
        $this->PersonObj = new Person();
        $this->FamilyId = $this->FamilyObj->getLastId();
        if ($this->FamilyId == "")
            $this->FamilyId = 0;        //se la tabella family è ancora vuota assegno 0 a FamilyId
        $this->db = new db();
    }

    /*
     * Salva il contenuto dell' array $_POST
     * @access public
     * @param array $_POST contenente anche l' id famiglia. Sarà un campo vuoto se si tratta di una nuova iscrizione; riempito nel caso di un update.
     * @return int id famiglia
     */

    public function saveFromPost($arr_post) {
        /*
        echo "<br /><br /><br />";
          print_r($arr_post);
          echo "<br /><br /><br />"; 
  */
        secur::addSlashes($arr_post);
        
        $arr_post['num_indig'] = count($arr_post['person_id']); //Aggiungo il numero di indigenti all' array
         

        foreach ($this->FAssocFormFamilyClass as $key => $value) {

            eval("\$this->FamilyObj->set" . $value . "(\$arr_post[\$key]);");
        }
        //  echo "<br />" . $this->FamilyObj->getPhoneNumber();
        
        //$num_person = count($arr_post['person_id']);

        $idf = $this->FamilyObj->Save();
        if ($idf != 0) {  //è 0 in caso di errore salvataggio in classe family
            //Ottengo l' array con gli id delle persone presenti e cancello quelli che non hanno corrispondenza con gli IDP ricevuti in POST
            $arr_persons_id = $this->PersonObj->getPersonsId($idf);   //restituisce un array vuoto se la scheda non è ancora registrata
            if (count($arr_persons_id))
                foreach ($arr_persons_id as $person_id) {
                    if (!in_array($person_id, $arr_post[$this->PPersonClassForm["IdPerson"]]))
                        $this->PersonObj->deletePerson($person_id);
                }

            //print_r($arr_post[$this->PPersonClassForm["IdPerson"]]);
            //echo "<br /><br /><br />";
            //$this->PersonObj->Delete($idf);

            for ($i = 0; $i < count($arr_post["lastName"]); $i++) {
                eval("\$this->PersonObj->setPosition(\$i);");
                foreach ($this->PAssocFormPersonClass as $k => $v) {
                    if (isset($arr_post[$k][$i])) {
                        eval("\$this->PersonObj->set" . $v . "(\$arr_post[\$k][\$i]);");
                    }//close if(isset($arr_post[$k][$i]))
                }//close foreach
                $this->PersonObj->setFamilyId($idf);
                //echo $this->PersonObj->getFamilyId(); 
                $idf = $this->PersonObj->Save();
            }//close if($idf!=0)
        }//close for
        return $idf;           //restituisce il family id con cui ha salvato. Torna 0 in caso di errore	 
    }

//saveFromPost close

    public function getFromDb($fid = "") {   //se non viene passato l' id famiglia il metodo tornerà un array con i campi vuoti (ma le key presenti)
        $arr = array();
        $arr2 = array();
        $session = new session();
        foreach ($this->FAssocFormFamilyClass as $key => $value)
            $arr[$key] = "";

        if ($fid != "") {
            $arr_temp = $this->db->getRow("family", "*", array(
                array("where", $this->FAssocFamilyClassDb["FamilyId"], "=", $fid)
                    ));
            foreach ($arr_temp as $key => $value)
                if (array_key_exists($key, $this->FAssocDbForm)) {
                    $arr[$this->FAssocDbForm[$key]] = $value;
                }

            $arr_temp = $this->db->getRows("person", "*", array(
                array("where", $this->PAssocPersonClassDb["FamilyId"], "=", $fid)
                    ), array(
                array("orderby", $this->PAssocPersonClassDb["Position"])
                    ));

            $arr_keys = array_keys($this->PAssocFormDb);

            foreach ($arr_keys as $key => $value) {
                $arr[$value] = array();
            }

            for ($i = 0; $i < count($arr_temp); $i++) {
                foreach ($arr_keys as $key => $value) {
                    array_push($arr[$value], $arr_temp[$i][$this->PAssocFormDb[$value]]);
                }
            }//close for 
        }//close if($fid!="")

        secur::stripSlashes($arr);

        return $arr;
    }

//getFromDb close

    public function getLastInsertId() {
        $session = new session();
        $id = $this->db->getMaxId($session->getSessionVar('prefix') . "family", $this->FAssocFamilyClassDb["FamilyId"]);
        if ($id > 0)
            return $id;              //se la tabella db è vuota setto il valore 0 in modo da visualizzare poi scheda n° 1 
        return 0;
    }

//converte una data contenuta in una stringa o un intero array		
    public function date_convert($data) {
        if (!is_array($data))
            $data = $this->singleStringDataConvert($data);

        else {//se si tratta di un array di date
            foreach ($data as $key => $value) {
                $data[$key] = $this->singleStringDataConvert($value);
            }
        }//close else

        return $data;
    }

    private function singleStringDataConvert($data) {

        $arr = array();
        if ($data == "0000-00-00")
            return "";

        else if (strripos($data, "/")) {
            $arr = explode("/", $data);
            $arr = array_reverse($arr);
            $data = implode("-", $arr);
        } else {
            $arr = explode("-", $data);
            $arr = array_reverse($arr);
            $data = implode("/", $arr);
        }
        return $data;
    }

//close private function singleStringDataConvert($data)
    
    
    public function getInfoFamily($pid){
        $arr1 = $this->db->getRow(array("family", "person"), array(
            "family.family_register_number",
            "address",
            "district_id",
            "num_indig",
            "surname",
            "name"
            ), array(
                array("on", "family.family_register_number", "=", "person.family_register_number", true),
                array("where", "id_person", "=", $pid, true)
            ));
        
        if(count($arr1) && $arr1["district_id"]!=0){
            $arr2 = $this->db->getRow("comuni", array("nomeComune","provincia"), array(
                array("where", "idComune", "=", $arr1["district_id"], true)
            ));
            unset($arr1["district_id"]);
            $arr1["nomeComune"] = $arr2["nomeComune"];
            $arr1["provincia"] = $arr2["provincia"];
        }
        return $arr1;
    }


}

//class close 
?>
	
