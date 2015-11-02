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
                <td><img src="../immagini/home.png" alt="">&nbsp;</img></td></tr>
            <tr><td><div class="text">
Effettuato il login si viene reindirizzati automaticamente sulla home page.
In alto è presente il menù di navigazione che sarà lo stesso in tutte le pagine.
Tre delle opzioni sono replicate mediante icone grandi sulla pagina.
La prima permette di effettuare la ricerca di una famiglia registrata.
Il programma intende la distribuzione come effettuata all' intera famiglia e non 
alla singola persona che viene a ritirare.
La ricevuta di ritiro verrà comunque intestata alla persona che è venuta a ritirare.
La seconda icona permette la registrazione di una nuova famiglia.
La terza effettua il logout.
A queste tre funzioni si può accedere anche mediante menù in alto.

Su alcune pagine sono spesso presenti dei suggerimenti utili all' uso del programma. 
                    
                </td>
            </tr>
        </table>

        <?php
        showMenu();
        ?>
    </body>

</html>