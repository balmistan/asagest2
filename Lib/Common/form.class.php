<?php

/**
 * form class
 * this class can make a form
 * @author NetMDM <1@3bsd.net>
 * @version 5.9
 * @package libss
 */
class form {

    private $formId = "form";
    private $formClass = "form";
    private $action;
    private $formname;
    private $focuscolor = "#ffffff";

    /**
     * Oggetto chiamante
     * @access private
     * 
     */
    protected $parent;
    private $method;
    private $content;
    private $rulesActive = false;
    private $innerScript = "";
    private $tabindex;

    // private $jquery_block=false;  //indica se nella pagina è già stato inserito il blocco JQUERY  //obsolete


    public function __construct($formname, $method, $action, $parent, $properties = array()) {
        $this->parent = $parent;
        $this->tabindex = 1;
        $this->formname = $formname;
        $this->method = $method;
        $this->action = $action;
        if (isset($properties['id'])) {
            $this->formId = $properties['id'];
            unset($properties['id']);
        }
        if (isset($properties['class'])) {
            $this->formClass = $properties['class'];
            unset($properties['class']);
        }
        $props = "";
        foreach ($properties as $key => $value) {
            $props.="$key = \"$value\" ";
        }

        $this->content = "\n";
        $this->content.= '<form id="' . $this->formId . '" name="' . $this->formname . '" method="' . $this->method . '" action="' . $this->action . '" ' . $props . '>
		      <table>';
    }

    /**
     * Permette di aggiungere un pulsante al form
     * @param $type[optional], può essere di tipo submit o cancel. Default submit
     * @param $value[optional], label del pulsante, default Invia
     */
    public function addButton($type = "submit", $buttonname = "Invia", $properties = array()) {

        if (isset($properties['value']))
            unset($properties['value']);
        $props = "";
        foreach ($properties as $key => $value) {
            $props.="$key = \"$value\" ";
        }
        $this->content.= "\n";
        $this->content.= '<tr><td></td><td>
		 <input type="' . $type . '" tabindex="' . $this->tabindex++ . '" name="' . $buttonname . '" id="' . $buttonname . '" value="' . $buttonname . '" ' . $props . ' />
		</td></tr>
		';
    }

    /**
     * Permette di aggiungere uno spazio verticale nel form
     * @param $size valore in px
     */
    public function addVSpace($size = 50) {
        $this->content.= "<tr><td><div style=\"height:" . $size . "px;\"></div></td></tr>\n";
    }

    /**
     * Permette di aggiungere un div nel form
     * @param string $label
     * @param string $content html da includere nel blocco div
     * @param array $props proprietà del div
     * @param boolean $newrow indica se inserire o no sulla stessa riga della tabella
     */
    public function addDiv($label, $content, $props = array(), $newrow = false) {
        if ($newrow) {
            $this->content.= '<tr>';
        } else {   //rimuovo il prec </tr> se è presente 
            $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");
            // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 
            //da implementare con le regexp
            if ($tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
                //elimino "</tr>\n" presente alla fine della stringa
                $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
            }
        }//close else  

        $code = "<td><label>" . $label . "</label><br /><div";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }
        $this->content.= $code . ">" . $content . "</div></td></tr>\n";
    }

//close function

    public function addTextField($name, $label, $properties = array()) {
        //if ((!is_array($properties)) die ("FORM::addTextField need a filled array");
        ///////verificare questo sopra/////////

        if (!isset($properties['id']))
            $properties['id'] = $name;
        $props = "";
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $props.="$key = \"$value\" ";
            }
        }
        $this->content.= "\n";
        $this->content.= "<tr><td>$label</td><td><input type=\"text\" tabindex=\"" . $this->tabindex++ . "\" name=\"$name\" $props /></td></tr>\n";
    }

    /**
     * $newrow boolean indica se inserire su una nuova riga 
     * $colspan=Array("0"=>"","1"=>"","2"=>"") "0"=>se è presente 
     * inserisce una colonna vuota a sinistra della label col valore di colspan specificato; "2"=>colspan su label; "3"=>colspan su textarea 
     * */
    public function addTextField_2($name, $label, $properties = array(), $newrow = true, $colspan = array("", "", "")) {
        //if ((!is_array($properties)) die ("FORM::addTextField need a filled array");
        ///////verificare questo sopra/////////

        if (!is_array($colspan) || count($colspan) != 3)
            die("FORM::addTextField_2 need a filled array colspan");


        if (!isset($properties["type"]))
            $type = "type=\"text\"";
        else
            $type = "";   //viene aggiunto attraverso l' array $properties

        foreach ($colspan as $k => $v) {
            if ($k != 0 && $v != "1" && $v != "")
                $colspan[$k] = " colspan=" . $colspan[$k];
        }//close foreach

        if (!isset($properties['id']))
            $properties['id'] = $name;
        $props = "";
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $props.="$key = \"$value\" ";
            }
        }

        if ($newrow) {

            $this->content.= '<tr>';
        } else {   //rimuovo il prec </tr> se è presente 
            $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");

            // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 
            //da implementare con le regexp
            if ($tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
                //elimino "</tr>\n" presente alla fine della stringa
                $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
            }
        }//close else


        if ($colspan[0] != "") {
            for ($i = 0; $i < intval($colspan[0]); $i++)
                $this->content.= '<td></td>';
        }

        $this->content.= "\n";
        $this->content.= "<td" . $colspan[1] . "><label>$label</label><br /><input " . $type . " tabindex=\"" . $this->tabindex++ . "\" name=\"$name\" $props /></td></tr>\n";
    }

    /**
     * Aggiunge uno script js alla pagina
     * @param string $filename è il nome del file js
     * @param array $props è l'array delle proprietà aggiuntive
     */
    public function addJS($filename, $props = array()) {
        $this->parent->addJS($filename, $props);
    }

    /**
     * Aggiunge un foglio di stile classico alla pagina
     * @param string $filename contiene il nome del file css
     * @parma string $media[optional] è il media su cui visualizzare lo script, default "all"" ma può anch'essere screen, print, phone 
     */
    public function addStyle($filename, $media = "all") {
        $this->parent->addStyle($filename, $media = "all");
    }

    /**
     * Aggiunge una textarea al form
     * @param string $name contiene il nome della textarea
     * @param string $label contiene la label da visualizzare sul form
     * @param array $properties contiene le proprietà aggiuntive
     * $newrow boolean indica se inserire su una nuova riga 
     * $colspan=Array("0"=>"","1"=>"","2"=>"") "0"=>se è presente 
     * inserisce una colonna vuota a sinistra della label col valore di colspan specificato; "2"=>colspan su label; "3"=>colspan su textarea  
     */
    public function addTextArea($name, $label, $properties = array(), $newrow = true, $colspan = array("", "", "")) {
        if (!is_array($colspan) || count($colspan) != 3)
            die("FORM::addTextArea need a filled array colspan");
        $sinserttext = "";  //testo da inserire prima della chiusura del tag <textarea>. 
        foreach ($colspan as $k => $v) {
            if ($k != 0 && $v != "1" && $v != "")
                $colspan[$k] = " colspan=" . $colspan[$k];
        }//close foreach

        if (!isset($properties['id']))
            $properties['id'] = $name;
        $props = "";
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $props.="$key = \"$value\" ";
            }
            if (isset($properties["value"]))
                $sinserttext = $properties["value"];
        }

        if ($newrow) {

            $this->content.= '<tr>';
        } else {   //rimuovo il prec </tr> se è presente 
            $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");

            // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 
            //da implementare con le regexp
            if ($tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
                //elimino "</tr>\n" presente alla fine della stringa
                $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
            }
        }//close else


        if ($colspan[0] != "") {
            for ($i = 0; $i < intval($colspan[0]); $i++)
                $this->content.= '<td></td>';
        }

        $this->content.= "\n";
        $this->content.= "<td" . $colspan[1] . "><label>$label</label><br /><textarea tabindex=\"" . $this->tabindex++ . "\" name=\"$name\" $props>" . $sinserttext . "</textarea></td></tr>\n";
    }

    public function addPassField($name, $label, $minlength, $repeat, $properties = array()) {
        if (!isset($properties['id'])) {
            $id_passfield = $name;
        } else {
            $id_passfield = $properties['id'];
            unset($properties['id']);
        }
        $props = "";
        foreach ($properties as $key => $value) {
            if ($key != $properties['id'])
                $props.="$key = \"$value\" ";
        }

        $id_confirm_passfield = "confirm_" . $id_passfield;

        $this->content.= "\n";
        $this->content.= "<tr><td><label>$label</label><br /><input type=\"password\"  tabindex=\"" . $this->tabindex++ . "\" name=\"$name\"  id=\"$id_passfield\" $props  /></td></tr>\n";
        if ($repeat == 1) {
            $props = str_replace("/" . $id_passfield . "/", "confirm_$name", $props);
            $this->content.= "<tr><td><label>Conferma $label</label><br /><input type=\"password\" tabindex=\"" . $this->tabindex++ . "\" name=\"confirm_$name\" id=\"$id_confirm_passfield\" $props/></td></tr>\n";

            //$this->addRule($name, array("password_length", $minlength));
            $this->addRule($name, array("validpassword", true), ".....");
        }
    }

    /**
     * Crea una selectbox
     * @param object $name, il nome della select
     * @param object $label, la label della select
     * @param object $optionslist, un array associativo contenente nome e label dell'opzione
     * @param object $selectedoption [optional], permette di specificare un'opzione predefinita
     * @param object $multiple [optional], permette di impostare una scelta multipla 
     * @param object $newrow boolean indica se inserire su una nuova riga 
     * @param object $colspan=Array("0"=>"","1"=>"","2"=>"") "0"=>se è presente 
     * inserisce una colonna vuota a sinistra deklla label col valore di colspan specificato; "2"=>colspan su label; "3"=>colspan su textarea 
     */
    public function addSelectBox_2($name, $label, $optionslist, $properties = array(), $selectedvalue = "", $newrow = true, $colspan = array("", "", "")) {

        if (!is_array($colspan) || count($colspan) != 3)
            die("FORM::addSelectBox_2 need a filled array colspan");

        foreach ($colspan as $k => $v) {
            if ($v != "1" && $v != "")
                $colspan[$k] = " colspan=" . $colspan[$k];
            else
                $colspan[$k] = "";
        }//close foreach



        if (!is_array($optionslist))
            die("form::addSelectBox need an array as optionslist\n<br />");

        if ($label == "")
            $html = "<td" . $colspan[2] . "><select name=\"$name\">\n";
        else
            $html = "<td" . $colspan[1] . "><label>" . $label . "</label><br /><select name=\"$name\" tabindex=\"" . $this->tabindex++ . "\">";

        //valuto posizione inserimento
        if ($newrow) {
            $this->content.= '<tr>';
        } else {   //rimuovo il prec </tr> se è presente 
            $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");

            // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 

            if ($tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
                //elimino "</tr>\n" presente alla fine della stringa
                $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
            }
        }//close else if not ($newrow)


        if ($colspan[0] != "") {

            $this->content.= '<td' . $colspan[0] . '></td>';
        }

        $this->content.= "\n";




        if ((!is_array($properties)) && (!is_null($properties))) {
            $selectedvalue = $properties;
        } else if (count($properties) > 0) {
            $html = rtrim($html, '>');
            foreach ($properties as $key => $value) {

                $value = preg_replace("/\"/", "\"", $value);
                $html.=" $key=\"$value\"";
            }
            $html.=">";
        }
        $this->content.= $html;

        foreach ($optionslist as $key => $value) {
            if ($key != $selectedvalue) {
                $this->content.= '<option value="' . $key . '">' . $value . '</option>' . "\n";
            } else {
                $this->content.= '<option value="' . $key . '" selected="selected">' . $value . '</option>' . "\n";
            }
        }
        $this->content.= "</select></td></tr>\n";
    }

    public function addMultiSelectBox($name, $label, $optionslist, $properties = array(), $selectedvalue = "") {
        $name.="[]";
        if (!is_array($optionslist))
            die("form::addMultiSelectBox need an array as optionslist\n<br />");
        if ($label == "") {
            $html = "<select id=\"$name\" name=\"$name\">\n";
        } else {
            $html = "<tr><td>$label</td><td>\n<select id=\"$name\" name=\"$name\" tabindex=\"" . $this->tabindex++ . "\" multiple=\"multiple\">\n";
        }

        if (count($properties) > 0) {
            $html = rtrim($html, '>');
            foreach ($properties as $key => $value) {
                $html.=" $key=$value";
            }
            $html.=">";
        }

        $this->content.= $html;

        if (!is_array($selectedvalue))
            $selectedvalue = array($selectedvalue);

        foreach ($optionslist as $key => $value) {
            if (!in_array($key, $selectedvalue)) {
                $this->content.= '<option name="' . $key . '" value="' . $key . '">' . $value . '</option>' . "\n";
            } else {
                $this->content.= '<option name="' . $key . '" value="' . $key . '" selected="selected">' . $value . '</option>' . "\n";
            }
        }
        $this->content.= "</select>\n</td></tr>";
    }

    public function addMultiSelectBox2($name, $label, $optionslist, $properties = array(), $selectedvalue = "") {
        if (!is_array($optionslist))
            die("form::addMultiSelectBox need an array as optionslist\n<br />");
        if ($label == "") {
            $html = "<select id=\"$name\" name=\"$name\">\n";
        } else {
            $html = "<tr><td>$label</td><td><select id=\"$name\" name=\"$name\" tabindex=\"" . $this->tabindex++ . "\" multiple=\"multiple\">\n";
        }
        if ((!is_array($properties)) && (!is_null($properties))) {
            $selectedvalue = $properties;
        } else if (count($properties) > 0) {
            $html = rtrim($html, '>');
            foreach ($properties as $key => $value) {
                $html.=" $key=$value";
            }
            $html.=">";
        }
        $this->content.= $html;

        foreach ($optionslist as $key => $value) {
            if ($key != $selectedvalue) {
                $this->content.= '<option name="' . $key . '" value="' . $key . '">' . $value . '</option>' . "\n";
            } else {
                $this->content.= '<option name="' . $key . '" value="' . $key . '" selected="selected">' . $value . '</option>' . "\n";
            }
        }
        $this->content.= "</select>\n</td></tr>\n";
    }

    /**
     * Metodo che crea un manager per una selectbox multipla
     */
    public function addMultiSelectBoxManager($name, $label, $optionslist, $properties = array(), $selectedvalue = "") {
        //$name.="[]";
        $this->parent->addCode("<script language=\"javascript\">
                      jQuery(document).ready(function(){
                        $('#minus').click(function(){
                          alert(jQuery('#$name option:selected').val());
                          $.get( 'delnumber.php', { id: index },  function(data){ alert('Data Loaded: ' + data); });                          
                        });
                      });
                      </script>
     ");
        if (!is_array($optionslist))
            die("form::addMultiSelectBoxManager need an array as optionslist\n<br />");
        if ($label == "") {
            $html = "<select name=\"$name\">";
        } else {
            $html = "<tr><td>$label</td><td><select name=\"$name\" id=\"$name\" tabindex=\"" . $this->tabindex++ . "\" multiple=\"multiple\">";
        }
        if ((!is_array($properties)) && (!is_null($properties))) {
            $selectedvalue = $properties;
        } else if (count($properties) > 0) {
            $html = rtrim($html, '>');
            foreach ($properties as $key => $value) {
                $html.=" $key=$value";
            }
            $html.=">";
        }
        $this->content.= $html;

        foreach ($optionslist as $key => $value) {
            if ($key != $selectedvalue) {
                $this->content.= '<option name="' . $key . '" value="' . $key . '">' . $value . '</option>';
            } else {
                $this->content.= '<option name="' . $key . '" value="' . $key . '" selected="selected">' . $value . '</option>';
            }
        }


        $this->content.= '</select><br /><a id="plus" href="javascript:;" tabindex="' . $this->tabindex++ . '"><img src="/' . LIBSSDIR . 'icons/plus.png" alt="+"></a>
                     <a id="minus" href="javascript:;" tabindex="' . $this->tabindex++ . '"><img src="/' . LIBSSDIR . 'icons/minus.png" alt="-"></a></td></tr>';
        //var index=document.getElementsByName(\''.$name.'\')[0].selectedIndex); 
        //$this->content.= '</select><br /><a href="javascript:;" onclick="var index=document.getElementsByName(\''.$name.'\')[0].selectedIndex); $.ajax ({ type: \'GET\', url:\'deletenumber.php&id=$var\', dataType: \'html\', success: function(html, textStatus){ $(\'body\').append(html); } error: function (xhr, textStatus, errorThrown){ 
        //alert(\'Errore: \' + errorThrown ? errorThrown : xhr.status);});" tabindex="'.$this->tabindex++.'"><img src="/'.LIBSSDIR.'icons/plus.png" alt="+"></a><a href="#" tabindex="'.$this->tabindex++.'"><img src="/'.LIBSSDIR.'icons/minus.png" alt="-"></a></td></tr>';
    }

    public function addNumberSelector($name, $label, $url, $title, $value = "") {
        if (!preg_match("/openNumberSelector\(pageURL\)/i", $this->content)) {
            $this->content.="\n
      <script language=\"javascript\">
      var targetWin;  
      function openNumberSelector(pageURL,title,w,h){
          var left = (screen.width/2)-(w/2);
          var top = (screen.height/2)-(h/2);
          targetWin = window.open (pageURL, title, 'scrollbars=yes, toolbar=no, location=no, menubar=no, resizable=no, width='+w+', height='+h+', top='+top+', left='+left);
          
        } 
    
      function setNumbers(numbers){
          var p=document.getElementById('$name');
          var link = p.getElementsByTagName('a')[0];
          link.innerHTML=numbers.toString();
          /*var selectedNumbers=document.createElement('select');
          //var selectedNumbers=document.getElementById('$name');
          selectedNumbers.setAttribute('name','selectedNumbers');
          selectedNumbers.setAttribute('id','selectedNumbers');
          selectedNumbers.setAttribute('multiple','multiple');
          for (var i=0; i<numbers.length;i++){
            //alert(numbers[i]);
          }
          */
          
          var hiddenfield=document.getElementsByName('$name')[0];
          hiddenfield.setAttribute('value',numbers.toString());
          targetWin.close();
      }
      function clearNumbersField(name){
          var select=document.getElementById(name);
          var link = select.getElementsByTagName('a')[0];
          link.innerHTML='Seleziona';
          var hiddenfield=document.getElementsByName('$name')[0];
          hiddenfield.setAttribute('value','');
      }
      </script>\n";
        }
        $this->content.="<tr><td>$label";
        $this->content.="<input type=\"hidden\" name=\"$name\" value=\"$value\">";
        $linktext = ($value == "") ? "Seleziona" : $value;
        $this->content.="</td><td id=\"$name\"><a tabindex=\"" . $this->tabindex++ . "\" href=\"javascript:openNumberSelector('$url','$title',700,600);void(0);\">$linktext</a>\n";
        $this->content.="</td><td><a href=\"javascript:clearNumbersField('$name')\">Cancella</a></td></tr>";
    }

    /**
     * per riprendere i dati fare un foreach($_POST['$name']
     * @param $name
     * @param $labels
     * @param $optionslist
     * @return unknown_type
     */
    public function addSelectForm($name, $labels, $optionslist, $defaultSet = NULL) {
        if (is_null($defaultSet)) {
            foreach ($labels as $label) {
                $this->content.="<tr><td>$label";
                $this->addHideField("select_list[]", "$label");
                $this->content.="</td><td>";
                $this->addSelectBox($label, "", $optionslist);
            }
        } else {
            foreach ($labels as $label => $defaultvalue) {
                $this->content.="<tr><td>$label";
                $this->addHideField("select_list[]", "$label");
                $this->content.="</td><td>";
                $this->addSelectBox($label, "", $optionslist, $defaultvalue);
            }
        }
    }

    public function addCaptcha($label, $properties = array()) {
        $this->content . "\n";

        if (!isset($properties['id']))
            $properties['id'] = "captcha";
        $props = "";
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $props.="$key = \"$value\" ";
            }
        }

        $this->content.="<tr>\n<td><label>$label</label><br /><input type=\"text\" tabindex=\"" . $this->tabindex++ . "\" name=\"captcha\" " . $props . " /><br /><br />\n<img src=\"../Lib/captcha.php\" /><td></tr>";
        //$this->addRule("captcha",array("required",true),"Codice di sicurezza richiesto!");
    }

    /**
     * Da attivare in futuro.
     *
     */
    public function addPIVA($name, $label, $properties) {
        $this->content . "\n";

        $props = "";
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $props.="$key = \"$value\" ";
            }
        }
        $this->content.= "<tr><td>$label</td><td><input type=\"text\" tabindex=\"" . $this->tabindex++ . "\" name=\"$name\" $props /></td></tr>\n";
    }

    public function addText($text, $class = "", $cspan=1) {
        if($cspan !=1)
            $this->content.="<tr><td colspan=\"".$cspan."\">$text</td></tr>";
        else
        $this->content.="<tr><td>$text</td></tr>";
    }

    public function addMultiTextField($name, $label, $properties = array()) {
        if (!preg_match("/addElement\(fieldName\)/i", $this->content)) {
            $this->content.="
        <script language=\"javascript\">
          function addElement(fieldName) {
            var elementi = document.getElementsByName(fieldName);
            if (elementi.length > 0) {
              var link=document.createElement('a');
              link.setAttribute('href','#');
              link.setAttribute('onclick','this.parentNode.parentNode.removeChild(this.parentNode);');
              var linkText=document.createTextNode('Rimuovi');
              link.appendChild(linkText);
            }
            var container=elementi[elementi.length-1].parentNode.parentNode.parentNode;
            var thisfield=elementi[elementi.length-1].parentNode.parentNode;
            var newField=thisfield.cloneNode(true);
            newField.lastChild.value='';
            if (elementi.length > 0) {
              var oldLink=newField.lastChild;
              newField.removeChild(oldLink);
              newField.appendChild(link);
            }  
            
            container.insertBefore(newField,thisfield.nextSibling);
            }
            </script>
        ";
        }

        if (!isset($properties['id']))
            $properties['id'] = $name;
        $props = "";
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $props.="$key = \"$value\" ";
            }
        }
        $this->content.= "\n";
        $name.="[]";
        $this->content.= "<tr><td>$label</td><td><input type=\"text\" tabindex=\"" . $this->tabindex++ . "\" name=\"$name\" $props /></td><td><a href=\"javascript:addElement('$name');\">Aggiungi</a></td></tr>\n";
    }

    public function addMultiTextWithSelect($name, $label, $properties = array()) {
        $this->addJS("/" . LIBSSDIR . "js/jquery-1.4.3.js");
        if (!preg_match("/addElement\(fieldName\)/i", $this->content)) {
            $this->content.="
        <script language=\"javascript\">
          function addElement(fieldName) {
            var elementi = document.getElementsByName(fieldName);
            if (elementi.length > 0) {
              var link=document.createElement('a');
              link.setAttribute('href','#');
              link.setAttribute('onclick','this.parentNode.parentNode.removeChild(this.parentNode);');
              var linkText=document.createTextNode('Rimuovi');
              link.appendChild(linkText);
            }
            var container=elementi[elementi.length-1].parentNode.parentNode.parentNode;
            var thisfield=elementi[elementi.length-1].parentNode.parentNode;
            var newField=thisfield.cloneNode(true);
            newField.lastChild.value='';
            if (elementi.length > 0) {
              var oldLink=newField.lastChild;
              newField.removeChild(oldLink);
              newField.appendChild(link);
            }  
            
            container.insertBefore(newField,thisfield.nextSibling);
            }
            </script>
        ";
        }

        if (!isset($properties['id']))
            $properties['id'] = $name;
        $props = "";
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $props.="$key = \"$value\" ";
            }
        }
        $this->content.= "\n";
        $name.="[]";
        $this->content.= "<tr><td>$label</td><td><input type=\"text\" tabindex=\"" . $this->tabindex++ . "\" name=\"$name\" $props /><select name=\"select_$name\"><option name=\"null\" value=\"null\">Seleziona piano</option>';  </select><td><a href=\"javascript:addElement('$name');\">Aggiungi</a></td></tr>\n";
    }

    public function addClientSelector($field = array(), $idContainerName, $properties = array()) {


        $this->addJS(LIBSSDIR . "js/jquery-1.4.4.js");
        $this->addJS(LIBSSDIR . "js/jquery.json.js");
        $this->addJS(LIBSSDIR . "js/client_sel.js");

        if (!isset($field[0]))
            $field[0] = "cliente";
        if (!isset($field[0]))
            $field[1] = "Cliente:";

        $props = "";
        foreach ($properties as $key => $value) {
            if ($key == 'value') {
                $value1 = $value[0];
                $value2 = $value[1];
                continue;
            }
            $props.="$key = \"$value\" ";
        }

        if (!preg_match("/clientselector/i", $this->content)) {
            $this->content.='
        <script type="text/javascript">
        $(document).ready(function (){
        $.clientselector({
          div_client: "' . $field[0] . '",
          div_ris_client: "ris_client",
          div_id: "clientId", 
          link_pag_php: "/' . LIBSSDIR . 'form.clientselector.php"
        });
      });
      </script>
      ';
        }

        $this->content.='<tr><td>' . $field[1] . '</td><td><input type="text" tabindex="' . $this->tabindex++ . '" id="' . $field[0] . '" name="' . $field[0] . '" autocomplete="off" ' . $props . '>
               </input><br /><div id="ris_client"></div>
               <input type="hidden" id="' . $idContainerName . '" name="' . $idContainerName . '"></input></div>
               </td></tr>
            ';
    }

    public function addFileSelector($name, $label, $url, $title, $value = "") {
        if (!preg_match("/openFileSelect\(pageURL\)/i", $this->content)) {
            $this->content.="\n
			<script language=\"javascript\">
			var targetWin;	
			function openFileSelect(pageURL,title,w,h){
					var left = (screen.width/2)-(w/2);
					var top = (screen.height/2)-(h/2);
					targetWin = window.open (pageURL, title, 'toolbar=no, location=no, menubar=no, resizable=no, width='+w+', height='+h+', top='+top+', left='+left);
				} 
		
			function setFileName(name,filename){
					var p=document.getElementById(name);
					var link = p.getElementsByTagName('a')[0];
					link.innerHTML=filename;
					var hiddenfield=document.getElementsByName(name)[0];
					hiddenfield.setAttribute('value',filename);
					targetWin.close();
			}
			function clearFileName(name){
					var p=document.getElementById(name);
					var link = p.getElementsByTagName('a')[0];
					link.innerHTML='...';
					var hiddenfield=document.getElementsByName(name)[0];
					hiddenfield.setAttribute('value','');
			}
			</script>\n";
        }
        $this->content.="<tr><td>$label";
        $this->content.="<input type=\"hidden\" name=\"$name\" value=\"$value\">";
        $url.="?fieldname=$name";
        $linktext = ($value == "") ? "..." : $value;
        $this->content.="</td><td id=\"$name\"><a tabindex=\"" . $this->tabindex++ . "\"  href=\"javascript:openFileSelect('$url','$title',700,600);void(0)\">$linktext</a>\n";
        $this->content.="</td><td><a href=\"javascript:clearFileName('$name')\">Elimina</a></td></tr>";
    }

    public function addDoubleSelectBox($names, $labels, $optionslist1, $optionslist2, $properties = array(), $selectedvalues = "") {
        if (!is_array($optionslist1) || !is_array($optionslist2))
            die("form::addSelectBox need two arrays of optionslist\n<br />");
        //$html="<tr><td>$label</td><td><select name=$name>";
        $html = "
		<tr>
      	  <td>$labels[0]</td>
		  <td>&nbsp;</td>
          <td>$labels[1]</td>
        </tr>
        <tr>
          <td rowspan=\"4\">
		    <select name=\"$names[0][]\" tabindex=\"" . $this->tabindex++ . "\" multiple=\"multiple\" id=\"$names[0]\"></select>
		  </td>
          <td>&nbsp;</td>
          <td rowspan=\"4\">
		    <select name=\"$names[1][]\" tabindex=\"" . $this->tabindex++ . "\" multiple=\"multiple\" id=\"$names[1]\"></select>
		  </td>
        </tr>
        <tr>
          <td>
		    <input type=\"submit\" tabindex=\"" . $this->tabindex++ . "\" name=\"&lt; -\" id=\"&lt; -\" value=\"&lt; -\" />
		  </td>
        </tr>
        <tr>
          <td>
		    <input type=\"submit\" tabindex=\"" . $this->tabindex++ . "\" name=\"- &gt;\" id=\"- &gt;\" value=\"- &gt;\" />
		  </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>";
        if (count($properties) > 0) {
            $newhtml = "";
            foreach ($properties as $key => $value) {
                $newhtml.=" $key=$value";
            }
            $html = preg_replace("/id=\"$names[0]\">/", "$newhtml id=\"$names[0]\">", $html);
            $html = preg_replace("/id=\"$names[1]\">/", "$newhtml id=\"$names[1]\">", $html);
        }

        $newhtml = "";
        if (!isset($selectedvalues[0]))
            $selectedvalues[0] = "NetMDM libss v.4";
        if (!isset($selectedvalues[1]))
            $selectedvalues[1] = "NetMDM libss v.4";

        foreach ($optionslist1 as $key => $value) {
            if ($key != $selectedvalues[0]) {
                $newhtml.= '<option name="' . $key . '" value="' . $key . '">' . $value . '</option>';
            } else {
                $newhtml.= '<option name="' . $key . '" value="' . $key . '" selected="selected">' . $value . '</option>';
            }
        }

        $html = preg_replace("/id=\"$names[0]\">/", "/id=\"$names[0]\">\n $newhtml", $html);


        $newhtml = "";
        foreach ($optionslist2 as $key => $value) {
            if ($key != $selectedvalues[1]) {
                $newhtml.= '<option name="' . $key . '" value="' . $key . '">' . $value . '</option>';
            } else {
                $newhtml.= '<option name="' . $key . '" value="' . $key . '" selected="selected">' . $value . '</option>';
            }
        }
        $html = preg_replace("/id=\"$names[1]\">/", "/id=\"$names[1]\">\n $newhtml", $html);
        $this->content.=$html;
    }

    /**
     * Aggiuge un campo nascosto
     * @param string $name è il nome del campo
     * @param string $value è il valore che il campo deve assumere
     * @param array $properties è un array di attributi aggiuntivi per il campo
     */
    public function addHideField($name, $value, $properties = array(), $newrow = true) {
        if ($newrow) {
            $this->content.= '<tr>';
        } else {   //rimuovo il prec </tr> se è presente 
            $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");

            // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 
            //da implementare con le regexp
            if ($tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
                //elimino "</tr>\n" presente alla fine della stringa
                $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
            }
        }//close else

        $props = "";
        foreach ($properties as $key => $val) {
            $props.="$key = \"$val\" ";
        }
        $this->content.="<td><input type=\"hidden\" name=\"$name\" value=\"$value\" $props /></td></tr>\n";
    }

    /**
     * Funzione  per l'inserimento di regole
     * es: $a->addRule("txtNome",array("minlength",5),"Nome troppo corto!");
     * es: $a->addRule("txtCognome",array("required",true),"Il campo cognome è necessario!");
     * @param string $fieldname
     * @param array $requisite
     * @param string $errormsg
     */
    public function addRule($fieldname, $requisite, $errormsg = "") {
        //$errormsg non è utile nel controllo password e verrà quindi ignorato.
        if ($errormsg == "" && $requisite[0] != "password_length")
            die("form::addRule error_msg non specificato per il campo " . $fieldname);

        //Se la gestione delle regole non erano attive la attiviamo e creiamo la base per lo script di controllo dei campi

        if ($this->rulesActive == false) {
            $this->rulesActive = true;

            $this->addJS(LIBSSDIR . "Lib/Common/js/validate.js");  //file con funzioni di validazione
            //Aggiungiamo il controllo javascript dei campi

            $this->content = preg_replace("/action=/", "onsubmit=\"return checkData(this);\" action=", $this->content);
            $this->content.='<tr><td><div id="errorDiv" align="center"></div></td></tr>';
//Il parent è l' oggetto page
            $this->innerScript.='
      
      <script language="JavaScript">
        
      function checkData(form) {
          var error_string = "";
          errordiv=document.getElementById(\'errorDiv\');
        
          //LIBSSCRIPT

        if (error_string !="") {
          errordiv.innerHTML=error_string;
            return false;
          }  else {
        errordiv.innerHTML="";
        return true;
          }    
      }
          </script>';
        } // if ($this->rulesActive==false)
        //se $requisite non è un array completo interrompiamo l'esecuzione
        if (!is_array($requisite)) {
            die("FORM::addRule -> \$requisite must be an array...");
        }
        //a questo punto possiamo compilare l'array dei requisiti
        //Creiamo la regola javascript
        $tmpScript = "
		    
				form.$fieldname.style.backgroundColor='';\n";

        for ($k = 0; $k < count($requisite); $k = $k + 2) {  // nel caso in cui una stessa textbox abbia più di una regola, il ciclo for permetterà di aggiungerle.
            switch ($requisite[$k]) {
                case "password_length":
                    $tmpScript.="
              if (form." . $fieldname . ".value.length<" . $requisite[$k + 1] . "){
                 error_string +='La lunghezza della password deve essere di almeno " . $requisite[$k + 1] . " caratteri<br />';
                 form.$fieldname.style.backgroundColor='#F3A1A1'; 
                 } 
              if(typeof(window[form.confirm_" . $fieldname . "]!=undefined)){ //nel caso di campo conferma password inserito dal metodo
                 //form::addPassField verranno confrontate le 2 password
              if( form.confirm_" . $fieldname . ".value != form." . $fieldname . ".value ){
                error_string +='Le password digitate non corrispondono<br />';
                form.$fieldname.style.backgroundColor='#F3A1A1';
                form.confirm_$fieldname.style.backgroundColor='#F3A1A1'; 
                }      
              }\n";
                    break;

                case "required":
                    $tmpScript.="
			 		if (form.$fieldname.value == \"\") {
       					error_string += \"$errormsg<br />\";
       		    		form.$fieldname.style.backgroundColor='#F3A1A1';
   						}\n";
                    break;
                case "minlength":
                    $tmpScript.="
			 		if (form.$fieldname.value.length < $requisite[1]) {
       					error_string += \"$errormsg<br />\";
       		    		form.$fieldname.style.backgroundColor='#F3A1A1';
   						}\n";
                    break;
                case "maxlength":
                    $tmpScript.="
			 		if (form.$fieldname.value.length < 6 ) {
       					error_string += \"$errormsg<br />\";
       		    		form.$fieldname.style.backgroundColor='#F3A1A1';
   						}\n";
                    break;
                case "validmail":
                    $tmpScript.="
						   var mailfilter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				 		   if (form.$fieldname.value!='' && !mailfilter.test(form.$fieldname.value)){
       				  		error_string += \"$errormsg<br />\";
       		     			form.$fieldname.style.backgroundColor='#F3A1A1';
   						  }\n";
                    break;

                case "validprice":
                    $tmpScript.="
             var pricefilter= /^[1-9]\d{0,2}(\.\d{3})*(\,\d{2})?$/;
             if ((form.$fieldname.value.length>0) && !pricefilter.test(form.$fieldname.value)){
                  error_string += \"$errormsg<br />\";
                    form.$fieldname.style.backgroundColor='#F3A1A1';
              }\n";
                    break;

                case "validpiva":
                    $tmpScript.="
             if (!validate_piva(form.$fieldname.value)){       //validate_piva è su file esterno
                  error_string += \"$errormsg<br />\";
                    form.$fieldname.style.backgroundColor='#F3A1A1';
              }\n";
                    break;

                case "validcf":
                    $tmpScript.="   
             if (form.$fieldname.value!='' && !validate_cf(form.$fieldname.value)){       //validate_cf è su file esterno
                  error_string += \"$errormsg<br />\";
                    form.$fieldname.style.backgroundColor='#F3A1A1';
              }\n";
                    break;

                case "validpassword":
                    $tmpScript.="   
             if (form.$fieldname.value!='' && !validate_passw(form.$fieldname.value)){       //validate_passw è su file esterno
     error_string += \"La password deve essere di almeno 8 caratteri, deve contenere almeno un carattere maiuscolo, uno minuscolo, un simbolo e un numero.<br />\";            

                    form.$fieldname.style.backgroundColor='#F3A1A1';
              }
              if(typeof(window[form.confirm_" . $fieldname . "]!=undefined)){ //nel caso di campo conferma password inserito dal metodo
                 //form::addPassField verranno confrontate le 2 password
              if( form.confirm_" . $fieldname . ".value != form." . $fieldname . ".value ){
                error_string +='Le password digitate non corrispondono<br />';
                form.$fieldname.style.backgroundColor='#F3A1A1';
                form.confirm_$fieldname.style.backgroundColor='#F3A1A1'; 
                }      
              }\n";
                    break;

                case "numeric":
                    switch (strtoupper($requisite[$k + 1])) {
                        case "INTEGER":
                            $tmpScript.="var numfilter=/(^-?\d\d*$)/;";
                            break;
                        case "INTEGER+":
                            $tmpScript.="var numfilter=/(^\d\d*$)/;";
                            break;
                        case "INTEGER-":
                            $tmpScript.="var numfilter=/(^-\d\d*$)/;";
                            break;
                        case "FLOAT":
                            $tmpScript.="var numfilter=/(^-?\d\d*\.\d\d*$)|(^-?\d\d*$)/;";
                            break;
                        case "FLOAT+":
                            $tmpScript.="var numfilter=/(^\d\d*\.\d\d*$)|(^-?\d\d*$)/;";
                            break;
                        case "FLOAT-":
                            $tmpScript.="var numfilter=/(^-\d\d*\.\d\d*$)|(^-?\d\d*$)/;";
                            break;
                        default:
                            $tmpScript.="var numfilter=/(^-?\d\d*\.\d\d*$)|(^-?\d\d*$)/;";
                    }

                    $tmpScript.="
				 		 if (!numfilter.test(form.$fieldname.value)){
       						error_string += \"$errormsg<br />\";
       		    			form.$fieldname.style.backgroundColor='#F3A1A1';
   						}\n";
                    break;
            }
        }//close for($k=0; $k<count($requisite); $k=$k+2)
        //innestiamo la regola javascript nello script di base
        $this->innerScript = preg_replace("/\/\/LIBSSCRIPT/", "$tmpScript\n//LIBSSCRIPT", $this->innerScript);
    }

    public function close() {
        if ($this->rulesActive) {
            //se sono attive le regole innestiamo il codice di controllo javascript nella pagina
            $this->content = $this->innerScript . "\n" . $this->content;
        }
        $this->parent->addCode($this->content . "\n</table></form>");    //modificato
        //echo $this->content."\n</table></form>";
    }

    public function getContent() {
        if ($this->rulesActive) {
            $this->content = $this->innerScript . "\n" . $this->content;
        }
        return $this->content . "\n</table></form>";
    }

    /**
     * Permette di aggiungere un pulsante al form
     * @param $type[optional], può essere di tipo submit o cancel. Default submit
     * @param $buttonname[optional], label del pulsante, default Invia (setta name id e value)
     * @param $properties[optional], per inserire proprietà aggiuntive
     * @param $columnprefix[optional], default=1 indica quante colonne vuote inserire a sinistra del pulsante 
     * @param boolean $newrow[optional], default=true indica se inserire il pulsante sulla stessa riga o su una nuova riga. 
     */
    public function addButton_2($type = "submit", $buttonname = "Invia", $properties = array(), $columnprefix = 1, $newrow = true, $colspan = "") {

        $tabindex = 5000;
        if (isset($properties['value']))
            unset($properties['value']);
        if (isset($properties['tabindex'])) {
            $tabindex = $properties['tabindex'];
            unset($properties['tabindex']);
        }

        $props = "";
        foreach ($properties as $key => $value) {
            $props.="$key = \"$value\" ";
        }
        if ($newrow) {

            $this->content.= '<tr>';
        }

        $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");

        // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 

        if (!$newrow && $tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
            //elimino "</tr>\n" presente alla fine della stringa
            $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
        }

        if (is_numeric($columnprefix)) {
            for ($i = 0; $i < $columnprefix; $i++)
                $this->content.= '<td></td>';
        }

        if ($colspan != "")
            $colspan = " colspan=" . $colspan;
        $this->content.='<td' . $colspan . '>
         <input type="' . $type . '" tabindex="' . $tabindex . '" name="' . $buttonname . '" id="' . $buttonname . '" value="' . $buttonname . '" ' . $props . ' />
        </td></tr>';
    }

    function addIntoTableForm($code) {
        $this->content.=$code;
    }

    public function addRadioButton($label_title, $labels, $content, $vertical = true) {
        if ($vertical) {
            $this->content.= "\n\n<tr><td><fieldset><legend>" . $label_title . "</legend><br /><center><table>\n";
            for ($i = 0; $i < count($content); $i++) {
                $this->content.= "<tr><td><input type=\"radio\"";
                foreach ($content[$i] as $k => $v) {
                    $this->content.= " " . $k . "=\"" . $v . "\"";
                }
                $this->content.= ">" . $labels[$i] . "</input></td></tr>\n";
            }//close for
            $this->content.= "</table></center></fieldset></td></tr>\n\n";
        } else {                                //if not vertical
            $this->content.= "\n\n<tr><td colspan=3><fieldset><legend>" . $label_title . "</legend><br /><center><table><tr>\n";
            for ($i = 0; $i < count($content); $i++) {
                $this->content.= "<td><input type=\"radio\"";
                foreach ($content[$i] as $k => $v) {
                    $this->content.= " " . $k . "=\"" . $v . "\"";
                }
                $this->content.= " />" . $labels[$i] . "</td>\n";
            }//close for
            $this->content.= "</tr></table></center></fieldset></td></tr>\n\n";
        }//close else
        }

        /**
         * 
         * @param type $name
         * @param type $arr_content array(
         * array("value"=>"", "label"=>""),
         * array("value"=>"", "label"=>"")
         * )
         */
        public function addRadioButton_2($name, $arr_content, $checked_val = "", $newrow = false) {
        if ($newrow)
            $this->content.= "\n\n<tr><td>";
        else {
            //rimuovo il </tr> finale
            $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");

            // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 
            //da implementare con le regexp
            if ($tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
                //elimino "</tr>\n" presente alla fine della stringa
                $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
            }
            $this->content.= "\n<td>";
        }//close else

        for ($i = 0; $i < count($arr_content); $i++) {
            if ($checked_val == $arr_content[$i]["value"])
                $this->content.= "<input type=\"radio\" checked=\"checked\" name=\"" . $name . "\" value=\"" . $arr_content[$i]["value"] . "\">" . $arr_content[$i]["label"] . "&nbsp;\n";
            else
                $this->content.= "<input type=\"radio\" name=\"" . $name . "\" value=\"" . $arr_content[$i]["value"] . "\">" . $arr_content[$i]["label"] . "&nbsp;\n";
        }
        $this->content.= "</td></tr>\n";
    }

    public function addCheckBox($name, $label, $properties = array(), $newrow = true, $colspan = array("", "", "")) {
        //if ((!is_array($properties)) die ("FORM::addTextField need a filled array");
        ///////verificare questo sopra/////////

        if (!is_array($colspan) || count($colspan) != 3)
            die("FORM::addCheckBox need a filled array colspan");


        if (!isset($properties["type"]))
            $type = "type=\"checkbox\"";
        else
            $type = "";   //viene aggiunto attraverso l' array $properties

        foreach ($colspan as $k => $v) {
            if ($v != "1" && $v != "")
                $colspan[$k] = " colspan=" . $colspan[$k];
            else
                $colspan[$k] = "";
        }//close foreach

        if (!isset($properties['id']))
            $properties['id'] = $name;
        $props = "";
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $props.="$key = \"$value\" ";
            }
        }

        if ($newrow) {

            $this->content.= '<tr>';
        } else {   //rimuovo il prec </tr> se è presente 
            $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");

            // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 
            //da implementare con le regexp
            if ($tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
                //elimino "</tr>\n" presente alla fine della stringa
                $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
            }
        }//close else


        if ($colspan[0] != "") {

            $this->content.= '<td' . $colspan[0] . '></td>';
        }

        $this->content.= "\n";
        // $this->content.= "<td".$colspan[1]."><input ".$type." tabindex=\"".$this->tabindex++."\" name=\"$name\" onFocus=\"this.style.backgroundColor='".$this->focuscolor."'\" onBlur=\"this.style.backgroundColor=''\" $props /></td><td".$colspan[2].">".$label."</td></tr>\n";
        $this->content.= "<td" . $colspan[1] . "><input " . $type . " tabindex=\"" . $this->tabindex++ . "\" name=\"$name\" $props /></td><td" . $colspan[2] . ">" . $label . "</td></tr>\n";
    }

    /* Metodo specifico per l' applicazione CRI. Aggiunge i radiobutton mostrare nei risultati ricerca? Sì Noù
     * funziona in accoppiamento con un file js esterno
     */

    public function addRisultSearch1($value = "", $newrow = true, $title = "") {
        if ($newrow) {

            $this->content.= '<tr>';
        } else {   //rimuovo il prec </tr> se è presente 
            $tr_pos = strlen($this->content) - strrpos($this->content, "</tr>");

            // echo strrpos($this->content,"</tr>")."   -   ".(strlen($this->content)-6); 
            //da implementare con le regexp
            if ($tr_pos < 8) {   //solo se l' ultimo <tr/> si trova meno di 8 caratteri prima della fine della stringa.
                //elimino "</tr>\n" presente alla fine della stringa
                $this->content = substr($this->content, 0, strlen($this->content) - $tr_pos);
            }
        }//close else

        $this->content.= '
           <td class="rr1">
            <center>
              <label>' . $value . '</label><br />
                <input type="hidden" name="rr[]" value="off" />         <!-- importante che sia il primo nodo di center -->
                <input type="checkbox" class="rr_check" title="' . $title . '" />             
              </center>
           </td></tr>';
    }

//function close 
}

//class close 
?>
