<!DOCTYPE HTML> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
    <head>
        <title>Carico</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="generator" content="NetMDM" />
        {foreach from=$arr_style_config item=linkcss}
            <link rel="stylesheet" href="{$linkcss}" type="text/css" media="all" />
        {/foreach}
        <link rel="stylesheet" href="../styles/allegati/print.css" type="text/css" media="print" />
        {foreach from=$arr_js_config item=linkjs}
            <script type="text/javascript" src="{$linkjs}"></script>
        {/foreach}
        <script type="text/javascript">
            //window.location = "all8.php"
        </script>
    </head>
    <body>
        {menu data=$menu class="admin_menu noprint"}
        <br /><br />
        <h2>Carico Prodotti su Registro AGEA ({$prog_agea})</h2>
        <form id="inputform" name="inputform" action="#" method="post">
            <table>
                <tr><td colspan=3><div id="msg"></div></td></tr>
                <tr>
                    <td class="nameother">DATA:</td><td colspan=2><input type="text" class="input_other" id="date" name="date" value="{$dateins}" /></td>
                </tr>
                <tr>
                    <td class="nameother">NUM. RIF:</td><td colspan=2><input type="text" id="numrif" class="input_other" autocomplete = "off" name="numrif" value="" /></td>
                </tr>
                <tr>
                    <td class="nameother">NUM. INDIG:</td><td colspan=2><input type="text" class="input_other" autocomplete = "off" name="numindig" value="{$prog}" /></td>
                </tr>

                {foreach from=$arr_products key="mykey" item="myitem"}
                    <tr>
                        <td class="nameproduct">{$myitem["nameproduct"]}:</td><td><input class="input_product" type="text" autocomplete = "off" name="load_{$myitem["idproduct"]}" value="0" /></td><td>{$myitem["umis"]}</td>
                    </tr>
                {/foreach}
                <tr>
                    <td><input type="hidden" id="insert_id" name="insert_id" value="" /></td>
                    <td>
                        <input id="Rimuovi" type="button" value="Rimuovi" />
                        <input id="Salva" type="submit" name="Salva" value="Salva" /></td>
                </tr>
            </table>
            <p>*Indicare le eventuali cifre decimali (max 2) usando il punto.</p>
            <p>*La Data è quella della bolla. Non devono essere state effettuate distribuzioni con data successiva ad essa.</p>
        </form>


        <div id="menuextern">
            <div id="menutitle">
                <h3 class="t">Carichi precedenti</h3>
            </div>
            <div>
                <div>
                    <ul id="menu">
                        {foreach from=$dateloads item=date}   
                            <li><a href="#">{$date}</a></li>
                            {/foreach}
                    </ul>             
                    <div class="cleared"></div>
                </div>
            </div>
            <div class="cleared"></div>
        </div>
        </div>


    </body>
</html>
