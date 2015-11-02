<?php 
/**
 * Cookie class to provide user authentication
 *
 * This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
 * @author Carmelo San Giovanni <admin@barmes.org>
 * @version 5.0
 * @copyright Copyright (c) 2010 Carmelo San Giovanni
 * @package libss
 */
 
class cookie {
    /**
     * @access protected
     * @var array
     */
	protected $content;
	
	/**
     * @access protected
     * @var integer
     */
	protected $cookielifetime;
	
	/**
	 * @access protected
	 * @var string
	 */
	protected $cookiename;
    
	/**
	 * @access protected
	 * @var boolean
	 */
    protected $debug;
    
    /**
     * Costruttore di classe cookie
     * @access public
     * @param string $cookiename, contiene il nome del cookie
     * @param boolean $debug, flag per attivare/disattivare il debugging
     */
     
    public function __construct($cookiename,$debug=false) {
          //prendiamo le vars di configurazione dal config.php (deve essere incluso nei file che istanziano l'oggetto)
		$this->debug=$debug;
        $this->cookiename=$cookiename;
        $this->cookielifetime=(isset($opt['site']['cookielifetime']))? ((int) $opt['site']['cookielifetime']) * 86400 + time() : (86400*7)+time();
        $this->content=array();
		if ($this->debug)
    		printf("Cookiename: ".$this->cookiename.", lifetime: ".$this->cookielifetime."\n<br />");
    }
    
    /**
     * Metodo speciale per prelevare qualunque valore dall'array dell'oggetto
     */
	 public function __get($id){
		 if ($this->debug)
		 	print ("COOKIE::Getting $id\n<br>");
		return $this->{'content'}[$id];    		
	}

	/**
	 * Metodo speciale per settare un qualunque campo dall'array dati dell'oggetto
	 */
    public function __set($id,$value){
    	if ($this->debug!=0)
		   printf("COOKIE::setting $value to $id.\n<br>");
		 	
		if ($this->{'content'}[$id] = $value)
		 	return true;
		return false;
  	}
    
	/**
	 * Aggiunge un campo proprietà al cookie
	 * @param string $name, contiene il nome della proprietà
	 * @param string $value, contiene il valore della proprietà
	 */
    /*public function addProp($name,$value){
    	$this->content[$name]=$value;
    	if ($this->debug)
    		print_r($this->content);		
    }
    
    /**
     * Rimuove una proprietà dal cookie
     * @param string $name, contiene il nome della proprietà da rimuovere
     */
    /*public function delProp($name){
    	unset($this->content[$name]);
    	if ($this->debug)
    		print_r($this->content);	
    }
    
    
	/**
	 * Modifica la data di scadenza del cookie
	 * @param int $days, contiene il numero di giorni rimasti prima della scadenza
	 */
    public function setLifeTime($days){
    	$this->cookielifetime=((int)$days*86400)+time();
    	if ($this->debug)
    		printf("New lifetime: ".$this->cookielifetime."\n<br />");
    }
    
    /**
     * Restituisce la data di scadenza del cookie in formato unix (non funziona se non è stata ancora settata)
     */
    public function getLifeTime(){
    	return (int)($this->cookielifetime-time())/86400;
    }
    
    /**
     * Scrive il cookie sull'hard disk
     */
    public function writeCookie() {
		foreach ($this->content as $key=>$value){
    		setcookie($this->cookiename."[$key]",$value,$this->cookielifetime);
		}
		if ($this->debug){
			printf("WRITING: ");
    		print_r($this->content);
    		printf(" to ".$this->cookiename." with lifetime of ".$this->cookielifetime."\n<br />");
		}	
		
    }
    
    /**
     * Carica un cookie preesistente se è presente e non è scaduto
     * @return unknown_type
     */
    public function loadCookie(){
    	if (isset($_COOKIE[$this->cookiename])){
    		foreach($_COOKIE[$this->cookiename] as $key=>$value){
    			$this->content[$key]=$value;
    		}
    	}
    }
    /**
     * Restituisce il contenuto di un cookie precedentemente letto
     */
    public function getContent(){
    	print_r($this->content);
    }
    /**
     * Rimuove un cookie dall'hard disk settando il suo lifetime ad adesso.
     */
    public function delete(){
    	$this->loadCookie();
    	$this->setLifeTime(0);
    	$this->writeCookie();
    }
    
}
?>
