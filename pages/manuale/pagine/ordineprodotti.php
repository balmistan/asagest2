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
                       Nel paragrafo precedente abbiamo visto come indicare al programma quali sono i prodotti Agea da distribuire.
                       Forniamo adesso l' informazione sull' ordine di apparizione nell' allegato 8.
                       La pagina di configurazione è raggiungibile da Varie &#8594; Configurazioni &#8594; Prodotti Agea &#8594; Ordina
                    </div></td></tr>
             <tr>
                <td><img src="../immagini/ordina.png" alt="">&nbsp;</img></td></tr>
            <tr>
                <tr><td><div class="text">
                            Per cambiare l' ordine di visualizzazione è sufficiente spostare le icone col mouse.
                        </div></td></tr>
        </table>
        <?php
        showMenu();
        ?>
    </body>

</html>
