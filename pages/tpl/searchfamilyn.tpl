<!DOCTYPE HTML> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
    <head>
        <title>{$title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="generator" content="NetMDM" />
        <link rel="shortcut icon" type="image/png" href="{$shortcuticon}" />

        {foreach from=$arr_style_config item=linkcss}
            <link rel="stylesheet" href="{$linkcss}" type="text/css" media="all" />
        {/foreach}


        {foreach from=$arr_js_config item=linkjs}
            <script type="text/javascript" src="{$linkjs}"></script>
        {/foreach}
    </head>
    <body>  
        {menu data=$menu class="admin_menu"}
        <form name="family_search" method="POST" action="?">
            <table id="tablesearch"> 
                <tr>
                    <td><label>Ricerca nomi:</label></td>
                    <td>
                        <div class="radiobtn">
                            <input type="radio" id="radio0" name="radio_block1" value="0" /><label for="radio0">Tutti</label>
                            <input type="radio" id="radio1" name="radio_block1" value="1" /><label for="radio1">Solo con R.R. selezionato</label>
                        </div>
                    </td>
                    <td><label>Da schede:</label></td>
                    <td>
                        <div class="radiobtn">
                            <input type="radio" id="radio2" name="radio_block2" value="0" /><label for="radio2">Tutte</label>
                            <input type="radio" id="radio3" name="radio_block2" value="1" /><label for="radio3">Attive</label>
                            <input type="radio" id="radio4" name="radio_block2" value="2" /><label for="radio4">Non attive</label>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
        <table id="DTable"></table>
    </body>
</html>
