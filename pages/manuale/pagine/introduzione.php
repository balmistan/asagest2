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

            <tr><td><div class="text">
                        Il programma consente l' archiviazione in formato digitale delle schede contenenti le anagrafiche delle famiglie che vengono a ritirare gli alimenti.
                        Esse possono contenere:<br />
                        - Nominativi dei componenti famiglia con C.F. e data di nascita.<br />
                        - Indirizzo domicilio.<br />
                        - Recapito telefonico.<br />
                        - Eventuali annotazioni.<br /> 
                        - Data di scadenza di un eventuale certificato.<br /><br />

                        Gestisce inoltre la distribuzione dei viveri con creazione di report periodici contenenti:<br />
                        - Numero di famiglie assistite.<br />
                        - Numero di indigenti assistiti.<br />
                        - Quantità di alimenti distribuiti.<br />
                        - Compilazione automatica dei registri Agea.<br /><br />
                        <b>Gli screenshot usati per questa guida sono stati acquisiti dal software di test, quindi tutti i dati inseriti sono inventati.</b>
                    </div></td></tr>


        </table>

        <?php
        showMenu();
        ?>

    </body>

</html>
