<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../config/config.php';
include_once('../class/class_product.php');
include_once('../class/class_alias.php');
include_once('../class/class_brand.php');
$Prod = new Product($folder);
$Alias = new Alias($folder);
$Brand = new Brand($folder);
set_time_limit(1000);

if (!isset($_POST["categ"]) || !is_numeric($_POST["categ"])) {
	die("<li>Ошибка: [".$_POST["categ"]."] нечисловой параметр категории</li>");
}
$Categ = $_POST["categ"];

if (!isset($_POST["tovid"]) || $_POST["tovid"] == "") {
	die("<li>Ошибка: [".$_POST["tovid"]."] невреный параметр товара</li>");
}

$TovArr = array();
if (substr_count($_POST["tovid"], ",") > 0) {
	$TovArr = explode(",", $_POST["tovid"]);
}
else {
	$TovArr[] = $_POST["tovid"];
}

function MarketTextRepl($txtRep) {
	return str_replace(
		array('&quot;', '&nbsp;', '&', '"', '>', '<', "'"),
		array('"', ' ', '&amp;', '&quot;', '&gt;', '&lt;', '&#39;'),
		$txtRep
	);

}

$YmlFile = "../../armma.yml";
$FH = fopen($YmlFile, 'a');

$r = mysqli_query( $folder, "SELECT MarketCategID, MarketPath, CategViewTovDisabled, CategClickPrice
FROM tbl_yandex_market_setup
WHERE ShopCategoryID='".$Categ."';") or die (mysqli_error($folder)."MarketSetup :(");
$MCateg = mysqli_fetch_assoc($r);

foreach($TovArr as $TovID) {
	
	$r = mysqli_query( $folder, "SELECT tovar_artkl, tovar_name_1
	FROM tbl_tovar WHERE tovar_id = '".$TovID."';") or die (mysqli_error($folder)."Get tovar :(");

	if (mysqli_num_rows($r) == 0) {
		echo "<li>Не найден товар ID ".$TovID."</li><script>SucssFunct(".$Categ.", 1);</script>";
		die();
	}
	$tovData = mysqli_fetch_assoc($r);

	$Store = $Prod->getProductOnWare($TovID);
	$BrDT = $Brand->getBrandOnProductId($TovID);

	if ($BrDT["brand_name"] == "Оригинал" || $Prod->getProductPrice($TovID) == 0 || $BrDT["CountryName"] == "Белоруссия"  || $BrDT["CountryName"] == "Англия") {continue;}

	$Img = $Prod->getProductPicOnArtkl($tovData["tovar_artkl"]);
	if (substr_count($Img, " ") > 0) {continue;}

	$Descr = $Prod->getProductMemo($TovID);

	$Descr = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $Descr);
	$Descr = preg_replace('#<!--(.*?)>(.*?)-->#is', '', $Descr);
	$Descr = str_replace(array("&laquo;","&raquo;", "  ", ""), array('"','"', " ", ""), $Descr);
	$Descr = str_replace(array("\r\n", "\t"), array("", ""), $Descr);
	$Descr = strip_tags($Descr);

	if (strlen($Descr) > 600) {
		$Descr = mb_substr($Descr, 0, 500, "UTF-8");
	}
	$Descr = MarketTextRepl($Descr);

	$Off = '
	<offer id="'.$TovID.'" available="'.((isset($Store) && $Store > 0) ? 'true' : 'false').'" bid="'.$MCateg["CategClickPrice"].'">
		<url>'.HOST_URL.'/'.MarketTextRepl($Alias->getProductAlias($TovID)).'</url>
		<price>'.number_format($Prod->getProductPrice($TovID), 2, '.', '').'</price>
		<currencyId>RUR</currencyId>
		<categoryId>'.$MCateg["MarketCategID"].'</categoryId>
		<market_category>'.$MCateg["MarketPath"].'</market_category>
		<picture>'.$Img.'</picture>
		<store>false</store>
		<pickup>false</pickup>
		<delivery>true</delivery>
		<name>'.MarketTextRepl($tovData["tovar_name_1"]).'</name>
		<vendor>'.MarketTextRepl($BrDT["brand_name"]).'</vendor>
		<vendorCode>'.MarketTextRepl($tovData["tovar_artkl"]).'</vendorCode>
		<description>'.$Descr.'</description>
		<country_of_origin>'.$BrDT["CountryName"].'</country_of_origin>
	</offer>
';
	fwrite($FH, $Off); //Yandex
}
fclose($FH);

echo "<script>SucssFunct(".$Categ.",".count($TovArr).");</script>";

?>