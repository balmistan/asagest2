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
                <td><img src="../immagini/configurazionesistema.png">&nbsp;</img></td></tr>
            <tr><td><div class="text">
Le configurazioni del sistema vanno fatte prima di utilizzare il software.
Esistono più tipi di configurazioni per cui la configurazione è la parte più complessa.
Va fatta quando si inizia ad usare il software la prima volta. 
E' la parte più delicata in quanto può creare problemi di funzionamento.
Inoltre alcune configurazioni è bene non modificarle più in quanto andrebbero a creare problemi 
di funzionamento. Spiegherò anche come risolverli.
Inoltre alcune configurazioni vanno effettuate necessariamente prima di usare il software, altre
prima di iniziare a distribuire i prodotti.
La configurazione del sistema è l' unica che va fatta subito.
La pagina di configurazione si raggiunge mediante il menù in alto andando su
Varie&#8594;Configurazioni&#8594;Sistema
apparirà la pagina mostrata sopra.
<br />Di seguito l' elenco dei settaggi:
<br /><br />
<b>Verifica stato del Certificato (SI/NO):</b> indica al programma se vogliamo essere avvisati quando il certificato sta per scadere. 
Il certificato in genere è il certificato ISEE o un foglio rilasciato dall' assistente sociale. La data di scadenza viene inserita 
in un apposito campo durante la compilazione delle schede contenenti i dati delle famiglie. Il campo scadenza certificato sarà presente 
solo se questa impostazione è SI.
<br /><br />
<b>Sigla provincia sede di appartenenza:</b> fa sì che durante la compilazione delle schede nella scelta deiComuni vengano mostrati solo 
quelli della provincia indicata. 
<br /><br />
<b>Distanza minima tra 2 ritiri giorni:</b> informa il programma della distanza minima che deve esserci tra due successivi ritiri viveri.
<br /><br />
<b>Data manuale sul blocchetto consegne (SI/NO):</b> indicando NO le distribuzioni verranno automaticamente salvate con la data in cui sono state effettuate.
Indicando Sì verrà richiesto l' inserimento della data per poter salvare. E' utile poter far ciò in quanto se non è possibile qualche giorno effettuare distribuzioni tramite PC, si dovranno poi ricopiare le distribuzioni rimaste indietro prima di poter andare avanti.
E' importante mantenere l' ordine delle distribuzioni per la corretta compilazione dei registri Agea.
Le varie operazioni effettuabili (come carico e distribuzioni) accettano sempre date progressive.
<br /><br />
<b>Preavviso scadenza Certificato giorni:</b> è un opzione che ha senso solo se Verifica stato del Certificato è impostata a Sì. In pratica 
indica al programma quanti giorni prima della scadenza deve iniziare ad apparire l' avviso.
<br /><br />
<b>Area firma sul blocchetto consegne (SI/NO):</b> le pagine del blocchetto consegne sono stampabili e questa opzione indica se deve essere 
presente l'area per la firma. 
                        
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>