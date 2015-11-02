<?php

$dbtype = 'mysql';
$hostname = 'localhost'; //se settato ha una priorità superiore rispetto all'ip
$ip = '127.0.0.1';
$port = '3306';
$user = 'root'; 
$dbname = 'servizioasa2';              
$password = '';

$usedebug = true;  

//////////////////////////////////////// FINE CONFIGURAZIONI ///////////////////////////////////////////////////////////

date_default_timezone_set('Europe/Rome');
$path="../";
$dsn = 'mysql:dbname='.$dbname.';host='.$hostname.';charset=UTF8';
$opt['mysql']['configtable']="config";

//Tabella delle sessioni
$opt['mysql']['sessiontable']='sessions';
//Tabelle degli utenti
$opt['mysql']['usertable']='users';
$opt['mysql']['userinfotable']='userinfo';

$opt['mysql']['grouptable']='groups';
$opt['mysql']['groupuserstable']='groupusers';

//Plugin jquery
define("JQPLUGINS",$path."Lib/Common/js");

//Cartella libreria
define("LIBNAME","Lib");

define("LIBSSDIR",$path);
define("LIBPATH",$path);

//Redirect in caso di logout
define("LOGIN",$path."pages/index.php");

//LINK A FILE JQUERY

define("JQTABLES",$path."Plugin/DataTables-1.9.1");
define("JQALERT",$path."Plugin/jquery.alerts-1.1");

define("JQUERY",$path."js/jquery-1.7.2.min.js");            
define("JQUERY_UI",$path."js/jquery-ui-1.8.20.custom.min.js");  

//define("UI_STYLE",LIBSSDIR.LIBNAME."/css/ui-lightness/jquery-ui.css");
define("UI_STYLE",LIBSSDIR.LIBNAME."/css/flick/jquery-ui.css");
define("SHORTCUTICON",$path."styles/page/images/cri.png");
define("PAGETITLE", "ASAGesT");
define("NOPHOTO", $path."styles/page/images/nophoto.png");
//////////////////////////////////////////// FINE CONFIGURAZIONI//////////////////////////////////////////////////////////////////////////
define("DSN",$dsn);
define("DBUSER",$user);
define("DBPASSWORD",$password);
define("USEDEBUG", $usedebug);

$opt['dbtype'] = $dbtype;
$opt['hostname'] = $hostname; //se settato ha una priorità superiore rispetto all'ip
$opt['ip'] = $ip;
$opt['port'] = $port;
$opt['dbname']= $dbname;  
$opt['username'] = $user;
$opt['password'] = $password;

define("CRLF", "\n<br />\n");

$opt['disponibleblocks'] = array("2014"=>"2014", "2015"=>"2015");   //devono esserci le tabelle nel db
$opt['progagea'] = array("prog1415"=>"Prog. 2014/2015");
?>
