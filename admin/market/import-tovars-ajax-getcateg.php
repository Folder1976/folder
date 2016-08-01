<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../config/config.php';
set_time_limit(1000);

if (!isset($_POST["categ"]) || !is_numeric($_POST["categ"])) {
	die("<li>Ошибка: [".$_POST["categ"]."] нечисловой параметр</li>");
}
$Categ = $_POST["categ"];

$r = mysqli_query( $folder, "SELECT TTOV.tovar_id
   FROM tbl_tovar TTOV
   LEFT JOIN tbl_parent_inet_path INPTH ON INPTH.path_id = '".$Categ."'
   INNER JOIN tbl_tovar_suppliers_items SUPIT ON SUPIT.tovar_id=TTOV.tovar_id
   WHERE TTOV.tovar_inet_id_parent = INPTH.category_id AND SUPIT.postav_id IN (3,45) AND TTOV.use_in_market=1 AND SUPIT.items > 0;") or die (mysqli_error($folder)."Get tovars :(");

if (mysqli_num_rows($r) == 0) {
	echo "<li>В категории ID ".$Categ." нет товаров (ни в ней, ни в потомках)</li>";
	echo "<script>delete TovLst; TovLst = []; ResetTovarsCateg(0); SucssCategFunct();</script>";
	die();
}
$tovArr = array();
$blockArr = array();
$i = 0;
while($tTov = mysqli_fetch_assoc($r)){
	if ($i == 100) {
		$tovArr[] = '"'.implode(",", $blockArr).'"';
		$blockArr = array();
		$i = 0;
	}
	$blockArr[] = $tTov["tovar_id"];
	$i++;
}
if (count($blockArr) > 0) {
	$tovArr[] = '"'.implode(",", $blockArr).'"';
	$blockArr = array();
}

$TvK = mysqli_num_rows($r);

echo "<script>delete TovLst; TovLst = [".implode(",",$tovArr)."]; ResetTovarsCateg(".$TvK."); MakeYML(".$Categ.", 0);</script>";
   
/*  
echo "<li>Сгенерирован список категорий и общая информация файла для Яндекс.Маркет</li>
<script>/*GetNextCateg(0);</script>";
*/

?>