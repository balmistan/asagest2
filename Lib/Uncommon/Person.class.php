<?php

class Person extends DataBoundObject {

    protected $IdPerson;
    protected $Position;
    protected $FamilyId;
    protected $Surname = "";
    protected $Name = "";
    protected $Cf = "";
    protected $BornDate = "0000-00-00";
    protected $IncludeInResultSearch = "off";
    protected $Title = "nad";
    protected $ImageLink = "";

    public function __construct() {
        $db = new db();
        $this->db = $db;
        parent::__construct($db);
    }

    protected function DefineTableName() {
        $session = new session();
        return $session->getSessionVar("prefix") . "person";
    }

    protected function DefineKeyId() {
        return "id_person";
    }

    protected function DefineRelationMap() {     //class ->db
        return(array(
            "IdPerson" => "id_person",
            "Position" => "vPosition",
            "ImageLink" => "imagelink",
            "FamilyId" => "family_register_number",
            "Surname" => "surname",
            "Name" => "name",
            "Cf" => "fiscal_code",
            "BornDate" => "born_date",
            "IncludeInResultSearch" => "search_result")
                );
    }

    public function Save() {
        $ret_val = 0;
        $row = array();
        $arr_map = $this->DefineRelationMap();
        foreach ($arr_map as $k => $v) {
            eval("\$row[\$v]=trim(\$this->\$k);");
        }
        unset($row[$arr_map["IdPerson"]]);

        if ($this->IdPerson != "")
            $issue = $this->db->update($this->DefineTableName(), $row, array(
                array("where", $arr_map["IdPerson"], "=", $this->IdPerson, true)
                    ));
        else {

            $issue = $this->db->insert($this->DefineTableName(), $row);
        }
        if ($issue)
            $ret_val = $this->FamilyId;

        return $ret_val;
    }

    public function Delete($idf) {

        $arr = $this->DefineRelationMap();
        $keyfamid = $arr["FamilyId"];
        eval("\$cond=array(array('where', \$keyfamid, '=', \$idf));");
        $this->db->delete($this->DefineTableName(), $cond);
    }

    /*
     * Restituisce 0 se il cf non è presente mentre restituisce l' id famiglia se il cf è gia presente. L' id famiglia è quello della scheda in cui è stato individuato il cf.
     */

    public function testIfCfIsPresent($cf, $idfamily) {

        $arr = $this->DefineRelationMap();
        $res = $this->db->getRow($this->DefineTableName(), array($arr["FamilyId"]), array(
            array("WHERE", $arr["Cf"], "=", $cf),
            array("AND", $arr["FamilyId"], "!=", $idfamily)
                ));
        if (count($res))
            return $res[$arr["FamilyId"]];       //se l' array restituito non è vuoto

        return "0";
    }

    public function getEtaAndSexByFamilyId($family_id) {
        $arr = $this->DefineRelationMap();
        $res = $this->db->getRows($this->DefineTableName(), array($arr["Surname"], $arr["Name"], $arr["Cf"], $arr["BornDate"]), array(
            array("WHERE", $arr["FamilyId"], "!=", $idfamily)
                ));
    }

    public function getPersonsId($idf) {
        $arr = $this->DefineRelationMap();

        $res = $this->db->getRows($this->DefineTableName(), $arr["IdPerson"], array(
            array("where", $arr["FamilyId"], "=", $idf, true)
                ));
        $res2 = array();
        foreach ($res as $arrval) {
            $res2[] = $arrval[$arr["IdPerson"]];
        }
        return $res2;
    }

    public function deletePerson($person_id) {
        $arr = $this->DefineRelationMap();
        $this->db->delete($this->DefineTableName(), array(
            array("where", $arr["IdPerson"], "=", $person_id, true)
        ));
    }

    /**
     * Restituisce un array contenente i link alle immagini che iniziano con $prefix
     * @param type $prefix 
     */
    public function getPersonImage($prefix = "") {
        $arr = $this->DefineRelationMap();
        $arr_res = $this->db->getRows($this->DefineTableName(), $arr["ImageLink"], array(
            array("where", $arr['ImageLink'], "LIKE", $prefix . "%")
                ));

        $arrout = array();

        for ($i = 0; $i < count($arr_res); $i++) {
            $arrout[] = $arr_res[$i][$arr['ImageLink']];
        }

        return $arrout;
    }

    /**
     * Restituisce una stringa contenente cognome e nome
     * @param type int $person_id  è l' id della persona 
     */
    public function getPersonNameById($person_id) {
        $arr = $this->DefineRelationMap();
        $arr_res = $this->db->getRow($this->DefineTableName(), array($arr["Surname"], $arr["Name"]), array(
            array("where", $arr['IdPerson'], "=", $person_id, true)
                ));
        $str_out = $arr_res[$arr["Surname"]] . " " . $arr_res[$arr["Name"]];
        secur::stripSlashes($str_out);
        return $str_out;
    }
    
    /**
     *Restituisce il numero dei componenti di una famiglia conoscendo l' id famiglia
     * @param type $idf
     * @return type int
     */
    
    public function getNumPersons($fid){
        $arr = $this->DefineRelationMap();
          //ottengo l' idf 
        $arr_res = $this->db->getRows($this->DefineTableName(), $arr['FamilyId'], array(
            array("where", $arr['FamilyId'], "=", $fid, true)
        ));
        
        return count($arr_res);
    }
    
    
    public function getNumComponents($personId){
        //ottengo il familyId dal personId
        $arr_res = $this->db->getRow($this->DefineTableName(), "family_register_number", array(
            array("where", "id_person", "=", $personId, true)
        ));
        $familyId=$arr_res['family_register_number'];
        
        //ottengo il numero di persone con questo familyId
        $arr_res = $this->db->getRows($this->DefineTableName(), "id_person", array(
            array("where", "family_register_number", "=", $familyId, true)
        ));
        
        return count($arr_res);
        
    }
    
    

    public function getIdFamily($person_id) {
        $arr = $this->DefineRelationMap();
        $arr_res = $this->db->getRows($this->DefineTableName(), array($arr["FamilyId"]), array(
            array("where", $arr["IdPerson"], "=", $person_id, true)
        ));
        $str_out = $arr_res[0][$arr["FamilyId"]];
        secur::stripSlashes($str_out);
        return $str_out; 
    }
    
    
    
    
    

}

//class close
?>