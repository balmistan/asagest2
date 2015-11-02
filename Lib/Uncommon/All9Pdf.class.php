<?php

class All9Pdf extends FPDF {

    private $ArrDbGet;
    private $NumAll9;
    private $Date;
    private $ArrOut;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $arr_dbget, $num_all_9, $date, $arr_out) {
        $this->ArrDbGet = $arr_dbget;
        $this->NumAll9 = $num_all_9;
        $this->Date = $date;
        $this->ArrOut = $arr_out;
        parent::__construct($orientation, $unit, $format);
    }

// Page header
    function Header() {

        // Arial bold 15
        $this->SetFont('Arial', '', 9);
        $border = 0;
        $this->Cell(15);
        $this->Cell(3, 0.5, "Allegato n. 6", $border, 0, 'R');
        $this->Ln(0.3);

        $this->Cell(19, 0.5, "AIUTI UE - REG. UE " . $this->ArrDbGet["reg_ue"], $border, 0, 'C');
        $this->Ln(0.4);
        $this->Cell(19, 0.5, "DICHIARAZIONE DI CONSEGNA AGLI INDIGENTI DI PRODOTTI ALIMENTARI GRATUITI", $border, 1, 'C');
        $this->Ln(0.3);
        $this->Cell(2, 0.5, "NUMERO", "LTB", 0, 'C');
        $this->Cell(2, 0.5, $this->NumAll9, 1, 0, 'C');
        $this->Cell(10);
        $this->Cell(2, 0.5, "DATA", "LTB", 0, 'C');
        $this->Cell(2, 0.5, $this->Date, 1, 1, 'C');
        $this->Ln(0.3);
        $this->Cell(19, 0.4, "Il sottoscritto " . iconv('UTF-8', 'windows-1252', $this->ArrDbGet["legalerappresentante"]), $border, 1, 'L');

        $this->Cell(19, 0.4, "nato a " . iconv('UTF-8', 'windows-1252', $this->ArrDbGet["luogodinascita"]) . " il " . $this->ArrDbGet["datadinascita"], $border, 1, 'L');

        $this->Cell(19, 0.4, iconv('UTF-8', 'windows-1252', "in qualità di legale rappresentante del " . $this->ArrDbGet["nomesede"]), $border, 1, 'L');
        $this->Cell(19, 0.4, iconv('UTF-8', 'windows-1252', "con sede a  " . $this->ArrDbGet["indirizzosede"]), $border, 1, 'L');
        $this->Multicell(19, 0.4, iconv('UTF-8', 'windows-1252', $this->ArrDbGet["corpo_all9"]));
        $this->Ln(0.2);
        $this->Cell(19, 0.4, "DICHIARA", $border, 1, 'C');
        $this->Ln(0.2);
        $this->Cell(19, 0.4, "A) che rappresentanti della struttura di cui in premessa, da me delegati, hanno distribuito in data", $border, 1, 'L');
        $this->Cell(19, 0.4, "odierna, a n. " . $this->ArrOut["serv_indigenti"] . " indigenti i seguenti prodotti:", $border, 1, 'L');
        $this->Ln(0.3);
    }

    function addBody() {

        $this->AddPage();

        $this->SetFont('Arial', '', 8);
        $this->Cell(3);
        $this->Cell(7, 0.4, "PRODOTTO", "LTB", 0, 'C');
        $this->Cell(3, 0.4, iconv('UTF-8', 'windows-1252', "Unità di misura"), 1, 0, 'C');
        $this->Cell(3, 0.4, iconv('UTF-8', 'windows-1252', "Quantità"), "TRB", 1, 'C');

        //ciclo per inserire i prodotti:

        foreach ($this->ArrOut["products"] as $elem) {
            $this->Cell(3);
            $this->Cell(7, 0.4, iconv('UTF-8', 'windows-1252', $elem['name_product']), "LB", 0, 'L');
            $this->Cell(3, 0.4, $elem['measureunity'], "LRB", 0, 'C');
            $elem['qty'] = sprintf("%g", $elem['qty']);
            $this->Cell(3, 0.4, $elem['qty'], "RB", 1, 'C');
        }

        $this->Ln(0.3);
        $this->Cell(19, 0.4, "B) che i su indicati prodotti vengono riportati nel registro di carico e scarico.", 0, 1, 'L');
        $this->Cell(19, 0.4, iconv('UTF-8', 'windows-1252', "Allego fotocopia integrale, fronte e retro di un documento di identità in corso di validità."), 0, 1, 'L');


        $this->Cell(19, 2, "TIMBRO DELLA STRUTTURA CARITATIVA E FIRMA DEL LEGALE RAPPRESENTANTE", "", 1, 'C');
    }

}
