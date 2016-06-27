<?php

require_once("../Personal/config.php");
require_once("../Lib/autoload.php");
require_once 'function.menuSmarty.php';        //plugin per menù Smarty
require_once("function.menu.php");
include ("orizontalmenu.php");

$session = new session();


if (!accesslimited::isInAutorizedGroups(user::getUserName($session->getUserId()), array("admins", "users"))) {
    header("Location:index?logout=1");
    exit(0);
}



$nophoto = NOPHOTO;
$title = "Prodotti Agea";
$shortcuticon = SHORTCUTICON;
$is_mobile = strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile');

$prefix = $session->getSessionVar('prefix');

//configurazioni per l' inclusione dei link nella pagina
$arr_style_config = array();
$arr_js_config = array();

$arr_style_config[] = UI_STYLE;
$arr_style_config[] = "../styles/page/page.css";
$arr_style_config[] = "../styles/orizontalmenu/orizontalmenu.css";
//$arr_style_config[] = "../styles/page/jquery.multiselect.css";
$arr_style_config[] = "../styles/products/products.css";

if (!$is_mobile)
    $arr_style_config[] = "../Plugin/jcrop/css/jquery.Jcrop.css";

$arr_js_config[] = JQUERY;
$arr_js_config[] = JQUERY_UI;
$arr_js_config[] = "js/fastsearch.js";
$arr_js_config[] = "js/products.js";
if (!$is_mobile) {
    $arr_js_config[] = "../Plugin/jpegcam/webcam.js";
    $arr_js_config[] = "../Plugin/jcrop/js/jquery.Jcrop.min.js";
    $arr_js_config[] = "js/mywebcam.js";
}
$menu = getOrrMenu();

$product = new product();

/* echo '<br />';
  print_r($_POST);
  echo '<br /><br /><br />'; */

if (isset($_POST['Salva'])) {
    $product->saveFromPost($_POST);
    
  //  $giaciniz = new giacenzainiziale();
    
  //  $giaciniz->initGiacenze();    //Aggiorno tabella giacenzeiniziali
}

$arr_in = $product->getFromDb();



$selectbox_options = array(
    "Pz" => "Pz",
    "Kg" => "Kg",
    "Lt" => "Lt"
);


$objSmarty = new Smarty();

$objSmarty->assign("title", $title);
$objSmarty->assign("shortcuticon", $shortcuticon);
$objSmarty->assign('arr_style_config', $arr_style_config);
$objSmarty->assign('arr_js_config', $arr_js_config);
$objSmarty->assign('menu', $menu);
$objSmarty->assign('arr_in', $arr_in);
$objSmarty->assign('selectbox_options', $selectbox_options);
$objSmarty->assign('nophoto', $nophoto);
$objSmarty->assign('prefix', $prefix);
$objSmarty->assign('encodedoptions', json_encode($selectbox_options));
$objSmarty->assign('is_mobile', $is_mobile);
$objSmarty->display('tpl/products.tpl');
?>


