<?php

class BlockPdf extends FPDF {

    private $ArrData;

    public function __construct($arr_data_input, $orientation = 'P', $unit = 'cm', $format = 'A5') {

        $this->ArrData = $arr_data_input;
        parent::__construct($orientation, $unit, $format);
    }

// Page header
    public function Header() {
        $border = 0;

        $this->SetFont('Arial', '', 8);
        
        $this->Cell(2.5, 0.5, "Ric. Num. " . $this->ArrData["recnum"], $border, 0, 'L');
        $this->Cell(1.5, 0.5, "[ " . $this->ArrData["sheetid"] . " ] ", $border, 0, 'R');
//$this->Cell(4.5, 0.5, "Ric. Num. " . $this->ArrData["recnum"] . " del " . $this->ArrData["date"], $border, 0, 'L');
       $this->SetFont('Arial', '', 14);

        $this->Cell(4.5, 0.5, "Scheda n. " . $this->ArrData["id_fam"], $border, 0, 'C');
         
        $this->SetFont('Arial', '', 8);
        
        $this->Cell(4.5, 0.5, "Data: " .  $this->ArrData["date"], $border, 1, 'C');
        
       // $this->Cell(1.5, 0.5, "[ " . $this->ArrData["sheetid"] . " ] ", $border, 0, 'L');

        //$this->Cell(2.5, 0.5, $this->ArrData["date"], $border, 0, 'R');


        


       // $this->SetFont('Arial', '', 10);

        //$this->Cell(5, 0.5, iconv('UTF-8', 'windows-1252', " N° comp: ") . $this->ArrData["num_indig"], $border, 1, 'C');

        $this->SetFont('Arial', '', 12);

        //$this->SetFont('Arial', '', 6);
        //$this->Cell(1.5, 0.8, "[ " . $this->ArrData["sheetid"] . " ] ", $border, 0, 'L');
        
        $this->SetFont('Arial', '', 12);
        $this->Cell(13, 0.8, "Spett.le " . iconv('UTF-8', 'windows-1252', $this->ArrData["surname"] . " " . $this->ArrData["name"]) . " (".$this->ArrData["num_indig"].")", $border, 1, 'C');

        //$this->Cell(5, 0.5, iconv('UTF-8', 'windows-1252', " N° comp: ") . $this->ArrData["num_indig"], $border, 2, 'C');
        
        $addr = iconv('UTF-8', 'windows-1252', $this->ArrData["addr"]);

        $com = "";

        if ($this->ArrData["com"] != "")
            $com = iconv('UTF-8', 'windows-1252', $this->ArrData["com"]) . " (" . $this->ArrData["prov"] . ")";

        $this->Cell(13, 0.5, $addr . " " . $com, $border, 1, 'C');
        

       
        
         //Inserisco il Comitato
        $this->SetFont('Arial', '', 10);
        $this->Cell(13, 0.5, iconv('UTF-8', 'windows-1252', $this->ArrData["comit"]), $border, 1, 'C');


        $this->SetFont('Arial', '', 9);
        $this->Cell(13, 0.4, "Prodotti gratuiti non commerciabili", $border, 1, 'C');
    }

    function Body() {
        $border = "B";
        $this->AddPage();
        $this->SetDrawColor(215, 215, 215);

        if (count($this->ArrData["agea"])) {
            $this->Ln();
            $this->SetFont('Arial', '', 12);
            $this->Cell(13, 0.8, "Prodotti Agea", 0, 1, 'C');
            $this->SetFont('Arial', '', 10);
            foreach ($this->ArrData["agea"] as $arr_values) {
                $this->Cell(1.5, 0.5, $arr_values["qty"] * 1, $border, 0, 'C');
                $this->Cell(1.5, 0.5, $arr_values["measureunity"], $border, 0, 'L');
                $this->Cell(10, 0.5, iconv('UTF-8', 'windows-1252', $arr_values["name_product"]), $border, 1, 'L');
            }
        }

        if (count($this->ArrData["banco"])) {
            $this->Ln();
            $this->SetFont('Arial', '', 12);
            $this->Cell(13, 0.5, "Prodotti Donazioni", 0, 1, 'C');
            $this->SetFont('Arial', '', 10);
            foreach ($this->ArrData["banco"] as $arr_values) {
                $this->Cell(1.5, 0.5, $arr_values["qty"] * 1, $border, 0, 'C');
                $this->Cell(1.5, 0.5, $arr_values["measureunity"], $border, 0, 'L');
                $this->Cell(10, 0.5, iconv('UTF-8', 'windows-1252', $arr_values["name_product"]), $border, 1, 'L');
            }
        }
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-2);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 10);

        $this->Cell(4, 1.5, "Data: ".$this->ArrData["date"], 0, 0, 'L');
        $this->Cell(9, 1.5, "Firma:_______________________________", 0, 0, 'R');
       
    }
    
    function getViewSheetId(){
        return $this->ArrData["sheetid"];
    }

}
