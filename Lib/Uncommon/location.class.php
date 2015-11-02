<?php

class location {

    private $db;
    private $UserAgent;
    private $IP;
    private $LocationTable;
    private $UserAgentIsInTable;
    
    public function __construct() {
        $this->db = new db();
        $this->UserAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->IP=$_SERVER['REMOTE_ADDR'];  //informazione in più
        $this->LocationTable = "location";
        $this->UserAgentIsInTable=$this->db->isPresent($this->LocationTable, array("useragent" => $this->UserAgent));
    }

    public function setLocation($loc) {
        if ($this->UserAgentIsInTable) {
            $this->db->update($this->LocationTable, array("location" => $loc, "ip"=>$this->IP), array(
                array("where", "useragent", "=", $this->UserAgent)
            ));
        }else{
            $this->db->insert($this->LocationTable, array("useragent" => $this->UserAgent, "location" => $loc));
        }
    }

    public function getLocation() {
        if ($this->UserAgentIsInTable) {
            $arr_loc=$this->db->getRow($this->LocationTable, "location", array(
                array("where", "useragent", "=", $this->UserAgent)
            ));
            return $arr_loc['useragent'];
        }
        return "";     //è il caso in cui l' useragent non è in tabella
    }

}

//close class
?>