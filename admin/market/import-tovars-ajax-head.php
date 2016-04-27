<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../config/config.php';
set_time_limit(1000);

$YmlFile = "../../armma.yml";

$MarketCateg = array();
$r = mysqli_query( $folder, "SELECT MarketCategID, MarketCategNazv, ShopCategoryID, MarketPath, CategViewTovDisabled, CategClickPrice
FROM tbl_yandex_market_setup
ORDER BY MarketCategID;") or die (mysqli_error($folder)."MarketSetup :(");
while($MCateg = mysqli_fetch_assoc($r)) {
	$MarketCateg[$MCateg["MarketCategID"]] = array(
		"path" => $MCateg["MarketPath"],
		"catnazv" => $MCateg["MarketCategNazv"],
		"viewdis" => $MCateg["CategViewTovDisabled"],
		"price" => $MCateg["CategClickPrice"],
	);
}

$MrKYML = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.date("Y-m-d H:i").'">
 
<shop>
	<name>Armma.Ru</name>
	<company>Магазин</company>
	<url>armma.ru</url>
	<currencies>
		<currency id="RUR" rate="1"/>
	</currencies>
	<categories>'."\n";

foreach($MarketCateg as $McID => $McData) {
	$MrKYML .= "		<category id=\"".$McID."\">".$McData["catnazv"]."</category>\n";
}
$MrKYML .= "	</categories>
	<offers>\n";

$FH = fopen($YmlFile, 'w');
fwrite($FH, $MrKYML); //Yandex

echo "<li>Сгенерирован список категорий и общая информация файла для Яндекс.Маркет</li>
<script>GetNextCateg(0);</script>";


?>