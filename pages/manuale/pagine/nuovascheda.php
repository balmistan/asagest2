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
                <td><img src="../immagini/nuovascheda.png">&nbsp;</img></td></tr>
            <tr><td><div class="text">
                        Per poter effettuare la distribuzione viveri, la famiglia a cui si sta distribuendo deve essere stata registrata.
                        E' possibile registrare una nuova famiglia in qualsiasi momento cliccando su Agg. Scheda nel menù di navigazione.
                        Conviene effettuare tutte le registrazioni prima di iniziare ad usare il software e poi le altre man mano che se
                        ne aggiungono delle nuove.
                        La pagina di inserimento nuova scheda differisce da quella di modifica scheda per il fatto che in alto compare 
                        la scritta "Nuova Scheda" mentre nel secondo caso ci sarà "Scheda n° ...".
                        L' aggiunta di una nuova scheda non dovrà mai essere fatta modificando i dati di una già inserita.
                        Questo perchè il programma attribuisce a registrazione avvenuta, un numero scheda che identifica univocamente quella famiglia.
                        Quando vengono modificati dei dati il programma riconosce che sono cambiati i dati di quella famiglia.
                        I dati possono essere modificati all' infinito. L' importante è non utilizzare quella stessa scheda per un' altra famiglia cambiandone ad esempio tutti i dati.
                        Una scheda se non serve più in quanto ad esempio quella famiglia non necessita più dei viveri, può essere spostata tra le schede disattivate (verrà spiegato meglio avanti). 
                </td>
            </tr>
            <tr>
                <td><img src="../immagini/nuovascheda2.png">&nbsp;</img></td></tr>
            <tr>
                <td><div class="text">
                        La prima cosa da fare è aggiungere un numero di righe pari al numero di componenti della famiglia.
                        E' possibile far ciò cliccando su una delle due frecce che appaiono accanto alla prima riga.
                        Una permette di aggiungere una riga al di sopra di quella esistente mentre l' altra al di sotto.
                        In questo caso può essere utilizzata indifferentemente o l' una o l' altra.
                        Nel caso di modifica scheda l' aggiunta al di sopra o al di sotto può permettere di mantenere lo 
                        stesso ordine dell' elenco cartaceo.
                        Una volta aggiunte le righe accanto ad esse apparirà una x che ne permetterà la cancellazione.
                        L' inserimento dei cognomi è sempre obbligatorio.
                        Nel caso in cui si conosca solo il nominativo della persona che viene a ritirare e il numero dei 
                        componenti della famiglia il numero di righe da aggiungere sarà pari ad esso.
                        Nelle righe di cui non si conoscono i nominativi, andrà inserita una x nel campo cognome.
                        E' importante far ciò in quanto il programma individua il numero di persone contando le righe.
                        Una volta finito di scrivere il cognome ci si può spostare sui campi adiacenti usando il tasto TAB.
                        Il campo codice fiscale non è obbligatorio ma è preferibile inserirlo.
                        Attraverso esso il programma fa la verifica se la persona è stata registrata in precedenza.
                        Inoltre ricava informazioni sul sesso e compila il campo data di nascita.
                        Per quanto riguarda quest' ultima, dovrà sempre essere verificata in quanto sul codice fiscale 
                        sono indicate solo le ultime due cifre dell' anno di nascita. Le prime due le aggiunge il programma. 
                        La data di nascita delle singole persone è importante se si distribuiscono prodotti Agea in quanto 
                        viene richiesto il numero di indigenti per fascia di età.
                        Una volta salvata una scheda in alto apparirà Scheda n°... 
                        Il numero scheda permette di accedere in modo rapido alla scheda quando una persona viene a ritirare.
                        La ricerca della scheda può essere fatta anche per nome/cognome. 
                        Dovrà essere quindi indicato al programma il nominativo della persona che in genere viene a ritirare.
                        Ciò viene fatto selezionando l' R.R. (includi in risultati ricerca) sulla riga corrispondente a quella 
                        persona.
                        Indirizzo e Comune si riferiscono al domicilio della famiglia.
                        I Comuni che verranno visualizzati per la scelta saranno quelli appartenenti alla provincia la cui sigla è 
                        stata indicata nella pagina configurazioni di sistema accessibile dal menù attraverso il percorso:
                        Varie->Configurazioni->Sistema.

                </td>
            </tr>
            <tr>
                <td><img src="../immagini/nuovascheda3.png">&nbsp;</img></td></tr>
            <tr>
                <td><div class="text">
                        C'è uno spazio per eventuali annotazioni e a fianco potrebbe esserci un campo "Scadenza certificato".
                        Il fatto che ci sia o meno dipende da un settaggio presente in Varie->Configurazioni->Sistema.
                        Per certificato il programma intende un documento che attesti che quella famiglia ha diritto ad 
                        ottenere i viveri. Può essere a seconda dei casi un documento rilasciato dall' Assistente Sociale o un
                        Certificato ISEE. Poichè si tratta di necessità momentanee i certificati sono soggetti a rinnovo.
                        L' obiettivo è quello di essere avvisati in caso di scadenza.
                        Questa funzionalità del programma può essere disattivata se non serve dal pannello Configurazioni.
                        Stato della scheda:
                        "Completa" indica che i dati necessari sono stati tutti inseriti.
                        "Incompleta" indica che manca ancora qualche informazione (es. numero di telefono) che si vuole
                        chiedere quando verrà a ritirare i viveri.
                        "Disattivata" è una sorta di eliminazione temporanea della scheda. E' possibile che al momento 
                        quella famiglia non ha bisogno i viveri ma potrebbe in seguito tornare. 
                        In tal caso è conveniente specificare nel campo Note il motivo della disattivazione.
                        Cliccando alla fine su Salva, apparirà il messaggio "Dati salvati" ed in alto il numero scheda univoco 
                        attribuito automaticamente dal programma. Questo numero che verrà anche stampato sulle ricevute di consegna
                        permetterà di velocizzare l' accesso alla scheda. Per una determinata famiglia resterà sempre lo stesso.
                        A questo punto si potranno effettuare anche modifiche se ci si è accorti di aver sbagliato qualcosa.
                        Si potrà cliccare su Annulla per rimuovere le modifiche fatte non ancora salvate.
                        Per ciascuna persona c'è la possibilità di inserire anche una foto. 
                        Per far ciò basta cliccare su NO FOTO per attivare una eventuale webcam posta a lato del PC.
                        Il programma è in grado di estrarre la foto direttamente da un documento di identità o da una sua 
                        fotocopia.    

                </td>
            </tr>
            <tr>
                <td><img src="../immagini/modificascheda.png">&nbsp;</img></td></tr>
            <tr>
                <td><div class="text">
                        Una volta effettuato il salvataggio oltre a comparire in alto il numero della scheda, compaiono accanto
                        alle date di nascita dei rettangoli colorati. Il colore indica se uomo o donna mentre il numero all' interno
                        l' età. Se questa è inferiore ad un anno verrà indicato <1 oppure -1
                    </div>
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>