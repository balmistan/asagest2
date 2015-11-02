<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");

$session = new session();

if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins"))) {
    header("Location:index?logout=1");
    exit(0);
}


/* backup the db OR just a table */

function backup_tables($host, $user, $pass, $name, $tables = '*') {

    $link = mysql_connect($host, $user, $pass);
    mysql_select_db($name, $link);

    mysql_query("SET NAMES utf8");

    $return = "
SET foreign_key_checks = 0;\n
SET time_zone = 'SYSTEM';\n
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';\n
";
    //get all of the tables
    if ($tables == '*') {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while ($row = mysql_fetch_row($result)) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    //cycle through
    foreach ($tables as $table) {
        $result = mysql_query('SELECT * FROM ' . $table);
        $num_fields = mysql_num_fields($result);

        $return.= 'DROP TABLE ' . $table . ';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
        $return.= "\n\n" . $row2[1] . ";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysql_fetch_row($result)) {
                $return.= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return.= '"' . $row[$j] . '"';
                    } else {
                        $return.= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return.= ',';
                    }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }
    $return .= "\n\n SET foreign_key_checks = 1;";
    return $return;
}

$txt = backup_tables($opt['hostname'], $opt['username'], $opt['password'], $opt['dbname']);

//save file
$fname = 'db-backup-' . date('d-m-y-H.i.s') . '.sql';
$fpath = '../Personal/_backup/';
$handle = fopen($fpath.$fname, 'w+');
fwrite($handle, $txt);
fclose($handle);



//Forzo il download
header('Content-Type: application/octet-stream');
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename= " . $fname);
header("Content-Transfer-Encoding: binary");
// Leggo il contenuto del file
readfile($fpath.$fname);
?>