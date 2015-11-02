<?php

//preleva i dati da una tabella contenente le giacenze iniziali ma si appoggia anche alla tabella product
class giacenzainiziale {

    private $db;

    public function __construct() {
        $this->db = new db();
    }

    /**
     * Le giacenze iniziali saranno modificabili solo se non sono state effettuate ancora distribuzioni Agea o carichi.
     * Posso dedurre questa condizione dal fatto che il registro Agea è ancora vuoto.
     */
    /*   public function checkIfModifiable() {
      $arr_temp = $this->db->getRow("all8registercum", "id_insert");
      if (count($arr_temp))
      return FALSE;
      return TRUE;               //Registro ancora vuoto ---> gicenze modificabili
      }
     */
    //I name dei prodotti inizieranno con load_ seguiti dall' id prodotto. Dovrò quindi scansionare i dati ricevuti in POST.
    public function saveGiacenze($arr_post) {

        //Scansiono l' array estraendo per ogni voce id_prodotto e quantità e li inserisco in un array temporaneo:

        $arr_temp = array();

        foreach ($arr_post as $key => $value) {
            if (strpos($key, "load_") !==FALSE) {       //Se trova load_ ad inizio stringa...
                $id_product = intval(substr($key, 5));
                $arr_temp[$id_product] = $value;
            }
        }
       
        //A questo punto per ogni prodotto nell' array temporaneo effettuerò inserimento o aggiornamento della tabella giacenzeiniziali.

        foreach ($arr_temp as $idp => $value) {
           
            //Verifico se l' id prodotto è già in tabella

            $arr_giacza = $this->db->getRow("giacenzeiniziali", "giacza", array(
                array("where", "id_product", "=", $idp, TRUE)
            ));

            if (count($arr_giacza) == 0) { //Se il prodotto non è presente, aggiungo
                $this->db->insert("giacenzeiniziali", array("id_product" => $idp, "giacza" => $value));
            } else {  //è già in tabella quell' id_product. Aggiorno solo se la giacenza è cambiata
                if ($arr_giacza["giacza"] != $value) {
                    $this->db->update("giacenzeiniziali", array("giacza" => $value), array(
                        array("where", "id_product", "=", $idp, true)
                    ));  //Aggiungere limit 1
                }
            }
        }// close foreach 
    }

// close  public function saveGiacenze

    public function getGiacenzeIniziali() {
        $arr_product = $this->db->getRows("product", array("id_product", "name_product", "measureunity", "modifiable"), array(), array("order", "by", "position_in_a8"));

        $arr_giacze_temp = $this->db->getRows("giacenzeiniziali", "*");

        //Creo un array in cui le chiavi sono gli id dei prodotti mentrei valori sono le giacenze:

        $arr_giacze = array();

        for ($i = 0; $i < count($arr_giacze_temp); $i++) {
            $arr_giacze[$arr_giacze_temp[$i]["id_product"]] = $arr_giacze_temp[$i]["giacza"];
        }

        //Scorro $arr_product aggiungendo l' informazione sulla giacenza:

        for ($i = 0; $i < count($arr_product); $i++) {

            if (array_key_exists($arr_product[$i]["id_product"], $arr_giacze)) {
                $arr_product[$i]["giacza"] = $arr_giacze[$arr_product[$i]["id_product"]];
            } else {
                $arr_product[$i]["giacza"] = 0;
            }
        }

        return $arr_product;    //tiene conto solo degli id_product della tabella product ignorando gli id_product eventualmente rimossi ma presenti ancora nella tabella giacenze iniziali.
    }

// close public function getGiacenzeIniziali()

    /**
     * Viene chiamata quando si aggiorna product. 
     */
    public function initGiacenze() {
        //Ottengo gli id dei prodotti presenti nella tabella product
        $arr_id_prod_1_temp = $this->db->getRows("product", "id_product");

        //Ordino l' array in modo da avere un nuovo array con gli id prodotti
        $arr_id_prod_1 = array();

        for ($i = 0; $i < count($arr_id_prod_1_temp); $i++) {
            array_push($arr_id_prod_1, $arr_id_prod_1_temp[$i]["id_product"]);
        }


        //Ottengo gli id prodotti presenti nella tabella giacenzeiniziali:

        $arr_id_prod_2_temp = $this->db->getRows("giacenzeiniziali", "id_product");

        //Ordino l' array in modo da avere un nuovo array con gli id prodotti
        $arr_id_prod_2 = array();

        for ($i = 0; $i < count($arr_id_prod_2_temp); $i++) {
            array_push($arr_id_prod_2, $arr_id_prod_2_temp[$i]["id_product"]);
        }

        //Per ogni prodotto, se non presente in tabella prodotti, lo elimino:

        foreach ($arr_id_prod_2 as $id_val) {
            if (!in_array($id_val, $arr_id_prod_1)) {
                $this->db->delete("giacenzeiniziali", array(
                    array("where", "id_product", "=", $id_val, true)
                ));       //Aggiungere il limit 1
            }
        }
        
        //Adesso per ogni prodotto presente nella tabella product, se non già presente in giacenze iniziali, lo aggiungo attribuendogli quantità 0:
        
        foreach ($arr_id_prod_1 as $id_val) {
             if (!in_array($id_val, $arr_id_prod_2)) {
                 $this->db->insert("giacenzeiniziali", array("id_product"=>$id_val, "giacza"=>9999));
             }
        }
        
    }

// close public function initGiacenze()
}

//Class close