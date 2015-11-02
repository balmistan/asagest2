<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="all" href="../css/manuale.css" />

        <?php
        require_once("header.php");
        echo "<title>Manuale " . getTitle() . "</title>";
        ?>
        <link rel = "icon" href = "../icone/ico_address.gif" />
    </head>

    <body>
        <?php
        showMenu();
        echo "<div class=\"div_title\">" . getTitle() . "</div>";
        ?>
        <table>
            <tr><td><div class="text">
                   E' importante informare il programma sulle giacenze Agea al primo utilizzo del software. Questo è utile alla compilazione dei registri Agea.
                   <span class="red">E' importante effettuare questa operazione prima di qualsiasi distribuzione o carico altrimenti i registri non verrebbero inizializzati correttamente.</span>
                    </div></td></tr>
            <tr>
                <td><img src="../immagini/giacenzainiziale.png" alt="">&nbsp;</img></td></tr>
            <tr>
                <tr><td><div class="text">
                            Non esiste tale operazione per i viveri delle donazioni in quanto per essi il programma non gestisce le operazioni di carico / giacenza ma solo scarico. 
                        </div></td></tr>
        </table>
        <?php
        showMenu();
        ?>
    </body>

</html>
