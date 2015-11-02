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
                <td><img src="../immagini/prodottidonazioni.png" alt="">&nbsp;</img></td></tr>
            <tr><td><div class="text">
Alla pagina per la configurazione dei prodotti delle donazioni si accede dal menù in alto andando su 
Varie&#8594;Configurazione&#8594;Prodotti Donaz. Lo sfondo della pagina è celeste anzichè grigio. 
La configurazione avviene esattamente come per i prodotti Agea.
Per Qtà/unità vale quanto detto per i prodotti Agea. Il Qtà/unità multiplo può essere ad esempio usato in quei
casi in cui si distribuiscono prodotti diversi che rientrano nella stessa tipologia ma le quantità non sono tra 
loro multiple. Un esempio potrebbe essere il tonno in scatola. Esistono ad esempio scatolette da 80gr così come 
esistono quelle da 200gr. Non essendo 200 multiplo di 80 si dovrà aggiungere un ulteriore icona.
Naturalmente quando verrà distribuito verrà considerato lo stesso prodotto cambierà solo la quantità distribuita. 
Una volta effettuata una distribuzione o carico l' unità di misura del prodotto così come il nome non possono più essere modificati.
E' importante eseguire tutte le modifiche necessarie prima di utilizzare il software.
Possono tuttavia essere aggiunti nuovi prodotti in seguito.
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>