<!DOCTYPE HTML> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
    <head>
        <title>{$title}</title>
        <link rel="shortcut icon" type="image/png" href="{$shortcuticon}" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="generator" content="NetMDM" />
        <link rel="stylesheet" href="../styles/allegati/print.css" type="text/css" media="print" />
        {foreach from=$arr_style_config item=linkcss}
            <link rel="stylesheet" href="{$linkcss}" type="text/css" media="all" />
        {/foreach}
        {foreach from=$arr_js_config item=linkjs}
            <script type="text/javascript" src="{$linkjs}"></script>
        {/foreach}
    </head>
    <body>
        {menu data=$menu class="admin_menu noprint"}
        
        <p class="dx alleg">Allegato n. 6</p>
        <div id="title">
            <p>AIUTI UE - REG. UE {$arr_dbget["reg_ue"]}</p>
            <p>DICHIARAZIONE DI CONSEGNA AGLI INDIGENTI DI PRODOTTI ALIMENTARI GRATUITI</p>

                <div id="left">
                    <table class="tableup">
                        <tr>
                    <td class="noborderdx">NUMERO</td>
                    <td><input type="text" id="num" readonly="readonly" value="{$num_all_9}" /></td>
                        </tr>
                    </table>
                </div>
                <div id="right">
                    <table class="tableup">
                        <tr>
                    <td class="noborderdx">DATA</td>
                    <td><input type="text" id="date" value="{$date}" /></td>
                        </tr>
                    </table>
                </div>
            </div>
        
        <div id="contentdiv">
            {if $female}
            <p class="p1">La sottoscritta {$arr_dbget["legalerappresentante"]}<br />
                 Nata a 
                {else}
                    <p class="p1">Il sottoscritto {$arr_dbget["legalerappresentante"]}<br />
                         Nato a 
                    {/if}
           {$arr_dbget["luogodinascita"]} il {$arr_dbget["datadinascita"]}<br />
            In qualità di legale rappresentante del {$arr_dbget["nomesede"]}<br />
            Con sede a {$arr_dbget["indirizzosede"]}<br />
            {$arr_dbget["corpo_all9"]}
            </p>
            <p id="dich">DICHIARA</p>
            <p>A) che rappresentanti della struttura di cui in premessa, da me delegati, hanno distribuito in data </p><p>odierna, a n. <span id="num_indig">{$arr_out["serv_indigenti"]}</span> indigenti i seguenti prodotti: </p>
            <div id="distr3">
                <table cellspacing=0 width=100%>
                    <tr>
                        <th class="noborderdx">PRODOTTO</th>
                        <th class="noborderdx">Unit&agrave; di misura</th>
                        <th>QUANTITA'</th>
                    </tr>
            {foreach from=$arr_out["products"] key=i item=elem}
                <tr>
                <td class="td1 nobordertop noborderdx">{$elem['name_product']}</td>
                <td class="centred nobordertop noborderdx">{$elem['measureunity']}</td>
                <td class="centred nobordertop">{$elem['qty']|string_format:"%g" }</td>
                </tr>
                {/foreach}
                </table>
            </div>
            <p class="p1">B) Che i su indicati prodotti vengono riportati nel registro di carico e scarico.<br />
            Allego fotocopia integrale, fronte e retro di un documento di identità in corso di validità.</p>
            <p class="centred">TIMBRO DELL' ENTE CARITATIVO E FIRMA DEL LEGALE RAPPRESENTANTE</p>
        </div>
            <form id="inputform" name="inputform" action="#" method="post">
<input type="submit" class="noprint" name="esportapdf" value="SALVA" />
</form>
            <!-- <a href="#" class="noprint" id="print" onclick="window.print();return false;"><img src="../styles/allegati/icons/print.png" alt="Stampa documento" title="Stampa il documento" /></a> -->
     <p class="noprint">*Cliccare sulla data per accedere ad un Allegato 6 con data differente.</p> 
    </body>
</html>