<?php

class Family extends DataBoundObject {

    protected $FamilyId = -1;
    protected $Address = "";
    //protected $District = "";
    protected $DistrictId = 0;
    protected $PhoneNumber = "";
    protected $StateInsc = 0;
    protected $NumIndig = 0;
    //completa, incompleta o cancellata
    protected $Note = "";
    protected $DateExpiryIsee = "0000-00-00";
    protected $db = NULL;

    public function __construct() {
        $db = new db();
        $this->db = $db;
        parent::__construct($db);
    }

    protected function DefineTableName() {
        $session = new session();
        return $session->getSessionVar("prefix") . "family";
    }

    protected function DefineKeyId() {
        return "family_register_number";
    }

     //le chiavi sono attributi della classe mentre i valori sono i nomi colonna db
    protected function DefineRelationMap() {
        return ( array("FamilyId" => "family_register_number", "Address" => "address", "DistrictId" => "district_id", "PhoneNumber" => "phonenumber", "StateInsc" => "state", "Note" => "note", "DateExpiryIsee" => "expirydate_isee", "StateInsc" => "state", "NumIndig" => "num_indig"));
    }

    public function Save() {

        $ret_id = 0;
        $row = array();
        $arr = $this->DefineRelationMap();
        $keyfamid = $arr["FamilyId"];
        //nome colonna tabella database relativa all' id famiglia
        unset($arr["FamilyId"]);
        //così non viene incl. nell' update (è una foreign key)

        foreach ($arr as $attr_class_fam => $db_column_name) {
            eval("\$row[\$db_column_name]=trim(\$this->\$attr_class_fam);");
        }
        eval("\$matches=array(\$keyfamid=>\$this->FamilyId);");

        eval("\$cond=array(array('where',\$keyfamid,'=',\$this->FamilyId));");
        if (intval($this->FamilyId) > 0) {//effettuo l' update
            $issue = $this->db->update($this->DefineTableName(), $row, $cond);
            if ($issue)
                $ret_id = $this->FamilyId;
        } else {//caso nuovo inserimento
            //print_r($row);
            $issue = $this->db->insert($this->DefineTableName(), $row);
            if ($issue)
                $ret_id = $this->db->getMaxId($this->DefineTableName(), $keyfamid);
        }
        return $ret_id;
    }

    public function getFamily($fid) {
        $arr = $this->db->getRow($this->DefineTableName(), "*", $cond = array(array("where", $this->DefineKeyId(), "=", $fid)));
        // print_r($arr);
    }

    public function getAddress($fid) {
        $arr = $this->db->getRow(array("family", "comuni"), array("address", "nomeComune", "provincia"), array(
            array("on", "idComune", "=", "district_id", true),
            array("where", "family_register_number", "=", $fid, true)
        ));
        if (!count($arr)) {
            $arr = array("address" => "", "nomeComune" => "", "provincia" => "");
        }
        secur::stripSlashes($arr);
        return $arr;
    }

    public function getForTable($arr_options) {
        $session = new session();
       
        $result_array = $this->db->getRows(array("family", "person"), array("surname", "name", "born_date", "address", "district_id", "family.family_register_number", "family.state", "search_result"), $arr_options, false);

        for ($i = 0; $i < count($result_array); $i++) {
            $arr_temp = $this->db->getRow("comuni", "nomeComune", array(
                array("where", "idComune", "=", $result_array[$i]["district_id"], true)
            ));
            //Rimpiazzo l' idComune col nome del comune per visualizzarlo sulla datatable
            if (count($arr_temp)) {
                $result_array[$i]["district_id"] = $arr_temp["nomeComune"];
            } else {
                $result_array[$i]["district_id"] = "";
            }
            
            //MODIFICO IL FORMATO DATA PER VIS: SU DATATABLE
            $result_array[$i]["born_date"] = $this->dateConvert($result_array[$i]["born_date"]);
        }
        //print_r($result_array);

        secur::stripSlashes($result_array);
        return $result_array;
    }

// close public function getForTable()

    public function getForTable2($arr_options = array()) {
        $session = new session();
        $prefix = $session->getSessionVar('prefix');
        //$result_array = $this->db->getRows(array("family", "person"), array("surname", "name", "born_date", "fiscal_code", "district", "family.family_register_number"), $arr_options, false);
        $result_array = $this->db->getRows(array("person", "family"), array("surname", "name", "district_id", "born_date", "fiscal_code", "person.family_register_number", "sex"), $arr_options);
        $arr_return = array();
        for ($i = 0; $i < count($result_array); $i++) {
            $arr_temp = array();
            
            //Strategia per determinare il sesso
            $sex = "-";  //valore di default
            
            if($result_array[$i]["fiscal_code"] !=""){
                $sex = $this->getSexByCf($result_array[$i]["fiscal_code"]);
            }else if($result_array[$i]["sex"]!='?'){
                $sex = $result_array[$i]["sex"];
            }
            
            
            foreach ($result_array[$i] as $key => $value) {
                $value = stripslashes($value);
                switch ($key) {
                    case "born_date":
                        $arr_temp[] = $this->dateConvert($value);
                        $value = $this->getEta($value);
                        break;
                    case "fiscal_code":
                        $value = $sex;
                        break;
                    default: break;
                }//close switch
                $arr_temp[] = $value;
            }
            $arr_return[] = $arr_temp;
        }

        return $arr_return;
    }

// close public function getForTable2($arr_options=array())


    public function dateConvert($date) {

        $isdbformat = false;

        if (strpos($date, "-") != 0) {
            $isdbformat = true;
            $arr_date = explode("-", $date);
        }
        else
            $arr_date = explode("/", $date);

        $arr_date = array_reverse($arr_date);

        if ($isdbformat)
            $date = implode("/", $arr_date);
        else
            $date = implode("-", $arr_date);

        if ($date == "00/00/0000")
            $date = "";
        return $date;
    }

    public function getSexByCf($cf) {

        if ($cf == "")
            return "-";
        if (substr($cf, 9, 2) > 40)
            return "F";
        return "M";
    }

    public function getEta($borndate) {                //    yyyy-mm-dd
        if ($borndate == "0000-00-00")
            return "-";
        $currentTime = date("Y-m-d");

        $arrdate = explode("-", $currentTime);

        $curr_day = $arrdate[2];
        $curr_month = $arrdate[1];
        $curr_year = $arrdate[0];

        $arr_borndate = explode("-", $borndate);

        $age = intval($curr_year, 10) - intval($arr_borndate[0], 10);
        if ($curr_month < ($arr_borndate[1]))
            $age--;
        if ((($arr_borndate[1]) == $curr_month) && ($curr_day < $arr_borndate[2]))
            $age--;

        if ($age == 0)
            $age = "-1";

        return $age;
    }

}

//close class
?>