<!DOCTYPE HTML> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="initial-scale=0.7, maximum-scale=0.7" name="viewport"  />
        {foreach from=$arr_style_config item=linkcss}
            <link rel="stylesheet" href="{$linkcss}" type="text/css" media="all" />
        {/foreach}
        {foreach from=$arr_js_config item=linkjs}
            <script type="text/javascript" src="{$linkjs}"></script>
        {/foreach}
    </head>
    <body> 
        <table cellpadding=0 cellspacing=0>
            <tr>
                <td colspan=4 id="spettle">Spett.le {$arr["surname"]} {$arr["name"]}</td>
            </tr>
            <tr>
                <td colspan=4 id="dataeora">Data: {$arr["dtime"]}</td>
            </tr>
            <tr>
                <td colspan=4 id="codcons">Codice consegna: {$codcons}</td>
            </tr>
            <tr><td colspan=4 id="prodotti">Elenco prodotti distribuiti:</td></tr>
            {if $distr['agea']|@count <> 0}
                <tr><td class="colred" colspan=4>--------------------------Agea-------------------------</td></tr>     
                {foreach from=$distr["agea"] key="mykey" item="myitem"}
                    <tr>
                        <td>{$myitem["qty"]}</td>
                        <td>{$myitem["measureunity"]}</td>
                        <td>{$myitem["name_product"]}</td>
                    </tr>
                {/foreach} 
            {/if}
            {if $distr['banco']|@count <> 0}
                <tr><td class="colred" colspan=4>-----------------------Donazioni--------------------</td></tr>
                {foreach from=$distr['banco'] key="mykey" item="myitem"}
                    <tr>
                        <td>{$myitem["qty"]}</td>
                        <td>{$myitem["measureunity"]}</td>
                        <td>{$myitem["name_product"]}</td>
                    </tr>
                {/foreach} 
            {/if}
          <!--  <tr><td colspan=4>
                    <div class="sig sigWrapper">
                        <div class="typed"></div>
                        <canvas class="pad" width="300" height="55"></canvas>
                        <input type="hidden" name="output" class="output">
                    </div>
                </td></tr> -->
        </table>
        <!--<input type="hidden" id="signature" value="{$arr["signature"]}" />-->
    </body>
</html>