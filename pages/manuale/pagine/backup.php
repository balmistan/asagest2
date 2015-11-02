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
                        Il backup permette di fare la copia dei dati come si trovano allo stato attuale. 
                        Può essere utile fare dei backup periodicamente in modo da poter in caso di problemi ripristinare lo stato che si aveva in una determinata data, ad una determinata ora.
                        Il backup è necessario effettuarlo ad esempio se si ha intenzione di formattare il PC. Come vedremo tra poco oltre a creare la copia di backup questa dovrà essere scaricata su un supporto esterno (es. pendrive).
                        La funzionalità di backup è eseguibile solo da utenti admin andando su Varie&#8594;Configurazioni&#8594;Backup o in Varie&#8594;Backup a seconda della versione che si sta usando.
                        Apparirà la seguente pagina:
                    </div></td></tr>
            <tr>
                <td><img src="../immagini/backup1.png" alt="">&nbsp;</img></td></tr>
            <tr><td><div class="text">
                        Per effettuare il backup bisognerà cliccare su Nuovo Backup. La cartella contenente il backup apparirà sulla pagina e potrà essere scaricata cliccandovi sopra. La stessa potrà essere rimossa cliccando sulla x rossa che appare accanto.

                    </div></td></tr>
            <tr>
                <td><img src="../immagini/backup2.png" alt="">&nbsp;</img></td></tr>

            <tr><td><div class="text">
                        Il backup una volta creato è sull' hard disk. Si possono conservare più backup. In tal caso appariranno le varie cartelle nela pagina.
                        Esse possono essere rimosse cliccando sul cestino.
                        Cliccando su Home si torna infine nella home page di Asagest.
                    </div></td></tr>
        </table>
        <?php
        showMenu();
        ?>
    </body>

</html>
