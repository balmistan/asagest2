<?php
/**
* Role class to provide groups and users role management
*
* This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
* @author Carmelo San Giovanni <admin@barmes.org>
* @version 2.7
* @copyright Copyright (c) 2009 Carmelo San Giovanni
* @package libss
*/

class role {
	/**
	 * @access private
	 * @var reference 
	 */
	
	
	/**
	 * @access private
	 * @var array 
	 * conterrà i nomi degli utenti a cui sarà consentito accedere ad una determinata area
	 */
	private $users;
	
	/**
	 * @access private
	 * @var array 
	 * conterrà i nomi dei gruppi a cui sarà consentito accedere ad una determinata area
	 */
	private $groups;

	
  /**
     * Costruttore di classe role
	 * @access public
	 * @param int $debug[optional] contiene la flag di debugging, di default è inattivo
	*/
	
	public function __construct($debug=0) {
		$this->users=array();
		$this->groups=array();
		$this->debug=$debug;
	}
	
	/**
	 * Crea un utente
	 * @param boolen $active [optional], permette di specificare se l'utente è già attivo nel sistema
	 */

	 public function grantUser($username){
	 	$this->users[]=$username;
	 }
	 
	 public function grantGroup($groupname){
	 	$this->groups[]=$groupname;
	 }

	
  public function AllUsersAccessAllowed(){
      $session=new session();
      $sid=$session->getUserId();
      unset($session);
      
      $username=user::getUserName($sid);
      $this->users[]=$username;
  }
  
   
	 public function isAllowed($username){
	 	if (in_array($username,$this->users)){
	 		return true;
	 	} else {
	 		foreach($this->groups as $groupname){
	 			if (group::isUserInGroup($username,$groupname))
					return true;
	 		}
	 	}
		return false;	
	 }
	 
}