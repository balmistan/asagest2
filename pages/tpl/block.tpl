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
        {menu data=$menu class="admin_menu noprint"}  
        <table>
            <tr>
                <td colspan=5 id="msg" class="noprint">{$msg}</td>
                <td rowspan=3 class="noprint">
                    <a href="viewfamily?fid={$family_id}" class="icolink"><img src="../styles/page/images/back.png" alt="Torna indietro" /></a>
                </td>
            </tr>
            <tr><td colspan=5 class="noprint"><input type="button" id="removedistr" value="Rimuovi Distribuzione" /></td></tr>
            <tr><td class="noprint linktext"  id="gotolast"><a href="{$link_last_distr}" >{$linktext}</a></td>
            </tr>
            <tr><td><div id="saved_msg">Dati Salvati!</div></td></tr>
           
            <tr>
                <td>
                    <!-- pulsanti -->
                    <div class="noprint" id="blockbtn">              
                        <div class="btn-group noprint">
                            <button class="btn btnlista1" title="copia da una lista precedentemente salvata">Lista 1</button>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li id="mem1">Memorizza</li>
                            </ul>
                        </div>
                        <div class="btn-group noprint">   
                            <input class="btn scorsa" type="button" value="Scorsa"  title="Copia dall' ultima distribuzione effettuata alla stessa famiglia" />
                        </div>  
                        <div class="pagination noprint">
                            <ul>
                                <li><span id="sw_banco">DONAZIONI</span></li>
                                <li class="active"><span id="sw_agea">AGEA</span></li>  
                            </ul>
                        </div>       
                    </div>  <!-- chiusura pulsanti  -->
                </td>
            </tr>
            
            
            <tr>
                <td colspan=2 class="noprint">
                    <ul class="blockicons b_agea" id="blockagea">
                        {foreach from=$arr_products key="mykey" item="myitem"}
                            {if {$myitem["rr"]} == "on"}
                                <li><div class="item" product_id="{$myitem["product_id"]}" qtyforunity="{$myitem["qtyforunity"]}">
                                        {if {$myitem["imagelink"]} != ""}
                                            <img class="photoproduct" src="../Personal/PhotoProducts/{$myitem["imagelink"]}" alt="" /><br />
                                        {/if}
                                        <p class="product_name">{$myitem["product"]}<br />{$myitem["qtyforunity"]} {$myitem["measureunity"]}</p>
                                    </div></li>
                                {/if}
                            {/foreach}    
                    </ul>
                </td></tr>
                    
            <tr>
                <td colspan=2 class="noprint">
                    <ul class="blockicons b_banco" id="blockbanco">
                        {foreach from=$arr_products_banco key="mykey" item="myitem"}
                            {if {$myitem["rr"]} == "on"}
                                <li><div class="item" product_id="banco_{$myitem["product_id"]}" qtyforunity="{$myitem["qtyforunity"]}">
                                        {if {$myitem["imagelink"]} != ""}
                                            <img src="../Personal/PhotoProducts/{$myitem["imagelink"]}" alt="" /><br />
                                        {/if}
                                        <p class="product_name">{$myitem["product"]}<br />{$myitem["qtyforunity"]}{$myitem["measureunity"]}</p>
                                    </div></li>
                                {/if}
                            {/foreach}    
                    </ul>
                </td></tr>  
            <tr><td colspan=3>
                    <div class="draggable" id="blocktableout">
                        <form id="blockform" class="sigPad" name="blockform" method="POST" action="?" >
                            <table class="tableout tout_agea" cellpadding=0 cellspacing=0>
                                <tbody>
                                    <tr><td colspan=4 id="numb">Scheda n° {$family_id}<input type="hidden" id="num_scheda" value="{$family_id}" /></td></tr>
                                    <tr><td>Ric. N° <span id="numrec">{$numrec}</span></td></tr>
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
                                            <!--
                                            <a href="foglio.pdf" target="IFrame">Link Text</a>
                                            <iframe name="IFrame" id="IFrame" src="foglio.pdf"  type="application/pdf" />
                                            -->
                                        </div>
                                    </form> 
                                    <input type="hidden" id="config_start_blocksheet" value="{$config_start_blocksheet}" />
                                    <input type="hidden" id="sheetId" name="sheetId" value="{$sheet_id}" />
                                    
                                </div>
                                
                                
                                
                            </td></tr>
                    </table> 
                                <div id="footer">
                                    
                                </div>
                </body>
            </html>
