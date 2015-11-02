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
                        La pagina è raggiungibile da Varie&#8594;Cerca persone.<br />
                        La ricerca avanzata permette di cercare persone proprio come ricerca scheda. Ha però in più la possibilità di selezionare per fasce di età e sesso.
                        Nel primo caso è necessario che le persone abbiano inserite le date di nascita, mentre nel secondo caso è richiesto il codice fiscale. Se mancano questi dati per alcune persone queste verranno escluse dai risultati di ricerca tranne il caso in cui la ricerca è generica.
                        Ad esempio se si indica tutte le età verranno mostrate anche le persone di cui manca la data di nascita. 
                        - indica informazione non conosciuta mentre -1 o <1 indica età inferiore ad 1 anno.
                        Attraverso questa pagina è possibile conoscere quanti indigenti ci sono per fasce di età.
                        Le persone mostrate sono solo quelle delle schede non disattivate. In fondo alla tabella è indicato il numero di persone trovato.
                    </div></td></tr>
            <tr>
                <td><img src="../immagini/ricercaavanzata.png" alt="">&nbsp;</img></td></tr>
        </table>
        <?php
        showMenu();
        ?>
    </body>

</html>
