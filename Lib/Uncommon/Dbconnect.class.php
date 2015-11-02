<?php
class Dbconnect {

private $pdo;

public function __construct() {

	try { 

	$this->pdo = new PDO('mysql:host=localhost;dbname=Project', "root", "not4resale", array(PDO::ATTR_PERSISTENT => TRUE));

	} catch (PDOException $e) {

	print "Error!: " . $e->getMessage() . "<br/>";

	die();

	}
}//close __construct


public function getPDO(){
	return $this->pdo;
}


}//close class

?>
