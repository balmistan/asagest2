<?php

/* Class mysqldump 
 * @version 0.7
 * @copyright Copyright (c) 2011 Carmelo San Giovanni
 * @package libss
 */

define("NEWLINE", "\r\n");

class mysqldump {

    private $tables = array();
    private $output;
    private $db;

    public function __construct(db $db_instance = null) {
        if (!$db_instance)
            die("mysqldump::construct istanza db null");
        $this->db = $db_instance;
        $this->output = "";
        $this->setTableNames();
    }

    private function setTableNames() {
        $this->tables = $this->db->getTableNames();
    }

    private function get_table_structure($tablename) {
        $this->output .= NEWLINE . "-- Dumping structure for table: $tablename" . NEWLINE;

        $this->output .= "DROP TABLE IF EXISTS `$tablename`;" . NEWLINE;

        $query = $this->db->freeQuery("show create table " . $tablename);
        if (count($query)) {
            $str = $query[0]["Create Table"];
            $this->output .= $str . ";" . NEWLINE;
        }
    }

    function list_values($tablename) {
        $arr_sql = $this->db->getRows($tablename, "*");

        if (count($arr_sql)) {

            $this->output .= NEWLINE . "-- Dumping data for table: $tablename" . NEWLINE;
            $arr_attr = array_keys($arr_sql[0]);
            $str_attr = implode("`, `", $arr_attr);

            for ($i = 0; $i < count($arr_sql); $i++) {
                $str_values = "(";

                foreach ($arr_sql[$i] as $value) {
                    secur::addSlashes($value);
                    if (is_numeric($value))
                        $str_values .= $value . ", ";

                    else {
                        $value = str_replace("'", "\'", $value);
                        $value = str_replace("\\'", "\'", $value);
                        $value = str_replace("\\\'", "\'", $value);
                        $str_values .= "'" . $value . "', ";
                    } 
                }

                //sostituisco la virgola finale con chiusura parentesi
                if (strlen($str_values) > 2)
                    $str_values = substr_replace($str_values, ")", strrpos($str_values, ","));

                if ($i == 0 || $i % 50 == 0) {
                    if ($i != 0)
                        $this->output .= ";" . NEWLINE;
                    $this->output .= "INSERT INTO `" . $tablename . "` (`" . $str_attr . "`) VALUES" . NEWLINE;
                    $this->output .= $str_values;
                }else {
                    $this->output .= "," . NEWLINE;
                    $this->output .= $str_values;
                }
            }
            $this->output .= ";" . NEWLINE;
        }
    }

//close function

    public function getDump() {
        $this->output .= '
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET foreign_key_checks = 0;' . NEWLINE;;


        foreach ($this->tables as $tablename) {
            $this->get_table_structure($tablename);
            $this->output .= NEWLINE;
            $this->list_values($tablename);
            $this->output .= NEWLINE;
        }

        $this->output .= '
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;SET foreign_key_checks = 1;' . NEWLINE;
        return $this->output;
    }

}

//class close
?>