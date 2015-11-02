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
        <link rel="stylesheet" href="../styles/report/print.css" type="text/css" media="print" />
        {foreach from=$arr_js_config item=linkjs}
            <script type="text/javascript" src="{$linkjs}"></script>
        {/foreach}   
    </head>
    <body>  
        {menu data=$menu class="admin_menu noprint"}
    
        <form name="report" method="POST" action="?">
            <table id="tablesearch" class="noprint">           
                <tr>
                    <td><label>Ricerca dal</label><br />
                        <input type="text" name="start_date" class="date" id="start_date" value="{$start_date}" /></td>
                    <td><label>al</label><br />
                        <input type="text" name="end_date" class="date" id="end_date" value="{$end_date}" /></td>
                    <td><label>N° Scheda</label><br />
                        <input type="text" name="family_id" id="family_id" value="{$family_id}" /></td>
                     <td><label>Comune</label><br />
                                     <select id="comune" name="comune">
                                         <option value="">Tutti</option>
                    {foreach from=$arr_district key=idcomune item=comune}
                      {*  {if $idcomune eq $idselectedcom}
                        <option value="{$idcomune}" selected="selected">{$comune}</option>
                        {else} *}
                            <option value="{$idcomune}">{$comune}</option>
                            {*{/if}*}
                    {/foreach}
                    <option value="0">Non inserito</option>
                </select>
            </td>
                         
                    <td><!--<br /><input type="button" id="update_btn" value="Aggiorna" />--></td>
                    <td><a href="#" class="icolink" onClick="javascript:history.back()"><img src="../styles/page/images/back.png" alt="Torna indietro" /></a></td>
                </tr>
            </table>
        </form>


        <div id="tabs" class="noprint">
            <ul>   
                <li><a class="tab_link" id="tabs-2" href="#tabs-1">Generale</a></li>
                <li><a class="tab_link" id="tabs-1" href="#tabs-1">Per Famiglia</a></li>                 
                <li><a class="tab_link" id="tabs-3" href="#tabs-1">Prodotti Distribuiti</a></li>
            </ul>
            <div id="tabs-1">
                <table id="DTable1"></table>
            </div>         
            <table id="block_table"></table>
            <table id="report-table"></table>
        </div>
        <div id="dialog" title="Prodotti distribuiti">

        </div>
        <div id="distr3"></div>
        <input type="hidden" id="default_selected_tabs" value="{$default_selected_tabs}" />
        <input type="hidden" id="config_start_blocksheet" value="{$config_start_blocksheet}" />
        <input type="hidden" id="refyear" value="{$refyear}" />
        <br /><br />
    </body>
        
</html>
