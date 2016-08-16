<?php
if (!isset($_POST["tovid"]) || !is_numeric($_POST["tovid"])) {die();}
$TovID = $_POST["tovid"];

$YS = 0;
if (isset($_POST["tvclck"]) && $_POST["tvclck"] == "true") {
	$YS = 1;
}

include '../config/config.php';

global $folder;

include "../class/class_product.php";
$Product = new Product($folder);

$sql = 'SELECT tovar_artkl FROM tbl_tovar WHERE tovar_id = \''.$TovID.'\';';
$r = $folder->query($sql);
$tmp = $r->fetch_assoc();

$allBrothers = $Product->getProductBrotherOnArtikl($tmp["tovar_artkl"]);

foreach($allBrothers as $tK => $tArr) {
	if (!isset($tArr["tovar_id"]) || !is_numeric($tArr["tovar_id"])) {continue;}
	$sql = 'UPDATE tbl_tovar SET `use_in_market`=' . $YS . ' WHERE tovar_id = \''.$tArr["tovar_id"].'\';';
	$folder->query($sql) or die('error update_social ' .$sql);
}

$sql = 'UPDATE tbl_tovar SET `use_in_market`=' . $YS . ' WHERE tovar_id = \''.$TovID.'\';';

$folder->query($sql) or die('error update_social ' .$sql);
die();
?>