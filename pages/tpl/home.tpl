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
        {menu data=$menu class="admin_menu noprint"}
        <br /><br /><br />
        <span id="logged_user">Utente: {$username} {$msg_last_date_login}</span>
        <div id="container">
            <ul>
                <li><a href="searchfamily"><img src="{$path_icons}search.png" class="icons" alt="Cerca scheda" title="Cerca scheda" /></a></li> 
                <li><a href="addmodfamily"><img src="{$path_icons}add.png" class="icons" alt="Aggiungi scheda" title="Aggiungi scheda" /></a></li>       
               <!-- <li><a href="manuale/pagine/index"><img src="{$path_icons}guide.png" class="icons" alt="manuale" title="manuale" /></a></li> -->
           <li><a href="logout"><img src="{$path_icons}logout.png" class="icons" alt="logout" title="logout" /></a></li>
            </ul>
        </div>
            <div id="bottom_pos">E' possibile accedere ad una scheda premendo e lasciando il tasto CTRL e digitando subido dopo in modo rapido il numero della scheda da cercare. L' operazione può essere effettuata da qualsiasi pagina.</div>
    </body>
</html>
