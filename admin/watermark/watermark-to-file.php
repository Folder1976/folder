<?php
set_time_limit(1000);
include "../config.php";
include '../init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

//Новое соединение с базой
$folder= mysqli_connect(DB_HOST,DB_USER,DB_PASS,BASE) or die("Error " . mysqli_error($folder)); 
mysqli_set_charset($folder,"utf8");

function SetFlag($Fvalue) {
	global $folder;

	if ($Fvalue == "") {return false;}

	$r = mysqli_query( $folder, "UPDATE `tbl_tovar_pic_watermark` SET `IsWater`='1'
	WHERE `image_large` = '".$Fvalue."';") or die ("Set flag to image:(");
	return true;
}

if (!file_exists("tovwater.png")) {
	die("<h2>Системная ошибка! Не найден файл tovwater.png ! Пожалуйста, загрузите его через панель администратора</h2>");
}

$watermark = imagecreatefrompng('tovwater.png');
$ww = imagesx($watermark);
$wh = imagesy($watermark);

$im = 0;
$r = mysqli_query( $folder, "SELECT `image_large` FROM `tbl_tovar_pic_watermark`
WHERE `IsWater`='0' AND `image_large` != '' LIMIT 0,200;") or die ("Get unmarked images :(");
while($tIsh = mysqli_fetch_assoc($r)) {
	$FileLarge = '/home/armma/armma.ru/docs/resources/products/'. $tIsh["image_large"];

	$OrigtFile = str_replace('.large', '.orig',  $tIsh["image_large"]);
	$OrigFile = '/home/armma/armma.ru/docs/resources/products/'.$OrigtFile;
	
	if (!file_exists($OrigFile)) {
		if (!file_exists($FileLarge)) {
			// Not found file on disc
			SetFlag($tIsh["image_large"]);
			continue;
		}
		//SetFlag($tIsh["image_large"]);
		//$im +=1;
		//continue;

		//copy($FileLarge, $OrigFile);
		if (!file_exists($OrigFile)) {
			// Error when copy
			SetFlag($tIsh["image_large"]);
			continue;
		}
	}

	//SetFlag($tIsh["image_large"]);
	//$im +=1;
	//continue;


	$Tdate = file_get_contents($OrigFile);
	$src = imagecreatefromstring($Tdate);

	imagecopy($src, $watermark, 0, 0, 0, 0, $ww, $wh); //$w_dest-$ww, $h_dest-$wh
	imagejpeg($src, $FileLarge, 90);
	imagedestroy($src);
	chmod($FileLarge, 0644);

	SetFlag($tIsh["image_large"]);
	$im += 1;
}

echo "<script>SucssFunct(".$im.");</script>";
?>