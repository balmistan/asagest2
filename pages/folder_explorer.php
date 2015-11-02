<?php

function dir_list($directory = FALSE) {
    $dirs = array();
    $files = array();

    if ($handle = opendir("./" . $directory)) {
        while ($file = readdir($handle)) {
            if (is_dir("./{$directory}/{$file}")) {
                if ($file != "." & $file != "..")
                    $dirs[] = $file;
            }
            else {
                if ($file != "." & $file != "..")
                    $files[] = $file;
            }
        }
    }
    closedir($handle);

    reset($dirs);
    sort($dirs);
    reset($dirs);

    reset($files);
    sort($files);
    reset($files);

    echo "<ul><strong>Cartelle:</strong>\n";
    while (list($key, $value) = each($dirs)) {
        $d++;
        echo "<li><a href=\"{$value}\">{$value}/</a>\n";
    }
    echo "</ul>\n";
    echo "<ul><strong>Files:</strong>\n";
    while (list($key, $value) = each($files)) {
        $f++;
        echo "<li><a href=\"{$directory}{$value}\">{$value}</a>\n";
    }
    echo "</ul>\n";

    if (!$d)
        $d = "0";
    if (!$f)
        $f = "0";
    echo "Sono presenti <strong>{$d}</strong> cartelle e <strong>{$f}</strong> file(s).</strong>\n";
}

// ==================================
// Richiamo la funzione
// per vedere cosa contiene
// la cartella specificata.
// E' necessario specificare il
// percorso esatto di una cartella
// esistente sul server
// ==================================
dir_list("cartella_di_esempio/");
?>