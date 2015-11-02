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
        <br />
        <center><div class = "sheet">
                <h2>Prodotti Da Donazioni</h2>
                <form id="form" name="add_product" method="POST" action="?" >
                    <table id="btable">
                        <tr><td><input type="hidden" name="Salva" value="" /></td></tr>
                        {if !empty($arr_in)}
                            {section name=xx loop=$arr_in["product_id"]}
                                <tr>
                                    <td>
                                        <input type="hidden" name="modifiable[]" class="mtf_textfield hfield_modifiable" value="{$arr_in["modifiable"][xx]}" />
                                        <input type="hidden" name="product_id[]" id="product_id" value="{$arr_in["product_id"][xx]}" class = "mtf_textfield hfield_product_id" />
                                        <div class="photodiv"><img src="../styles/page/images/nophoto.png" alt="" /></div>
                                        <input type="hidden" name="imagelink[]" value="{$arr_in["imagelink"][xx]}" class = "mtf_textfield hfield_imglink" />
                                    </td>
                                    {if $arr_in["modifiable"][xx] eq 1}  
                                        <td><label>Prodotto<span class="red">*</span>:</label><br /><input type="text" name="product[]" class = "mtf_textfield product_name_tbox" value = "{$arr_in["product"][xx]}" id = "product[]"  /></td>
                                        <td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity[]" class = "mtf_textfield qta_tbox" value = "{$arr_in["qtyforunity"][xx]}" id = "qtyforunity[]"  /></td>
                                        <td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity2[]" class = "mtf_textfield qta_tbox" value = "{$arr_in["qtyforunity2"][xx]}" id = "qtyforunity2[]"  /></td>
                                        <td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity3[]" class = "mtf_textfield qta_tbox" value = "{$arr_in["qtyforunity3"][xx]}" id = "qtyforunity3[]"  /></td>
                                        <td><label>Unità di mis.<span class="red">*</span>:</label><br /><select name="measureunity[]" tabindex="4" class="mtf_textfield unity_tbox"><option value="selezionare">selezionare</option>
                                                {foreach from=$selectbox_options key=k item=elem}
                                                    {if $k eq {$arr_in["measureunity"][xx]}}
                                                        <option value="{$k}" selected="selected">{$elem}</option>
                                                    {else}
                                                        <option value="{$k}">{$elem}</option>
                                                    {/if}
                                                {/foreach}                
                                            </select></td>
                                    {else}  <!-- se non modificabile -->
                                        <td><label>Prodotto<span class="red">*</span>:</label><br /><input disabled="disabled" type="text" tabindex="2" name="product[]" class = "mtf_textfield product_name_tbox" value = "{$arr_in["product"][xx]}" id = "product[]"  /></td>
                                        <td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity[]" class = "mtf_textfield qta_tbox" value = "{$arr_in["qtyforunity"][xx]}" id = "qtyforunity[]"  /></td>
                                        <td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity2[]" class = "mtf_textfield qta_tbox" value = "{$arr_in["qtyforunity2"][xx]}" id = "qtyforunity2[]" /></td>
                                        <td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity3[]" class = "mtf_textfield qta_tbox" value = "{$arr_in["qtyforunity3"][xx]}" id = "qtyforunity3[]" /></td>
                                        <td><label>Unità di mis.<span class="red">*</span>:</label><br /><select disabled="disabled" name="measureunity[]" tabindex="4" class="mtf_textfield unity_tbox"><option value="selezionare">selezionare</option>
                                                {foreach from=$selectbox_options key=k item=elem}
                                                    {if $k eq {$arr_in["measureunity"][xx]}}
                                                        <option value="{$k}" selected="selected">{$elem}</option>
                                                    {else}
                                                        <option value="{$k}">{$elem}</option>
                                                    {/if}
                                                {/foreach}                
                                            </select></td>
                                        {/if}
                                    <td class="rr1">
                                        <center>
                                            <label>Vis.</label><br />
                                            <input type="hidden" class="rr" name="rr[]" value="{$arr_in["rr"][xx]}" />       
                                            {if $arr_in["rr"][xx] eq off}
                                                <input type="checkbox" class="rr_check" title="Indica se visualizzare il prodotto sul blocchetto consegne" /> 
                                            {else}
                                                <input type="checkbox" class="rr_check" checked="checked" title="Indica se visualizzare il prodotto sul blocchetto consegne" />
                                            {/if}
                                        </center>
                                    </td>
                                    <td>
                                        {if $arr_in["modifiable"][xx] eq 1}
                                            <input type="button" class="rem" value="X" title="Rimuove riga" />
                                        {/if}
                                    </td>

                                </tr> 

                            {/section}
                        {/if}  <!-- close if !empty -->
                        <tr>
                            <td><div id="refdiv"></div></td>
                        </tr>
                        <tr>
                            <td colspan=2></td>
                            <td><input type="button" id="addrow" value="Aggiungi" class="bproducts" /></td>
                            <td><input type="button" id="submit_btn" value="Salva" /></td>
                            <td><input type="button" name="Annulla" id="Annulla" value="Annulla"  /></td>
                        </tr>
                        <tr>
                            <td><div id = "saved_msg">Dati salvati!</div></td>
                        </tr>
                    </table></form></div>
        </center><div id = "content_webcam"></div>
        <input type="hidden" id="nophoto" value="{$nophoto}" /> <!-- serve al plugin webcam per conoscere il link dell' icona foto assente. -->
        <input type="hidden" id="encodedoptions" value={$encodedoptions} /> <!-- serve a product.js per conoscere le opzioni della selectbox -->
        <input type="hidden" id="prefix" value="{$prefix}" /> <!-- prefisso per path immagini upload -->
        {if !$is_mobile}
            <p>(Per inserire la foto cliccare su "NO FOTO" una volta aggiunta la riga)</p>
            <p>(Qtà/unità indica la quantità minima di prodotto distribuibile; l'unità di misura deve essere quella che vogliamo nei report)</p>
            <p>Qtà/unità sono 3 campi. Vanno compilati da sinistra verso destra. In quelli non utilizzati inserire 0</p>
        {/if}
    </body>
</html>
