<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


function pronunciabile($lunghezza){
	$vocali = array('a', 'e', 'i', 'o', 'u', 'ae', 'ai', 'ea', 'ia', 'io', 'ou');
	$consonanti = array('b', 'c', 'd', 'f', 'g', 'h', 'l', 'm', 'n', 'p', 'r', 's', 't', 'u', 'v','tr', 'cr', 'fr', 'dr', 
			    'pr','ch', 'st', 'sl', 'cl');

	$numerovocali=count($vocali);
	$numeroconsonanti=count($consonanti);
	$result="";
	
        for ($i = 0; $i < $lunghezza; $i++) {
            $result.= $consonanti[mt_rand(0, $numeroconsonanti-1)] . $vocali[mt_rand(0, $numerovocali-1)];
        }

        return substr($result, 0, $lunghezza);
}


function randomFont($directory="./fonts"){
	$filelist=scandir($directory);
	for($i=0;$i<count($filelist);$i++){
		$filename=explode(".",$filelist[$i]);
		$extension=$filename[count($filename)-1];
		//attiviamo qui dentro invece il controllo delle directory e delle estensioni
		if ((is_dir($filelist[$i])) || (strtolower($extension)!="ttf")) unset($filelist[$i]);
	}
	$filelist=array_values($filelist);
	return $directory."/".$filelist[mt_rand(0,count($filelist)-1)];
}	


@session_start();

header("Content-type: image/png");

//Impostiamo le dimensioni del riquadro del captcha
$x = 180;
$y = 70;

$code = pronunciabile(6);
$_SESSION['code']=$code;

	if (isset($_GET['disturbo'])){
		$disturbo=intval($_GET['disturbo']);
	}else{
		$disturbo=300;
	}

//Spazio tra i caratteri
$space = $x / (strlen($code)+1);

//creiamo l'immagine
$image = imagecreatetruecolor($x,$y);

//Generiamo il colore per lo sfondo
$backgroundColor = imagecolorallocate($image,255,255,255);

$border = imagecolorallocate($image,200,200,200);
//Creiamo i colori da usare
$colors[] = imagecolorallocate($image,126,60,188);
$colors[] = imagecolorallocate($image,186,68,130);
$colors[] = imagecolorallocate($image,115,173,60);


imagefilledrectangle($image,1,1,$x-2,$y-2,$backgroundColor);
imagerectangle($image,0,0,$x-1,$y-2,$border);

	
	
$font=randomFont("./fonts");

//Scriviamo il testo necessario
for ($i=0; $i< strlen ($code); $i++){
	$color = $colors[$i % count($colors)];
	
	imagettftext($image,20+rand(0,9),-20+rand(0,50),($i+0.6)*$space,50+rand(0,10),$color,$font,$code[$i]);
}

//generazione disturbo con linee casuali
for($i=0;$i<$disturbo;$i++){
	$x1 = rand(3,$x-3);
	$y1 = rand(3,$y-3);
	$x2 = $x1-2-rand(0,8);
	$y2 = $y1-2-rand(0,8);
	imageline($image,$x1,$y1,$x2,$y2,$colors[rand(0,count($colors)-1)]);
}
 

imagepng($image);
 


?>
