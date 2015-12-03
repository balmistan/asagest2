<?php

//error_reporting(0);
/* Class DB 
 * @version 0.7
 * @copyright Copyright (c) 2011 Carmelo San Giovanni
 * @package libss
 */
class db {

    /**
     * @access private
     * @var string
     */
    private $dbType = ""; // database type

    /**
     * @access private
     * @var string
     */
    private $dbHost = ""; //database server hostname
    private $dbIp = ""; //database server ip

    /**
     * @access private
     * @var string
     */
    private $dbName = ""; // database name 

    /**
     * @access private
     * @var string
     */
    private $dbUser = ""; // username that can be access to database

    /**
     * @access private
     * @var string
     */
    private $dbPass = ""; // user's password
    /**
     * @access private
     * @var integer__construct
     */
    private $connectionId = 0; // result of the last mysql_connect call
    /**
     * @access private
     * @var integer
     */
    private $queryId = 0; // result of the last mysql_query call
    /**
     * @access private
     * @var array
     */
    private $lastRecord = array(); // last lastRecord fetched
    /**
     * @access private
     * @var boolean
     */
    private $autoCommit = true;    // automatic transaction commit
    /**
     * @access private
     * @var integer
     */
    private $currentRow; // current row number
    /**
     * @access private
     * @var integer
     */
    private $errorNumber = 0; // last error number
    /**
     * @access private
     * @var string
     */
    private $errorMessage = ""; // last error message
    /**
     * @access private
     * @var string
     */
    private $errorLocation = ""; // last error location
    /**
     * @access private
     * @var boolean
     */
    private $show_error_messages = true;

    /**
     * @access protected
     * @var object
     */
    protected $objPDO;
    protected $debug = false;
    private static $instance;
    private $test;

    /**
     * @access private
     * @var integer
     */
    private $lastRowCount = 0;
    private $Logger;
    private $LoggerDebug;

    public function __construct($dbtype = null, $hostname = null, $ip = null, $port = null, $dbname = null, $username = null, $password = null) {
        global $opt;
        if ($dbtype == null)
            $dbtype = $opt['dbtype'];
        if ($hostname == null)
            $hostname = $opt["hostname"];
        if ($ip == null)
            $ip = $opt["ip"];
        if ($port == null)
            $port = $opt["port"];
        if ($dbname == null)
            $dbname = $opt["dbname"];
        if ($username == null)
            $username = $opt["username"];
        if ($password == null)
            $password = $opt["password"];

        $this->dbType = $dbtype;
        $this->dbHost = $hostname;
        $this->dbName = $dbname;

        $this->Logger = new logger("../elog/dbclass.log", 0);
        $this->LoggerDebug = new logger("../elog/debug_dbclass.log", 0);

        $this->test = mt_rand(1, 10000000);

        $dsn = $dbtype . ":dbname=" . $dbname . ";" . (($hostname !== "") ? "host=" . $hostname : "ip=" . $ip) . ";port=" . $port;

        if ($this->debug)
            echo $dsn . CRLF;
        //       date_default_timezone_set('UTC');
        try {
            //echo $dsn." ".$username." ".$password." ";
            $this->objPDO = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => TRUE, PDO::ERRMODE_EXCEPTION => TRUE, PDO::ATTR_PERSISTENT => TRUE, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->errorLocation = "";
            $this->errorMessage = "";
        } catch (PDOException $e) {
            $this->errorLocation = "db::__construct -> " . $e->getLine();
            $this->errorMessage = $e->getMessage();
            die($this->getLastError());
        }
    }

    public static function getInstance() {
        static $database;
        if (!isset($database)) {
            $database = new db();
        }
        //echo CRLF.var_dump($database).CRLF;
        return $database;
    }

//     public static function getInstance() {
//         
//        if (!self::$instance) {
//            var_dump(self::$instance);
//            try {
//                self::$instance = new db();
//                echo CRLF."Genero nuovo db".CRLF;
//            } catch (PDOException $e) {
//                echo CRLF.'Connessione fallita: ' . $e->getMessage();
//            }
//        }
//        return self::$instance;
//    }

    public function numRows() {
        return $this->lastRowCount;
    }

//close __construct

    /**
     * Restituisce true se la colonna è di tipo INT
     * @access public
     * @param string $table nome della tabella coinvolta
     * @param string $fieldname nome del campo contenente da verificare
     */
    public function fieldIsInt($table, $fieldname) {
        $sql = "describe " . $table . " " . $fieldname . ";";
        try {
            $objStatement = $this->objPDO->query($sql);
            $this->errorLocation = "";
            $this->errorMessage = "";
        } catch (PDOException $e) {
            $this->errorLocation = "db::fieldIsInt -> " . $e->getLine();
            $this->errorMessage = $e->getMessage();
        }
        $arRow = $objStatement->fetchAll(PDO::FETCH_ASSOC);

        if (strtolower(substr($arRow[0]["Type"], 0, 3)) == "int")
            return 1;

        return 0;
    }

    public function getRows($table, $fields, $cond = array(), $options = array(), $debug = true) {

        //parsing array options
        if ($debug == true) {
            //echo "Debugging active.<br>";
        }

        $select = "SELECT";         //default
        $end_str = "";

        for ($i = 0; $i < count($options); $i++) {
            $clause = strtoupper($options[$i][0]);
            switch ($clause) {
                case "DISTINCT":
                    $select = "SELECT DISTINCT";
                    break;
                case "ORDERBY":
                    $end_str .= " ORDER BY " . $options[$i][1];
                    break;
                case "ORDER":
                    $end_str .=" " . $options[$i][1];
                    break;
                case "LIMIT":
                    if (isset($options[$i][2]))
                        $end_str .=" LIMIT " . $options[$i][1] . "," . $options[$i][2];
                    else
                        $end_str .=" LIMIT " . $options[$i][1];
                default: break;
            }//switch close
        } //for close;
        //////// fine parsing per array option


        if (!is_array($fields))
            $fields = array($fields);  // se non è array lo trasformo in array

        $strQuery = "";

        foreach ($fields as $value) {           //elenco le colonne interessate dalla query (I nomi delle colonne non sono protette da sql injection così come il nome tabella)
            $strQuery .= $value . ", ";
        }
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 2);

        if (!is_array($table)) {
            $strQuery = $select . " $strQuery FROM " . $table . ";";
        } else {
            $strQuery = $select . " " . $strQuery . " FROM ";

            $joinParameter = strtolower($table[count($table) - 1]);
            switch ($joinParameter) {
                case "inner":
                    $joinType = "INNER JOIN";
                    unset($table[count($table) - 1]);
                    break;
                case "join":
                    $joinType = "JOIN";
                    unset($table[count($table) - 1]);
                    break;
                case "left":
                    $joinType = "LEFT JOIN";
                    unset($table[count($table) - 1]);
                    break;
                case "right":
                    $joinType = "RIGHT JOIN";
                    unset($table[count($table) - 1]);
                    break;
                case "full":
                    $joinType = "FULL JOIN";
                    unset($table[count($table) - 1]);
                    break;
                default:
                    $joinType = "INNER JOIN"; //se non è specificato il tipo di join eseguiamo una inner join
                //ed evitiamo di cancellare l'ultimo parametro
            }

            foreach ($table as $tablename) {
                $strQuery.="$this->dbName.$tablename $joinType ";
            }
            $strQuery = rtrim($strQuery, " $joinType") . ";";
        }

        //se sono specificate delle condizioni (where...)

        if (count($cond) > 0) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 1);

            for ($i = 0; $i < count($cond); $i++) {
                $clause = $cond[$i][0];
                $field1 = $cond[$i][1];
                $operator = $cond[$i][2];
                $field2 = $cond[$i][3];
                if (isset($cond[$i][4])) { //la condizione 4 posta a true forza a trattare come intero (serve solo nel caso si utilizzi un nome colonna che non va trattato come stringa anche se lo è)
                    $strQuery.=" " . $clause . " " . $field1 . " $operator " . $field2;
                } else {
                    $strQuery.=" " . $clause . " " . $field1 . " $operator :f2_" . $i;
                }
            } //close for
        }//close if(count($cond)>0)


        $strQuery = rtrim($strQuery, ";");

        $strQuery.=$end_str . ";";

        $objStatement = $this->objPDO->prepare($strQuery);
        $dbgquery = $strQuery;
        for ($i = 0; $i < count($cond); $i++) {
            if (isset($cond[$i][4]) && $cond[$i][4] == true) {

                continue;
            }
            if (is_numeric($cond[$i][3]) || $cond[$i][3] == NULL) {
                $objStatement->bindParam(':f2_' . $i, $cond[$i][3], PDO::PARAM_INT);
                if ($debug) {
                    $dbgquery = preg_replace("/:f2_$i/", $cond[$i][3], $dbgquery);
                    //$dbgquery = str_replace(':f2_'.$i, $cond[$i][3], $dbgquery);
                }
            } else {
                $objStatement->bindParam(':f2_' . $i, $cond[$i][3], PDO::PARAM_STR);
                if ($debug) {
                    //echo $cond[$i][3];		
                    $dbgquery = preg_replace("/:f2_$i/", "'" . $cond[$i][3] . "'", $dbgquery);    //ho un errore se la query contiene il %
                    //$dbgquery = str_replace(':f2_'.$i, "'".$cond[$i][3]."'", $dbgquery);  
                }
            }//close else   
        } //close for
        // eseguo la query	
        if ($debug)
        // echo $dbgquery . CRLF;
            $this->LoggerDebug->rawLog($dbgquery);
        try {

            if ($debug)
            //echo "exec: " . $dbgquery . CRLF;
                $this->LoggerDebug->rawLog($dbgquery);
            $objStatement->execute();
            $this->errorLocation = "";
            $this->errorMessage = "";
        } catch (PDOException $e) {
            //echo $dbgquery . CRLF;
            $this->LoggerDebug->rawLog($dbgquery. "ERROR EXCEPTION");
            $this->errorLocation = "db::getRows -> " . $e->getLine();
            $this->errorMessage = $e->getMessage();
        }
        $arRow = $objStatement->fetchAll(PDO::FETCH_ASSOC);
        $this->lastRowCount = count($arRow);

        return $arRow;
    }

//close public function getRows

    public function getRow($table, $fields, $cond = array(), $options = array(), $debug = 0) {

        $num_element = count($options);

        $options[$num_element] = array("LIMIT", 1);

        $arr = $this->getRows($table, $fields, $cond, $options, $debug);
        if (count($arr) != 0)
            $arr = $arr[0];

        $this->lastRowCount = 1;
        return $arr;
    }

    public function setDebug($value = true) {
        $this->debug = $value;
    }

    /**
     * Restituisce l' ultimo id della riga inserita (colonna con autoincrement). Non è sicura come tecnica (vedere manuale php)
     * @access public
     * */
    function getLastInsertedId() {
        return $this->objPDO->lastInsertId();
    }

    /**
     * Restituisce il massimo tra gli id già occupati
     * @access public
     * @param string $table nome della tabella coinvolta
     * @param string $idfieldname nome del campo contenente l'id
     * @param boolean $debug visualizza informazioni di debug
     * @return integer $result[$idfieldname] massimo id allocato
     */
    function getMaxId($table, $idfieldname, $debug = 0) {
        $sql = "SELECT Max($idfieldname) AS '$idfieldname' FROM " . $table . ";";
        $objStatement = $this->objPDO->query($sql);

        $arRow = $objStatement->fetch(PDO::FETCH_ASSOC);

        return $arRow[$idfieldname];   //torna una stringa vuota se la tabella è vuota
    }

    /**
     * @access public
     * @param string $table nome della tabella coinvolta
     * @param array $matches  contenente la presenza dei campi da confrontare del tipo array("name"=>"john")
     * @param boolean $debug visualizza informazioni di debug
     * @return boolean true se l'elemento è presente
     */
    public function isPresent($table, $matches, $debug = 0) {
        $cond = array();
        $i = 0;
        foreach ($matches as $key => $val) {
            if ($i > 0)
                $cond[$i][0] = "AND";
            else
                $cond[0][0] = "WHERE";           //se non è >0 sarà = 0			
            $cond[$i][1] = $key;
            $cond[$i][2] = "=";
            $cond[$i][3] = $val;
            $i++;
        }


        $arr = $this->getRow($table, array("*"), $cond, array(), $debug);
        //print_r($arr);
        if (count($arr) > 0)
            return 1;

        return 0;
    }

    /**
     * Funzione per l'inserimento di valori in una riga della tabella 
     * @access public
     * @param string $table contiene il nome della tabella in cui inserire i valori
     * @param array $row è un array contenente i valori da inserire nei vari campi es. array('name'=>'John','age'=>22)
     * @param boolean $debug imposta la flag per attivare/disattivare il debugging, default 0 (disattivo) 
     */
    public function insert($table, $row, $debug = 0) {
//        echo CRLF;
//        print_r($row);
//        echo CRLF;

        $strKeys = "";
        $strValues = "";
        if ($debug > 0) {
            $parallel = "INSERT INTO " . $table . " (" . implode(", ", array_keys($row)) . ") VALUES ('" . implode("','", array_values($row)) . "');" . CRLF;
            //  echo $parallel;
        }

        foreach ($row as $key => $value) {

            $strKeys .= "$key, ";
            $strValues .= ":" . $key . ", ";
        }

        $strKeys = substr($strKeys, 0, strlen($strKeys) - 2);
        $strValues = substr($strValues, 0, strlen($strValues) - 2);

        $strQuery = "INSERT INTO " . $table . " (" . $strKeys . ") VALUES (" . $strValues . ");";

        $objStatement = $this->objPDO->prepare($strQuery);
        $test = $strQuery;

        foreach ($row as $key => &$value) {
            if (is_numeric($value) || $value == NULL) {
                $objStatement->bindParam(':' . $key, $value, PDO::PARAM_INT);
                $test = preg_replace("/\:$key/", $value, $test);
                //echo "KEY = ".$key.", VALUE = $value".CRLF;
            } else {
                $objStatement->bindParam(':' . $key, $value, PDO::PARAM_STR);
                $test = preg_replace("/\:$key/", "'$value'", $test);
                //echo "KEY = ".$key.", VALUE = '$value'".CRLF;
            }
        }
        if ($debug)
            echo "Query: " . $test;

        try {
            $a = "asd";
            $issue = $objStatement->execute();
            $this->errorLocation = "";
            $this->errorMessage = "";
        } catch (PDOException $e) {
            $this->errorLocation = "db::insert -> " . $e->getLine();
            $this->errorMessage = $e->getMessage();
            echo $this->errorLocation;
            echo $this->errorMessage;
        }

        return $issue;
    }

//close function insert

    /**
     * Funzione per l'aggiornamento di valori in una riga della tabella 
     * @access public
     * @param string $table contiene il nome della tabella su cui lavorare
     * @param array $row è un array contenente i vari campi da modificare con rispettivi valori 
     * es. array('name'=>'John','age'=>22)
     * @param array $cond è l'array di condizioni per filtrare il risultato es. array("WHERE"=>"name = 'John'")
     * @param boolean $debug imposta la flag per attivare/disattivare il debugging, default 0 (disattivo) 
     */
    function update($table, $row = array(), $cond = array(), $debug = 0) {

        if (!is_array($row) || count($row) == 0) {
            echo "Update Error! row deve essere un array non vuoto";
            die();
        }

        $strQuery = "UPDATE " . $table . " SET ";

        foreach ($row as $key => $value) {
            $strQuery .= $key . "=:" . $key . ", ";
        }

        $strQuery = substr($strQuery, 0, strlen($strQuery) - 2);

        //se sono specificate delle condizioni (where...)

        if (count($cond) > 0) {

            for ($i = 0; $i < count($cond); $i++) {
                $clause = $cond[$i][0];
                $field1 = $cond[$i][1];
                $operator = $cond[$i][2];
                $field2 = $cond[$i][3];

                $strQuery .=" " . $clause . " " . $field1 . " $operator :f2_" . $i;
            } //close for
        }//close if(count($cond)>0)

        $strQuery .=";";

        $objStatement = $this->objPDO->prepare($strQuery);

        foreach ($row as $key => &$value) {
            if (is_numeric($value) || $value == NULL)
                $objStatement->bindParam(':' . $key, $value, PDO::PARAM_INT);
            else
                $objStatement->bindParam(':' . $key, $value, PDO::PARAM_STR);
        }

        for ($k = 0; $k < count($cond); $k++) {
            if (is_numeric($cond[$k][3]) || $cond[$k][3] == NULL)
                $objStatement->bindParam(':f2_' . $k, $cond[$k][3], PDO::PARAM_INT);
            else
                $objStatement->bindParam(':f2_' . $k, $cond[$k][3], PDO::PARAM_STR);
        } //close for
        // eseguo la query	
        try {
            $issue = $objStatement->execute();
            $this->errorLocation = "";
            $this->errorMessage = "";
        } catch (PDOException $e) {
            $this->errorLocation = "db::update -> " . $e->getLine();
            $this->errorMessage = $e->getMessage();
        }

        if ($debug)
            echo $strQuery;
        return $issue;
    }

//close function update	   

    /**
     * Funzione per la cancellazione di una riga dalla tabella 
     * @access public
     * @param string $table contiene il nome della tabella su cui lavorare
     * @param array $cond è un array contenente le clausule da rispettare per la cancellazione array("WHERE"=>"name ='John'","AND"=>"age = 22")
     * @param boolean $debug imposta la flag per attivare/disattivare il debugging, default 0 (disattivo) 
     */
    function delete($table, $cond = array(), $debug = 0) {

        if (!is_array($cond)) {
            echo "Delete Error! cond deve essere un array";
            die();
        }

        if (count($cond) == 0) {
            $strQuery = "TRUNCATE " . $table;
        } else {
            $strQuery = "DELETE FROM " . $table;

            for ($i = 0; $i < count($cond); $i++) {
                $clause = $cond[$i][0];
                $field1 = $cond[$i][1];
                $operator = $cond[$i][2];
                $field2 = $cond[$i][3];

                $strQuery .=" " . $clause . " " . $field1 . " $operator :f2_" . $i;
            } //close for
        }// close else
        $strQuery .=";";

        $objStatement = $this->objPDO->prepare($strQuery);

        for ($k = 0; $k < count($cond); $k++) {
            if (is_numeric($cond[$k][3]) || $cond[$k][3] == NULL)
                $objStatement->bindParam(':f2_' . $k, $cond[$k][3], PDO::PARAM_INT);
            else
                $objStatement->bindParam(':f2_' . $k, $cond[$k][3], PDO::PARAM_STR);
        } //close for
        //
       
        // eseguo la query	
        try {
            $issue = $objStatement->execute();
            $this->errorLocation = "";
            $this->errorMessage = "";
        } catch (PDOException $e) {
            $this->errorLocation = "db::dalete -> " . $e->getLine();
            $this->errorMessage = $e->getMessage();
        }

        if ($debug)
            echo $strQuery;
        return $issue;
    }

//close function delete

    /**
     * Restituisce l'oggetto pdo istanziato dalla classe db per poterci lavorare esternamente
     * @return type PDOObject
     */
    public function getPdoObject() {
        return $this->objPDO;
    }

    public function getMessage() {
        return $this->errorLocation . "<br />" . $this->errorMessage;
    }

    public function getLastError() {
        return $this->getMessage();
    }

    /**
     * Permette di eseguire una query libera. Da utilizzare solo se i dati vengono generati dal sistema e non immessi dagli utenti 
     */
    public function freeQuery($strQuery, $dbg_msg = "", $debug=true) {

        $this->Logger->rawLog($strQuery);
        //$this->Logger->rawLog($dbg_msg);

        $objStatement = $this->objPDO->prepare($strQuery);
        // eseguo la query	
        try {
            $issue = $objStatement->execute();
            $this->errorLocation = "";
            $this->errorMessage = "";
        } catch (PDOException $e) {
            $this->errorLocation = "db::freeQuery -> " . $e->getLine();
            $this->errorMessage = $dbg_msg . ": " . $e->getMessage();
            $this->Logger->rawLog($this->errorLocation);
            $this->Logger->rawLog($this->errorMessage);
            $this->Logger->rawLog($dbg_msg);
        }
        if($debug){
        
        }
        try{
        $arRow = $objStatement->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            $this->errorLocation = "db::freeQuery -> " . $e->getLine();
            $this->errorMessage = $dbg_msg . ": " . $e->getMessage();
            $this->Logger->rawLog($this->errorLocation);
            $this->Logger->rawLog($this->errorMessage);
            $this->Logger->rawLog($strQuery);
        $this->Logger->rawLog($dbg_msg);
        }
        return $arRow;
    }

    public function getTableNames($prefix = "") {
        $sql = "SHOW TABLES";
        $tableList = array();
        try {
            $objStatement = $this->objPDO->query($sql);
            $tableList = $objStatement->fetchAll(PDO::FETCH_COLUMN);
            $this->errorLocation = "";
            $this->errorMessage = "";
        } catch (PDOException $e) {
            $this->errorLocation = "db::isValidTable -> " . $e->getLine();
            $this->errorMessage = $e->getMessage();
        }
        if ($prefix == "")
            return $tableList;
        else {
            $filteredList = array();
            foreach ($tableList as $tableName) {
                if (preg_match("/^$prefix/", $tableName))
                    $filteredList[] = $tableName;
            }
            return $filteredList;
        }
    }

}

//close class
?>
