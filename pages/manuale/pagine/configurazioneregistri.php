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
                <td></td></tr>
            <tr><td><div class="text">
Configurare i registri signfica sincronizzarli con la versione cartacea.

Si va alla pagina Varie&#8594;Configurazioni&#8594;Allegati 8-9 e si completano i vari campi.
                        
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
<b>Nome Sede CRI abbreviato:</b> nome sede che apparirà sull' allegato 8. (NON DISPONIBILE)
<br /><br />
<b>Indirizzo:</b> indirizzo della sede.
<br /><br />
<b>Legale Rappresentante:</b> nome e cognome del legale rappresentante.
<br /><br />
<b>Nato a:</b> luogo di nascita del legale rappresentante.
<br /><br />
<b>il:</b> data di nascita del legale rappresentante.
<br /><br />
<b>Inizio num. reg eventi:</b> (NON DISPONIBILE) Indicare 1
<br /><br />
<b>Inizio numer. All. 8:</b> (NON DISPONIBILE) Indicare 1
<br /><br />
<b>Inizio numer. All. 9:</b> si assegna il numero pagina allegato 9 che il programma dovrà utilizzare come successivo.
 Se ad esempio l' ultimo allegato 9 disponibile è il numero 13 il successivo all 9 sarà il numero 14 quindi inserirò 14. 
<br /><br />
<b>Inizio numer. Blocchetto:</b> numero progressivo da attribuire alla successiva pagina del blocchetto. Es. 24 indicherà che la successiva ricevuta di distribuzione avrà n° 24.
<br /><br />


                </td>
            </tr>
            <tr>
                <td></td></tr>
            <tr>
                <td>
                    <div class="text">
                        
A fine servizio verificare sempre la corretta compilazione dell' Allegato 9.
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>