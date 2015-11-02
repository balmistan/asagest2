<?php
/**
 * Classe per generare i menù dropdown utilizzando Smarty. Fa parte del progetto libss.
* Questa classe permette di creare un array con le opzioni nello stesso formato prodotto dalla classe SmartyMenu di Monte Ohrt http://www.phpinsider.com/php/code/SmartyMenu/ 
 */

require_once("menudataboundobject.class.php");
require_once("menuitem.class.php");
class Smenu {

    private $objarr = array();   //conterrà le istanze di menuitem corrispondenti alle voci del menù.
    private $menu = array();     //conterrà l' output finale
    private $index_selected;
    private $use_session;
    private $in_session = false;

    public function __construct($index_selected="", $use_session=true) {
        $this->index_selected = $index_selected;
        $this->use_session = $use_session;
        if($use_session && !isset($_SESSION)) session_start();
    }
    
    /**
     * Se il menù è in Sessione, restituisce true. Per visualizzarlo occorre eseguire il close()
     * @return boolean true se il menù è già in sessione. In caso contrario restituisce false.
     */
    
    public function getFromSession(){
        if(!$this->use_session) return false;                          //torno false perchè voglio rigenerare il menù poichè use_session è false
        if(isset($_SESSION["SMARTYMENU".$this->index_selected])){
            $this->in_session=true;
            $this->menu=$_SESSION["SMARTYMENU".$this->index_selected];
        } 
    }

    /**
     * Aggiunge una opzione al menù
     * @param type array $arr è un array associativo contenente le seguenti opzioni:
     * Nomi chiavi ammesse:
     * Text: testo da visualizzare sul pulsante
     * Link: link alla pagina su cui puntare (facoltativo) default considera "#"
     * Index: indice posizione voce menù es: 1  1.1   1.2   1.2.1  ecc.
     * @return type none
     */
    
    public function addItem($arr) {
        if($this->in_session) return;
        if (!array_key_exists("Index", $arr))
            die("specificare un valore per Index");
        //$arr["Index"] = preg_match("/(\.0$|\.$)/",$arr["Index"]);   // rimuovo l' eventuale . .0 finale. Serve nel caso in cui addItem viene gestituito da un ciclo for
        //echo $arr["Index"] ."<br />";
        $obj = new menuitem($arr);
        $this->objarr[$arr["Index"]] = $obj;
    }

// close public function addItem

/**
 *Determina le posizioni in cui inserire gli item nell' array menu ed effettua chiamate ai metodi addMenu e addSubMenu che si occupano proprio dell' inserimento. 
 */
    private function setMenuArray() {
        if (!count($this->objarr))
            die("Il menù non ha elementi.");
        //ordino l' array in base alle chiavi. una volta ordinato l' array aggiungo la chiave indicante il livello. 
        //l' ordine numerico mi serve solo ad ordinare l' array. Una volta che è ordinata sarà importante solo il livello in cui andrà posizionato l' item.
        //ottengo l' array con gli index che indicano le varie posiziono
        $arr_keys = array_keys($this->objarr);

        sort($arr_keys);   //ordino l' array $arr_keys

        $lastlevel = 1;
      
        for ($i = 0; $i < count($arr_keys); $i++) {

            $obj = $this->objarr[$arr_keys[$i]];          //prelevo l' oggetto in base alla sequenza di chiavi ordinata

            $level = $this->getItemLevel($obj);           //calcolo quale dovrà essere la profondità dell' oggetto nel sottomenù
  
            if ($level == 1) {                            //se si tratta del primo oggetto
                $this->addToMenu($obj);
            } else if ($level > $lastlevel) {  //primo item che apre un sottomenù
                $this->addToSubMenu($obj, 1);
            } else if ($level < $lastlevel) {         //è il caso in cui è < ma diverso da 1. Si collegherà al submenù che precede l' ultimo o il penultimo, ecc. in base al salto di profondita rispetto al caso precedente.
                $this->addToSubMenu($obj, $level - $lastlevel);
            }else
                $this->addToSubMenu($obj, 0);        //è il caso in cui il livello rimane uguale e non si crea quindi un nuovo sottomenù

            $lastlevel = $level;
          
        }//close for
    }

    /**
     *
     * @param type $obj oggetto menuitem
     * @return type int restituisce la profondità che dovrà avere l' item nel sottomenù. Es 1.2 è il 2° livello, 1.2.1 è il terzo e così via.
     */
    private function getItemLevel($obj) {
        return count(explode(".", $obj->getIndex()));
    }

    /**
 *Crea fisicamente l' array item da agganciare al menù o ad un sottomenù.
 * @param type $obj Oggetto di tipo menuitem relativo all' item da convertire in array()
 */
    private function makeMenuItem($obj) {
        if($this->index_selected!="" && $this->index_selected == $obj->getIndex())
           $this->setSelectedAttribute($obj);
        $arrtemp = array(
            'text' => $obj->getText()
        );
        if ($obj->getLink() != "")
            $arrtemp["link"] = $obj->getLink();
        if ($obj->getClass() != "")
            $arrtemp["class"] = $obj->getClass();

        return $arrtemp;
    }

    /**
     * Aggiunge a menù
     * @param type $obj Oggetto di tipo menuitem da aggiungere al menù (array principale)
     */
    private function addtoMenu($obj) {
        $this->menu[] = $this->makeMenuItem($obj);
    }
    
    /**
     * Aggiunge a menù
     * @param type $obj Oggetto di tipo menuitem da aggiungere ad un submenù
     * @param type int $first. Indica dove inserire secondo il seguente criterio:
     * 0 Indica che non si tratta del primo elemento. Quindi aggiunge subito dopo l' ultimo inserimento.
     * 1 Indica che deve creare un submenù e inserire subito (1 sta per primo elemento del submenù).
     * <0 Un valore negativo indica che deve risalire l' array menu del numero di submenù specificati prima di effettuare l' inserimento.
     */

    private function addToSubmenu($obj, $first) {   //la prima volta crea il sottomenù
        if (!$first) {
            eval(" \$this->menu" . $this->getFinal($first) . "[] =  \$this->makeMenuItem(\$obj);");
        } else if ($first < 0) {
            eval(" \$this->menu" . $this->getFinal($first) . "[] =  \$this->makeMenuItem(\$obj);");
        } else {
            eval(" \$this->menu" . $this->getFinal($first) . "['submenu'] =  array(\$this->makeMenuItem(\$obj));");


            //   print_r($this->menu);
            //   echo "<br />";
            //   echo $this->getFinal($first) . "<br />";
        }//close else
    }

    /**
     *Il metodo addToSubmenu() si appoggia a quest' altro per trovare il percorso dell' array  (si tratta della seguenza parentesi quadre con indici)
     * @param type int $first vedere il metodo addToSubmenu 
     * @return type string restituisce il percorso array come seguenza di parentesi quadre e indici
     */
    private function getFinal($first) {  //torna il path array che permetterà di accedere all' ultimo elemento.
        $arr = $this->menu;                            //$arr viene esplorato fino in fondo
        $path = "";
        if ($n = count($arr)) {     //se $arr non è vuoto  if 1
            $path .="[" . ($n - 1) . "]";

            $arr = $arr[$n - 1];

            while (1) {
                if (array_key_exists("submenu", $arr)) {         //if 2
                    $path .= "['submenu']";
                    $arr = $arr['submenu'];
                    if ($n = count($arr)) {     //se $arr non è vuoto    //if 3
                        $path .= "[" . ($n - 1) . "]";
                        $arr = $arr[$n - 1];
                    }//close if 3
                }//close if 2
                else
                    break;
            }//close while
        }// close if 1
        if (!$first)
            $path = substr($path, 0, strrpos($path, '['));
        else if ($first < 0) {
            for($i=0; $i<-$first; $i++){
                $path = substr($path, 0, strrpos($path, '[\'submenu\']'));
                $path = substr($path, 0, strrpos($path, '['));
            }
        }
        return $path;
    }

//close function

    
    /**
     *Restituisce l' indice dell' item parent a cui agganciare
     * @param type object $obj (Item da agganciare) oggetto di tipo menuitem
     * @return type string rappresentante un indice item es. 1.1, 1.2, ecc
     */

    private function getParentIndex($obj) {

        if (!strrpos($obj->getIndex(), '.'))
            return $obj->getIndex();

        return substr($obj->getIndex(), 0, strrpos($obj->getIndex(), '.'));
    }

// close private function getParentIndex($obj)
    
  /**
   *Aggiunge class="selected" al tag li dell' oggetto menuitem $obj
   * @param type object $obj  Oggetto di tipo menuitem 
   */  
    private function setSelectedAttribute($obj){
        $cl=$obj->getClass();
        if($cl!="")$cl.=" ";
        $obj->setClass($cl."selected");
    }

    
    public function close() {
        if(!$this->in_session){
            $this->setMenuArray();
            $_SESSION["SMARTYMENU".$this->index_selected] = $this->menu;
        }
        return $this->menu;    
    }

}

//close class menu
?>
