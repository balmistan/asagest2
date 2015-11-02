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
        <form name="adv_search" method="POST" action="?">
            
            <table id="tablesearch"> 

                <tr>        
                    <td><label>Ricerca persone per fascia di età</label></td>
                    <td><span id="error_msg"></span></td>
                    <td><label>Comune</label></td>
                    <!--<td><label>Sesso</label></td>-->
                </tr>
                <tr>
                    <td><label>Da:</label>&nbsp<select id="min_age" class="age">
                            <option value="-1">< 1 anno</option>
                            {foreach from=$arr_age item=age}
                                <option value="{$age}">{$age}</option>
                            {/foreach}
                        </select> </td>
                    <td><label>A:&nbsp</label><select id="max_age" class="age">
                            <option value="-1">< 1 <b>an</b>no</option>
                            {foreach from=$arr_age item=age}   
                                <option value="{$age}">{$age}</option>
                            {/foreach}
                            <option value="all" selected="selected">Tutte</option>
                        </select> </td>
                    <td><select id="comune" name="comune">
                            <option value="">Tutti</option>
                            {foreach from=$arr_district key=idcomune item=comune}
                                <option value="{$idcomune}">{$comune}</option>
                            {/foreach}
                            <option value="0">Non indicato</option>
                        </select>
                    </td>
                    <!--<td>
                        <div class="radiobtn">
                            <input type="radio" id="radio0" name="sex" value="M" checked="checked" /><label for="radio0">Uomo</label>
                            <input type="radio" id="radio1" name="sex" value="F" /><label for="radio1">Donna</label>
                            <input type="radio" id="radio2" name="sex" value="X" checked="checked" /><label for="radio2">Entrambi</label>
                        </div>
                    </td>-->
                    <!--<td>
                        <div class="radiobtn">
                            <input type="radio" id="radio3" name="typegroup" value="active" checked="checked" /><label for="radio3">Attive</label>
                            <input type="radio" id="radio4" name="typegroup" value="inactive" /><label for="radio4">Disattivate</label>
                            <input type="radio" id="radio5" name="typegroup" value="x" /><label for="radio5">Tutte</label>
                        </div>
                    </td>-->
                </tr>
              <!--  <tr>
                    <td colspan=2><span id="error_msg"></span></td>
                </tr> -->
            </table>
                            
        </form>
        <table id="DTable"></table>

        <div id="debug"></div>
    </body>
</html>
