<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

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
                    <td><img src="../immagini/login.png">&nbsp;</img></td></tr>
                <tr></tr><td><div class="text">
E' possibile accedere anche senza usare il mouse.
All' apertura della pagina il cursore si troverà già posizionato nella casella di testo username e quindi si potrà 
direttamente digitare l' username.
Per spostarsi su campo password è possibile utilizzare il tasto Tab oppure freccia in giù. 
Per tornare al campo username freccia in sù.
Dopo aver digitato sia username che password si preme invio.
Nel caso in cui le credenziali non siano corrette, l' accesso non avverrà e non verrà mostrato alcun messaggio.
Se si sbaglia per tre volte consecutive, verrà richiesto oltre alle credenziali corrette anche il codice captcha.  
                        </div></td>

                </tr>
            </table>

            <?php
            showMenu();
            ?>
      
    </body>

</html>
