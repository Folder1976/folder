
<h3>Выбрать поставщика</h3>
Тут перепарсиваются цены и остатки(где есть)... Парс продолжатеся с прерванного места. Если нужно начать с начала - нажми Обнулить.
<br>Пауза между запросами страницы - 10 сек. Парсится дооооолго.
<br><br>
<a href="main.php?func=add_products&supplier=setnull"><b>Обнулить</b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=prices"><b>Все Цены</b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=prices&minus=5"><b>Все Цены (-5 руб)</b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=clear"><b>Почистить мусор</b></a> <= Удаляет записи по товарам которых нет.
<a href="main.php?func=add_products&supplier=clear_photo"><b>Почистить фотки</b></a> <= Удаляет фото товаров которых нет.
<hr>
Тут парсим сами сайты - товары.
<br><br>
<a href="main.php?func=add_products&supplier=sturmuniform">sturmuniform.ru</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=magellanrus">magellanrus.ru</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=stich">stich.su</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=allmulticam">allmulticam.ru[нет]</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=militarist">militarist.ua</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=btcgroup">btcgroup.ru</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<!--a href="main.php?func=add_products&supplier=garsing">garsing.ru<font color="red">*</font></a>&nbsp;&nbsp;|&nbsp;&nbsp;-->
<a href="main.php?func=add_products&supplier=wht">wht.ru<font color="red">*</font></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=splav">splav.ru<font color="red">*</font></a>
<a href="main.php?func=add_products&supplier=sturmuniform_new">sturmuniform.ru_new<font color="red">*</font></a>
<hr>
<?php

$supp = '';
if(isset($_GET['supplier'])) $supp = $_GET['supplier'];


if($supp == 'sturmuniform') include 'parsing/sturmuniform.php';
if($supp == 'magellanrus') include 'parsing/magellanrus.php';
if($supp == 'allmulticam') include 'parsing/allmulticam.php';
if($supp == 'stich') include 'parsing/stich.php';
if($supp == 'militarist') include 'parsing/militarist.php';
if($supp == 'btcgroup') include 'parsing/btcgroup.php';
if($supp == 'garsing') include 'parsing/garsing.php';
if($supp == 'wht') include 'parsing/wht.php';
if($supp == 'splav') include 'parsing/splav.php';
if($supp == 'prices') include 'parsing/prices.php';
if($supp == 'clear') include 'parsing/clear.php';
if($supp == 'clear_photo') include 'parsing/clear_photo.php';
if($supp == 'setnull'){
   		$sql = "UPDATE tbl_tovar_links SET updated = '0';";
		$folder->query($sql);
}
/*
2. Олмультикам
3. Магеллан
4. Стич профи
5. Вхт 
header("Content-Type: text/html; charset=UTF-8");
echo "<pre>";  print_r(var_dump( $_GET )); echo "</pre>";
*/


function cutStr($text, $char, $position){
	
	if(strpos($text, $char) === false){
		return $text;
	}
	
	$tmp = explode($char, $text);
	
	if(count($tmp) < $position){
		return $text;
	}
	
	$text = '';
	$count = 1;
	foreach($tmp as $s){
		if($count <= $position){
			$text .= $s.$char;
		}else{
			break;
		}
		$count++;
	}
	
	return trim($text, ',');
}                                                                                                                                                                                                                                                                                                                                                                                          
?>