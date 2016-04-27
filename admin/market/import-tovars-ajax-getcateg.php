<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../config/config.php';
set_time_limit(1000);

if (!isset($_POST["categ"]) || !is_numeric($_POST["categ"])) {
	die("<li>Ошибка: [".$_POST["categ"]."] нечисловой параметр</li>");
}
$Categ = $_POST["categ"];

$r = mysqli_query( $folder, "SELECT tovar_id
   FROM tbl_tovar
   LEFT JOIN tbl_parent_inet_path ON path_id = '".$Categ."'
   WHERE tovar_inet_id_parent = category_id;") or die (mysqli_error($folder)."Get tovars :(");

if (mysqli_num_rows($r) == 0) {
	echo "<li>В категории ID ".$Categ." нет товаров (ни в ней, ни в потомках)</li>";
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