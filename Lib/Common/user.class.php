<?php
require_once 'db.class.php';
/**
* User class to provide user memorization
*
* This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
* @author Carmelo San Giovanni <admin@barmes.org>
* @version 3.2
* @copyright Copyright (c) 2009 Carmelo San Giovanni
* @package libss
*/

class user {
	/**
	 * @access private
	 * @var reference 
	 */
	private $dbstore;
	/**
	 * @access private
	 * @var string 
	 */
	private $usertable;
	
	/**
	 * @access private
	 * @var string 
	 */
	private $userinfotable;
	/**
	 * @access private
	 * @var integer 
	 */
	
	private $userId;
	
	private $username;
	/**
	 * @access private
	 * @var integer $active contiene lo stato dell'utente
	 */
	private $active;
	/**
	 * @access private
	 * @var array $contentLight, contiene i dati base dell'utente corrente
	 */
	private $contentLight;
	
	/**
	 * @access private
	 * @var array $contentFull, contiene l'anagrafica dell'utente corrente
	 */
	private $contentFull;
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
	
	public function __construct($debug=0) {
		$this->debug=$debug;
		global $opt; //prendiamo le vars di configurazione dal config.php (deve essere incluso nei file che istanziano l'oggetto)
		$session=new session();
                $this->usertable = $session->getSessionVar('prefix').$opt['mysql']['usertable']; //memorizziamo il nome della tabella con gli attributi base dell'utente
		$this->userinfotable= $session->getSessionVar('prefix').$opt['mysql']['userinfotable']; //memorizziamo il nome della tabella con le info dettagliate dell'utente
		if ((trim($this->usertable)=="") || (trim($this->userinfotable)==""))  die('USER::CONSTRUCT --> tablename empty...'); 
		$issue=$this->dbstore=new db(); //ci agganciamo al database
                if(!$issue) die(db::getLastError());
	     }
	
	/**
	 * Crea un utente
	 * @param boolena $active [optional], permette di specificare se l'utente è già attivo nel sistema
	 */
	function create($username,$active=false){
		$this->active=$active;
		if (! $this->dbstore->isPresent($this->usertable,array('username'=>$username))){
		  $this->contentLight=array('username'=>$username);
		  
		  if (!$this->active){
		     $this->contentFull=array('regDate'=>date("Y-m-d H:i:s"),'reg_ip_address'=>$_SERVER['REMOTE_ADDR'],'user_agent'=>$_SERVER['HTTP_USER_AGENT']);
		  } else {
			 $this->contentFull=array('regDate'=>date("Y-m-d H:i:s"),'reg_ip_address'=>$_SERVER['REMOTE_ADDR'],'user_agent'=>$_SERVER['HTTP_USER_AGENT'],'active'=>1);
		  }
		  $this->username=$username;
		  $this->dbstore->insert($this->userinfotable,$this->contentFull);
		  $this->userId=$this->dbstore->getLastInsertedId();
		  $this->contentLight['userId']=$this->userId;
		  ////////////////////////////////////////////////////////////////////////////7
		  //$this->userId=$this->getId($username);
		  
		  $this->dbstore->insert($this->usertable,$this->contentLight);
		  return true;
		}else{
		  return false;
	      exit;
		}	
	}
	
	/*public function __call( $method, $args ){
   	print_r($this->content);
		//return $this->{'content'}[strtolower($method)];
		echo $method;
 	}*/
	
	/**
	 * Funzione speciale per prelevare un qualunque campo dall'array dati dell'oggetto
	 * @param object $id, contiene l'id dell'utente di cui si vuole ricevere l'attributo
	 */
	
	public function __get($id){
		 if ($this->debug!=0)
		 	print ("USER:Getting $id\n<br>");
		if (($id !="username") && ($id !="password") && ($id !="active")) {
    		return $this->{'contentFull'}[$id];
		}else{
			return $this->{'contentLight'}[$id];
		}
	}

	/**
	 * Funzione speciale per settare un qualunque campo dall'array dati dell'oggetto
	 * Notiamo che gli attributi username, password, active vanno nell'array contentLight che verrà poi memorizzato in usertable
	 * mentre il resto va in contentFull che verrà memorizzato invece in userinfotable
	 * @param object $id, contiene l'id dell'utente di cui si vuole settare l'attributo
	 */
    public function __set($id,$value){
       // $logger=new logger("debug.txt");
                //$logger->rawLog($this->contentLight);
                //$logger->rawLog($this->contentFull);
               // $logger->log($id."--->".$value);
        
    	
		 //  printf("USER::setting $value to $id.\n<br>");
   		 if (($id !="username") && ($id !="password") && ($id !="active")) {
		 	$this->contentFull[$id] = $value;
		 } else {
		 	$this->contentLight[$id] = $value;
		 }

  	}
	/**
	 * Carica un utente dal database
	 * @param object $id, contiene l'id dell'utente a cui si vuole accedere
	 */
	public function loadUserFromId($id){
		$username=$this->getUserName($id);
		$this->loadUser($username);
	}
	
	/**
	 * Carica un utente dal database
	 * @param object $username, contiene il nome dell'utente a cui si vuole accedere
	 */
	public function loadUser($username){
		
		$this->contentLight=$this->dbstore->getRow($this->usertable, "*",array(array("WHERE","username", "=",$username)), array(), $this->debug);
		
		$this->userId=$this->getId($username);
		
		$this->contentFull=$this->dbstore->getRow($this->userinfotable, "*", array(array("WHERE","userId", "=",$this->userId)), array(), $this->debug);
		if (count($this->contentLight)>0) 
			$this->username=$this->contentLight['username'];
	}
	
	public function getId($username){
		$user=$this->dbstore->getRow($this->usertable, "userId",array(array("WHERE","username", "=",$username)));
		return $user['userId'];
	}
	
	public static function getUserName($userId){
            
		global $opt;
                $session=new session();
		$usertable = $session->getSessionVar('prefix').$opt['mysql']['usertable'];
		$db=new db();
		$user=$db->getRow($usertable,"username",array(array("WHERE","userId", "=", $userId,true)));
		if (isset($user['username'])){
			return $user['username'];
		}else{
			return "guest";
		}
	}
	
	public static function getUserId($username){
		global $opt;
                $session=new session();
		$usertable = $session->getSessionVar('prefix').$opt['mysql']['usertable'];
		$db=new db();
		$user=$db->getRow($usertable,"userId",array(array("WHERE","username", "=",$username)));
		return $user['userId'];
	}
	
	/**
	 * Restituisce tutti i dati dell'attuale utente
	 * @return array associativo contenente le accoppiate chiave => valore
	 */
	public function show(){
		return array_merge($this->contentLight,$this->contentFull);
	}
	
	/**
	 * Sincronizza le informazioni dell'oggetto con quelle del database
	 */
	public function Update(){
		if ($this->debug==1){
			print_r($this->contentLight);
			print_r($this->contentFull);
		}
                
                //$logger=new logger("debug.txt");
                //$logger->rawLog($this->contentLight);
                //$logger->rawLog($this->contentFull);
               
		$this->dbstore->update($this->usertable,$this->contentLight,array(array("WHERE","userId", "=", $this->userId)),$this->debug);
		$this->dbstore->update($this->userinfotable,$this->contentFull,array(array("WHERE","userId", "=", $this->userId)),$this->debug);
  	}
	
	public static function isUserExist($username){
		global $opt;
                $session=new session();
		$usertable = $session->getSessionVar('prefix').$opt['mysql']['usertable'];
		if (trim($usertable)=="") die('USER::isUserExist --> tablename empty...'); 
		$db=new Db();
		if ($db->isPresent($usertable,array('username'=>$username)))
		  return true;
		return false;
	}
  /**
   * Verifica se l' utente l0ggato è del gruppo admins
   */
  public function isAdmins($session){
    return group::isUserInGroup( user::getUserName($session->getUserId()), "admins");
  }
	
	public static function getUsers(){
		global $opt;
                $session=new session();
		$usertable = $session->getSessionVar('prefix').$opt['mysql']['usertable'];
		$db=new db();
		return $db->getRows($usertable,array("username"));
	}
	

	public function delete($username=""){
		if ($this->debug==1)
			print_r($this->content);
			//$devnull=group::delUserFromAllGroups($this->username);
			if (trim($username)==""){
				$this->dbstore->delete($this->usertable,array(array("WHERE","username", "=",$this->username)),$this->debug);
			} else {
				$this->dbstore->delete($this->usertable,array(array("WHERE","username", "=",$username)),$this->debug);
			}
	} 
	public function makePassword($cleartext,$salt=""){
		if (trim($salt)==""){
			$salt=$this->makeSalt(12);
		} 
		$password=hash('sha1',$salt.$cleartext);
		return $salt.":".$password;
	}
	
	public function makeSalt($saltlength=12){
		$salt=md5(uniqid(mt_rand(), true));
		$salt=substr($salt,0,$saltlength);
		return $salt;
	}
	
	public function getSalt($username=""){
		if (trim($username=="")){
			$user=$this->dbstore->getRow($this->usertable,"password",array(array("WHERE","userId", "=",$this->userId)));
		}else{
			$user=$this->dbstore->getRow($this->usertable,"password",array(array("WHERE","username", "=",$username)));
		}
		
		
		$salt=$user['password'];
		$salt=explode(":",$salt);
		$salt=$salt[0];
		return $salt;
	}
	
	public function checkPassword($cleartext,$username=""){
		$salt=$this->getSalt();
		return $this->makePassword($cleartext,$salt);
	}
		 
	/**
	 * Rende attivo un utente
	 */
	public function setActive(){
		$this->contentLight['active']=1;
		$this->contentFull['confirmDate']=date("Y-m-d H:i:s");
		$this->contentFull['confirm_ip_address']=$_SERVER['REMOTE_ADDR'];		
	}
        
 
        public function checkIfActiveUser($username){
            $db=new db();
            $res = $db->getRow("users", "active", array(
                array("where", "username", "=", $username)
            ));
            if(!isset($res["active"]))
                return false;
            return $res["active"];
        }
   
	
 }
?>
