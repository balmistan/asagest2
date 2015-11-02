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
                        L' operazione di setup viene effettuata la prima volta che si utilizza il software. Essa consiste nel fornire al programma tutte le informazioni necessarie a poter lavorare.
                        Si tratta di un insieme di operazioni da effettuare elencate nel menù sotto la voce Varie&#8594;Configurazioni. Tali opzioni sono accessibili solo agli utenti admins (vedere per maggiori dettagli il paragrafo sui tipi di utente).
                    </div></td></tr>
            <tr>
                <td><img src="../immagini/setupiniziale.png" alt="">&nbsp;</img></td></tr>
            <tr>
                <tr><td><div class="text">
                            Ciascuna operazione è stata descritta in modo approfondito nei paragrafi successivi.
                        </div></td></tr>
        </table>
        <?php
        showMenu();
        ?>
    </body>

</html>
