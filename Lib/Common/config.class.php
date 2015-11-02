<?php

/**
 * Config class
 *
 * This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
 * @author Carmelo San Giovanni <admin@barmes.org>
 * @version 0.7
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v2.0
 * @package libss
 */
class config {

    public static function getConfig($configName = null, $configTable = "") {
        global $opt;
        $session = new session();
        //prendiamo le vars di configurazione dal config.php (deve essere incluso nei file che istanziano l'oggetto)
        if ($configTable == "")
            $configTable = $opt['mysql']['configtable'];
        if ((trim($configTable) == ""))
            die('config --> tablename empty...');
        $dbstore = db::getInstance();
        //echo var_dump($dbstore);

        if ($configName != null) {
            $result = $dbstore->getRow($configTable, "configValue", array(array("WHERE", "configName", "=", $configName)));
            if (!is_array($result)) {
                return null;
            }
            if (isset($result['configValue']))
                return $result['configValue'];
            else
                return "";
        }else {
            $result = $dbstore->getRows($configTable, array("configName", "configDescription", "configValue", "configTitle", "configEditable"));
            return $result;
        }
    }

    public static function setConfig($configName, $configValue, $configDescription = "", $configTable = "") {
        global $opt;
        $session = new session();
        //prendiamo le vars di configurazione dal config.php (deve essere incluso nei file che istanziano l'oggetto)
        if ($configTable == "")
            $configTable = $opt['mysql']['configtable'];
        if ((trim($configTable) == ""))
            die('config --> tablename empty...');
        $dbstore = db::getInstance();
        if (!$dbstore->isPresent($configTable, array("configName" => $configName)))
            $result = $dbstore->insert($configTable, array("configName" => $configName, "configValue" => $configValue));
        else
            $dbstore->update($configTable, array("configValue" => $configValue), array(array("WHERE", "configName", "=", $configName)));
    }

    public static function isEditable($configName = null) {
        global $opt;
        $session = new session();
        //prendiamo le vars di configurazione dal config.php (deve essere incluso nei file che istanziano l'oggetto)
        $configTable = $session->getSessionVar('prefix') . $opt['mysql']['configtable'];
        if ((trim($configTable) == ""))
            die('config --> tablename empty...');
        $dbstore = db::getInstance();

        if ($configName != null) {
            $result = $dbstore->getRow($configTable, "configEditable", array(array("WHERE", "configName", "=", $configName)));
            if (!is_array($result)) {
                return false;
            }
            if (isset($result['configEditable']))
                return intval($result['configEditable']);
            else
                return 0;
        }
        return 0;
    }

}

?>