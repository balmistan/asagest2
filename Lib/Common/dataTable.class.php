<?php

/**
 * dataTable  class
 * this class can make a table by giving an array of strings
 * @version 0.7
 * @copyright Copyright (c) 2011 Carmelo San Giovanni
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v2.0
 * @package libss
 */
define('INDENT', 3);

class dataTable {

    private $columnNumber;
    private $optionsActive;
    private $tableName = '', $tableSummary = '', $tableCSSclass = '', $thCSSclass = '';
    private $content;
    private $page;

    public function __construct($values, $properties = array(), $options = "", page $page=null) {
        //Controllo errori
        if ((!is_array($values)))
            die(get_class($this) . "::__construct -> Errore: Ho bisogno di un array dati non vuoto.\n<br />");
        if (!is_array($properties) && (count($properties != 4)))
            die(get_class($this) . "::__construct -> Errore: Ho bisogno di un array dati non vuoto.\n<br />");

        $this->columnNumber = (count($values));
        $this->optionsActive = (trim($options) != "") ? true : false;
        $this->tableName = $properties[0];
        $this->tableSummary = $properties[1];
        $this->tableCSSclass = $properties[2];
        $this->thCSSclass = $properties[3];
        $this->page = $page;

        $this->content = "\n<table name='" . $this->tableName . "' align='center' summary='" . $this->tableSummary . "' class='" . $this->tableCSSclass . "' id='" . $this->tableCSSclass . "' cellpadding=0>\n" . str_repeat(' ', INDENT) . "<thead>\n" . str_repeat(' ', 2 * INDENT) . "<tr>\n";

        for ($i = 0; $i < $this->columnNumber; $i++) {
            $this->content .= str_repeat(' ', 3 * INDENT) . "<th class='ui-widget-header' scope='$this->thCSSclass'>$values[$i]</th>\n";
        }
        if ($this->optionsActive)
            $this->content .= str_repeat(' ', 3 * INDENT) . "<th class='ui-widget-header' scope='$this->thCSSclass'>$options</th>\n";

        $this->content .= str_repeat(' ', 2 * INDENT) . "</tr>\n" . str_repeat(' ', INDENT) . "</thead>\n" . str_repeat(' ', INDENT) . "<tbody>\n";
    }

    /**
     * L'array di opzioni è realmente un array di array associativi 
     * in cui ognuno di questi deve seguire il seguente schema:
     * image  => immagine per la rappresentazione dell'opzione
     * name   => nome dell'opzione (es. Visualizza)
     * action => azione da intraprendere al click (es. "index.php?op=show")
     */
    public function addRow($values, $options = array(), $icons_confirm = array("delete" => "Sei sicuro?")) {
//	print_r($options);
        //verifichiamo siano stati passati sufficienti argomenti

        if ((!is_array($values)) || (count($values) == 0))
            die("Errore: Ho bisogno di un array dati non vuoto.\n<br />");
        //if ($this->columnNumber != ($paramCount=count($values)) )
        //	  die("Errore: classe: dataTable, function: addrow(), errore: numero parametri insufficienti   ($paramCount invece di $this->columnNumber)");
        if (($this->optionsActive) && (($optionsNum = count($options)) == 0))
            die("DATATABLE::addRow -> l'array delle opzioni è vuoto.\n<br />");

        
        //cominciamo a popolare la riga	
        $this->content .= str_repeat(' ', 2 * INDENT) . "<tr>\n";
        //$printid=false;
        foreach ($values as $value) {
            $this->content .= str_repeat(' ', 3 * INDENT) . "<td align=\"center\">$value</td>\n";
        }
        if ($this->optionsActive) {
            $this->content .= str_repeat(' ', 3 * INDENT) . "<td align=\"center\" style=\"width: " . ($optionsNum * 30) . "px;\">\n";


            for ($i = 0; $i < $optionsNum; $i++) {
                parse_str(substr($options[$i]['action'], 1));    // $action conterrà il valore assegnato ad action

                if ($action == "none") {
                    $this->content .= str_repeat(' ', 4 * INDENT) . "<img src='" . $options[$i]['image'] . "' alt='' />\n";
                    continue;
                }
                if (array_key_exists($action, $icons_confirm)) {

                    $msg = $icons_confirm[$action];

                    $this->content .= str_repeat(' ', 4 * INDENT) . "<a id='" . $options[$i]['id'] . "' href='" . $options[$i]['action'] . "'><img src='" . $options[$i]['image'] . "' alt='" . $options[$i]['name'] . "' onclick=\"return confirm('" . $msg . "');\" title='" . $options[$i]['name'] . "' /></a>\n";
                } else {
                    $this->content .= str_repeat(' ', 4 * INDENT) . "<a id='" . $options[$i]['id'] . "' href='" . $options[$i]['action'] . "'><img src='" . $options[$i]['image'] . "' alt='" . $options[$i]['name'] . "' title='" . $options[$i]['name'] . "' /></a>\n";
                }
            }
            $this->content .= str_repeat(' ', 3 * INDENT) . "</td>\n";
        }

        $this->content .= str_repeat(' ', 2 * INDENT) . "</tr>\n";
    }
    
    
    /**
     * Versione per datatableTree
     * L'array di opzioni è realmente un array di array associativi 
     * in cui ognuno di questi deve seguire il seguente schema:
     * image  => immagine per la rappresentazione dell'opzione
     * name   => nome dell'opzione (es. Visualizza)
     * action => azione da intraprendere al click (es. "index.php?op=show")
     */
    public function addRowTree($id, $childof, $values, $options = array(), $icons_confirm = array("delete" => "Sei sicuro?")) {
//	print_r($options);
        //verifichiamo siano stati passati sufficienti argomenti

        if ((!is_array($values)) || (count($values) == 0))
            die("Errore: Ho bisogno di un array dati non vuoto.\n<br />");
        //if ($this->columnNumber != ($paramCount=count($values)) )
        //	  die("Errore: classe: dataTable, function: addrow(), errore: numero parametri insufficienti   ($paramCount invece di $this->columnNumber)");
        if (($this->optionsActive) && (($optionsNum = count($options)) == 0))
            die("DATATABLE::addRow -> l'array delle opzioni è vuoto.\n<br />");

        
        //cominciamo a popolare la riga	
        
        if($childof !=1) $this->content .= str_repeat(' ', 2 * INDENT) . "<tr id=\"node-".$id."\" class=\"child-of-node-".$childof."\">\n";
        else $this->content .= str_repeat(' ', 2 * INDENT) . "<tr id=\"node-".$id."\">\n";
//$printid=false;
        foreach ($values as $value) {
            $this->content .= str_repeat(' ', 3 * INDENT) . "<td>$value</td>\n";
        }
        if ($this->optionsActive) {
            $this->content .= str_repeat(' ', 3 * INDENT) . "<td>\n";


            for ($i = 0; $i < $optionsNum; $i++) {
                parse_str(substr($options[$i]['action'], 1));    // $action conterrà il valore assegnato ad action

                if ($action == "none") {
                    $this->content .= str_repeat(' ', 4 * INDENT) . "<img src='" . $options[$i]['image'] . "' alt='' />\n";
                    continue;
                }
                if (array_key_exists($action, $icons_confirm)) {

                    $msg = $icons_confirm[$action];

                    $this->content .= str_repeat(' ', 4 * INDENT) . "<a href='" . $options[$i]['action'] . "'><img src='" . $options[$i]['image'] . "' alt='" . $options[$i]['name'] . "' onclick=\"return confirm('" . $msg . "');\" title='" . $options[$i]['name'] . "' /></a>\n";
                } else {
                    $this->content .= str_repeat(' ', 4 * INDENT) . "<a href='" . $options[$i]['action'] . "'><img src='" . $options[$i]['image'] . "' alt='" . $options[$i]['name'] . "' title='" . $options[$i]['name'] . "' /></a>\n";
                }
            }
            $this->content .= str_repeat(' ', 3 * INDENT) . "</td>\n";
        }

        $this->content .= str_repeat(' ', 2 * INDENT) . "</tr>\n";
    }
    
    

    public function getContent() {
        $this->content .= "</tbody>\n</table>\n";
        return $this->content;
    }

   
   
   public function close(){
   	$this->content .= "</tbody>\n</table>\n";
	if(!$this->page) echo $this->content;
	else $this->page->addCode($this->content);
   }
   
public function makeAction($id, $options = array()) {
    if (count($options) == 0) {
        $opt = array(array('image' => "icons/profile.png", 'name' => "Visualizza", 'action' => "?action=view&id=$id", "id" => $id), array('image' => "icons/modify.png", 'name' => "Modifica", 'action' => "?action=edit&id=$id", "id" => $id), array('image' => "icons/delete.png", 'name' => "Cancella", 'action' => "?action=delete&id=$id", "id" => $id));
    } else {
        $opt = array();

        foreach ($options as $value)
            switch ($value) {

                case "view":
                    $opt[] = array('image' => "icons/profile.png", 'name' => "Visualizza", 'action' => "?action=view&id=$id", "id" => $id);
                    break;
                case "info":
                    $opt[] = array('image' => "icons/info.png", 'name' => "Informazioni", 'action' => "?action=info&id=$id", "id" => $id);
                    break;
                case "upload":
                    $opt[] = array('image' => "icons/document_add.png", 'name' => "Upload", 'action' => "?action=upload&id=$id", "id" => $id);
                    break;
                case "download":
                    $opt[] = array('image' => "icons/download.png", 'name' => "Download", 'action' => "?action=download&id=$id", "id" => $id);
                    break;
                case "moveup":
                    $opt[] = array('image' => "icons/moveup.png", 'name' => "Sposta su", 'action' => "?action=moveup&id=$id", "id" => $id);
                    break;
                case "movedown":
                    $opt[] = array('image' => "icons/movedown.png", 'name' => "Sposta giù", 'action' => "?action=movedown&id=$id", "id" => $id);
                    break;
                case "edit":
                    $opt[] = array('image' => "icons/modify.png", 'name' => "Modifica", 'action' => "?action=edit&id=$id", "id" => $id);
                    break;
                case "delete":
                    $opt[] = array('image' => "icons/delete.png", 'name' => "Cancella", 'action' => "?action=delete&id=$id", "id" => $id);
                    break;
                case "select":
                    $opt[] = array('image' => "icons/select.png", 'name' => "Seleziona", 'action' => "?action=select&id=$id", "id" => $id);
                    break;
                case "add":
                    $opt[] = array('image' => "icons/add.png", 'name' => "Aggiungi", 'action' => "?action=add&id=$id", "id" => $id);
                    break;
                default:
                    $opt[] = array('image' => "icons/transparent_icon.png", 'action' => "?action=none");
                    break;
            }//close switch
    }//close else
    return $opt;
}

   
 }
?>
