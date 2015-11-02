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
                <td><img src="../immagini/riepilogo.png" alt="">&nbsp;</img></td></tr>
            <tr><td><div class="text">
Per poter distribuire i prodotti è necessario che la famiglia sia già registrata.
Si effettua la ricerca scheda in una delle due modalità precedentemente descritta.
La ricerca per nominativo viene ad esempio utilizzata quando non si conosce il numero 
scheda attribuito alla famiglia dal programma in fase di registrazione.
La ricerca rapida invece è più pratica e si effettua premendo e lasciando il tasto CTRL e digitando subito dopo (entro 3 secondi) il numero scheda che identifica la famiglia.
In qualunque modo verrà effettuata la ricerca si giungerà sempre alla pagina di riepilogo mostrata sopra.
Si clicca adesso sul nome della persona che è venuta a ritirare (Se si tratta della prima della lista si può semplicemente premere invio). Questa operazione serve solo ad inserire sul foglio consegna il nome. La distribuzione è in ogni caso considerata dal programma effettuata all' intera famiglia e non alla 
singola persona. Fatto ciò si visualizzerà il blocchetto distribuzioni:
                        
                </td>
            </tr>
            <tr>
                <td><img src="../immagini/distribuzione1.png" alt="">&nbsp;</img></td></tr>
            <tr><td><div class="text">
La parte sinistra conterrà le informazioni sulla distribuzione.
Ci sarà il numero scheda che identifica in modo univoco la famiglia alla quale si stanno distribuendo i viveri.
Sotto ci sarà il nome della persona a cui vengono consegnati e sotto l' indirizzo del domicilio.
Questi dati il software li ottiene dalla scheda di registrazione della famiglia.
Sotto ci sarà la sede dell' ente che sta distribuendo i viveri. I dati vengono letti dalle configurazioni dei
registri Agea.
Il numero foglio blocchetto viene attribuito in modo progressivo partendo da quello indicato in configurazione registri e verrà visualizzato una volta salvata la distribuzione.
Alla fine c'è la seguente riga: (Sopra ↑ viveri Agea, sotto ↓ viveri da donazioni).
Si tratta di una riga di separazione. Al di sopra di essa il programma elencherà i viveri Agea mentre al di sotto
quelli delle donazioni. Sullo stesso foglio vengono quindi elencati sia i viveri Agea, sia quelli delle donazioni. 
A destra sono presenti due pulsanti. Sul primo c'è scritto Agea mentre su quello accanto Donazioni.
In figura è attiva la selezione dei viveri Agea. Si capisce dal fatto che il pulsante Agea non è disattivato e
anche dal fatto che le icone hanno i bordi di colore grigio. La selezione degli alimenti distribuiti si fa cliccando 
sulle icone. Ciascuna icona rappresenta le quantità minime distribuibili (Qtà/unità)indicate nei file di configurazione 
dei prodotti visti in precedenza. Nell' esempio, se si stanno distribuendo 2.5 Kg di pasta occorrerà cliccare una volta
su Pasta 0.5Kg e due volte su pasta 1Kg. Se nel file di configurazione dei prodotti Agea si fosse indicata solo come Qtà/unità 
minima 0.5, sarebbe apparsa solo l' icona 0,5 Kg e quindi si sarebbe dovuto cliccare 5 volte sull' icona Pasta.
La quantità selezionata verrà scritta a sinistra. Se si ditribuiscono insieme anche i prodotti delle donazioni si 
deve passare alle icone delle donazioni cliccando su Donazioni. Agea apparirà deselezionato e le icone avranno i bordi 
celesti. <span class="red"> Completato l' elenco, prima di salvare ci si dovrà assicurare che i dati inseriti siano corretti in quanto dopo
aver salvato non si potranno più apportare modifiche a quella scheda. La distribuzione può essere comunque rimossa e reinserita. Per far ciò si dovrà cliccare sul pulsante Rimuovi distribuzione. Quest' ultimo apparirà solo sull' ultima pagina del blocchetto per cui solo quest' ultima potra essere rimossa.
Il messaggio: "ULTIMA DISTRIBUZIONE EFFETTUATA" che appare in rosso a volte sulla destra indica che quella che si sta visualizzando è l' ultima distribuzione effettuata per la famiglia in questione e quindi non si tratta necessariamente dell' ultima pagina del blocchetto.</span> Una volta salvata la distribuzione apparirà il pulsante di stampa.
Questa avverrà su foglio bianco A5 (metà A4).
In alto a destra possono apparire dei link blu con scritto "Visualizza ultima distribuzione effettuata" oppure 
"Effettua una nuova distribuzione". Nel primo caso permette di visualizzare l' ultima distribuzione effettuata 
ai fini della stampa ad esempio. Se quella che si sta visualizzando è l' ultima effettuata apparirà scritto grande in rosso
"Ultima distribuzione effettuata". Potrebbe esserci sotto il link "Effettua nuova distribuzione" oppure non esserci. Ciò deriva dal fatto 
che il programma in genere non fa effettuare due distribuzioni alla stessa famiglia in modo consecutivo. Ciò serve ad evitare 
che in qualche caso possa essere erroneamente salvata due volte la stessa distribuzione.
Lista 1 e Lista 2 permettono di salvare l' elenco di alimenti selezionato per avercelo le volte successive senza 
doverlo riscrivere se si distribuiscono le medesime cose. Per salvare si clicca sulla freccia accanto e poi su memorizza.
Per recuperare l' elenco si clicca su Lista 1 o Lista 2 a seconda di quale dei due elenchi si vuole recuperare.
Una volta recuperato l' elenco è possibile modificarlo. Per aggiungere delle quantità si cliccherà sulle icone a destra
mentre per toglierne, si dovrà cliccare sulle x a sinistra della pagina accanto al nome dell' alimento.
Verrà così cancellata l' intera riga e bisognerà reinserire quell' alimento con la giusta quantità cliccando sulle icone 
a destra.
"Scorsa" permette di caricare lo stesso elenco di prodotti della precedente distribuzione a quella famiglia.
La differenza tra "visualizza ultima distribuzione" e scorsa sta nel fatto che nel primo caso si apre la pagina di blocchetto con la precedente distribuzione a quella famiglia mentre con "Scorsa" il contenuto viene copiato nella nuova pagina che si sta compilando.

                </td>
            </tr>
            <tr>
                <td></td></tr>
            <tr><td><div class="text">

                </td>
            </tr>
            
            <tr><td><div class="text">

Può capitare che si distribuiscono sempre e solo prodotti Agea. In questo caso può essere conveniente
disattivare il vis. dalle configurazioni prodotti Banco in modo da non visualizzare le icone Banco ed evitare
di confondersi.
Altra situazione che potrebbe capitare è di non poter utilizzare un giorno il pc per effettuare le distribuzioni.
In tal caso si effettueranno con blocchetto cartaceo per poi ricopiare. 
Bisognerà dunque prevedere il modo di conoscere la data dell' ultimo ritiro non potendo poi consultare da pc.
Il software ad ogni distribuzione aggiunge automaticamente la data. Se però un giorno non viene utilizzato, quelle 
ricevute dovranno essere ricopiate con la data corretta. Per far ciò basta andare in Varie&#8594;Configurazioni&#8594;Sistema e 
inserire Sì in "Data manuale su blocchetto consegne". Apparirà il campo per l' inserimento della data sulle ricevute.
<span class="red">Occorre ricordarsi di controllare sempre tutti i dati prima di salvare in quanto non è possibile modificarli successivamente.
Inoltre le date delle distribuzioni devono sempre essere in ordine crescente. Questo significa che non si può
continuare ad usare il programma senza aver prima inserito le ricevute arretrate.</span>  
                        
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>
