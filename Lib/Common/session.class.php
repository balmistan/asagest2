<?php 
require_once 'db.class.php';
/**
 * Session class to provide user authentication
 *
 * This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
 * @author Carmelo San Giovanni <admin@barmes.org>
 * @version 2.2
 * @copyright Copyright (c) 2009 Carmelo San Giovanni
 * @package libss
 */
 
class session {
    /**
     * @access private
     * @var reference
     */
    private $dbstore;
    /**
     * @access private
     * @var string
     */
    private $sesstable = null;
    /**
     * @access private
     * @var string
     */
    private $tsid = null;
    /**
     * @access private
     * @var integer
     */
    private $userId;
    /**
     * @access private
     * @var integer
     */
    private $timeout;
    
    /**
     * Costruttore di classe sessione
     * @access public
     * @param int $timeout contiene il numero di minuti di timeout, se non settato viene impostato di default a 10
     */
     
    public function __construct($timeout = 180) {
        global $opt; //prendiamo le vars di configurazione dal config.php (deve essere incluso nei file che istanziano l'oggetto)
        $this->timeout = $timeout;
        ob_start();
        $this->sesstable = $opt['mysql']['sessiontable']; //memorizziamo il nome della tabella che conterrà i dati delle sessioni in un attributo
        if (trim($this->sesstable) == "")
            die('SESSION::CONSTRUCT --> tablename empty...');
        $issue=$this->dbstore=new db(); //ci agganciamo al database
        if(!$issue) die(db::getLastError());	
        @session_start();
        if (!isset($_SESSION['tsid'])) {
        	//se non c'è nessuna sessione ne carichiamo una nuova e scriviamo i dati di inizializzazione
            $this->tsid = $this->makeTsid();
            $this->userId = -1;
            $this->dbstore->insert($this->sesstable, array('tsid'=>$this->tsid, 'enterDate'=>date("Y-m-d H:i:s"), 'lastDate'=>date("Y-m-d H:i:s"), 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'user_agent'=>$_SERVER['HTTP_USER_AGENT'], 'timeout'=>$timeout, 'active'=>1));
            $this->setTsid(); //memorizziamo l'id protetto della sessione all'interno della sessione per prenderlo dalle altre pagine
        } else {
            //se c'era già una sessione attiva ci agganciamo ad essa e recuperiamo tutti i dati dal db
        	$this->loadSession($_SESSION['tsid']);
            
        }
  //      ob_end_flush();
    }
    
    /**
     * Funzione che si occupa di chiudere una sessione attiva
     * @access public
     * @param string $motivation[optional] contiene il motivo di chiusura della sessione
     */
    public function close($motivation = "logout") {
        $this->dbstore->update($this->sesstable, array('lastDate'=>date("Y-m-d H:i:s"), 'lastOp'=>$motivation, 'active'=>0), array(array("WHERE","tsid", "=", $this->tsid)));
        $this->removeTsid();
        //echo "Db updated and tsid removed...<br>";
    }
    
    /**
     * Funzione per la creazione di un token per la sessione che sia impossibile da indovinare
     * @access private
     * @return string $token, è l'id univoco generato in maniera del tutto random
     */
    private function makeTsid() {
        $token = md5(uniqid(mt_rand(), true));
        return $token;
    }
    /**
     * Funzione per la cancellazione di un token di sessione
     * @access private
     */
    private function removeTsid() {
        unset($_SESSION['tsid']);
    }
    
    /**
     * Restituisce l'attuale token
     * @access public
     * @return string $this->tsid, è l'id univoco generato e memorizzato all'interno dell'oggetto
     */
    public function getTsid() {
        return $this->tsid;
    }
    
    /**
     * Memorizza un token precedentemente generato all'interno della sessione e memorizza anche data ed ora
     * del momento in cui lo fa per un successivo confronto
     * @access public
     */
    public function setTsid() {
        $_SESSION['tsid'] = $this->tsid;
        $_SESSION['time'] = time();
    }
    
    /**
     * Ricrea un oggetto sessione prelevando dal db tutte le info necessarie ed usando il token per referenziarlo.
     * Se la sessione è scaduta o è marcata come inattiva la distrugge e la reinizializza.
     * In caso contrario aggiorna la data di ultimo accesso in modo da rimandare il timeout.
     * @access private
     */
    private function loadSession($tsid) {
        $this->tsid = $_SESSION['tsid'];
        if (!$this->dbstore->isPresent($this->sesstable, array('tsid'=>$this->tsid))) {
            $this->removeTsid();
            $this->__construct();
        } else {
            $sessions = $this->dbstore->getRow($this->sesstable, array('userId', 'active', 'timeout', 'enterDate', 'lastDate'), array(array("WHERE","tsid", "=", $tsid)));
          
		    if (($sessions['active'] != 1) || ((($sessions['timeout'] * 60) + strtotime($sessions['lastDate'])) < strtotime(date("Y-m-d H:i:s")))) {
                $this->close("timeout");
                $this->__construct($this->timeout);
            } else {
                //se invece esiste una sessione che è attiva e non scaduta arriviamo qui
                $this->userId = $sessions['userId'];
                $prefix= (isset($_SESSION['prefix'])) ? $_SESSION['prefix'] : "";
                $this->dbstore->update($this->sesstable, array('location'=>$prefix, 'lastDate'=>date("Y-m-d H:i:s")), array(array("WHERE","tsid", "=", $this->tsid)));
            }
        }
    }

    
    /**
     * Setta l'username per una sessione utente, questa funzione provvede ad associare univocamene
     * una sessione al suo rispettivo proprietario scrivendo anche il suo username nel database.
     * Provvede inoltre ad aggiornare la data di ultimo accesso ed a settare come ultima operazione effettuata
     * quella di login.
     * @access public
     */
    public function setUserId($userId) {
        //global $dbstore;
        $this->userId = $userId;
        $this->dbstore->update($this->sesstable, array('userId'=>$userId, 'lastDate'=>date("Y-m-d H:i:s"), 'lastOp'=>'login'), array(array("WHERE","tsid", "=", $this->tsid)));
    }
    
    /**
     * Restituisce l'user-id del proprietario della sessione
     * @access public
     */
    public function getUserId() {
        return $this->userId;
    }
    
    public static function getUserFromTsid($tsid,$debug=false){
    	global $opt;
    	$sessiontable = $opt['mysql']['sessiontable'];	
    	if (trim($sessiontable)=="") die('SESSION::getUserFromTsid --> tablename empty...'); 
    	$db=new db();
    	if ($db->isPresent($sessiontable,array('tsid'=>$tsid),$debug)){
    	  $result=$db->getRow($sessiontable,"userId",array(array("WHERE", "tsid","=",$tsid)));
    	  return $result['userId'];
    	} else {
    		return -1;
    	}
    }
    
    public function setSessionVar($varname, $value){
        $_SESSION[$varname] = $value;
    }
    
    public function getSessionVar($varname, $returnifnotisset=""){
        if (isset($_SESSION[$varname])) return $_SESSION[$varname];
        return $returnifnotisset;
    }
    
}
?>
