<?php
$ppt = "tbl_";
$FSubUrl = 'main.php?func=market';

if(!isset($_GET["tvmact"]) || $_GET["tvmact"] == "") {
	$TovMAct = "";
	echo "YML для Яндекс.Маркет";
}
else {
	$TovMAct = $_GET["tvmact"];
	echo '<a href="'.$FSubUrl.'">YML для Яндекс.Маркет</a> | ';
}

switch($TovMAct) {
	case "":
		include("interf-menu.php");
	break;

	case "categs":
		include("categs-upravl.php");
	break;
	
	case "importtovars":
		include("import-tovars.php");
	break;

}


?>