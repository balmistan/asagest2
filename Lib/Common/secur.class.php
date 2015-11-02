<?php

class secur {

    public static function addSlashes(&$input) {  //effettuo la chiamata subito appena ricevo i dati con $_POST o $_GET
        if (get_magic_quotes_gpc())
            return;
        secur::addSlashes2($input);
    }

    public static function stripSlashes(&$input) { //effettuo la chiamata subito prima di inviare i dati al form.    
        secur::stripSlashes2($input);
    }

    private static function addSlashes2(&$input) {
        if (!is_array($input))
            $input = addslashes($input);
        else
            foreach ($input as $key=>$val) {
                secur::addSlashes2($input[$key]);
            }
    }

    private static function stripSlashes2(&$input) {   
         if (!is_array($input)){
            $input = stripslashes($input);
            $input = strip_tags($input);
         }else
            foreach ($input as $key=>$val) {
                secur::stripSlashes2($input[$key]);
            }
    }

}

//class close
?>
