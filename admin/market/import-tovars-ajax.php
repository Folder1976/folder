<?php
die();
if(!isset($_POST["mcat"]) || !is_numeric($_POST["mcat"])) {
	echo "<script>SucssFunct(0);alert(0);</script>";
	die();
}
$MCategID = $_POST["mcat"];

function MarketTextRepl($txtRep) {
	return str_replace(
		array('"', '>', '<', "'", '&'),
		array('&quot;', '&gt;', '<', "&lt;", '&amp;'),
		$txtRep
	);

}

include("../../core.php");
set_time_limit(2000);


$r = mysqli_query( $hlnk, "SELECT MarketCategNazv, ShopCategoryID, MarketPath
FROM ".$ppt."yandex_market_setup WHERE MarketCategID='".$MCategID."';") or die ("MarketSetup :(");
$MarketCateg = mysqli_fetch_assoc($r);

$BrendsArr = array();
$r = mysqli_query( $hlnk, "SELECT NOMBR.brand_id, NMPRD.ProducerNazv, NOMCOUT.CountryName
FROM ".$ppt."nomenkl_brend NOMBR
LEFT JOIN ".$ppt."nomenkl_producer NMPRD ON NOMBR.ProducerID=NMPRD.ProducerID
LEFT JOIN ".$ppt."nomenkl_countries NOMCOUT ON NMPRD.CountryID=NOMCOUT.CountryID
WHERE 1;") or die ("Brands :(");
while($tdt = mysqli_fetch_assoc($r)) {$BrendsArr[$tdt["brand_id"]] = array($tdt["ProducerNazv"], $tdt["CountryName"]);}

$TovBrends = array();
$r = mysqli_query( $hlnk, "SELECT ArticulSystem, BrandId
FROM ".$ppt."nomenkl;") or die ("Brands :(");
while($tdt = mysqli_fetch_assoc($r)) {$TovBrends[$tdt["ArticulSystem"]] = $tdt["BrandId"];}

$aglnk = mysqli_connect(MAGAZSQLHOST,  MAGAZSQLUSER,  MAGAZSQLPASS, MAGAZSQLBASE) or die(mysqli_error($aglnk)."<hr>Сервер  базы данных магазина недоступен или неверный логин-пароль");
mysqli_query($aglnk, 'SET NAMES utf8');

$CategSpis = array();

$AllChildren = array();
$AllParents = array();
$r = mysqli_query( $aglnk, "SELECT categoryID, name, parent, sort_order
FROM SS_categories WHERE parent != '' ORDER BY parent, BINARY(name);") or die ("GetMagID:(");
while($tC = mysqli_fetch_assoc($r)) {
	if ($tC["parent"] == 1 && $tC["sort_order"] == 0) {continue;}
	$AllChildren[$tC["parent"]][$tC["categoryID"]] = $tC["name"];
	$AllParents[$tC["categoryID"]] = $tC["parent"];
}


$tree = array();
$nl = 0;
$curSelCat = 0;
function MakeTree($lv, $Parent, $CurrChildrens) {
	global $AllChildren, $tree, $nl, $CurrentCateg, $uchoise, $curSelCat, $CategSpis;
	foreach($CurrChildrens as $Categid => $CategName) {
		$CategSpis[] = $Categid;
		$tree[$lv][$Parent][] = array($Categid, $CategName);

		if ($Categid == $CurrentCateg) {
			$uchoise[$lv] = $CurrentCateg;
			$nl = $lv-1;
			$uchoise[$nl] = $Parent;
			$curSelCat = $Parent;
		}

		if (isset($AllChildren[$Categid])) {//Check if parent
			MakeTree($lv+1, $Categid, $AllChildren[$Categid]);
		}
		unset($AllChildren[$Categid]);
	}
}

MakeTree(2, $MarketCateg["ShopCategoryID"], $AllChildren[$MarketCateg["ShopCategoryID"]]);

$PhotoSpis = array();
$r = mysqli_query( $aglnk, "SELECT photoID, enlarged FROM SS_product_pictures;") or die ("GetMagID:(");
while($PrPic = mysqli_fetch_assoc($r)) {
	$PhotoSpis[$PrPic["photoID"]] = $PrPic["enlarged"];
}

$RedirectsTargets = array();
$r=mysqli_query( $aglnk, "SELECT target, url
FROM SS_redirects;") or die("Select all redirects :(");
while ($trdr = mysqli_fetch_assoc($r)) {
	$RedirectsTargets[$trdr["target"]] = $trdr["url"];
}


$AllChars = array();
$r0=mysqli_query( $aglnk, "SELECT optionID, name FROM SS_product_options ORDER BY sort_order;") or die("GetNames");
while($ACh = mysqli_fetch_assoc($r0)) {$AllChars[$ACh["optionID"]] = MarketTextRepl($ACh["name"]);}

$r = mysqli_query( $aglnk, "SELECT productID, name, Price, product_code, default_picture
FROM SS_products WHERE categoryID IN (".implode(",",$CategSpis).")
AND enabled='1' ;") or die ("GetMagID:(");
$i = 0;
while ($ShopTovs = mysqli_fetch_assoc($r)) {

	$tProdName = MarketTextRepl($ShopTovs["name"]);
	$First = strstr($tProdName, '. Артикул');
	$ProdName = str_replace($First, '', $tProdName);
	$Vendor = $VendCountry = $VendorCode = "";

	if (isset($TovBrends[$ShopTovs["product_code"]])) {
		$TovBrID = $TovBrends[$ShopTovs["product_code"]];
		if (isset($BrendsArr[$TovBrID])) {
			$Vendor = $BrendsArr[$TovBrID][0];
			$VendCountry = $BrendsArr[$TovBrID][1];
		}
	}

	$ProdSysCode = preg_replace('/[^a-zA-Z0-9]/', '', $ShopTovs["product_code"]);

	

	$Opis = "";
	$CharsVals = array();
	$r1=mysqli_query( $aglnk, "SELECT optionID, option_value
	FROM  SS_product_options_values
	WHERE productID='".$ShopTovs["productID"]."';") or die("GetTovarChars");
	while($OptVal = mysqli_fetch_assoc($r1)) {
		$OptValue = trim($OptVal["option_value"]);
		if ($OptValue == "") {continue;}
		/* LOCKED 30-01-2015
		if ($OptVal["optionID"] == 21) {
			list($tVendor, $tVendCountry) = explode(",", $OptValue);
			$VendCountry = trim($tVendCountry);
			$Vendor = trim(str_replace('"', "", $tVendor));
		}
		*/
		if($OptVal["optionID"] == 14) {
			$VendorCode = MarketTextRepl($OptValue);
		}
		else {
			$CharsVals[$OptVal["optionID"]] = $OptVal["option_value"];
		}
	}
	foreach ($AllChars as $OptID => $OptValue) {
		if (!isset($CharsVals[$OptID])) {continue;}
		if ($Opis != "") {$Opis .= ", ";}
		$Opis .= $OptValue.": ".$CharsVals[$OptID];
	}
	
	$TovImage = "";
	if(isset($PhotoSpis[$ShopTovs["productID"]])) {
		$TovImage = $PhotoSpis[$ShopTovs["productID"]];
	}

	$TovURL = "";
	if (isset($RedirectsTargets["index.php?productID=".$ShopTovs["productID"]])) {
		$TovURL = $RedirectsTargets["index.php?productID=".$ShopTovs["productID"]];
	}

	$rzc = mysqli_query( $hlnk, "INSERT INTO ".$ppt."yandex_market_content SET
	ShopProductID='".$ShopTovs["productID"]."', MarketCategID='".$MCategID."',
	TovNazv='".$ProdName."', TovSysCode='".$ProdSysCode."',
	TovVendorCode='".$VendorCode."', TovPrice='".$ShopTovs["Price"]."',
	TovURL='".$TovURL."', TovVendorName='".$Vendor."', TovCountryName='".$VendCountry."', TovSpecDescr='".$Opis."', TovImage='".$TovImage."'
	ON DUPLICATE KEY UPDATE MarketCategID='".$MCategID."', TovNazv='".$ProdName."', TovSysCode='".$ProdSysCode."', TovVendorCode='".$VendorCode."', TovPrice='".$ShopTovs["Price"]."', TovURL='".$TovURL."', TovVendorName='".$Vendor."', TovCountryName='".$VendCountry."', TovSpecDescr='".$Opis."', TovImage='".$TovImage."';") or die ("Content for market :(");
	$i = 1;
}
//echo "<script>SucssFunct(".$i.");</script>";
echo "INSERT INTO ".$ppt."yandex_market_content SET
	ShopProductID='".$ShopTovs["productID"]."', MarketCategID='".$MCategID."',
	TovNazv='".$ProdName."', TovSysCode='".$ProdSysCode."',
	TovVendorCode='".$VendorCode."', TovPrice='".$ShopTovs["Price"]."',
	TovURL='".$TovURL."', TovVendorName='".$Vendor."', TovCountryName='".$VendCountry."', TovSpecDescr='".$Opis."', TovImage='".$TovImage."'
	ON DUPLICATE KEY UPDATE MarketCategID='".$MCategID."', TovNazv='".$ProdName."', TovSysCode='".$ProdSysCode."', TovVendorCode='".$VendorCode."', TovPrice='".$ShopTovs["Price"]."', TovURL='".$TovURL."', TovVendorName='".$Vendor."', TovCountryName='".$VendCountry."', TovSpecDescr='".$Opis."', TovImage='".$TovImage."';<hr>";
die();
?>