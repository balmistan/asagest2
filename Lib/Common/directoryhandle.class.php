<?php

class directoryhandle {

    public function __construct() {
        
    }

    public function listdir($path) {
        $content = array();
        
        if(is_file($path)){
            $dir=null;
            $content[] = $path;
        } else 
            $dir = dir($path);
        
        while ($dir && $item = $dir->read()) {
            if (in_array($item, array(".", "..")))
                continue;


            $file = realpath($path . "/" . $item);
            if (is_dir($file))
                $content = array_merge($content, listdir($file));
            else
                $content[] = $file;
        } // close while

        return $content;
    }

//function close



    public function getFileList($path) {
        $arr = $this->listdir($path);
        $arr_files = array();
        foreach ($arr as $file) {
            if (is_file($file))
                $arr_files[] = $file;
        }
        sort($arr_files);
        return $arr_files;
    }

    public function getDirectoryList($path) {
        $arr = $this->listdir($path);
        $arr_dirs = array();
        foreach ($arr as $dir) {
            if (is_dir($dir))
                $arr_dirs[] = $dir;
        }
        sort($arr_dirs);
        return $arr_dirs;
    }

    /**
     * Comprime una cartella o file
     * @param type $arr_source (cartella contenente i link ai file e directory da aggiungere all' archivio con relativo path)
     * @param type $dest (mome della cartella o file compresso che verrà creato)
     * @param type $esclude array di stringhe contenente tutti i percorsi da non includere nell' archivio compresso.
     */
    public function zip($arr_source, $dest, $pre_esclude = "") {

        $archive = new ZipArchive();

        if ($archive->open($dest, ZIPARCHIVE::CREATE) !== true)
            echo "Impossibile creare l'archivio!";

        

        foreach ($arr_source as $source) {

            $list = $this->listdir($source);
            
            foreach ($list as $file) {

                $pos = strpos($file, $pre_esclude);

                $substring = substr($file, $pos);

                $archive->addFile($substring);
            }
        }
        $archive->close();
    }

}

//class close
?>
