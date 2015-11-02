<?php

class Pdf extends FPDF {

    private $NomeStruttura;
    private $RegUe;
    private $Pagen;
    private $RegisterType;
    private $StartIndexCons;
    private $ArrInd;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $nome_struttura = '', $reg_ue = '', $pagen = '', $register_type = '', $start_index_cons = '') {
        $this->NomeStruttura = $nome_struttura;
        $this->RegUe = $reg_ue;
        $this->Pagen = $pagen;
        $this->RegisterType = $register_type;
        $this->StartIndexCons = $start_index_cons;
        $this->ArrInd = $arr_ind = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j"); //Array indicizzazione pagine registro
        parent::__construct($orientation, $unit, $format);
    }

// Page header
    function Header() {

        // Arial bold 15
        $this->SetFont('Arial', 'I', 12);
        $border = 0;
        $this->Cell(8.5, 0.6, 'STRUTTURA CARITATIVA', $border, 0, 'C');
        $this->Cell(14, 0.6, $this->RegisterType, $border, 1, 'C');
        $this->Cell(8.5, 0.6, iconv('UTF-8', 'windows-1252', $this->NomeStruttura), $border, 0, 'C');
        $this->Cell(14, 0.6, iconv('UTF-8', 'windows-1252', $this->RegUe), $border, 0, 'C');
        $this->Cell(4.5, 0.6, "Pag. " . $this->Pagen . " " . $this->ArrInd[$this->PageNo() - 1], $border, 0, 'R');
        // Line break
        $this->Ln(1);
    }

    function addBody($arr_products, $arr_view) {

        //arr_products ha già i prodotti suddivisi in gruppi di 4 dato che ogni tabella/pagina contiene al massimo 4 prodotti.
        //Ciclo per inserire le pagine
        for ($i = 0; $i < count($arr_products); $i++) {
            $this->AddPage();
            //Inserisco la tabella
            //Prima riga header
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(5, 0.5, "DOC. CONSEGNA", 1, 0, 'C');
            for ($j = 0; $j < 3; $j++) {
                $this->Cell(6, 0.5, iconv('UTF-8', 'windows-1252', $arr_products[$i][$j]["nameproduct"]), 1, 0, 'C');
            }
            $this->Cell(4, 0.5, "", 1, 1, 'C');
            //seconda riga header

            $this->Cell(3, 0.5, "Data", 1, 0, 'C');
            $this->Cell(2, 0.5, iconv('UTF-8', 'windows-1252', "N°"), 1, 0, 'C');
            for ($k = 0; $k < 3; $k++) {
                $this->Cell(1.5, 0.5, iconv('UTF-8', 'windows-1252', "unità m."), 1, 0, 'C');
                $this->Cell(1.5, 0.5, "carico", 1, 0, 'C');
                $this->Cell(1.5, 0.5, "scarico", 1, 0, 'C');
                $this->Cell(1.5, 0.5, "giac.za", 1, 0, 'C');
            }
            $this->Cell(4, 0.5, "n. indigenti", 1, 1, 'C');

            //Inserisco le righe body della tabella
            $this->SetFont('Arial', 'I', 10);



            foreach ($arr_view as $key => $value) {

                //Devo impostare il colore della riga

                $isload = false;

                for ($cnt = 0; $cnt < count($arr_products); $cnt++) {

                    if (
                            trim($arr_view[$key][$arr_products[$cnt][0]["idproduct"]]["carico"]) != "" ||
                            trim($arr_view[$key][$arr_products[$cnt][1]["idproduct"]]["carico"]) != "" ||
                            trim($arr_view[$key][$arr_products[$cnt][2]["idproduct"]]["carico"]) != ""           
                    ) {
                        $isload = true;
                        break;
                    }
                }

                if ($isload)
                    $this->SetTextColor(255, 0, 0);   //colore rosso
                else
                    $this->SetTextColor(0, 0, 0);

                $this->Cell(3, 0.5, $arr_view[$key]["date"], 1, 0, 'C');    //Data
//Per numrif bisogna traslare in caso di scarico
                $numrif = $arr_view[$key]["numrif"];
                if (trim($numrif) != "" && !$isload)
                    $numrif = intval($numrif) + intval($this->StartIndexCons) - 1;
                $this->Cell(2, 0.5, $numrif, 1, 0, 'C');    //N° cons.

                for ($k = 0; $k < 3; $k++) {
                    //L' unità di misura e il resto, lo visualizzo se la riga non è vuota. Per vedere se non è vuota, verifico la presenza della data.
                    if (trim($arr_view[$key]["date"]) != "") {
                        $this->Cell(1.5, 0.5, $arr_products[$i][$k]["umis"], 1, 0, 'C');   //Unità misura
                        $this->Cell(1.5, 0.5, $arr_view[$key][$arr_products[$i][$k]["idproduct"]]["carico"], 1, 0, 'C');                                //carico
                        $this->Cell(1.5, 0.5, $arr_view[$key][$arr_products[$i][$k]["idproduct"]]["scarico"], 1, 0, 'C');                            //scarico
                        $this->Cell(1.5, 0.5, $arr_view[$key][$arr_products[$i][$k]["idproduct"]]["giacza"], 1, 0, 'C');                            //giacenza
                    } else {
                        //campi vuoti
                        $this->Cell(1.5, 0.5, "", 1, 0, 'C');   //Unità misura
                        $this->Cell(1.5, 0.5, "", 1, 0, 'C');                                //carico
                        $this->Cell(1.5, 0.5, "", 1, 0, 'C');                            //scarico
                        $this->Cell(1.5, 0.5, "", 1, 0, 'C');
                    }
                }//close for
                if (isset($arr_view[$key]["date"]))
                    $this->Cell(4, 0.5, $arr_view[$key]["numindig"], 1, 1, 'C');   //numrif
                else
                    $this->Cell(4, 0.5, "", 1, 1, 'C');   //numrif
            }
          
            $this->SetFont('Arial', 'I', 12);
           
            $this->SetXY(10, $this->GetY());
            $this->Cell(18, 2, "TIMBRO DELLA STRUTTURA CARITATIVA E FIRMA DEL LEGALE RAPPRESENTANTE", "B", 1, 'L');

        }//Chiusura ciclo pagine
    }

}
