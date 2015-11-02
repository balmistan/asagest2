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
                <td><img src="../immagini/prodottiagea.png">&nbsp;</img></td></tr>
            <tr><td><div class="text">
La pagina è raggiungibile dal menù in alto andando su Configurazioni&#8594;Prodotti Agea.
Lo scopo è quello di informare il programma dei prodotti Agea esistenti.
Una pagina analoga esiste anche per i prodotti delle donazioni.
La pagina dei prodotti Agea differisce anche per lo sfondo che è di colore grigio.
La pagina delle donazioni ha invece lo sfondo di colore celeste.
Questa fase di configurazione permette di informare il programma sui prodotti Agea distribuibili e le loro unità di misura ma non sulle quantità presenti in magazzino.
<br /><br /><b>Aggiungere prodotti:</b>
La pagina contiene nell' esempio solo pochi prodotti. 
Per aggiungere un prodotto all' elenco occorre cliccare sul pulsante aggiungi
presente in fondo alla pagina.
Per eliminare un prodotto si clicca sulla x a fine riga.
I dati relativi ad un prodotto sono modificabili interamente solo all' inizio. 
Se si accede alla pagina dopo aver effettuato delle distribuzioni le righe relative ai prodotti distribuiti
non sono più cancellabili. Inoltre non saranno più modificabili ne nome ne unità di misura.
Bisogna prestare attenzione al fatto che i nomi attribuiti ai prodotti quì sono quelli che il programma riporterà
sui registri Agea. Così pure l' unità di misura.
I prodotti elencati in questa pagina sono quelli che appariranno sui registri.
ciascuna riga contiene:
l' <b>immagine del prodotto</b> ciò è importante in quanto la compilazione del blocchetto consegne avviene mediante 
icone. Per inserire o modificare un' immagine occorre fotografare il prodotto con una webcam.
si collega la webcam al pc e poi si clicca su no foto o sull' icona foto che si vuole cambiare.
Si accenderà così la webcam e sarà possibile fotografare il prodotto.
<b>Qtà/unità</b> indica la quantità di prodotto minima distribuibile. Per esempio se i pacchi di pasta sono da 1/2 Kg 
la quantità minima di prodotto che potrò distribuire sarà 0.5 Kg.
Esistono 3 campi Qtà/unità per ciascun prodotto. Il riempito deve sempre avvenire da sinistra verso destra e sui campi 
non utilizzati si dovrà indicare 0.
La compilazione del blocchetto di consegna avviene cliccando sulle icone degli alimenti.
Se pasta ha Qtà/unità uguale a 0.5 Kg e di norma distribuisco 4 Kg di pasta, significa che ogni volta dovrò cliccare 8 
volte sull' icona della pasta. Avere anche un' ulteriore Qtà/unità impostata a 4 Kg mi permette di avere sul blocchetto 
anche una seconda icona uguale alla precedente che inserisce però 4 Kg alla volta. E' sempre lo stesso prodotto ma 
l' inserimento diviene più rapido.
<b>Vis.</b> indica di visualizzare l' icona sul blocchetto consegne.   
Una volta effettuata una distribuzione o carico l' unità di misura del prodotto così come il nome non possono più essere modificati.
E' importante eseguire tutte le modifiche necessarie prima di utilizzare il software.
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>