

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
        <link rel="stylesheet" href="../styles/block/print.css" type="text/css" media="print" />
        {foreach from=$arr_js_config item=linkjs}
            <script type="text/javascript" src="{$linkjs}"></script>
        {/foreach}
    </head>
    <body>  


        <div class="container-fluid">

            <div class="row">                       {*Menu orizzontale*}
                <div class="col-xs-12 col-sm-12">
                    {menu data=$menu class="admin_menu noprint"}    
                </div>
            </div>


            <div class="row">
                <div class="col-xs-6 col-sm-6 redcolor">
                    {*blocco sinistro*}

                    <div class="draggable" id="blocktableout">
                        <form id="blockform" class="sigPad" name="blockform" method="POST" action="?" >
                            <table class="tableout tout_agea" cellpadding=0 cellspacing=0>
                                <tbody>
                                    <tr><td colspan=4 id="numb">Scheda n° {$family_id}<input type="hidden" id="num_scheda" value="{$family_id}" /></td></tr>
                                    <tr><td colspan=4 id="otherinfo">{$otherinfo}</td></tr>
                                    <tr><td colspan=4 id="person_name">Spett.le {$person_name}</td></tr>
                                    <tr><td colspan=4 id="address">{$address}</td></tr>
                                    <tr><td colspan=4 id="intestazione">{$nome_struttura}<br />Prodotti gratuiti non commerciabili</td></tr>

                                    {foreach from=$arr_products key="mykey" item="myitem"}
                                        {if $mykey|substr:-1 <> "a" && $mykey|substr:-1 <> "b" }
                                            <tr class="rowp">
                                                <td><input type="hidden" name="product_id[]" class="out_product_id" value="{$myitem["product_id"]}" /></td>
                                                <td><input type="text" readonly="readonly" name="qtytot[]" class="out_qtytot" product_id="{$myitem["product_id"]}" value="{$myitem["qtytot"]}" /></td>
                                                <td><input type="text" readonly="readonly" class="out_measure_unity" product_id="{$myitem["product_id"]}" value="{$myitem["measureunity"]}" /></td>
                                                <td><input type="text" readonly="readonly" class="out_product_name" product_id="{$myitem["product_id"]}" value="{$myitem["product"]}" /></td>
                                                <td class="noprint"><input type="button" value="X" class="X" /></td>
                                            </tr>
                                        {/if}
                                    {/foreach}    
                                </tbody>
                            </table>
                            <center>&nbsp;&nbsp;(Sopra &uarr; Viveri Agea, sotto &darr; Viveri donazioni)</center>
                            <table class="tableout tout_banco" cellpadding=0 cellspacing=0>
                                <tbody>
                                    {foreach from=$arr_products_banco key="mykey" item="myitem"}
                                        {if $mykey|substr:-1 <> 
										"a" && 
										$mykey|substr:-1 <> 
										"b"}
                                        <tr class="rowp">
                                            <td><input type="hidden" name="product_id[]" class="out_product_id" value="banco_{$myitem["product_id"]}" /></td>
                                            <td><input type="text" readonly="readonly" name="qtytot[]" class="out_qtytot" product_id="banco_{$myitem["product_id"]}" value="{$myitem["qtytot"]}" /></td>
                                            <td><input type="text" readonly="readonly" class="out_measure_unity" product_id="{$myitem["product_id"]}" value="{$myitem["measureunity"]}" /></td>
                                            <td><input type="text" readonly="readonly" class="out_product_name" product_id="{$myitem["product_id"]}" value="{$myitem["product"]}" /></td>
                                            <td class="noprint"><input type="button" value="X" class="X" /></td>
                                        </tr>
                                        {/if}
                                            {/foreach}    
                                            </tbody>
                                        </table>

                                        <input type="hidden" name="person_id" value="{$person_id}" />
                                        <input type="hidden" id="modifiable" name="modifiable" value="1" />
                                        <input type="hidden" id="sheetId" name="sheetId" value="{$sheet_id}" />

                                        {if $init_registri eq 1}
                                            <label id="label_date" class="noprint">Data distribuzione:</label><input type="text" name="date" class="noprint" value="" id="date" size="3" />      
                                        {else} 
                                            <input type="hidden" name="date" class="noprint" value="{$dataemiss}" id="date" /> 
                                        {/if} 

                                        <div id="content2"> 
                                            {if $signature_block eq 1}  
                                                <span id="signature_field">....................................................&nbsp;&nbsp;<br /></span>
                                            {/if} 
                                            <input type="hidden" id="num_indig" name="num_indig" value="{$num_indig}" />
                                            <input type="hidden" id="usesignature" value="{$signature_block}" />
                                            <input type="submit" id="Salva" class="noprint" name="Salva" value="Salva" />
                                            <img src="../styles/block/icons/loading.gif" class="noprint" id="img_wait" alt="Attendere..." title="" />
                                            <a href="#" class="noprint" id="print" title="Stampa la ricevuta"><img src="../styles/block/icons/print.png" class="noprint" alt="stampa" /></a>
                                        </div>
                                    </form> 
                                    <input type="hidden" id="config_start_blocksheet" value="{$config_start_blocksheet}" />
                                </div>

                                {*fine blocco sinistro*}
                            </div>
                            <div class="col-xs-6 col-sm-6 greencolor">
                                {*blocco destro*}



                                {*fine blocco destro*}
                            </div>
                        </div>

                    </div>



                </body>
            </html>