<?php

/**
 * Group class to provide group memorization
 *
 * This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
 * @author Carmelo San Giovanni <admin@barmes.org>
 * @version 2.9
 * @copyright Copyright (c) 2009 Carmelo San Giovanni
 * @package libss
 */
class group {

    /**
     * @access private
     * @var reference 
     */
    private $dbStore;

    /**
     * @access private
     * @var string 
     */
    private $groupTable;

    /**
     * @access private
     * @var string 
     */
    private $groupUsersTable;

    /**
     * @access private
     * @var integer 
     */
    private $groupName;

    /**
     * @access private
     * @var integer $active contiene lo stato del gruppo
     */
    private $active;

    /**
     * @access private
     * @var array $content, contiene i dati del gruppo corrente
     */
    private $content;

    /**
     * @access private
     * @var integer $debug, è la flag per attivare/disattivare il debugging
     */
    private $debug;

    /**
     * Costruttore di classe User, crea una connessione al database degli utenti
     * @access public
     * @param int $active[optional] contiene lo stato iniziale dell'utente, di default è inattivo
     */
    public function __construct($debug = 0) {
        global $opt;
        $this->debug = $debug;
        $session=new session();
        $this->groupTable = $session->getSessionVar('prefix').$opt['mysql']['grouptable'];
        $this->groupUsersTable = $session->getSessionVar('prefix').$opt['mysql']['groupuserstable'];

        $issue = $this->dbStore = new db(); //ci agganciamo al database
        if (!$issue)
            die(db::getLastError());
    }

    /**
     * Crea un utente
     * @param boolena $active [optional], permette di specificare se l'utente è già attivo nel sistema
     */
    function create($groupname, $active = false) {
        $this->active = $active;
        $this->groupName = $groupname;

        if (!group::isGroupExist($groupname)) {
            $this->dbStore->insert($this->groupTable, array('groupName' => $groupname, 'active' => $active), $this->debug);
        }
    }

    /* public function __call( $method, $args ){
      print_r($this->content);
      //return $this->{'content'}[strtolower($method)];
      echo $method;
      } */

    /**
     * Funzione speciale per prelevare un qualunque campo dall'array dati dell'oggetto
     * @param object $id, contiene l'id dell'utente di cui si vuole ricevere l'attributo
     */
    public function __get($id) {
        if ($this->debug != 0)
            print ("GROUP:Getting $id\n<br>");
        return $this->{'content'}[$id];
    }

    /**
     * Funzione speciale per settare un qualunque campo dall'array dati dell'oggetto
     * @param object $id, contiene l'id del gruppo di cui si vuole settare l'attributo
     */
    public function __set($id, $value) {
        if ($this->debug != 0)
            printf("GROUP::setting $value to $id.\n<br>");
        $this->{'content'}[$id] = $value;
    }

    /**
     * Carica un gruppo dal database
     * @param object $groupname, contiene il nome del gruppo a cui si vuole accedere
     */
    public function loadGroup($groupname) {
        $this->dbStore=new db();
        $this->content = $this->dbStore->getRow($this->groupTable, "*", array(array("WHERE", "groupName", "=", $groupname)));	
        if (count($this->content)>0) 
        		$this->groupName=$this->content['groupName'];
    }

    /**
     * Aggiunge un utente al gruppo corrente
     * @param string $username è l'username dell'utente da aggiungere al gruppo
     * @return $success, true se è riuscito ad aggiungerlo, false altrimenti
     */
    public function addUser($username) {
        if ((user::isUserExist($username)) && (!$this->isUserPresent($username, $this->debug))) {
            $this->dbStore->insert($this->groupUsersTable, array("groupName" => $this->groupName, "username" => $username), $this->debug);
            if ($this->debug != 0)
                printf("GROUP::addUser: inserted $username into $this->groupName");
            return true;
        }
        if ($this->debug != 0) {
            if (!user::isUserExist($username))
                printf("GROUP:addUser: $username does not exist" . CRLF);
            if ($this->isUserPresent($username, $debug))
                printf("GROUP:addUser: $username already in group " . $this->groupName . CRLF);
        }
        return false;
    }

    /**
     * Cancella un utente dal gruppo corrente
     * @param string $username è l'username dell'utente da rimuovere dal gruppo
     * @return $success, true se è riuscito ad aggiungerlo, false altrimenti
     */
    public function delUser($username) {
        if ((user::isUserExist($username)) && ($this->isUserPresent($username))) {
            $this->dbStore->delete($this->groupUsersTable, array(array("WHERE", "groupName", "=", $this->groupName),
                array("AND", "username", "=", $username)), $this->debug);
            if ($this->debug != 0)
                printf("GROUP::delUser: deleted $username from $this->groupName");
            return true;
        }
        if ($this->debug != 0)
            printf("GROUP:delUser: Cannot delete $username from $this->groupName");
        return false;
    }

    /**
     * Cancella un utente da tutti i gruppi
     * @param string $username è l'username dell'utente da rimuovere dai gruppi
     * @return $success, true se è riuscito ad aggiungerlo, false altrimenti
     */
    public static function delUserFromAllGroups($username, $debug = 0) {
        if ((user::isUserExist($username))) {
            $db = new db();
            $session=new session();
            $groupuserstable = $session->getSessionVar('prefix').$opt['mysql']['groupuserstable'];
            $db->delete($groupuserstable, array(array("WHERE", "username", "=", $username)), $debug);
            if ($debug)
                printf("GROUP::delUserFromAll: deleted $username from all groups");
            return true;
        }
        if ($this->debug != 0)
            printf("GROUP:delUserFromAll: Cannot delete $username from $this->groupName");
        return false;
    }

    /**
     * Verifca l'esistenza di un gruppo
     * @param string $groupname, contiene il nome del gruppo da verificare
     * @return boolean $exist, true in caso affermativo, falso altrimenti
     */
    public static function isGroupExist($groupname) {
        global $opt;
        $session=new session();
        $grouptable = $session->getSessionVar('prefix').$opt['mysql']['grouptable'];
        if (trim($grouptable) == "")
            die('GROUP::isGroupExist --> tablename empty...');
        $db = new db();
        return $db->isPresent($grouptable, array('groupName' => $groupname));
    }

    /**
     * Funzione per effettuare la cancellazione di un gruppo
     * @param $groupname, contiene il nome del gruppo da cancellare, se non viene specificato cancellerà il gruppo corrente
     * @return $success, true se è riuscito a cancellare il gruppo
     */
    public function delete($groupname = "") {
        if (trim($groupname) == "")
            $groupname = $this->groupName;
        //eliminiamo prima le associazioni degli utenti al gruppo
        $this->dbStore->delete($this->groupUsersTable, array(array("WHERE", "groupName", "=", $groupname)));
        if ($this->debug != 0)
            printf("GROUP::delete: deleted " . $this->dbStore->affectedRows() . " users from group $groupname." . CRLF);
        //adesso eliminiamo il gruppo
        $this->dbStore->delete($this->groupTable, array(array("WHERE", "groupName", "=", $groupname)));
        if ($this->dbStore->affectedRows() > 0) {
            if ($this->debug != 0)
                printf("GROUP::delete: deleted $groupname" . CRLF);
            return true;
        } else {
            if ($this->debug != 0)
                printf("GROUP::delete: group $groupname does not exist" . CRLF);
            return false;
        }
    }

    /**
     * Funzione per la verifica della presenza di un utente nel gruppo attuale
     * @param $username, contiene il nome utente da controllare
     * @return $present, true se l'utente è presente nel gruppo, false altrimenti.
     */
    public function isUserPresent($username) {
        if ($this->dbStore->isPresent($this->groupUsersTable, array('groupName' => $this->groupName, 'username' => $username), $this->debug)) {
            if ($this->debug != 0)
                printf("GROUP::isUserPresent: association between " . $this->groupName . " and $username is already present" . CRLF);
            return true;
        } else {
            if ($this->debug != 0)
                printf("GROUP::isUserPresent: association between " . $this->groupName . " and $username not present" . CRLF);
            return false;
        }
    }

    /**
     * Restituisce tutti i gruppi a cui un utente appartiene
     * @param object $username
     * @param object $debug [optional]
     * @return array of groups
     */
    public static function groupsOf($username, $debug = false) {
        global $opt;
        $session=new session();
        $groupuserstable = $session->getSessionVar('prefix').$opt['mysql']['groupuserstable'];
        if (trim($groupuserstable) == "")
            die('GROUP::groupsOf --> tablename empty...');

        $db = new db();
        return $db->getRows($groupuserstable, "groupName", array(array("WHERE", "username", "=", $username)), $debug);
    }

    /**
     * Funzione statica per la verifica della presenza di un utente in un gruppo
     * @param string $username, contiene il nome utente da controllare
     * @param string $groupname, contiene il nome del gruppo in cui effettuare la ricerca
     * @parma boolena $debug, è la flag per attivare/disattivare il debugging 
     * @return $present, true se l'utente è presente nel gruppo, false altrimenti.
     */
    public static function isUserInGroup($username, $groupname, $debug = 0) {
        global $opt;
        $session=new session();
        $groupuserstable = $session->getSessionVar('prefix').$opt['mysql']['groupuserstable'];

        if (trim($groupuserstable) == "")
            die('GROUP::isGroupExist --> tablename empty...');
        $db = new db();
        if ($db->isPresent($groupuserstable, array('groupName' => $groupname, 'username' => $username), $debug))
            return true;
        return false;
    }

    /**
     * Restituisce un array di array di gruppi con all'interno tutti i gruppi presenti.
     * @return array(array("groupName"=>"utenti","groupDescription"=>"Utenti standard"),array(...)) 
     */
    public static function getGroups($onlynames = false) {
        global $opt;
        $session=new session();
        $grouptable = $session->getSessionVar('prefix').$opt['mysql']['grouptable'];
        $db = new db();
        if (!$onlynames) {
            return $db->getRows($grouptable, array("groupName", "groupDescription"));
        } else {
            $resultarray = $db->getRows($grouptable, "groupName");
            foreach ($resultarray as $row)
                $groups[] = $row['groupName'];
            return $groups;
        }
    }

    /**
     * Restituisce tutte gli attributi del gruppo attivo
     * @return array (associativo)
     */
    public function show() {
        return $this->content;
    }

    /**
     * Restituisce una lista di tutti gli utenti facenti parte del gruppo attivo
     * @return array
     */
    public function getUsers() {
        return $this->dbStore->getRows($this->groupUsersTable, "username", array(array("WHERE", "groupName", "=", $this->groupName)));
    }

    /**
     * Rende attivo un gruppo
     */
    public function setActive() {
        $this->dbStore->update($this->groupTable, array('active' => $active), array(array("WHERE", "groupName", "=", $this->groupName)), $this->debug);
    }

    /**
     * Aggiorna le informazioni sul gruppo
     * @param object $debug [optional], imposta la flag per il debugging.
     */
    public function Update() {
        if ($this->debug == 1)
            print_r($this->content);
        $this->dbStore->update($this->groupTable, $this->content, array(array("WHERE", "groupName", "=", $this->groupName)), $this->debug);
    }
    
    
    public function updateGroupsOf($username, $arr_groups){
     $db=new db();
    
        //cancello dal db, i gruppi ai quali l' utente appartiene
        $this->dbStore->delete($this->groupUsersTable, array(array("WHERE", "username", "=", $username)));    
          
        //assegno i nuovi gruppi:
        for($i=0; $i<count($arr_groups); $i++){
            $this->dbStore->insert($this->groupUsersTable, 
                    array("groupName" => $arr_groups[$i][0], "username" => $username), $this->debug);
        } 
           
        return;
    }

}

?>
