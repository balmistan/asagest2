<?php

/**
 * Image class for image handling
 *
 * This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
 * @author Carmelo San Giovanni <admin@barmes.org>
 * @version 3.9
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v2.0
 * @package libss
 */
class image {

    private $filename;
    private $image;
    private $width;
    private $height;
    private $ratio;
    private $type;

    /**
     * Costruttore di classe Image, inizializza le variabili
     * @access public
     * @param string $filename contiene il nome del file immagine
     */
    public function __construct($filename) {
        //ini_set('gd.jpeg_ignore_warning', 1);
        //ini_set('gd.png_ignore_warning', 1);

        $this->filename = $filename;
        $this->image = NULL;
    }

    /**
     * Verifica l'esistenza del file
     * @return boolean
     */
    public function exist() {
        return file_exists($this->filename);
    }

    /**
     * Ritorna la dimensione su disco del file
     * @param string $order, l'ordine di grandezza per il risultato
     * @param string $whitsuffix, permette di visualizzare l'ordine di grandezza per il risultato
     * @return mixed, int o string
     */
    public function getSize($order = "KB", $whitsuffix = 0) {
        if (!$this->exist($this->filename))
            return -1;

        switch ($order) {
            case "KB":
                if ($whitsuffix == 0)
                    return (filesize($this->filename) / 1024);
                return (filesize($this->filename) / 1024) . "KB";
            case "MB":
                if ($whitsuffix == 0)
                    return ((filesize($this->filename) / 1024) / 1024);
                return ((filesize($this->filename) / 1024) / 1024) . "MB";
            case "GB":
                if ($whitsuffix == 0)
                    return (((filesize($this->filename) / 1024) / 1024) / 1024);
                return (((filesize($this->filename) / 1024) / 1024) / 1024) . "GB";
            case "TB":
                if ($whitsuffix == 0)
                    return ((((filesize($this->fname) / 1024) / 1024) / 1024) / 1024);
                return ((((filesize($this->fname) / 1024) / 1024) / 1024) / 1024) . "TB";
        }
    }

    /**
     * Ritorna l'estensione del file immagine
     * @return string
     */
    public function getExtension($filename = "") {
        if ($filename == "")
            $filename = $this->filename;
        if (strstr($filename, ".") === FALSE)
            return "";
        $tmp = explode(".", $filename);       //Controlliamo l'estensione
        $filetype = $tmp[count($tmp) - 1];
        return $filetype;
    }

    public function getRealExtension($filename = "") {
        if ($filename == "")
            $filename = $this->filename;
        $filetype = "";
        if (strstr($filename, ".") === FALSE)
            return $filetype;
        $fileinfo = getimagesize($filename);
        $tmp = explode("/", $fileinfo['mime']);       //Controlliamo il tipo di immagine
        $filetype = $tmp[count($tmp) - 1];
        return $filetype;
    }

    private function translate() {
        if ($this->getSize() < 0)
            die("IMAGE::Tanslate --> corrupted file: " . $this->filename);
        $type = strtoupper($this->getRealExtension());
        switch ($type) {
            case 'BMP':
                $this->image = imagecreatefrombmp($this->filename);
                break;
            case 'JPEG':
                $this->image = imagecreatefromjpeg($this->filename);
                break;
            case 'JPG':
                $this->image = imagecreatefromjpeg($this->filename);
                break;
            case 'JPEG':
                $this->image = imagecreatefromjpeg($this->filename);
                break;
            case 'PNG':
                $this->image = imagecreatefrompng($this->filename);
                //imagealphablending($this->image, true);
                break;
            case 'TIF':
                $this->image = imagecreatefromtif($this->filename);
                break;
            case 'TIFF':
                $this->image = imagecreatefromtif($this->filename);
                break;

            default:
                die("IMAGE::Translate --> unsupported filetype:$type!");
        }
        $this->setAttribs();
    }

    public function getWidth() {
        if (!$this->width || !$this->height)
            $this->setAttribs();
        return $this->width;
    }

    public function getHeight() {
        if (!$this->width || !$this->height)
            $this->setAttribs();
        return $this->height;
    }

    public function getRatio() {
        if (!$this->ratio)
            $this->setAttribs();
        return $this->ratio;
    }

    private function setAttribs() {
        if (is_null($this->image))
            $this->translate();
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
        $this->ratio = $this->width / $this->height;
    }

    /**
     * Effettua il cropping di un'immagine
     * @param type int $newWidth, contiene la larghezza dell'immagine di destinazione
     * @param type int $newHeight, contiene l'altezza dell'immagine di destinazione
     * @param type int $x, contiene il punto orizzontale da cui cominciare ad effettuare il cropping 
     * @param type int $y, contiene il punto verticale da cui cominciare ad effettuare il cropping 
     */
    public function crop($newWidth, $newHeight, $x = 0, $y = 0) {
        if (is_null($this->image))
            $this->translate();
        $newImage = imagecreatetruecolor($newWidth, $newHeight);  //Creiamo una nuova immagine e copiamoci sopra quella vekkia ridimenzionata
        imagecopy($newImage, $this->image, 0, 0, $x, $y, $this->width, $this->height);
        $this->width = $newWidth;
        $this->height = $newHeight;
        $this->image = $newImage;
    }

    /**
     * Effettua lo scaling dell'immagine mantenendo le proporzioni nel caso in cui questa sia più grande delle
     * dimensioni specificate nei parametri
     * @param $maxWidth, contiene la larghezza massima che l'immagine deve assumere
     * @param $maxHeight, contiene l'altezza massima che l'immagine deve assumere
     */
    public function resizeIN($maxWidth, $maxHeight, $ratio=-1) {
        if (is_null($this->image))
            $this->translate();
        $newWidth = $this->width;
        $newHeight = $this->height;
        if($ratio!=-1) $this->ratio=$ratio;

        $pending = false;
        if ($this->width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = $newWidth / $this->ratio;
            $pending = true;
        }
        if ($this->height > $maxHeight) {
            $newHeight = $maxHeight;
            $newWidth = $newHeight * $this->ratio;
            $pending = true;
        }
        if ($pending) {
            $newImage = imagecreatetruecolor($newWidth, $newHeight);  //Creiamo una nuova immagine e copiamoci sopra quella vekkia ridimenzionata
            //imagealphablending($newImage, true);
            imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
            $this->width = $newWidth;
            $this->height = $newHeight;
            $this->image = $newImage;
        }
    }

    /**
     * Ruota un'immagine
     * @param int $degrees, contiene l'angolo di rotazione
     * @return unknown_type
     */
    public function rotate($degrees) {
        $this->image = imagerotate($this->image, $degrees, 0);
        $tmp = $this->width;
        $this->width = $this->height;
        $this->height = $tmp;
    }

    /**
     * Ottimizza l'immagine usando l'interlacciamento
     */
    public function optimize() {
        if (is_null($this->image))
            $this->translate();
        imageinterlace($this->image);
    }

    /**
     * Salva l'immagine attuale su file
     * @param $filename [optional] se settato indica il file di destinazione, quando non è settato sovrascrive 
     * il file attuale
     * @param $type, permette di specificare il formato di destinazione del file ("jpg","png","gif","bmp")
     * @param $quality, permette di specificare la qualità dell'immagine di destinazione
     */
    public function save($filename = NULL, $quality = 90) {
        if (is_null($this->image))
            $this->translate();
        if (is_null($filename))
            $filename = $this->filename;
        $type = $this->getExtension($filename);
        switch (strtoupper($type)) {
            case "JPG":
                imagejpeg($this->image, $filename, $quality);
                break;
            case "JPEG":
                imagejpeg($this->image, $filename, $quality);
                break;
            case "PNG":
                imagesavealpha($this->image, true);
                imagepng($this->image, $filename);
                break;
            case "GIF":
                imagegif($this->image, $filename);
                break;
            case "BMP":
                imagewbmp($this->image, $filename);
                break;
            default:
                die("IMAGE::save --> unsupported filetype");
        }
    }

    /**
     * Funzione per aggiungere un'immagine di overlay all'attuale immagine
     * @param $position può essere un valore da 1 a 9 corrispondenti a:
     * 1: In alto a sinistra
     * 2: In alto al centro
     * 3: In alto a destra
     * 4: A sinistra centrato verticalmente
     * 5: Centrato verticalmente ed orizzontalmente
     * 6: A destra centrato verticalmente
     * 7: In basso a sinistra
     * 8: In basso al centro
     * 9: In basso a destra
     * @param $overlayImage, immagine di overlay, dev'essere una png
     */
    public function addOverlayImage($overlayImage, $position = 9) {
        if (is_null($this->image))
            $this->translate();
        //if(strtoupper($this->getExtension($overlayImage))!="PNG") die("IMAGE::addOverlayImage --> Overlay image is not a PNG!");
        //imagealphablending($this->image, true);
        $type = $this->getRealExtension($overlayImage);
        switch (strtoupper($type)) {
            case "JPG":
                $overlay = imagecreatefromjpeg($overlayImage);
                break;
            case "JPEG":
                $overlay = imagecreatefromjpeg($overlayImage);
                break;
            case "PNG":
                $overlay = imagecreatefrompng($overlayImage);
                imagesavealpha($overlay, true);
                break;
            case "GIF":
                $overlay = imagecreatefromgif($overlayImage);
                break;
            case "BMP":
                $overlay = imagecreatefromwbmp($overlayImage);
                break;
            default:
                die("IMAGE::addOverlayImage --> unsupported filetype");
        }




        //Prendiamo le dimensioni dell'immagine di overlay
        $owidth = imagesx($overlay);
        $oheight = imagesy($overlay);

        //se l'immagine di overlay è + grande dell'immagine su cui andiamo a metterla dobbiamo ridimensionarla

        if (($owidth > $this->width) || ($oheight > $this->height)) {
            $oratio = $owidth / $oheight;
            if ($owidth > $this->width) {
                $newOWidth = $this->width;
                $newOHeight = $newOWidth / $oratio;
            }
            if ($oheight > $this->height) {
                $newOHeight = $this->height;
                $newOWidth = $newOHeight * $oratio;
            }
            $newOverlay = imagecreatetruecolor($newOWidth, $newOHeight);  //Creiamo una nuova overlay e copiamoci sopra quella vecchia ridimenzionata
            //imagesavealpha($newOverlay,true);
            imagecopyresampled($newOverlay, $overlay, 0, 0, 0, 0, $newOWidth, $newOHeight, $owidth, $oheight);
            $overlay = $newOverlay;
            $owidth = $newOWidth;
            $oheight = $newOHeight;
        }

        $dest_x = 0;
        $dest_y = 0;
        switch ($position) {
            case 1:
                break;
            case 2:
                $dest_x = (int) ($this->width - $owidth) / 2;
                break;
            case 3:
                $dest_x = ($this->width - $owidth);
                break;
            case 4:
                $dest_y = (int) ($this->height - $oheight) / 2;
                break;
            case 5:
                $dest_x = (int) ($this->width - $owidth) / 2;
                $dest_y = (int) ($this->height - $oheight) / 2;
                break;
            case 6:
                $dest_x = ($this->width - $owidth);
                $dest_y = (int) ($this->height - $oheight) / 2;
                break;
            case 7:
                $dest_y = $this->height - $oheight;
                break;
            case 8:
                $dest_x = (int) ($this->width - $owidth) / 2;
                $dest_y = $this->height - $oheight;
                break;
            case 9:
                $dest_x = $this->width - $owidth;
                $dest_y = $this->height - $oheight;
                break;
            default:
                die("IMAGE::addOverlayImage --> unrecognized posizion.");
        }
        //sovrapponiamo l'immagine di overlay a quella attuale
        imagecopy($this->image, $overlay, $dest_x, $dest_y, 0, 0, $owidth, $oheight);
    }

    /**
     * Visualizza l'immagine attuale
     */
    public function show() {
        if (is_null($this->image))
            $this->translate();
        imagesavealpha($this->image, true);
        header('Content-type: image/jpeg');
        //header('Content-Disposition: attachment; filename='.basename($this->filename));
        //header('Expires: Sat, 04 Jan 2020 08:52:00 GMT');
        //header('Cache-Control: max-age=864000, public');
        imagejpeg($this->image);
    }

    public function delete() {
        @unlink($this->filename);
    }

    public function __destruct() {
        if (!is_null($this->image))
            imagedestroy($this->image);
    }

    /**
     * Cambia il nome del file su cui stiamo compiendo operazioni
     * @param string $newfilename, contiene il nuovo nome
     */
    public function setFileName($newfilename) {
        $this->filename = $newfilename;
    }

    /**
     * Disegna un rettangolo nell' immagine (utile per il debug)
     * @param int $w larghezza
     * @param int $h altezza
     * @param int $x coord x spigolo in alta a sx
     * @param int $y coord y spigolo in alta a sx
     */
    public function drawRect($w, $h, $x, $y) {
        $x1 = intval($x);
        $y1 = intval($y);
        $x2 = $x1+intval($w);
        $y2 = $y1+intval($h);
        if (is_null($this->image))
            $this->translate();
        $color = imageColorAllocate($this->image, 0, 0, 255);
        imageRectangle($this->image, $x1, $y1, $x2, $y2, $color);
    }

}

?>
