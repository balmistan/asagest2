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

        <div class="content">
            <h2>Ordine di visualizzazione in Allegato 5</h2>
            <p>*Spostare le icone col mouse per cambiare l' ordine di visualizzazione.</p>

            <div class="gridster">
                <ul>
                    {section name=val start=0 loop=count($arrout) step=1}

                        {section name=inside start=0 loop=count($arrout[{$smarty.section.val.index}]) step=1}
                            <li product_id="{$arrout[{$smarty.section.val.index}][{$smarty.section.inside.index}]["product_id"]}" data-row="{$smarty.section.val.index + 1}" data-col="{$smarty.section.inside.index +1}" data-sizex="1" data-sizey="1">
                                <div class="item" product_id="{$arrout[{$smarty.section.val.index}][{$smarty.section.inside.index}]["product_id"]}">

                                    {if {$arrout[{$smarty.section.val.index}][{$smarty.section.inside.index}]["imagelink"]} != ""}
                                        <img class="photoproduct"  src="../Personal/PhotoProducts/{$arrout[{$smarty.section.val.index}][{$smarty.section.inside.index}]["imagelink"]}" alt="" /><br />
                                    {/if}
                                    <span class="product_name">
                                        {$arrout[{$smarty.section.val.index}][{$smarty.section.inside.index}]["product"]}
                                    </span>
                                </div>
                            </li>
                        {/section}


                    {/section}
                </ul>
            </div>
            <!--
                        <ul class="blockicons">
            {section name=products start=0 loop=count($arr_products_8) step=1}

                <li id="{$arr_products_8[$smarty.section.products.index]["product_id"]}"><div class="item" product_id="{$arr_products_8[$smarty.section.products.index]["product_id"]}">
                {if {$myitem["imagelink"]} != ""}
                    <img class="photoproduct"  src="../Personal/PhotoProducts/{$arr_products_8[$smarty.section.products.index]["imagelink"]}" alt="" /><br />
                {/if}
                <span class="product_name">{$arr_products_8[$smarty.section.products.index]["product"]}</span>
            </div></li>

            {/section}    


        </ul>
            -->
        </div>              
            <button id="btn">Salva</button>
    </body>
</html>