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
                <td><img src="../immagini/configurazionesistema.png" alt="">&nbsp;</img></td></tr>
            <tr><td><div class="text">
Configurare i registri signfica sincronizzarli con la versione cartacea. Si continua la distribuzione cartacea finchè non si 
completa la pagina dell' allegato 8(Registro di carico e scarico). 
A questo punto si prenderanno gli ultimi fogli del registro di carico e scarico e si andrà
alla pagina: Varie&#8594;Configurazioni&#8594;Prodotti Agea&#8594;Ordina:
                        
                </td>
            </tr>
            <tr>
                <td><img src="../immagini/ordina.png" alt="">&nbsp;</img></td></tr>
            <tr>
                <td>
                    <div class="text">
                       
In questa pagina ogni fila orizzontale di icone rappresenta le colonne di una pagina del registro di carico e scarico.
Le icone vanno spostate col mouse al fine di ottenere lo stesso ordine che si ha sul cartaceo.
Alla fine non è presente alcun pulsante di salvataggio in quanto le nuove posizioni vengono salvate automaticamente.
Si va adesso alla pagina Varie&#8594;Configurazioni&#8594;Allegati 8-9 e si completano i vari campi.
                        
                </td>
            </tr>
            <tr>
                <td><img src="../immagini/configurazioniall89.png" alt="">&nbsp;</img></td></tr>
            <tr>
                <td>
                    <div class="text">
      Le assegnazioni in questa pagina andranno fatte prima di usare il software. L' avanzamento delle numerazioni pagine avverrà poi automaticamente.
      Tutte le modifiche che in futuro verranno effettuate in questa pagina si ripercuoteranno anche sulle pagine di registro già compilate.
<br /><br />
<b>Sede CRI di:</b> Nome sede che apparirà sull' allegato 9.
<br /><br />
<b>Nome Sede CRI abbreviato:</b> nome sede che apparirà sull' allegato 8.
In quest' ultimo infatti c'è meno spazio per l' intestazione. 
<br /><br />
<b>Indirizzo:</b> indirizzo della sede.
<br /><br />
<b>Legale Rappresentante:</b> nome e cognome del legale rappresentante.
<br /><br />
<b>Nato a:</b> luogo di nascita del legale rappresentante.
<br /><br />
<b>il:</b> data di nascita del legale rappresentante.
<br /><br />
<b>Inizio num. reg eventi:</b> è un registro mostrante le singole operazioni effettuate durante la giornata. Si può anche lasciare 1 dato che si sta iniziando ad usare adesso.
<br /><br />
<b>Inizio numer. All. 8:</b> è il numero pagina che il programma andrà ad assegnare alla pagina registro di carico e scarico che andrà a compilare. 
<br /><br />
<b>Inizio numer. All. 9:</b> si assegna il numero pagina allegato 9 che il programma dovrà utilizzare come successivo.
 Se ad esempio l' ultimo allegato 9 disponibile è il numero 13 il successivo all 9 sarà il numero 14 quindi inserirò 14. 
<br /><br />
<b>Inizio numer. Blocchetto:</b> numero progressivo da attribuire alla successiva pagina del blocchetto. Es. 24 indicherà che la successiva ricevuta di distribuzione avrà n° 24.
<br /><br />
La procedura finora descritta vale se si inizia ad usare il software durante l' anno quando c'è già un registro di carico e scarico
cartaceo.
Nel caso in cui si inizia ad usare il software ad inizio anno e dunque non esiste ancora il registro cartaceo, in tutti e tre i casi
si indicherà come inizio numerazione 1.
Tutte le configurazioni settate in questa pagina possono essere modificate in ogni momento. Le modifiche si ripercuoteranno anche sulle pagine già compilate.
Completate queste operazioni, si accede all' allegato 8 da Varie&#8594;Allegati&#8594;Allegato 8. 

                </td>
            </tr>
            <tr>
                <td><img src="../immagini/allegato8.png" alt="">&nbsp;</img></td></tr>
            <tr>
                <td>
                    <div class="text">
                        
Si controlla che le pagine siano state generate correttamente. Che le intestazioni, il numero pagina indicato in alto a destra e 
l' ordine delle colonne coincidano con quelli del cartaceo.
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>