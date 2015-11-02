<?php

class logger {

    protected $filename;
    protected $fileHandle;
    private $enabled;

    public function __construct($filename, $enabled = false) {
        $this->enabled = $enabled;
        if ($this->enabled) {
            $this->filename = $filename;
            $this->fileHandle = fopen($this->filename, 'a+') or die("Logger::log -> can't open file");
        }
    }

    public function log($string) {
        if ($this->enabled) {
            $message = "[" . date("Y-m-d H:i:s") . "] $string\n";
            fwrite($this->fileHandle, $message);
        }
    }

    public function rawLog($data) {
        if ($this->enabled) {
            $message = "[" . date("Y-m-d H:i:s") . "] " . $this->vardump($data) . "\n";
            fwrite($this->fileHandle, $message);
        }
    }

    private function vardump() {
        if ($this->enabled) {
            ob_start();
            $var = func_get_args();
            call_user_func_array('var_dump', $var);
            return ob_get_clean();
        }
    }

    public function __destruct() {
        if ($this->enabled) {
            fclose($this->fileHandle);
        }
    }

}

?>
