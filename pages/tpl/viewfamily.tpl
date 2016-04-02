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
        <input type="button" id="distr_btn" />
        <a href="searchfamily" id="pageback"><img src="../styles/page/images/back.png" alt="Torna indietro" /></a>
        <table id="table1">

            <tr><td colspan=4 class="suggestion">Per iniziare la distribuzione cliccare sul nome di chi è venuto a ritirare o premere Invio!</td></tr>
            <tr><td class="{$class_color_msg}" id="num_scheda" colspan=4>Scheda n° {$idfamily}</td></tr>
            {if $arr_res["img_lk"]|@count eq 0}
                <tr><td>Scheda non trovata!</td></tr>
            {else}
                {foreach from=$arr_res["img_lk"] key=index item=link}
                    {if $arr_res["rr"][{$index}] eq "on"}
                        <tr>
                            {if $usephoto}
                                <td class="lk"><img src="image_wrapper.php?url={$link}" alt="" /></td>
                            {else}
                                <td class="lk">&nbsp;&nbsp;</td>
                            {/if}
                            <td class="lk">{$arr_res["lname"][{$index}]}</td>
                            <td class="lk">{$arr_res["fname"][{$index}]}</td>
                            <td><div class = "sexeta"></div></td>   
                            <td><img src="../styles/viewfamily/icons/freccia_sx.png" alt="" class="arrow" /></td>
                            <td><input type="hidden" class = "cf_tbox" value="{$arr_res["cf"][{$index}]}" /></td>
                            <td><input type="hidden" class = "borndate_tbox" value="{$arr_res["born"][{$index}]}" /></td>
                            <td><input type="hidden" class="pid" value={$arr_res["person_id"][{$index}]} /></td>                 
                        </tr>
                    {/if}
                {/foreach}

                {foreach from=$arr_res["img_lk"] key=index item=link}
                    {if $arr_res["rr"][{$index}] eq "off"}
                        <tr>
                            {if $usephoto}
                                <td class="lk"><img src="image_wrapper.php?url={$link}" alt="" /></td>
                            {else}
                                <td class="lk">&nbsp;&nbsp;</td>
                            {/if}
                            <td class="lk">{$arr_res["lname"][{$index}]}</td>
                            <td class="lk">{$arr_res["fname"][{$index}]}</td>
                            <td><div class = "sexeta"></div></td>   
                            <td><img src="../styles/viewfamily/icons/freccia_sx.png" alt="" class="arrow" /></td>
                            <td><input type="hidden" class = "cf_tbox" value="{$arr_res["cf"][{$index}]}" /></td>
                            <td><input type="hidden" class = "borndate_tbox" value="{$arr_res["born"][{$index}]}" /></td>
                            <td><input type="hidden" class="pid" value={$arr_res["person_id"][{$index}]} /></td>                 
                        </tr>
                    {/if}
                {/foreach}


                <tr>
                    <td colspan=2><button id="change">Modifica</button></td>
                    <td><button id="report">Report</button></td>
                </tr>
                <tr><td colspan=4><div class="msg_bottom">

                            {if $note != ""}
                                <b>Note:</b><br />
                                <textarea align = "top" rows = "2" cols = "45" readonly="readonly" style = "resize:none;">{$note}</textarea>
                            {/if}
                            {foreach from=$msg key="index" item=str}
                                <p>{$str}</p>
                            {/foreach}   

                            {if !empty($wmsg)}  
                                {foreach from=$wmsg key="index" item=str}
                                    <p class="yellow">{$str}</p>
                                {/foreach}  
                            {/if}     

                            {foreach from=$emsg key="index" item=str}
                                <p class="red">{$str}</p>
                            {/foreach}  
                        </div></td></tr>
                    {/if}
            <tr><td colspan=3><input type="hidden" id="idfamily" value={$idfamily} /></td></tr>
        </table>

    </body>
</html>
