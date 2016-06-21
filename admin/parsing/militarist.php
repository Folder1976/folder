<?php
set_time_limit(0);
include 'constants.php';
define("GETCONTENTVIAPROXY", 1);
//define("GETCONTENTVIANAON", 1);
$postav_id = 59; //MILITARIST
$pausa = 30;
$currency = 5;
$kurs = 3;
$skidka = 0.75;
$nacenka = 1.35; //Наценка на розницу! не на закуп!!!
$nacenka110 = 1.10; //Наценка на розницу! не на закуп!!!
$nacenka135 = 1.35; //Наценка на розницу! не на закуп!!!


$nacenka110 = $nacenka110 * 1.04; //наценили еще 4%
$nacenka135 = $nacenka135 * 1.04;

function sort_by_len($f,$s)
{
	if(strlen($f)<strlen($s)) return true;
	else return false;
}

?>

<style>
	.error{
		display: block;
		position: absolute;
		left: 10px;
		top: 10px;
		background-color: #FFCCC9;
		border: 2px solid gray;
		border-radius: 3px;
		padding: 10px;
	}
	.key_close{
		cursor: pointer;
	}
	#container{
		display: none;
		position: absolute;
		left: 10px;
		top: 80px;
		width: 600px;
		background-color: #C9F7FF;
		border: 2px solid gray;
		border-radius: 3px;
		padding-right: 10px;
	}
	
	.tree{
		margin-left: 15px;
	}
	.tree_ul{
		margin-left: 0px;
	}
	.handle {
		background: transparent url(images/tree-handle.png) no-repeat left top;
		display:block;
		float:left;
		width:15px;
		height:17px;
		cursor:pointer;
	}
	    .product-type-edit  li {
        list-style-type: none; 
    }
       
    .product-type-edit ul {
        margin-top: 15px;
        margin-left: 20px; /* Отступ слева в браузере IE и Opera */
        padding-left: 0; /* Отступ слева в браузере Firefox, Safari, Chrome */
    }
	li {
        padding-top: 3px;
        padding-bottom: 4px;
        list-style-type: none; 
    }
	.closed { background-position: left 2px; }
	.opened { background-position: left -13px; }
</style>
  <div class="msg_back"></div>
  
  <style>
	.msg_back{width: 100%;height: 100%;opacity: 0.7;display: none;position: absolute;background-color: gray;top:0;left:0;}
  </style>
<?php
//=================================================================================================================================
//=================================================================================================================================
//=================================================================================================================================
//=================================================================================================================================
//=================================================================================================================================
//=================================================================================================================================
//=================================================================================================================================
//=========================================== тут стрипты исправлений
/*
$sql = 'SELECT tovar_artkl, tovar_id FROM tbl_tovar WHERE
				tovar_artkl LIKE "%#901" OR
				tovar_artkl LIKE "%#902" OR
				tovar_artkl LIKE "%#903" OR
				tovar_artkl LIKE "%#904" OR
				tovar_artkl LIKE "%#905" OR
				tovar_artkl LIKE "%#906" OR
				tovar_artkl LIKE "%#907" OR
				tovar_artkl LIKE "%#908" OR
				tovar_artkl LIKE "%#909";';
$tmp = $folder->query($sql) or die('==' . $sql);
if($tmp->num_rows > 0){
	
	while($prod = $tmp->fetch_assoc()){
		
		$artikl = $prod['tovar_artkl'];
		
		$artikl = str_replace('#901','#XS', $artikl);
		$artikl = str_replace('#902','#S', $artikl);
		$artikl = str_replace('#903','#M', $artikl);
		$artikl = str_replace('#904','#L', $artikl);
		$artikl = str_replace('#905','#XL', $artikl);
		$artikl = str_replace('#906','#XXL', $artikl);
		$artikl = str_replace('#907','#3XL', $artikl);
		$artikl = str_replace('#908','#4XL', $artikl);
		$artikl = str_replace('#909','#5XL', $artikl);

		$sql = 'UPDATE tbl_tovar SET tovar_artkl = "'.$artikl.'" WHERE tovar_id = "'.$prod['tovar_id'].'"';
		//echo '<br>'.$sql;
		$folder->query($sql);
		
	}
	
	
}*/
//die();

//===========================================

?>
<br><br>
<a href="main.php?func=add_products&supplier=militarist&resetlinks">Обнулить сайтмап (заново)</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=militarist&links"><b><font color="blue">Продолжить парсить</font></b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="main.php?func=add_products&supplier=militarist&links&unset"><b><font color="orange">Пропустить товар</font></b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<?php

$http = 'https://militarist.ua/catalog/';
//просто парсим ссылки
if(isset($_GET['resetlinks'])){
	
	$sql = 'UPDATE tbl_parsing_militarist SET view = \'0\' WHERE view = "1";';
	$folder->query($sql) or die('==' . $sql);

}

if(isset($_GET['links'])){
		include 'simple_html_dom/simple_html_dom.php';

		//тупо посчитаем все запись
		$sql = 'SELECT count(id) AS id FROM tbl_parsing_militarist;';
		$tmp = $folder->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$all = $tmp['id'];

		$sql = 'SELECT count(id) AS id FROM tbl_parsing_militarist WHERE view = \'0\';';
		$tmp = $folder->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$none = $tmp['id'];
echo '<b>Всего ликов - '.$all.'. Пропарсено - '.($all - $none).'. Осталось - '.$none.'.</b>';
		
		$sql = 'UPDATE tbl_parsing_militarist SET view = \'1\' WHERE url like "%airsoft/%";';
		$url = $folder->query($sql) or die('==' . $sql);
    
	if(isset($_GET['unset'])){
		$sql = 'SELECT id FROM tbl_parsing_militarist WHERE view = \'0\' LIMIT 1;';
		$url = $folder->query($sql) or die('==' . $sql);
		$list = $url->fetch_assoc();
		$sql = 'UPDATE tbl_parsing_militarist SET view = \'2\' WHERE id = "'.$list['id'].'";';
		$url = $folder->query($sql) or die('==' . $sql);
		
		?>
			<script>
				function reload() {
					location.href() = 'main.php?func=add_products&supplier=militarist&links';
				}
			</script>
		<?php
	}
	    
		$sql = 'SELECT * FROM tbl_parsing_militarist WHERE view = \'0\' LIMIT 1;';
		$url = $folder->query($sql) or die('==' . $sql);
	
	
	if($url->num_rows > 0){
				$list = $url->fetch_assoc();

echo ' <b>Урл ID - '.$list['id'].'. </b>'; 
			
			if(isset($_GET['url'])){
				$list['url'] = $_GET['url'];
			}
			
			//$list['url'] = 'https://militarist.ua/catalog/clothes/raincoats/miltek-bundes-parka-nepromokaemaya-flektarn-vse-razm/';
			//$list['url'] = 'https://militarist.ua/catalog/clothes/jackets/bushlaty/brandit-bushlat-chernyy-all-sizes/';
			//while($list = $url->fetch_assoc()){							
				$brand = '';
				$price = '';
				$view = '';
				$size = '';
				
				//Если попадаются кривые линки!!!!!!!
				if(strpos($list['url'], 'http://militarist.ua')){
					$list['url'] = str_replace('http://militarist.ua','',$list['url']);
					
					$sql = 'UPDATE tbl_parsing_militarist SET `url` = \''.$list['url'].'\' WHERE `id` = \''.$list['id'].'\';';
					$folder->query($sql) or die('==' . $sql);
		
					echo '<h3 style="color:orange;">url - '.$list['url'].'</h3>';
				}else{
					echo '<h3 style="color:green;">url - '.$list['url'].'</h3>';
				}
				$list['url'] = str_replace('http://militarist.ua','http://militarist.ua/',$list['url']);
				$list['url'] = str_replace('//','/',$list['url']);
				$list['url'] = str_replace('//','/',$list['url']);
				$list['url'] = str_replace('http:/','http://',$list['url']);
				$list['url'] = str_replace('https:/','https://',$list['url']);

				
				//Get content via proxy
				$html = @file_get_html($list['url']);
				
				
				//Если это кривой линк - возможно удаленный товар
				if(!$html){
					$sql = 'UPDATE tbl_parsing_militarist SET view = "1" WHERE `url` = \''.$list['url'].'\';';
					$folder->query($sql) or die('==' . $sql);
	
		
	
					?>
					<h3>Ошибка 404</h3>
						<script>
							$(document).ready(function(){
								<?php if($error == 0 OR $name == 'category'){ 
									echo 'setTimeout(reload, '.$pausa.'000);';
								 } ?>
							}
							);
							
							function reload() {
								location.reload();
							}
						</script>
					<?
					return false;
				}
				
				//Хлебная крошка
				$tmp = $html->find('.breadcrumbs',0);
				if($tmp){
					$breadcrumbs_html = $tmp->innertext();
					$html_tmp = str_get_html($breadcrumbs_html);
					$breadcrumbs_html = $html_tmp->find('li a');
					$breadcrumbs = array();
					foreach($breadcrumbs_html as $tt){
						$breadcrumbs[$tt->innertext()] = $tt->innertext();
					}
					echo 'Крошки родителя ($breadcrumbs_txt => $category_id)-> <b>'.implode('>',$breadcrumbs).'</b><br>';
					$breadcrumbs_txt = implode('>',$breadcrumbs);
				}else{
					$breadcrumbs_txt = '';
				}
			
			
				//Массив ссылок
				$str_tmp = $html->find('a');
				
				
				$artkl = '';
				//Это товар
				if($html->find('.item-brands',0)){
					
					//echo '11111111';die();
					//Если это товар - то его уже не сканим
					//$view = '1';
					//============================================================================================
					
					$memo = $html->find('.tabbable',0)->innertext();
					if(isset($memo)){
						//выдерем сначала фотки от туда
						$tmp_html = str_get_html($memo);
						$img_tmp = $tmp_html->find('img');
						if(isset($img_tmp)){
							$img_memo[] = array();
							$img_memo_dell[] = array();
							$img_memo_repp[] = array();
							foreach($img_tmp as $option){
								if(strpos($option->src, 'photos.militarist.com.ua') !== false){
									$img_memo[]	= $option->src;
									$img_memo_dell[] = $option->outertext();
								}elseif(strpos($option->src, 'photos.militarist.ua') !== false){
									$img_memo[]	= $option->src;
									$img_memo_dell[] = $option->outertext();
								}elseif(strpos($option->src, 'militarist.ua/upload') !== false){
									$img_memo[]	= $option->src;
									$img_memo_dell[] = $option->outertext();
								}elseif(strpos($option->src, 'upload/resize_cache/iblock') !== false){
									$img_memo[]	= 'https://militarist.ua'.$option->src;
									$img_memo_dell[] = 'https://militarist.ua'.$option->outertext();
								}
								
								//upload/resize_cache/iblock/60c/190_190_16a9cdfeb475445909b854c588a1af844/60cf4ced964bfbee9484e0eb055a6494.jpg
								
							}
						}
						//Переделаем описание под себя
						$str_tmp = $tmp_html->find('.tab-pane');
							$memo = '';
							foreach($str_tmp as $option){
								$tttt = $option->innertext();
								foreach($img_memo_dell as $find){
									$tttt = str_replace($find, '<b>фото в товаре</b>', $tttt);
								}
								$memo	.= str_replace('tab-pane', 'new-tab-pane', $tttt);
							}
					}
					$memo = str_replace('С уважением Хог','', $memo);
					echo '<br>Описание ($memo) -> <b><font color="red">Скрыто</font></b>';
					//============================================================================================
					$brand = $html->find('.item-brands a img',0)->alt;
					echo '<br>Бренд ($brand => $brand_id) -> <b><font color="red">'.$brand.'</font></b>';
					//============================================================================================
					$name = $html->find('.item-name',0)->innertext();
					echo '<br>Название ($name) -> <b><font color="red">'.$name.'</font></b>';
					//============================================================================================
					$artkl_original = $artkl = $html->find('.item-code',0)->innertext();
					$artkl = str_replace('Код товара: ','', $artkl);
					$artkl = str_replace('/','-', $artkl);
					$artkl = str_replace('(','-', $artkl);
					$artkl = str_replace(')','-', $artkl);
					$artkl = str_replace(')','-', $artkl);
					$artkl = str_replace('*','-', $artkl);
					$artkl = str_replace(',','-', $artkl);
					$artkl = str_replace('ALL','', $artkl);
					echo '<br>Актикл ($artkl) -> <b><font color="red">'.$artkl.'</font></b>';
					//============================================================================================
					//============================================================================================
					$price = $html->find('.item-price big',0)->innertext();
					$rep_tmp = '';
					if(strpos($price, '<span') !== false){
						//Если это уцененка
						$price = str_replace(' ', '', $price);
						$price = substr($price, 0 , strpos($price, '<span'));
						$skidka = 1;
						$nacenka = $nacenka135;
					}else{
						//Если там нем акций - то минимальная наценка
						$nacenka = $nacenka110;
					}
					//Проверим еще на стикеры
					$stiker = $html->find('#all-stiker',0);
					if(isset($stiker)){
						$stikers = $stiker->innertext();
						//Если в стикерах есть стикеры Распродажа или Акция то ставим макс наценку. А закуп ставим без скидки
						if(strpos($stikers, 'qsale') !== false OR strpos($stikers, 'action') !== false){
							$skidka = 1;
							$nacenka = $nacenka135;
						}
					}
					
					$price = str_replace(' ','', $price);
					$price = str_replace('<small>грн.</small>','', $price);
					echo '<br>Цена ($price) -> <b><font color="red">'.$price.'</font></b>';
					//============================================================================================
					$image = array();
					$image_small = array();
					$t = $html->find('.gallery-thumbs a');
					foreach($t as $tt){
						$image_small[] = 'https://militarist.ua'.$tt->href;
						$image[] = str_replace('resize_320_245', 'resize_1000_1000', 'https://militarist.ua'.$tt->href);
					}
					if(isset($img_memo) AND count($img_memo) > 0){
						foreach($img_memo as $tmp){
							if(is_string($tmp)){
								$image[] = $tmp;
							}
						}
					}
					//Если нет фоток, значит нет галереи и фотка только одна - берем ее из главного окна
					if(count($image) == 0){
						$t = $html->find('.gallery-thumbs img', 0);
						
						if(isset($t)){
							$tmp = $t->href;
							$image_small[] = 'https://militarist.ua'.str_replace('resize_1000_1000', 'resize_320_245', $tmp);;
							$image[] =  'https://militarist.ua'.$tmp;
						}
					}
					
					
					echo '<br>';
					foreach($image_small as $index => $img){
						if(is_string($img)){
							echo '<img width="150" src="'.$img.'">';	
						}
					}
					
					//echo '<br>Картинки -> <b><font color="red"><br>'.implode('<br>', $image).'</font></b>';
					//============================================================================================
					//============================================================================================
					$find = array('   Код товара Размер Цена Наличие в магазинах Количество купить     ',"\t","\t","\t","\t","\t",'  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ','  ');
					$rep = array(' ',' ',' ',' ',' ',' ',' ',' ',' ',' ', ' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ' );
					$tables = $html->find('table');
					//Просмотрим все таблицы - и охереем! НАйдем размеры
					foreach($tables as $table){
						
						if(strpos($table, 'Размер') !== false){
							
								$tab = $table->plaintext;
							$tab = str_replace($find, $rep, $tab);
							
							//Вот тут разложение таблицы! Испольщзуем оригинальный артикл - с говносимволами
							//$tmp_size = explode($artkl, $tab);
							//$artkl_original = $artkl;
							$artkl_original = trim($artkl_original);
							$artkl_original = str_replace('Код товара: ','', $artkl_original);
							$artkl_original = str_replace('-ALL','', $artkl_original);
							$artkl_original = str_replace('ALL','', $artkl_original);
							$artkl_original = str_replace('/','-', $artkl_original);
							$artkl_original = str_replace('(','-', $artkl_original);
							$artkl_original = str_replace(')','-', $artkl_original);
							$artkl_original = str_replace('*','-', $artkl_original);
							
							$tab = str_replace('/','-', $tab);
							$tab = str_replace('(','-', $tab);
							$tab = str_replace(')','-', $tab);
							$tab = str_replace(')','-', $tab);
							$tab = str_replace('*','-', $tab);
							//$tab = str_replace(',','-', $tab);
							$tab = str_replace('-ALL','', $tab);
							$tab = str_replace('ALL','', $tab);
	
						$tmp_size = explode((string)$artkl_original, (string)$tab);

							foreach($tmp_size as $size1){
								
								if(strpos($size1, 'грн.') !== false){
							
									$t = explode(' ', $size1);
									$siz = str_replace('-','/',$t[1]);
									//Размер
									$size[$siz]['size'] = $siz;
									
									//Цена
									$s = explode('грн.', $size1);
									$s = str_replace('-', '', $s[0]);
									$s = str_replace(' ', '', $s);
									$s = str_replace(' ', '', $s);
									$new_price = explode($siz, $s);
									//$price	= $new_price[1];
									
									//Пошло все нахер!!! пишем базовую цену! Если какието размеры дороже - уточнять!
									//Лучше пару товаров с левыми цеными, чем половина милтека! бля!
									$size[$siz]['price'] = preg_replace("/\D/","",$price);;

									//Наличие
									if(strpos($size1, 'нет на складе') !== false){
										$size[$siz]['yes'] = false;
									}else{
										$size[$siz]['yes'] = true;	
									}
									
								}
								
							}
							foreach($size as $index => $siz){
								if($siz['size'] == ''){
									unset($size[$index]);
								}
							}
						}else{
							$size['0']['size'] = '';
							$size['0']['price'] = $price;
							//Наличие
							$size['0']['yes'] = true;
							$stat = $html->find('.status',0)->innertext();
							if(strpos($stat, 'нет на складе') !== false){
								$size['0']['yes'] = false;
							}
						}
					}
					if(isset($size)){
						//echo '<br>Размер ($size) -> <b><font color="red"><pre>'; print_r(var_dump( $size )); echo '</pre></font></b>';
						echo '<br>Размер ($size) -> <b><font color="red">Размеры в массиве</font></b>';
					}else{
						echo '<br>Размер ($size) -> <b><font color="red">Без размера</font></b>';
					}
					//============================================================================================
					//============================================================================================
					$view = '0'; //Тоже в ноль его пока полное добавление не пройдет
				}else{
					$view = '0';
					$name = 'category';
				}
				
				echo '<h4>Найдены линки новые линки</h4>';
				$view = '0';
				foreach($str_tmp as $option){
				
					$href = str_get_html($option->href);
					
					if(strpos($href, '/catalog/') !== false AND
						strpos($href, '?sort=') === false AND
						strpos($href, '?SET_PAGE_COUNT') === false AND
						strpos($href, '?action=ADD') === false
						){
						
						
						if(strpos($href,'http') === false){
							$href = str_replace('http://militarist.ua/catalog/', '', $href);
							$href = str_replace('http://militarist.ua/', '', $href);
							$href = str_replace('http://militarist.ua', '', $href);
							$href = str_replace('https://militarist.ua/catalog/', '', $href);
							$href = str_replace('https://militarist.ua/catalog', '', $href);
							$href = str_replace('https://militarist.ua/', '', $href);
							$href = str_replace('https://militarist.ua', '', $href);
							$href = 'https://militarist.ua'. $href;
						}
						
						$href = str_replace('HTTP://https:/militarist.ua/catalog/', 'https://militarist.ua/catalog/', $href);
						$href = str_replace('http://https:/militarist.ua/catalog/', 'https://militarist.ua/catalog/', $href);
						
						$sql = 'SELECT id FROM tbl_parsing_militarist WHERE url = \''.$href.'\';';
						$t = $folder->query($sql) or die('==' . $sql);
						
						if($t->num_rows == 0){
							$sql = 'INSERT INTO tbl_parsing_militarist SET
										 `url` = \''.$href.'\',
										 `key` = "'.$name.'",
										 `view` = \''.$view.'\',
										 `date` = \''.date('Y-m-d H:i:s').'\',
										 `breadcrumbs` = \'\';';
							$folder->query($sql) or die('==' . $sql);
							echo $href.'<br>';
						}
					
					}
	 
				}
				
				if(!isset($size_n)){
					$size_n = array('nosize'=>'0');
				}
				
				$sql = 'UPDATE tbl_parsing_militarist SET view = \'0\',
												`breadcrumbs` = \''.$breadcrumbs_txt.'\',
												`artkl` = \''.$artkl.'\',
												`brand` = \''.$brand.'\',
												`price` = \''.$price.'\',
												`size` = \''.implode(', ', $size_n).'\'
												
												WHERE url = \''.$list['url'].'\';';
				$folder->query($sql) or die('==' . $sql);
     
	 
				//=============================================================
				//Опеределяем данные
				$error = 0;
				
				//Бренд
				$sql = 'SELECT brand_id FROM tbl_brand WHERE brand_name = "'.$brand.'" ORDER BY brand_name ASC;';
				$br = $folder->query($sql) or die('==' . $sql);
				if($br->num_rows > 0){
					$tmp = $br->fetch_assoc();
					$brand_id = $tmp['brand_id'];
				}else{
					$sql = 'SELECT brand_id FROM tbl_brand_alternative WHERE postav_id = "'.$postav_id.'" AND brand_name = "'.$brand.'" ';
					$br = $folder->query($sql) or die('==' . $sql);
					if($br->num_rows > 0){
						$tmp = $br->fetch_assoc();
						$brand_id = $tmp['brand_id'];
					}else{
						$error = 1;
					}
				}
				
				//Категория
				$sql = 'SELECT category_id FROM tbl_category_alternative WHERE breadcrumbs = "'.$breadcrumbs_txt.'" AND postav_id = "'.$postav_id.'";';
				$br = $folder->query($sql) or die('==' . $sql);
				if($br->num_rows > 0){
					$tmp = $br->fetch_assoc();
					$category_id = $tmp['category_id'];
				}else{
					$error = 1;
				}
		

		
		
				//Цвет
				$colors_original = array();
				$sql = 'SELECT * FROM tbl_colors';
				$br = $folder->query($sql) or die('==' . $sql);
				while($tmp = $br->fetch_assoc()){
					$colors[$tmp['color']] = $tmp['id'];
					$colors_original[$tmp['id']] = $tmp['color'];
				}
				$sql = 'SELECT * FROM tbl_colors_alternative';
				$br = $folder->query($sql) or die('==' . $sql);
				while($tmp = $br->fetch_assoc()){
					$colors[$tmp['color']] = $tmp['id'];
				}
				
				uasort($colors,'sort_by_len');
				$up_name = mb_strtoupper(addslashes($name),'UTF-8');
				foreach($colors as $color => $id){
					if(!empty($color)){
						if(strpos($up_name, mb_strtoupper(addslashes($color),'UTF-8')) !== false){
							echo $color . ' = = '.$id;
							$color_id = $id;
							$color_name = $colors_original[$color_id];
							break;
						}
					}
				}
				if(isset($color_id)){
					$color_id_str = $color_id;
					if((int)$color_id < 10){
						$color_id_str = '00'.$color_id;
					}elseif((int)$color_id < 100){
						$color_id_str = '0'.$color_id;
					}
					
					echo '<br>Определил цвет isset($color_name) - <b>'.$color_name.'</b><img src="/resources/colors/'.$color_id_str.'.png">';
				}
	 
	 
	 
	 
		if($error AND $name != 'category'){
			echo '<div class="error">';
			if(!isset($brand_id)){
				
				include 'class/class_localisation.php';

				$Localisation = new Localisation($folder);
				$country = $Localisation->getCountry();
				
				include 'class/class_brand.php';
				$Brand = new Brand($folder);
				$brands = $Brand->getBrands();
				?>
				<br>Не удалось определить бренд ( <b> <?php echo $brand; ?> </b> ) -
						<select class="brand" style="width: 200px;">
							<option value="0">Состыкуй бренд</option>
						<?php foreach($brands as $value){?>
							<option value="<?php echo $value['brand_id'];?>"><?php echo $value['brand_name'];?></option>
						<?php } ?>
						
						</select> ===>
						<br><a href="javascript:" id="add_brand" target="_blank"><b>Добавить <?php echo $brand; ?></b></a>
						<select name="country0" class="brand_country" data-id="0" style="width:200px;">
							<?php foreach($country as $id => $value){
								  echo '<option value="'.$id.'">'.$value.'</option>';	    
							} ?>
						  </select>
						<a href="edit_brands.php?brand_id=297" target="_blank"> редактор брендов тут</a>
						<header>
							<title>*** STOP!</title>
						</header>
						<hr>
				<?php
			}
			
			if(!isset($category_id)){
				  $sql = "SELECT parent_inet_id AS product_type_id, parent_inet_parent AS product_parent_id, parent_inet_1 AS product_type_name
					FROM  tbl_parent_inet  WHERE parent_inet_parent = '0' ORDER BY parent_inet_1 ASC;";
					$rs = $folder->query($sql) or die ("Get product type list ".$sql);
					
					$body = "
							<div id=\"container\" class = \"product-type-tree\">
							<div class='key_close'>Закрыть [x]</div>
							<input type='hidden' id='selected_menu' value=''>
							<ul  id=\"celebTree\"><li><span id=\"span_0\"><a class = \"tree\" href=\"javascript:\" id=\"0\">Категории</a></span><ul>";
					while ($Type = mysqli_fetch_assoc($rs)) {
						if($Type['product_parent_id'] == 0){
							$body .=  "<li><span id=\"span_".$Type['product_type_id']."\"> <a class = \"tree\" href=\"javascript:\" id=\"".$Type['product_type_id']."\">".$Type['product_type_name']."</a>";
							$body .= "</span>".readTree($Type['product_type_id'],$folder);
							$body .= "</li>";
						}
					}
					$body .= "</ul>
						</li></ul></div>";
						
				echo $body;
				echo '<hr>Категория? <b>'.$breadcrumbs_txt.'</b> - <a href="javascript:" class="breadcrumbs">Назначить этим крошкам</a>
							&nbsp;&nbsp;&nbsp;&nbsp;===>&nbsp;&nbsp;<a href="edit_inet_parent_table.php" target="_blank"> редактор категорий тут</a>';
				?>
					<header>
						<title>*** STOP!</title>
					</header>
				
				<?php
			}
		}
		//============================================================================================================
		
		//header("Content-Type: text/html; charset=UTF-8");
		//echo "<pre>";  print_r(var_dump( $size )); echo "</pre>";die();
		
		//============================================================================================================
		//============================== З А П И С Ь =================================================================
		//============================================================================================================
		//============================================================================================================
		if($name != 'category'){	
			if($error == 1 ){
				echo '<h2>НЕ ХВАТАЕТ ДАННЫХ!</h2></div>';
				$view = 0;
			}else{
				include 'class/class_product_edit.php';
				$ProductEdit = new ProductEdit($folder);
				//Подчищаем модель
				$model = trim($artkl);
				$model = str_replace(' ', '-', $model);
				$model = translitArtkl($model);
				
				$str_name = $name;
				foreach($size as $siz){
					
					//Если массив с размерами - добавим его
					if($siz['size'] != ''){
						$str_artkl = $model.'#'.$siz['size'];
					}else{
						if(count($size) == 1){
							$str_artkl = $model;
						}else{
							continue;
						}
					}
					
					//$kurs = 3;
					//$skidka = 0.75;
					//$nacenka = 1.3; //Наценка на розницу! не на закуп!!!
					$_zakup = (int)($siz['price'] * $kurs) * $skidka ;
					$_price = (int)($siz['price'] * $kurs) * $nacenka;
					$_items = '0';
					if($siz['yes'] == true){
						$_items = '10';
					}
					
					$product_ids = $ProductEdit->getProductIdOnArtiklAndSupplier($str_artkl);
			
					if($product_ids AND count($product_id) > 0){
						foreach($product_ids as $product_id){	
							echo '<br><font color="green">Нашел продукт <b>'.$product_id.'</b>.
									<br>Обновлю: Бренд, Наличие, Цену</font>';
							
							//Обновим некоторые параметры
							if($brand_id > 0){
								$sql = 'UPDATE tbl_tovar SET brand_id = \''.$brand_id.'\' WHERE tovar_id = \''.$product_id.'\';';
								$folder->query($sql) or die('Не удалось обновить бренд' . $sql);
							}
						}
					}else{
						echo '<br>'.$str_artkl . ' ' . $str_name .' = Такого продукта нет. <br><b><font color="green">Пробую добавить</font></b><br>';
					
						$data['tovar_artkl'] = $str_artkl;
						$data['tovar_name_1'] = $str_name;
						$data['tovar_inet_id'] = 10;
						$data['tovar_inet_id_parent'] = $category_id;
						$data['brand_id'] = $brand_id;
						$data['tovar_model'] = $model;
						$data['tovar_parent'] = 2;
						$data['tovar_memo'] = $memo;
						$data['tovar_purchase_currency'] = 1;
						$data['tovar_sale_currency'] = 1;
						$data['tovar_sale_currency'] = 1;
						$data['tovar_dimension'] = 1;
						
						$product_id = $ProductEdit->addProduct($data);
						//echo '==='.$product_id.'<br>';
					}	
				
					//Обновим категорию =================================
					$sql = 'UPDATE tbl_tovar SET
							tovar_inet_id_parent = \''.$category_id.'\'
							WHERE 
							tovar_id = \''.$product_id.'\';';
					//echo $sql;
					$folder->query($sql) or die(' - '.$sql);
					$alias = '';
					//Получим алиас категории
					$sql = 'SELECT seo_alias FROM tbl_seo_url WHERE seo_url = \'parent='.$category_id.'\';';
					$tovar = $folder->query($sql);
					if($tovar->num_rows > 0){
						$tmp = $tovar->fetch_assoc();
						$alias .= ''.$tmp['seo_alias'];
					}
					
					//Получим код бренда
					$sql = 'SELECT brand_code FROM tbl_brand WHERE brand_id = \''.$brand_id.'\';';
					$tovar = $folder->query($sql) or die('add product - ' . $sql);
					if($tovar->num_rows > 0){
						$tmp = $tovar->fetch_assoc();
						$alias .= '/'.$tmp['brand_code'];
					}
					
					$alias .= '/'.$model;
					
					$sql = 'UPDATE tbl_seo_url SET seo_alias = \''.$alias.'\' WHERE seo_url = \'tovar_id='.$product_id.'\';';
					$tovar = $folder->query($sql) or die('add product - ' . $sql);
					//===========================================================		
					
					//Сохраним этот линк для этого поставщика на этот продукт.
					$sql = 'INSERT INTO tbl_tovar_links SET
							product_id = \''.$product_id.'\',
							postav_id = \''.$postav_id.'\',
							url = \''.$list['url'].'\'
							ON DUPLICATE KEY UPDATE
							url = \''.$list['url'].'\'
							';
					//echo $sql;
					$folder->query($sql) or die(' - '.$sql);
					
					//Если определился цвет - И если он уже не указан! Могли исправить на правильны! ВАЖНО!
					if(isset($color_name)){
						$sql = 'SELECT * FROM tbl_attribute_to_tovar WHERE tovar_id  = \''.$product_id.'\' AND attribute_id  = \'2\';';
						$r2 = $folder->query($sql);
						if($r2->num_rows == 0){
							$sql = 'INSERT INTO tbl_attribute_to_tovar SET
									tovar_id = \''.$product_id.'\',
									attribute_id  = \'2\',
									attribute_value = \''.$color_name.'\';';
							//echo $sql;
							@$folder->query($sql) or die(' - '.$sql);
						}
					}
					
					//Запишем наличие и цены
					$sql = 'INSERT INTO tbl_tovar_suppliers_items SET
							`tovar_id` = \''.$product_id.'\',
							`postav_id`=\''.$postav_id.'\',
							`price_1`=\''.number_format($_price,0,'','').'\',
							`zakup` = \''.$_zakup.'\',
							`items`=\''.$_items.'\'
								ON DUPLICATE KEY UPDATE 
							`price_1`=\''.number_format($_price,0,'','').'\',
							`zakup` = \''.$_zakup.'\',
							`items`=\''.$_items.'\';';
					$folder->query($sql) or die('Остатки - '.$sql);
						
					//Загрузим фото - только если у товара нет фото
					include_once ('import/import_url_getfile.php');
					$noload = true;
					//if(!file_exists(UPLOAD_DIR.$model.'/'.$model.'.0.small.jpg')) {
						if(isset($image)){
							foreach($image as $str_img){
							
								$direct_load = true;
								$IMGPath = $str_img;
								$str_artkl = $model;
								$sql = 'INSERT INTO parsing_militarist_photo SET
											model = "'.$model.'",
											img = "'.$str_img.'"
											';
								
								//include 'import/import_url_photo.php';
							}
						}else{
							echo '<br>Нет фото';    
						}
					
					//}else{
						   //include '../import/import_url_getfile.php';
					//}
				
					//Вот теперь когда все сделали - поставим этому линку статус вью 1
					$sql = 'UPDATE tbl_parsing_militarist SET `view` = \'1\' WHERE `url` = \''.$list['url'].'\';';
					$folder->query($sql) or die('==' . $sql);
					
				
				}
			}
		}else{
			
			//Если это была категория
			//Вот теперь когда все сделали - поставим этому линку статус вью 1
			$sql = 'UPDATE tbl_parsing_militarist SET `view` = \'1\' WHERE `url` = \''.$list['url'].'\';';
			$folder->query($sql) or die('==' . $sql);
			
		}
		
		//return false;
	}
	 
				
			//}		
							
}else{
	die('<h2>ЗАКОНЧИЛ!</h2>');
}
//echo $sql;die();
//Рекурсия=================================================================
function readTree($parent,$folder){
   $sql = "SELECT parent_inet_id AS product_type_id, parent_inet_parent AS product_parent_id, parent_inet_1 AS product_type_name
			FROM  tbl_parent_inet  WHERE parent_inet_parent = '$parent' ORDER BY product_type_name ASC;";
    $rs1 = $folder->query($sql) or die ("Get product type list".$sql);

    $body = "";

     while ($Type = mysqli_fetch_assoc($rs1)) {
	    $body .=  "<li><span id=\"span_".$Type['product_type_id']."\"><a class = \"tree\" href=\"javascript:\" id=\"".$Type['product_type_id']."\">".$Type['product_type_name']."</a>";
	    $body .= "</span>".readTree($Type['product_type_id'],$folder);
	    $body .= "</li>";
    }
    if($body != "") $body = "<ul>$body</ul>";
    return $body;

}
function translitArtkl($str) {
    $rus = array('І','и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('I','u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
   return str_replace($rus, $lat, $str);
}
?>
<script>
	$(document).on('click', '.breadcrumbs', function(){
		$('#container').css('display', 'block');
		$('.msg_back').css('display', 'block');
		
	});

	$(document).on('click', '.msg_back', function(){
		$('#container').css('display', 'none');
		$('.msg_back').css('display', 'none');
	});
	
	
	
	$(document).on('change', '.brand', function(){
		
		var brand_id = $(this).val();
		var postav_id = "<?php echo $postav_id; ?>";
		var brand_name = "<?php echo $brand; ?>";
		$.ajax({
			type: "POST",
			url: "parsing/edit_alternative.php",
			dataType: "text",
			data: "brand_id="+brand_id+"&postav_id="+postav_id+"&brand_name="+brand_name+"&key=add_brand",
			beforeSend: function(){
			},
			success: function(msg){
				console.log( msg );
			}
		});
		
	});

	$(document).on('click', 'span', function(event){
		
		var id = event.target.id;
		var parent_id = $(this).children("a").first().attr('id');
		console.log(id);
	
		if (id) {
			switch(id){
				case "dell-carfit":
							 dellItem(parent_id);
				break;
				case "insert-carfit":
							  insertItem(parent_id);
				break;
				case "new_category":
							  //insertItem(parent_id);
				break;
				default:                  
					var category_id = id;
					var postav_id = "<?php echo $postav_id; ?>";
					var breadcrumbs = "<?php echo $breadcrumbs_txt; ?>";
					
					if(confirm('Назначить '+$('#'+id).html()+' ?')){

						$.ajax({
							type: "POST",
							url: "parsing/edit_alternative.php",
							dataType: "text",
							data: "category_id="+category_id+"&postav_id="+postav_id+"&breadcrumbs="+breadcrumbs+"&key=add_category",
							beforeSend: function(){
							},
							success: function(msg){
								$('.breadcrumbs').html('<b>'+$('#'+id).html()+'</b>');
								console.log($('#'+id).html());
								console.log( msg );
							}
						});
					}
			}
				}else{
					$(this).toggleClass('closed opened').nextAll('ul').toggle(300);
				}
				   
	});
	//==========Кнопка Закрыть окно редактирования
	$(".key_close").click(function(){
		$("#container").css("display","block").toggle('slow');
	});
	
	
$(document).ready(function(){
	
	//Скрипт дерева ========================
	$('#celebTree ul')
		.hide()
		.prev('span')
		.before('<span></span>')
		.prev()
		.addClass('handle closed')
		.click(function() {
		  // plus/minus handle click
				   // $(this).toggleClass('closed opened').nextAll('ul').toggle();
		});
	$('#celebTree ul')
		.prev('span')
		.children('a')
		.toggleClass('tree tree_ul')
		.click(function() {
		  // plus/minus handle click
				   // $(this).toggleClass('closed opened').nextAll('ul').toggle();
		});
		
	  //Развернем первый уровень
	$("#0").parent('span').parent('li').children('span').first().toggleClass('closed opened').nextAll('ul').toggle();
	console.log('ready');
	//setTimeout(reload, 10000);
});
	
	//Тут прописать - если пролетели без ошибок - валим дальше
	$(document).ready(function(){
		<?php if(($error == 0 OR $name == 'category') AND !isset($_GET['url'])){ 
			echo 'setTimeout(reload, '.$pausa.'000);'; //'.($pausa * 1000).'
		 } ?>
	}
	);
	
	function reload() {
        location.reload();
    }
	<?php
		$brand_code = translitArtkl($brand);
		$brand_code = str_replace('+', '-', $brand_code);
		$brand_code = str_replace(' ', '-', $brand_code);
		$brand_code = strtolower($brand_code);
	?>
	 
   $(document).on('click','#add_brand', function(){
		var brand_name = "<?php echo $brand; ?>";
		var brand_code = "<?php echo $brand_code; ?>";
		var country_id = $(".brand_country").val();
		
		brand_code = brand_code.replace('&', '@*@');
		brand_name = brand_name.replace('&', '@*@');
		
		$.ajax({
		type: "POST",
		url: "brand/ajax_edit_brand.php",
		dataType: "text",
		data: "brand_name="+brand_name+"&brand_code="+brand_code+"&country_id="+country_id+"&key=add",
		beforeSend: function(){
		},
		success: function(msg){
		  console.log(  msg );
		  alert('Добавил новый бренд.\n\rНекоторые спецсимволы модут быть заменены\n\rВозможно он не привяжется сам - привяжи через выбор');
		  location.reload();
		  //$('#msg').html('Изменил');
		  //setTimeout($('#msg').html(''), 1000);
		}
	  });
		
	});
	
//=======Новый добавляемый элемент
var menu = "&nbsp;&nbsp;<input type=\"text\" style=\"width:100px;\" id=\"new_category\" class=\"new_category\" value=\"\" placeholder=\"новая папка\"><a href=\"javascript:\" id=\"insert-carfit\" class=\"insert-carfit drop_key-carfit\" style=\"z-index:999;\">[вставить]</a>";

//=======Наводимся на элемент
$(document).on('mouseleave', '.new_category', function(){
	var parent_id = $(this).parent("span").attr('id');
	if ($(this).val() != '') {
		var span = $('#selected_menu').val();
		$('#'+span).children("a").last().remove();
		$('#'+span).children("input").last().remove();
		
        $('#selected_menu').val(parent_id);
    }
	
});

$(document).on('mouseenter', '#container span', function(e){
            if (this.id) {
                if (this.id.indexOf('arent') > 0) {
                    
                }else{
					if($(this).attr('id') != $('#selected_menu').val()) {
						$(this).children("a").after(menu);       
					}
                }
            }});
//=======Уходим с элемента
$(document).on('mouseleave', '#container span', function(e){
            if (this.id) {
                if (this.id.indexOf('arent') > 0) {
                    
                }else{
					
					if ($(this).attr('id') != $('#selected_menu').val()) {
						$(this).children("a").last().remove();
						$(this).children("input").last().remove();
						//$(this).children("a").last().remove();
					}
                }
            }
              
});
//=========== Вставляем элемент
	function insertItem(id){
		
		var name = $('.new_category').val();
		var parent_id = id;
		
		console.log('Категория - '+ name + ' ' + parent_id);
			$.ajax({
				type: "POST",
				url: "parsing/ajax_edit_category.php",
				dataType: "json",
				data: "key=add&parent_id="+parent_id+"&name="+name,
				success: function(msg){
					//console.log(msg);
				
					var insert = "<li><span id=\"span_"+
								  msg.id+
								  "\"><a href=\"javascript:\" class=\"tree-carfit\" id=\""
								  +msg.id+"\">"+name+"</a></span></li>";
					//Если нет вложенного списка - создаем его
					if ($("#"+id).parent("span").parent("li").children("ul").html()) {
						$("#"+id).
							parent("span").
							parent("li").
							children("ul").
							children("li").
							last().after(insert);
					
					}else{
						$("#"+id).toggleClass('tree-carfit tree_ul-carfit');
						$("#"+id).parent("span").last("span").after("<ul style=\"display: block;\" class=\"new\"></ul>");
						$(".new").parent("li").children("span").before("<span class=\"handle-carfit opened-carfit\"></span>");
						$(".new").html(insert);
						$(".new").toggleClass('new old');
								 
					}
					 
					if ($("#"+id).
						parent("span").
						parent("li").
						children(".handle-carfit").hasClass("closed-carfit")) {
					  
						$("#"+id).parent("span").
								  parent("li").
								  children(".handle-carfit").
								  toggleClass('closed-carfit opened-carfit')
								  .nextAll('ul')
								  .toggle(300);;
					}
				
			  
			}
		}); 
	}

	//});
</script>