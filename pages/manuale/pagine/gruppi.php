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
                        Per utenti intendo i volontari che hanno accesso al programma.
                        L' accesso avviene mediante uso di username e password personali.
                        Ogni account è identificato univocamente mediante username.
                        Ciò significa che il programma non permette l' assegnazione di uno stesso username a due utenti diversi neanche dopo la disattivazione dell' account.
                        Attualmente esistono sul Sistema due gruppi di utente: Admins e Users.
                        Se un utente viene assegnato al gruppo Admins ha tutti i privilegi ovvero può creare/modificare/disattivare account, può modificare le impostazioni di sistema ed effettuare i backup.
                        Gli utenti del gruppo users possono usare regolarmente il programma per le operazioni comuni ma hanno accesso solo alle configurazioni del proprio account.
                        Possono modificare solo i propri dati personali e la propria password.
                    </div></td></tr>
        </table>
        <?php
        showMenu();
        ?>
    </body>

</html>
