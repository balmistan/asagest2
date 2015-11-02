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
                <td><img src="../immagini/carico.png" alt="">&nbsp;</img></td></tr>
            <tr><td><div class="text">
Il carico prodotti riguarda solo gli alimenti Agea. Si accede alla pagina da Varie&#8594;Carico.
Alcuni campi saranno già impostati. Ad esempio la data indicata è quella odierna in quanto si
presume che il carico venga registrato il giorno stesso in cui viene consegnato
(Deve essere indicata la data presente sulla bolla) La data può eventualmente essere modificata 
se non ancora salvata. Sopra compare il messaggio: "Nessun carico salvato con questa data". 
Questo perchè il campo data funge anche da campo di ricerca. Quindi se nella data indicata è già 
stato effettuato un carico, il programma si predispone alla modifica delle quantità. 
Ciò può essere utile in caso di errori di inserimento.
L' unico campo che non potrà essere più modificato è il campo data. Per questo quando si tenta di 
salvare verrà chiesta conferma. NUM. RIF è il numero della bolla di carico. Il campo NUM. INDIG è
quello presente sul registro Agea. Andrà compilato con /.
Sotto apparirà l' elenco dei prodotti Agea. Si potranno indicare le quantità caricate spostandosi da
una casella alla successiva anche col tasto TAB.<span class="red"> Eventuali dati non interi andranno indicati con due 
cifre decimali. Es. 42.5 Kg andrà indicato con 42.50 (la virgola andrà indicata col punto).
Occorrerà verificare sempre la correttezza dei dati inseriti prima di salvare perchè non potranno più essere modificati.</span>
Alla fine si dovrà cliccare su Salva in fondo alla pagina.
<span class="red">La data del carico deve essere superiore a quella delle distribuzioni già effettuate</span>
                      
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>
