<?php


function getOrrMenu() {         //crea il codice html del menù
    global $session;
   
    $menu = new Smenu("", true);

    if (accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), "admins")) {

        //prototipo:
        // $menu->addItem(array('Text'=>'', 'Link'=>'#', 'Class'=>'',  'Index'=>''));
        
        $menu->addItem(array('Text' => 'Home', 'Link' => 'home', 'Class' => 'home', 'Index' => '1'));
        
        $menu->addItem(array('Text' => 'Agea', 'Link' => '#', 'Class' => 'agea', 'Index' => '2'));
        $menu->addItem(array('Text' => 'Carico', 'Link' => 'load', 'Class' => 'load', 'Index' => '2.1'));
        $menu->addItem(array('Text' => 'Allegato 5', 'Link' => 'all5?dwn=1', 'Class' => 'allegati', 'Index' => '2.2'));
        $menu->addItem(array('Text' => 'Allegato 6', 'Link' => 'all6', 'Class' => 'allegati', 'Index' => '2.3'));
        $menu->addItem(array('Text' => 'Report', 'Link' => 'report', 'Class' => 'report', 'Index' => '2.4'));
        
        $menu->addItem(array('Text' => 'Cerca scheda', 'Link' => 'searchfamily', 'Class' => 'search', 'Index' => '3'));

        $menu->addItem(array('Text' => 'Agg. scheda', 'Link' => 'addmodfamily', 'Class' => 'add', 'Index' => '4'));
        
        $menu->addItem(array('Text' => 'Configurazioni', 'Link' => '#', 'Class' => 'settings', 'Index' => '5'));
        $menu->addItem(array('Text' => 'Sistema', 'Link' => 'settings', 'Class' => 'system', 'Index' => '5.1'));
        $menu->addItem(array('Text' => 'Prodotti Agea', 'Link' => 'products', 'Class' => 'products', 'Index' => '5.2'));
        $menu->addItem(array('Text' => 'Prodotti Donaz.', 'Link' => 'bproducts', 'Class' => 'products', 'Index' => '5.3'));
        $menu->addItem(array('Text' => 'Ordine Visualizzazione', 'Link' => 'sortproducts', 'Class' => 'products', 'Index' => '5.4'));
        $menu->addItem(array('Text' => 'Info Allegati', 'Link' => 'info_all', 'Class' => '', 'Index' => '5.5'));
        
        
    
        $menu->addItem(array('Text' => 'Account', 'Link' => '#', 'Class' => 'account', 'Index' => '5.6'));
        $menu->addItem(array('Text' => 'Nuovo Account', 'Link' => 'useradd', 'Class' => 'newaccount', 'Index' => '5.6.1'));
        $menu->addItem(array('Text' => 'Account volontari', 'Link' => 'userlist', 'Class' => 'viewmod', 'Index' => '5.6.2'));
        $menu->addItem(array('Text' => 'Mia password', 'Link' => 'changepwd', 'Class' => 'changepwd', 'Index' => '5.6.3'));
        $menu->addItem(array('Text' => 'Miei dati personali', 'Link' => 'mypersonaldata', 'Class' => 'mydata', 'Index' => '5.6.4'));

        $menu->addItem(array('Text' => 'Altro', 'Link' => '#', 'Class' => 'other', 'Index' => '6')); 
        $menu->addItem(array('Text' => 'Cerca persone', 'Link' => 'advancedsearch', 'Class' => 'advancedsearch', 'Index' => '6.1'));
        
        $menu->addItem(array('Text' => 'Certif. scaduti', 'Link' => 'expiredisee', 'Class' => 'expiredisee', 'Index' => '6.2'));
        $menu->addItem(array('Text' => 'Guida', 'Link' => 'manuale/pagine/index', 'Class' => 'manuale/pagine', 'Index' => '6.3'));
        $menu->addItem(array('Text' => 'Backup', 'Link' => 'backup', 'Class' => 'backup', 'Index' => '6.4'));
   
        $menu->addItem(array('Text' => 'Logout', 'Link' => 'logout', 'Class' => 'logout', 'Index' => '7'));
        
        
        
    } else if (accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), "users")) {

        //prototipo:
        // $menu->addItem(array('Text'=>'', 'Link'=>'#', 'Class'=>'',  'Index'=>''));
        
        $menu->addItem(array('Text' => 'Home', 'Link' => 'home', 'Class' => 'home', 'Index' => '1'));
        
        $menu->addItem(array('Text' => 'Agea', 'Link' => '#', 'Class' => 'agea', 'Index' => '2'));
        $menu->addItem(array('Text' => 'Carico', 'Link' => 'load', 'Class' => 'load', 'Index' => '2.1'));
        $menu->addItem(array('Text' => 'Allegato 5', 'Link' => 'all5?dwn=1', 'Class' => 'allegati', 'Index' => '2.2'));
        $menu->addItem(array('Text' => 'Allegato 6', 'Link' => 'all6', 'Class' => 'allegati', 'Index' => '2.3'));
        $menu->addItem(array('Text' => 'Report', 'Link' => 'report', 'Class' => 'report', 'Index' => '2.4'));
        $menu->addItem(array('Text' => 'Cerca scheda', 'Link' => 'searchfamily', 'Class' => 'search', 'Index' => '3'));

        $menu->addItem(array('Text' => 'Agg. scheda', 'Link' => 'addmodfamily', 'Class' => 'add', 'Index' => '4'));
        
        
    
        $menu->addItem(array('Text' => 'Account', 'Link' => '#', 'Class' => 'account', 'Index' => '5'));
        $menu->addItem(array('Text' => 'Mia password', 'Link' => 'changepwd', 'Class' => 'changepwd', 'Index' => '5.1'));
        $menu->addItem(array('Text' => 'Miei dati personali', 'Link' => 'mypersonaldata', 'Class' => 'mydata', 'Index' => '5.2'));

        $menu->addItem(array('Text' => 'Altro', 'Link' => '#', 'Class' => 'other', 'Index' => '6')); 
        $menu->addItem(array('Text' => 'Cerca persone', 'Link' => 'advancedsearch', 'Class' => 'advancedsearch', 'Index' => '6.1'));
        
        $menu->addItem(array('Text' => 'Certif. scaduti', 'Link' => 'expiredisee', 'Class' => 'expiredisee', 'Index' => '6.2'));
        $menu->addItem(array('Text' => 'Guida', 'Link' => 'manuale/pagine/index', 'Class' => 'manuale', 'Index' => '6.3'));
        
        $menu->addItem(array('Text' => 'Logout', 'Link' => 'logout', 'Class' => 'logout', 'Index' => '7'));
        
        
    }


    return $menu->close();
}

?>
