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
           
        </div>                  
    </body>
</html>