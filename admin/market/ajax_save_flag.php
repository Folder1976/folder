<?php
if (!isset($_POST["tovid"]) || !is_numeric($_POST["tovid"])) {die();}
$TovID = $_POST["tovid"];

$YS = 0;
if (isset($_POST["tvclck"]) && $_POST["tvclck"] == "true") {
	$YS = 1;
}

include '../config/config.php';

global $folder;


$sql = 'UPDATE tbl_tovar SET `use_in_market`=' . $YS . ' WHERE tovar_id = \''.$TovID.'\';';

$folder->query($sql) or die('error update_social ' .$sql);
die();
?>