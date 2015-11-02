<?php

//Permette di convertire l' array smarty generato con la classe Smenu in stringa contenente il markup del menù utilizzabile mediante echo nelle pagine che non usano 
//Smarty.

//Si utilizza così:

//echo menu_convert(array("data" =>  $arr_Smarty_out, "class" => "admin_menu"));

//dove $arr_Smarty_out è l' array restituito dalla classe Smenu.

//$menu = new Smenu();

// ...........
// ...........

// $arr_Smarty_out = $menu->close();



////////////////////////////////////////////////////////////////////////////////////////////////////////////

// number of chars to indent unordered list level
define('MENU_INDENT', 3);

function ins_item($element,$level) {
    
    $_output = '';

    if(isset($element['link']))
        $_text = "<a href=\"" . htmlspecialchars($element['link']) . "\">" . htmlspecialchars($element['text']) . "</a>";
    else
        //$_text = '<span class="nolink">' . htmlspecialchars($element['text']). '</span>';  //tolta
          $_text =  htmlspecialchars($element['text']);   //aggiunta
    if(isset($element['submenu'])) {
    
        $_class = isset($element['class']) ? $element['class'] : 'nav_parent';
        
        $_output .= str_repeat(' ', $level * MENU_INDENT) . "<li class=\"$_class\">" . $_text . "\n";
        $_output .= str_repeat(' ', $level * MENU_INDENT) . "<ul>\n";

        foreach($element['submenu'] as $_submenu) {
            $_output .=  ins_item($_submenu, $level + 1);
        }

        $_output .= str_repeat(' ', $level * MENU_INDENT) . "</ul>\n";
        $_output .= str_repeat(' ', $level * MENU_INDENT) . "</li>\n";

    } else {
        $_class = isset($element['class']) ? $element['class'] : 'nav_child';
        $_output .= str_repeat(' ', $level * MENU_INDENT) . "<li class=\"$_class\">" . $_text . "</li>\n";        
    }
    
    return $_output;
}

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     menu
 * Purpose:  generate menu
 * -------------------------------------------------------------
 */
function menu_convert($params)
{
    if(empty($params['data'])) {
        return false;
    }

      $_id = isset($params['id']) ? $params['id'] : 'nav';
      
      $_class = "";
      if(isset($params['class'])) $_class = " class=\"".$params['class']."\" ";
      
    $_output = "<div class=\"container4\">\n";
     $_output .= "<div class=\"menu4\">\n";
    $_output .= "<ul id=\"$_id\"".$_class.">\n";
    
    foreach($params['data'] as $_element) {
        $_output .= ins_item($_element, 1);   
    }
    
    $_output .= "</ul>\n";
    
    $_output .= "</div>\n";
    $_output .= "</div>\n";

    return $_output;
}

/* vim: set expandtab: */



?>
