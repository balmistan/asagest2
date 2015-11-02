<html>
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
            <tr>
                <td><img src="../immagini/cercascheda.png">&nbsp;</img></td></tr>
            <tr><td><div class="text"><pre>
Per modificare una scheda bisognerà prima cercarla. Per far ciò si può ad esempio andare alla pagina 
Cerca Scheda attraverso il menù in alto. Si clicca sulla riga contenente il nominativo della persona
(La colonna corrispondente al numero scheda non è sensibile al click del mouse. Ciò è stato fatto per 
evitare problemi derivanti dall' utilizzo con tablet).
Cliccando sulla riga si accederà ad una pagina di riepilogo:
                        </pre>
                        <tr>
                <td><img src="../immagini/riepilogo.png">&nbsp;</img></td></tr>
            <tr><td><div class="text"><pre>
Cliccando su modifica si accederà alla pagina di modifica.
Dalla pagina di riepilogo non è possibile modificare infatti alcun dato.
                        </pre>
                </td>
            </tr>
            <tr>
                <td><img src="../immagini/modificascheda.png">&nbsp;</img></td></tr>
            <tr><td>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>