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
                        Durante gli aggiornamenti l' unica cartella da non sovrascrivere mai è la Personal, che in genere non sarà presente negli aggiornamenti.
                        L' aggiornamento va eseguito sempre all' ultima versione disponibile e si effettua sovrascrivendo (non sostituendo) le cartelle e i file esistenti (eccetto Personal).
                    </div></td></tr>


        </table>

        <?php
        showMenu();
        ?>

    </body>

</html>
