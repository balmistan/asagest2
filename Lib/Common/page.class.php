<?php

/**
 * Page class to handle pages
 *
 * This class is part of NetMDM libss project {@link http://www.netmdm.com/libss}.
 * @author Carmelo San Giovanni <admin@barmes.org>
 * @version 4.0
 * @copyright Copyright (c) 2009 Carmelo San Giovanni
 * @package libss
 */

class page {
	
	private $html="";

    /**
     * Costruttore di classe Page, genera le intestazioni per la pagina
     * @access public
     * @param string $lang[optional] contiene la lingua di default (facoltativo)
     */
    public function __construct($lang="it") {
 //       ob_start();

//<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\"> 
//<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />
$this->html="
<!DOCTYPE HTML> 
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"" . $lang . "\" lang=\"" . $lang . "\">
<head>
<title></title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<meta name=\"generator\" content=\"NetMDM\" />
<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"/favicon.ico\" />
<link rel=\"icon\" href=\"/favicon.ico\" type=\"image/x-icon\" />
<!-- ADD CSS -->
<!-- ADD SCRIPT -->
</head>
<body>
";
    }

    /**
     * Funzione per cambiare il titolo alla pagina
     * @param object $title, es: "Pagina di prova"
     * @access public
     */
    public function setTitle($title) {
      //  $html = ob_get_contents();
      //  ob_clean();
        $this->html = preg_replace("/<title>.*?<\/title>/", "<title>$title</title>", $this->html);
      //  echo $html;
    }

    /**
     * Funzione per cambiare il programma generatore della pagina
     * @param object $generator, nome del programma che ha generato la pagina es: "NetMDM CMS"
     */
    public function setGenerator($generator) {
      // $html = ob_get_contents();
      //  ob_clean();
        $this->html = preg_replace("/<meta name=\"generator\" content=\".*?\" \/>/", "<meta name=\"generator\" content=\"$generator\" />", $this->html);
      //  printf($html);
    }

    /**
     * Funzione per cambiare icona alla pagina
     * @param object $icon, nome del file icona es: favicon.ico
     */
    public function setIcon($icon) {
        $extension = strtolower(substr(strrchr($icon, "."), 1));
        switch ($extension) {
            case "png": $imagetype = "image/png";
                break;
            case "jpg": $imagetype = "image/jpeg";
                break;
            case "jpeg": $imagetype = "image/jpeg";
                break;
            default: $imagetype = "image/x-icon";
        }
       // $html = ob_get_contents();
       // ob_clean();
        $this->html = preg_replace("/<link rel=\"shortcut icon\" .*? \/>/", "<link rel=\"shortcut icon\" type=\"$imagetype\" href=\"$icon\" />", $this->html);
        $this->html = preg_replace("/<link rel=\"icon\" .*? \/>/", "<link rel=\"icon\" type=\"$imagetype\" href=\"$icon\" />", $this->html);
       // printf($html);
    }

    /**
     * Setta le proprietà del body
     * @param array $props, contiene le proprietà
     * @example $page->setBody(array("onload"=>"javascript:checkUser();");
     */
    public function setBodyProps($props) {
        if (is_array($props)) {
          //  $html = ob_get_contents();
          //  ob_clean();
            $body = "<body";
            foreach ($props as $key => $value) {
                $body.=" $key=\"$value\"";
            }
            $body.=">";
            $this->html = preg_replace("/<body.*?>/", $body, $this->html);
           // printf($html);
        }
    }

    /**
     * Funzione per aggiungere fogli di stile
     * @param object $filename è il nome del foglio di stile es: style.css
     * @param object $media [optional], il tipo di media es: print, all, screen, handheld
     */
    public function addStyle($filename, $media="all") {
       // $html = ob_get_contents();
      //  ob_clean();
        $basename=basename($filename);
	if (!preg_match("/$basename/i", $this->html)) {
           $this->html = preg_replace("/<!-- ADD CSS -->/", "<link rel=\"stylesheet\" href=\"$filename\" type=\"text/css\" media=\"$media\" />\n<!-- ADD CSS -->", $this->html);
        }//close if (!preg_match("/$basename/i", $this->html))
//  printf($html);
    }

    /**
     * Aggiunge degli stili tra i tag style
     * @param string $code, codice dello stile
     */
    public function addStyleCode($code, $media="all") {
      //  $html = ob_get_contents();
      //  ob_clean();
        $this->html = preg_replace("/<\/head>/", "<style type=\"text/css\" media=\"$media\">$code</style>\n</head>", $this->html);
        //printf($html);
    }

    /** Funzione per aggiungere fogli di stile usando la tecnica import
     * @param object $filename è il nome del foglio di stile es: style.css
     */
    public function addIStyle($filename, $media="all") {
      //  $html = ob_get_contents();
      //  ob_clean();
        $this->html = preg_replace("/<\/head>/", "<style type=\"text/css\" media=\"$media\">\n@import \"$filename\"</style> \n</head>", $this->html);
        //printf($html);
    }

    /**
     * Funzione per aggiungere rel link all'head
     * @param string $name, contiene il nome del rel es. stylesheet
     * @param array $params, è un array associativo che contiene una lista di attributi per il campo rel
     * Esempi: addRel("print",array("title"=>"Formato di stampa","type"=>"text/css","href"=>"print.css","media"=>"print"));
     * addRel("copyright",array("href"=>"http://creativecommons.org/licenses/by-sa/3.0/"));
     * @example addRel("print",array("title"=>"Formato di stampa","type"=>"text/css","href"=>"print.css","media"=>"print"));
     * @example addRel("copyright",array("href"=>"http://creativecommons.org/licenses/by-sa/3.0/"));
     */
    public function addRel($name, $params) {
        if (!is_array($params))
            die("PAGE::addRel: \$params need to be an array...");
    //    $html = ob_get_contents();
    //    ob_clean();

        $rel = '<link rel="' . $name . '" ';
        foreach ($params as $key => $value) {
            $rel.="$key=\"$value\" ";
        }
        $rel.=" />\n</head>";
        $this->html = preg_replace("/<\/head>/", "$rel", $this->html);
        //printf($html);
    }

    /**
     * Aggiunge un meta tag alla pagina corrente
     * @param string $content, è il content
     * @param array $params, contiene tutte le varie opzioni
     */
    public function addMeta($content, $params) {
        if (!is_array($params))
            die("PAGE::addMeta: \$params need to be an array...");
      //  $html = ob_get_contents();
      //  ob_clean();
        $meta = '<meta content="' . $content . '" ';
        foreach ($params as $key => $value) {
            if(!is_numeric($value))$meta.="$key=\"$value\" ";
            else $meta.="$key=$value ";
        }
        $meta.=" />\n</head>";
        $this->html = preg_replace("/<\/head>/", "$meta", $this->html);
       // printf($html);
    }

    /**
     * Funzione per l'aggiunta di un file javascript
     * @param object $filename
     * @return 
     */
    
/*   //vecchio metodo
 
    public function addJS($filename, $props=array()) {
        $html = ob_get_contents();
        ob_clean();
        $basename = basename($filename);
        if (!preg_match("/$basename/i", $html)) {
            if (!is_array($props)) {
                $script = "</head>\n<script type=\"text/javascript\" src=\"$filename\"></script>";
            } else {
                $script = "</head>\n<script type=\"text/javascript\" src=\"$filename\"";
                foreach ($props as $key => $value)
                    $script.=" $key = \"$value\"";
                $script.="></script>\n";
            }
            $html = preg_replace("/<\/head>/", "$script", $html);
        }
        printf($html);
    }
*/

//nuovo metodo

public function addJS($filename,$props=array()){
	//	$html=ob_get_contents();
	//	ob_clean();
   $basename=basename($filename);
		if (!preg_match("/$basename/i", $this->html)) {
		  if (!is_array($props)){
  			$script="\n<script type=\"text/javascript\" src=\"$filename\"></script>".
  			"\n";
	   	}else{
			 $script="<script type=\"text/javascript\" src=\"$filename\"";
			 foreach ($props as $key=>$value)
			 	 $script.=" $key = \"$value\"";
			 $script.="></script>\n";	
		  }
    	 $this->html=preg_replace("/<!-- ADD SCRIPT -->/",$script."<!-- ADD SCRIPT -->",$this->html);
     //  $html=preg_replace("/<!--END SCRIPT LINK-->/","$script",$html);
       }   
		//printf($html);	
	}

	/**
	 * Funzione per l'aggiunta di codice javascript subito prima dello </head>
	 * @param string $script
	 * @return 
	 */
	public function addJSCode($script){
		//$html=ob_get_contents();
	//	ob_clean();
        $this->html=preg_replace("/<\/head>/","$script",$this->html);
		//printf($html);	
	}
	

    /**
     * Setta una funzione javascript per l'esecuzione al caricamento della pagina
     * @param object $function, è il nome della funzione da eseguire non appena la pagina è caricata interamente.
     */
    public function onLoad($function) {
      //  $html = ob_get_contents();
      //  ob_clean();
        $this->html = preg_replace("/<body.*?>/", "<body onload=\"$function\" />", $this->html);
      //  printf($html);
    }

    /**
     * Apre il tag per un div
     * @param array $props, contiene le proprietà del div
     * @example $page->openDiv(array("align"=>"center"));
     */
    public function openDiv($props=array()) {
        $code = "<div";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }
		$this->html.=$code.">";
       // echo $html;
    }

    /**
     * Chiude un div
     */
    public function closeDiv() {
    	$this->html.="</div>\n";
       // printf("</div>");
    }

    /**
     * Aggiunge un tag singolo compatibile xhtml 1.1 transitional
     * @param string $tagname, contiene il nome del tag
     * @param array $props, proprietà aggiuntive
     */
    public function addSingleTag($tagname, $props=NULL) {
        if (!is_array($props)) {
        	$this->html.="<".$tagname." />";
           // printf("<$tagname />");
        } else {
            $code = "<$tagname";
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
            
			$this->html.=" />\n".$code;
           // printf($html);
        }
    }

    /**
     * Apre un tag html                                          come addSingleTag ?????????
     * @param string $tagname è il nome del tag
     * @param array $props, contiene gli attributi aggiuntivi
     * @example $page->openTag("h1");
     */
    public function openTag($tagname, $props=NULL) {
        if (!is_array($props)) {
        	$this->html.="<".$tagname.">";
           // printf("<$tagname>\n");
        } else {
            $code = "<$tagname";
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";  
			$this->html.=">\n".$code;
           // printf($html);
        }//close else
    }

    /**
     * Chiude un tag precedentemente aperto
     * @param string $tagname
     * @example $page->closeTag("h1");
     */
    public function closeTag($tagname) {
    	$this->html.="</".$tagname.">";
       // printf("</$tagname>");
    }

    /**
     * Alias per il metodo addSingleTag
     * @param $tagname, contiene il nome del tag
     * @param $props, contiene le eventuali opzioni raggruppate in un array
     */
    public function addTag($tagname, $props=NULL) {
        $this->addSingleTag($tagname, $props);
    }

    /**
     * Apre uno span
     * @param array $props, contiene le proprietà per lo span
     */
    public function openSpan($props=array()) {
        $code = "<span";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }
		$this->html.=">\n".$code;
      //  printf($html);
    }

    public function closeSpan() {
    	$this->html.="</span>\n";
       // printf("</span>\n");
    }

    public function addText($text, $props=array()) {
        $code = "<p";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }
		$this->html.=$code.">$text</p>\n";
       // printf($html);
    }

    /**
     * Alias della funzione echo, manda in output la stringa che gli viene passata
     * @param string $text è il testo da visualizzare
     */
    public function addSimpleText($text) {
    	$this->html.=$text;
        //echo $text;
    }

    /**
     * Apre i tag per una nuova tabella
     * @param string $tablename, contiene il nome della nuova tabella 
     * @param array $props, contiene le proprietà per la tabella
     */
    public function openTable($tablename, $props=array()) {
        $code = "<table name=\"$tablename\"";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }
		$this->html.=">\n".$code;
       // printf($html);
    }

    public function closeTable() {
    	$this->html.="\n</table>";
        //printf("\n</table>");
    }

    public function openTableRow($props=array()) {
        $code = "<tr";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }
		$this->html.=">\n".$code;
       // printf($html);
    }

    public function openTableField($props=array()) {
        $code = "<td";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }
        $code.=">\n";
		$this->html.=$code;
       // printf($html);
    }

    public function closeTableRow() {
    	$this->html.="</tr>\n";
        //printf("</tr>\n");
    }

    public function closeTableField() {
    	$this->html.="</td>\n";
       // printf("</td>\n");
    }

    /**
     * Permette di aggiungere un'immagine alla pagina
     * @param string $imagename contiene il percorso completo dell'immagine
     * @param array $props è l'array delle proprietà
     */
    public function addImage($imagename, $props=array()) {
        $code = "<img src=\"$imagename\"";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }	
        $this->html.=$code." />\n";
        //printf($html);
    }

    /**
     * Aggiunge un link alla pagina
     * @param string $link contiene la destinazione
     * @param boolean $leaveopen[optional] consente di lasciare aperto il tag link per aggiungere oggetti al suo interno, default true
     * @param array $props, contiene attributi aggiuntivi per il tag
     * @example $page->addLink("index.html",false,array("target"=>"_blank"));
     */
    public function addLink($link, $leaveopen=true, $props=array()) {
        $code = "<a href=\"$link\"";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }

        if ($leaveopen) {
            $code.=">\n";
        } else {
            $code.=">\n</a>\n";
        }
		$this->html.=$code;
        //echo $html;
    }

    public function addLinkWithText($text, $link, $props=array()) {
        $code = "<a href=\"$link\"";
        if (is_array($props)) {
            foreach ($props as $key => $value)
                $code.=" $key = \"$value\"";
        }
        $code.=">\n$text</a>\n";
		$this->html.=$code;
       // echo $html;
    }

    /**
     * Chiude un link
     */
    public function closeLink() {
    	$this->html.="</a>\n";
        //printf("</a>\n");
    }

    /**
     * Stampa un messaggio centrato, chiude la pagina ed interrompe l'esecuzione dello script
     * @param string $text, contiene il testo da stampare
     */
    public function critical($text) {
        echo "<p class=\"critical\" align=\"center\">$text</p>\n";
        $this->close();
        exit();
    }

    /**
     * Stampa un messaggio di info centrato
     * @param string $text, contiene il testo da stampare
     */
    public function info($text) {
        $this->html.="<p class=\"info\" align=\"center\">$text</p>\n";
    }

    /**
     * Permette di aggiungere del codice html alla pagina
     * @param string $code
     */
    public function addCode($code) {
    	$this->html.=$code;
        //echo $code;
    }

    public function addSkin($templatename) {
        $path = config::getConfig("TEMPLATES") . "$templatename/";
        $filename = $path . "skin.html";
       //$html = ob_get_contents();
       // ob_clean();
        $this->html = preg_replace("/<\/head>\n<body>/", "", $this->html);
        if (file_exists($filename)) {
            $this->html.=implode("", file($filename));
            $this->html = preg_replace("/__TEMPLATEPATH__/", $path, $this->html);
            //echo $html;
        }
    }

    public function addHeader($templatename, $links=array()) {
        $path = config::getConfig("TEMPLATES") . "$templatename/";
        $headerfile = $path . "header.html";
        if (file_exists($headerfile)) {
            $header = implode("", file($headerfile));
            $header = preg_replace("/__TEMPLATEPATH__/", $path, $header);
            $linkmenu = "";
            for ($i = 0; $i < count($links); $i++) {
                //$linkmenu.='<li class="level1 item'. $i+1 .' parent"><a href="'.$links[$i]['url'].'">'.$links[$i]['label'].'</a></li>';
                $linkmenu.="<li class=\"level1 item" . ($i + 1) . " parent\"><a href=\"" . $links[$i]['url'] . "\">" . $links[$i]['label'] . "</a></li>";
                /* 	if ($i==count($links)-1){
                  $i++;
                  $linkmenu.= '<li class="level1 item'.($i+1).'"><a href="'.$links[$i]['url'].'">'.$links[$i]['label'].'</a></li>';
                  break;
                  } */
            }
            $header = preg_replace("/<!-- LINKS -->/", $linkmenu, $header);
          //  $html = ob_get_contents();
          //  ob_clean();
          $this->html = preg_replace("/<!-- HEADER -->/", "$header\n<!-- HEADER -->", $this->html);        
           // echo $html;
        }
    }

    public function addMenu($templatename, $menucontent) {
        $path = config::getConfig("TEMPLATES") . "$templatename/";
     //   $html = ob_get_contents();
     //   ob_clean();
        $this->html = preg_replace("/<!-- MENU -->/", $menucontent, $this->html);
      //  echo $html;
    }

    public function addRightBlock($templatename, $title, $content) {
        $path = config::getConfig("TEMPLATES") . "$templatename/";
        $blockfile = $path . "block_right.html";
        if (file_exists($blockfile)) {
            $block = implode("", file($blockfile));
            $block = preg_replace("/TITLE/", $title, $block);
            $block = preg_replace("/CONTENT/", $content, $block);
         //   $html = ob_get_contents();
         //   ob_clean();
            $this->html = preg_replace("/<!-- RIGHT BOX -->/", "$block\n<!-- RIGHT BOX -->", $this->html);
          //  echo $html;
        }
    }

    public function addLeftBlock($templatename, $title, $content) {
        $path = config::getConfig("TEMPLATES") . "$templatename/";
        $blockfile = $path . "block_left.html";
        if (file_exists($blockfile)) {
            $block = implode("", file($blockfile));
            $block = preg_replace("/TITLE/", $title, $block);
            $block = preg_replace("/CONTENT/", $content, $block);
         //   $html = ob_get_contents();
         //   ob_clean();
            $this->html = preg_replace("/<!-- LEFT BOX -->/", "$block\n<!-- LEFT BOX -->", $this->html);
          //  echo $html;
        }
    }

    public function addBlackBox($templatename, $title, $content) {
        $path = config::getConfig("TEMPLATES") . "$templatename/";
        $blockfile = $path . "block_black.html";
        if (file_exists($blockfile)) {
            $block = implode("", file($blockfile));
            $block = preg_replace("/TITLE/", $title, $block);
            $block = preg_replace("/CONTENT/", $content, $block);
        //    $html = ob_get_contents();
        //    ob_clean();
            $this->html = preg_replace("/<!-- BLACK BOX -->/", "$block\n<!-- BLACK BOX -->", $this->html);
          //  echo $html;
        }
    }

    public function addContent($templatename, $title, $content) {
        $path = config::getConfig("TEMPLATES") . "$templatename/";
        $contentfile = $path . "content.html";
        if (file_exists($contentfile)) {
            $contenthtml = implode("", file($contentfile));
            $contenthtml = preg_replace("/TITLE/", $title, $contenthtml);
            $contenthtml = preg_replace("/CONTENT/", $content, $contenthtml);
         //   $html = ob_get_contents();
         //   ob_clean();
            $this->html = preg_replace("/<!-- MODULECONTENT -->/", "$contenthtml\n<!-- MODULECONTENT -->", $this->html);
         //   echo $html;
        } else {
            echo $contentfile . " does not exists!";
        }
    }


    /**
     * Chiude i tag html e manda in output la pagina
     */
    public function close() {
    	echo $this->html."</body>\n</html>\n";
      //  echo "</body>\n</html>";
        //Se si eliminano i commenti ci saranno problemi con quelli lasciati per risolvere
        //i problemi di compatibilità con internet explorer.
        /* $html=ob_get_contents();
          ob_end_clean();
          $html = preg_replace("/<!--.*-->/Uis", "", $html);
          echo $html;
         */
    //    ob_end_flush();
    }

    /**
     * Metodo che effettua un redirect javascript verso una nuova pagina
     * @param string $newpage, pagina di destrinazione
     */
    public function redirectTo($newpage, $timeout=10) {
    	header("Location: ".$newpage);

	/*
       $strcode='<script language="javascript" type="text/javascript">
              function pageRedirect(){
                window.location.replace("' . $newpage . '");
              }  
              window.setTimeout(pageRedirect(),' . $timeout . ');
              ';
			  
			   if($timeout)$strcode.='
                   document.write("<center>Reindirizzamento in corso, se non vieni reindirizzato entro ' . $timeout . ' secondi clicca <a href=\"' . $newpage . '\">qui</a></center>");';      
			  
	   $this->addJSCode($strcode.'</script>');	 
	 */
    }

    public function addAnalytics($analyticsCode) {
        echo "<script type=\"text/javascript\">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', '$analyticsCode']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
          </script>";
    }

    public function getReferer() {
        return $_SERVER['HTTP_REFERER'];
    }

    public function getRefererModule() {
        $url = $_SERVER['HTTP_REFERER'];
        return preg_replace('/\A=/', '', strstr($url, '='));
    }

    public function addKeywords($keywords) {
    //    $html = ob_get_contents();
    //    ob_clean();
        $content = "<meta name=\"keywords\" content=\"$keywords\" />";
        $this->html = preg_replace("/<\/head>/", "$content\n</head>", $this->html);
      //  printf($html);
    }

    public function addDescription($description) {
     //   $html = ob_get_contents();
     //   ob_clean();
        $content = "<meta name=\"description\" content=\"$description\" />";
        $this->html = preg_replace("/<\/head>/", "$content\n</head>", $this->html);
      //  printf($html);
    }

    /**
     * Restituisce il codice per il pulsante +1 di Google
     * @param string $size: "small, medium, normal,tall";
     * @return type 
     */
    public function getPlusButtonCode($size="small") {
        if ($size == "normal")
            $content = "<g:plusone></g:plusone>";
        else
            $content = "<g:plusone size=\"$size\"></g:plusone>";

        return $content;
    }

    public function getAddThisButtonCode() {
        //  return "<span>LollA</span>";

        $content = '<div style="float:right">
                        <!-- AddThis Button BEGIN -->
                        <div class="addthis_toolbox addthis_default_style ">
                        <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4d4f28052a1fde49"
class="addthis_button_compact">Condividi</a>
                        <span class="addthis_separator">|</span>
                        <a class="addthis_button_preferred_1"></a>
                        <a class="addthis_button_preferred_2"></a>
                        <a class="addthis_button_preferred_3"></a>
                        <a class="addthis_button_preferred_4"></a>
                        </div>
                        <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4d4f28052a1fde49"></script>
                        <!-- AddThis Button END --></div>
                ';
        return $content;
    }

    public function addPlusCode() {
    //    $html = ob_get_contents();
    //    ob_clean();

        $content = "<script type=\"text/javascript\" src=\"https://apis.google.com/js/plusone.js\">
  {lang: 'it'}
</script>";

        $this->html = preg_replace("/<\/head>/", "$content\n</head>", $this->html);
        //printf($html);
    }
    
    public function getHTML(){
    	return $this->html;
    }
    
    public function setHTML($htmlcode){
    	$this->html=$htmlcode;
    }

}

?>
