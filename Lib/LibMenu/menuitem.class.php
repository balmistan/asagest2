<?php

//require_once 'menudataboundobject.class.php';
/* l' item è la voce menù. Ogni istanza di oggetto di questa classe identifica una voce nel menù */

class menuitem extends menudataboundobject {

    protected $Text = "";         //voce che apparirà come opzione nel menù
    protected $Link = "#";        //link al quale si viene reindirizzati cliccando su una voce del menù
    protected $Index = "";        //identificativo associato alla voce menù. Permette di aggiungere nuove voci al menù appendendole in cascata.
    protected $Class = "";        //attributo class per l' item
     
    protected $Level = "";        //inserito automaticamente dalla classe.
    
    public function __construct($arr) {                  //setto gli attributi della classe con i valori passati attraverso l' array delle opzioni
        foreach ($arr as $key => $value) {
            eval('$this->set' . $key . '("' . $value . '");');
        }
    }

}

?>