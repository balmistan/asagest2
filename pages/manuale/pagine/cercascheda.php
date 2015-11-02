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
                <td><img src="../immagini/home.png">&nbsp;</img></td></tr>
            <tr><td><div class="text"><pre>
La ricerca scheda può essere effettuata in due modalità. La ricerca standard si fa accedendo alla 
pagina di ricerca. La ricerca veloce viene effettuata direttamente da tastiera. Conviene 
utilizzare la seconda anche se a volte è indispensabile la prima.
Nell' esempio ho appena effettuato il login e devo cercare la scheda relativa
alla famiglia della persona che è venuta a ritirare i viveri.
Clicco su Cerca Scheda nel menù in alto. Il fatto che mi trovi sulla Home è indifferente.
Il menù è uguale in tutte le pagine.
Verrò reindirizzato alla pagina di ricerca:
                        </pre>                 
                </td>
            </tr>
            <tr>
                <td><img src="../immagini/cercascheda.png">&nbsp;</img></td></tr>
            <tr><td><div class="text"><pre>
La ricerca standard viene effettuata quando non conosco il numero della scheda ma il nominativo della persona.
Da quì posso anche effettuare la ricerca per numero scheda.
Inizialmente i risultati saranno ordinati per cognome e posso scorrere la lista per cercare oppure ridurre
prima il numero di risultati visualizzati inserendo parte del cognome o del nome in alto a destra.
analogamente posso inserire il numero scheda (nell' esempio sto cercando la scheda n° 1). In questo caso la scheda cercata 
verrà spostata sulla prima riga.
Per effettuare qualsiasi operazione dovrò effettuare la ricerca scheda. Individuata la scheda accederò alla pagina di 
riepilogo. Si tratta di una pagina centrale contenente un riepilogo del contenuto scheda. Da questa pagina è possibile
accedere a:
-blocchetto consegne per una nuova distribuzione.
-report delle distribuzioni effettuate per quella famiglia ordinate in modo decrescente dall' ultima alla prima.
-modifica scheda.
Anche se questa procedura potrebbe sembrare lunga, in realtà durante il servizio ci si muove solo tra pagina di riepilogo e 
blocchetto consegne. Ciò permette di essere sempre informati su alcuni dati relativi alla famiglia ogni volta che si distribuisce.
Ricapitolando:
Per poter effettuare operazioni su una famiglia, quali distribuzione viveri, modifica della scheda anagrafica o conoscere tutte le 
distribuzioni effettuate verso quella famiglia occorre accedere alla pagina di riepilogo. Questa è da considerare come una home page 
per quella famiglia.
L' accesso a questa pagina può essere eseguito tramite ricerca scheda utilizzando quindi l' opzione sul menù in alto o da tastiera (non ancora visto).
In quest' ultimo caso si preme e si lascia il tasto CTRL e si digita il numero scheda.
Da quando si lascia il tasto CTRL si hanno 3 secondi per digitare il numero scheda. Bisogna sempre ricordarsi di lasciare il tasto CTRL prima di
digitare il numero altrimenti non viene acquisito. Completata la digitazione può passare qualche secondo prima di visualizzare la pagina di riepilogo.
Il numero digitato appare anche in basso a sinistra sulla pagina mentre lo si scrive. 
L' operazione è effettuabile da qualsiasi pagina. 
Dalla pagina di riepilogo si può accedere al blocchetto consegne direttamente premendo invio. Il nominativo inserito sarà il primo della lista.
                        </pre>
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>