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
                <td><img src="../immagini/riepilogo.png">&nbsp;</img></td></tr>
            <tr><td><div class="text">
Si tratta di un riepilogo dati della famiglia. E' la pagina da cui si parte per effettuare una distribuzione.
Se devo effettuare una distribuzione viveri vado alla pagina di riepilogo e clicco sul nome della persona che è 
venuta a ritirare.
Quindi per effettuare una distribuzione dovrò accedere prima alla pagina di riepilogo.
La stessa cosa vale se devo modificare la scheda anagrafica della famiglia. In questo caso
cliccherò sul pulsante modifica in basso.
Per effettuare una distribuzione o per modificare la scheda anagrafica si dovrà accedere a questa pagina necessariamente.
Per il report è possibile accedere anche in modo diretto dal menù di navigazione.
Alla scheda di riepilogo non si accede dal menù ma digitando il numero scheda (relativo alla famiglia) da tastiera dopo aver 
premuto e lasciato il tasto CTRL. In alternativa se non si conosce il numero scheda si fa una ricerca del nominativo di chi è
venuto a ritirare dalla pagina apposita. Entrambi i metodi sono descritti in maniera più dettagliata in Ricerca Scheda.
Vediamo come è strutturata questa pagina:
In alto appare il numero scheda.
E' importante guardare il colore:
<br /><br />
<span class="ucase green">VERDE</span> indica che è tutto OK.
<br /><br />
<span class="ucase yellow">ARANCIONE</span> indica che sono presenti degli avvisi. Questi si troveranno in fondo alla pagina.
Un esempio di avviso potrebbe essere quello di scheda incompleta. Se sulla scheda anagrafica ho indicato incompleta, verrò 
avvisato in modo da avere la possibilità di chiedere le informazioni mancanti.
Un altro avviso tipico potrebbe essere quello del certificato in scadenza. 
Questa segnalazione viene data solo se nelle configurazioni di Sistema accesibili dal menù mediante il 
percorso: Varie->Configurazioni->Sistema ho indicato di voler essere avvisato e con quanti giorni di anticipo. 
<br /><br />
<span class="ucase red">ROSSO</span> potrebbe indicare che non è trascorso il tempo minimo dall' ultima distribuzione, ma anche che c'è il certificato scaduto
oppure si tratta di una scheda disattivata. Gli avvisi vanno impostati in Varie->Configurazioni->Sistema.
In ogni caso l' avviso sarà descritto in modo chiaro in fondo alla pagina.
Gli avvisi potrebbero essere anche più di uno. In questo caso il colore sarà quello dell' avviso con priorità maggiore.
<br /><br />
Sotto l' indicazione Scheda n° ... vengono elencati tutti i componenti della famiglia con accanto l' età.
L' eventuale colore di sfondo indica se si tratta di uomo o donna.
Conoscere l' età può servire a decidere cosa distribuire.
Cliccando sul nominativo si accede alla pagina del blocchetto consegne già intestata a quella persona.
La procedura serve solo ad inserire il destinatario (Spett.le ...) sulla pagina. Le distribuzioni vengono considerate dal programma 
come effettuate alla famiglia e mai alla singola persona. Nel riepilogo distribuzioni (Report) comparirà anche il nominativo della 
persona che ha effettuato il ritiro.
Per effettuare la distribuzione è possibile accedere al blocchetto consegne direttamente premendo invio. Il nominativo inserito sarà il primo della lista.
In alternativa si potrà cliccare sul nome della persona che è venuta a ritirare.
Se sulla scheda di registrazione sono presenti le foto, queste appariranno accanto ai nomi.
Su questa pagina appariranno anche le eventuali note presenti sulla scheda di registrazione.
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>