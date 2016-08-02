
<?php
set_time_limit(10000);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'class/class_product_edit.php';
include 'constants.php';

//define("GETCONTENTVIAPROXY", 1);
$mysqli = $folder;
$postav_id = 3; //ШтурмУниформ
$pars_key = 'sturmuniform_new';
$pars_table = 'tbl_parsing_sturmuniform_new';
$add_row_key = '';
$http = 'http://sturmuniform.ru/';
$http2 = 'http://www.sturmuniform.ru/';
$pausa = 10;
$currency = 1;
$kurs = 1;
$skidka = 0.7;
$nacenka = 1; //Наценка на розницу! не на закуп!!!
$brand_id = 2324; //Сплав
//$category_id = 407;
//define('UPLOAD_DIR', '/tiande-brovary.com/www/images/stories/virtuemart/product/');
//define('UPLOAD_DIR', '../../images/stories/virtuemart/product/');

//$nacenka110 = 1.10; //Наценка на розницу! не на закуп!!!
//$nacenka135 = 1.35; //Наценка на розницу! не на закуп!!!
//$nacenka110 = $nacenka110 * 1.04; //наценили еще 4%
//$nacenka135 = $nacenka135 * 1.04;

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


?>
<br><br>
<a href="?func=add_products&supplier=<?php echo $pars_key;?>&resetlinks">Обнулить сайтмап (заново)</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="?func=add_products&supplier=<?php echo $pars_key;?>&links"><b><font color="blue">Продолжить парсить</font></b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="?func=add_products&supplier=<?php echo $pars_key;?>&links&unset"><b><font color="orange">Пропустить товар</font></b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<?php

//просто парсим ссылки
if(isset($_GET['resetlinks'])){
	
	$sql = 'UPDATE '.$pars_table.' SET view = \'0\' WHERE view = "1";';
	$mysqli->query($sql) or die('==' . $sql);

}
		$sql = 'DELETE FROM '.$pars_table.' WHERE url LIKE \'%/en/catalog/%\' OR  url LIKE \'%/cz/catalog/%\' OR  url LIKE \'%/pl/catalog/%\';';
		$url = $mysqli->query($sql) or die('==' . $sql);
		
if(isset($_GET['links'])){
		include 'simple_html_dom/simple_html_dom.php';

		//тупо посчитаем все запись
		$sql = 'SELECT count(id) AS id FROM '.$pars_table.';';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$all = $tmp['id'];

		$sql = 'SELECT count(id) AS id FROM '.$pars_table.' WHERE view = \'0\';';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$none = $tmp['id'];
echo '<b>Всего ликов - '.$all.'. Пропарсено - '.($all - $none).'. Осталось - '.$none.'.</b>';
		
	   
	if(isset($_GET['unset'])){
		$sql = 'SELECT id FROM '.$pars_table.' WHERE view = \'0\' LIMIT 1;';
		$url = $mysqli->query($sql) or die('==' . $sql);
		$list = $url->fetch_assoc();
		$sql = 'UPDATE '.$pars_table.' SET view = \'2\' WHERE id = "'.$list['id'].'";';
		$url = $mysqli->query($sql) or die('==' . $sql);
		
		?>
			<script>
				function reload() {
					location.href() = '?func=add_products&supplier=<?php echo $pars_key; ?>&links';
				}
			</script>
		<?php
	}
	    
		$sql = 'SELECT * FROM '.$pars_table.' WHERE view = \'0\' LIMIT 1;';
		$url = $mysqli->query($sql) or die('==' . $sql);
	

	
	
	if($url->num_rows > 0){
				$list = $url->fetch_assoc();

echo ' <b>Урл ID - '.$list['id'].'. </b>'; 
			
			if(isset($_GET['url'])){
				$list['url'] = $_GET['url'];
			}
				$brand = '';
				$price = '';
				$view = '';
				//$size = '';
				
				//Если попадаются кривые линки!!!!!!!
				if(strpos($list['url'], $http)){
					$list['url'] = str_replace($http,'',$list['url']);
					
					$sql = 'UPDATE '.$pars_table.' SET `url` = \''.$list['url'].'\' WHERE `id` = \''.$list['id'].'\';';
					$mysqli->query($sql) or die('==' . $sql);
		
					echo '<h3 style="color:orange;">url - '.$list['url'].'</h3>';
				}else{
					echo '<h3 style="color:green;">url - '.$list['url'].'</h3>';
				}
	
				//Для тестов
//$list['url'] = 'http://tiande.ru/catalog/dlya_volos/sredstva_ne_trebuyuschie_smyvaniya/1252112/';
//$list['url'] = 'http://splav.ru/goodsdetail.aspx?gid=20150618152916331092';
//echo 	$list['url']; die();

				$html = file_get_html($list['url']);
				
				$html1 = file_get_contents($list['url']);
				
				$sql = 'UPDATE '.$pars_table.' SET view = "1" WHERE `url` = \''.$list['url'].'\';';
				//echo $sql; die();
				//$folder->query($sql) or die('==' . $sql);
		
				//Если это кривой линк - возможно удаленный товар
				if(!$html){
					?>
					<h3>Ошибка 404</h3>
						<script>
							$(document).ready(function(){
								<?php if($error == 0 OR $name == 'catalogue'){ 
									echo 'setTimeout(reload, '.$pausa.'000);';
								 } ?>
							}
							);
							
							function reload() {
								location.reload();
							}
						</script>
					<?php
					return false;
				}
				
				//Хлебная крошка
				$tmp = $html->find('.breadcrumb',0);
				if($tmp){
					$breadcrumbs_html = $tmp->innertext();
					$html_tmp = str_get_html($breadcrumbs_html);
					$breadcrumbs_html = str_replace('+', ' ', $html_tmp->find('li a'));
					
					$breadcrumbs = array();
					foreach($breadcrumbs_html as $tt){
						$tmp = str_replace('+', '_', $tt->innertext());
						$breadcrumbs[$tmp] = $tmp;
					}
					echo 'Крошки родителя ($breadcrumbs_txt => $category_id)-> <br><b>'.implode('>',$breadcrumbs).'</b><br>';
					$breadcrumbs_txt = implode('>',$breadcrumbs);
				}else{
					$breadcrumbs_txt = '';
				}
				
				$breadcrumbs_txt = htmlentities($breadcrumbs_txt);
				$breadcrumbs_txt = str_replace('&','',$breadcrumbs_txt);
				$breadcrumbs_txt = str_replace(';','',$breadcrumbs_txt);
				
				//Массив ссылок
				$str_tmp = $html->find('a');
				
				$artkl = '';
/* =================================================================================================================================
 * =================================================================================================================================
 * =================================================================================================================================
											//Это товар
 * =================================================================================================================================
 * =================================================================================================================================
 */

 
				$price_buff = 0;
				$all_goods = array();
				$artikles = array();
				//if($html->find('.product-text h1',0)){
				if(strpos($list['url'], 'goodsdetail') !== false){

					//Разбираем метатеги					
					
					$html2 = file_get_contents($list['url']);
					
					$tmp = explode('xmlRs', $html2);
					$gods_det = explode('good id', $tmp[1]);
					$gods_a = explode('article id', $tmp[1]);
					
					//Артиклы и Названия
					foreach($gods_a as $Text){
						$tt = explode('>', $Text);
						
						if(strpos($tt[0], 'version="1.0"') === false){
							$tt2 = explode('="', $tt[0]);
							
							$artkl = trim(trim(trim(trim($tt2[1]), 'name'), '"'));
							$artkl = trim(trim($artkl, '"'));
							$artikles[] = $artkl;
							$name = trim(trim(trim(trim($tt2[2]), 'img1number'), '"'));
							$name = str_replace('\\\'','"', $name);
							$name = trim(trim($name, '"'));
							$name = ltrim(ltrim($name, '"'));
							
							$all_goods[$artkl]['name'] = $name;
							$all_goods[$artkl]['artkl'] = 'SP_'.$artkl;
							
							$str_tmp1 = $html->find('#ac'.$artkl, 0);
							
							if($str_tmp1){
								$str_tmp1 = $str_tmp1->outertext();
								$str_tmp1 = str_get_html($str_tmp1);
								$rows = $str_tmp1->find('.tvExpandLeaf');
								foreach($rows as $row){
									
									$id = str_replace('gd','', trim($row->id));
									if(isset($all_goods[$artkl]['id'])){
										$all_goods[$artkl]['id'] .= '/*'.$id.'*/';
									}else{
										$all_goods[$artkl]['id'] = '/*'.$id.'*/';
									}
								}
								
							}
							
						}
					}

					//Детали по размерам цвету и цене
					$count = 0;
					foreach($gods_det as $Text){
						if(strpos($Text, 'version=') === false){
							
							$Text = str_replace('\\\'','@@@', $Text);
							$Text = str_replace("'",'"', $Text);
							
							//$Text = $good; //'"="20130318125953600129" name="Жилет Resolve Primaloft черный с капюшоном 40-42/158-164" price1="3200" price2="3200" f0="Y" sz="40-42/158-164" clr="черный"';
							if (preg_match_all('#\s+([^=\s]+)\s*=\s*((?(?="|\') (?:"|\')([^"\']+)(?:"|\') | ([^\s]+)))#isx', $Text, $matches)) {
								
							  if($matches[0][0] != '' AND $matches[0][0] != 'name'){
								
								if(isset($artikles[$count])) $artkl = $artikles[$count];
								$count++;
								//echo '<br>======='.$count.' '.$artkl;
								
								
								$tmp = explode('"', $Text);
								$id = $tmp[1];
								
								$name = trim(trim(str_replace('name=','',$matches[0][0]),'"'));
								$name = str_replace('@@@', '"', $name);
								$name = ltrim(ltrim($name, '"'));
							
								$size = '';
							
								foreach($matches[0] as $tmp){
									if(strpos($tmp,'sz=') !== false){
										$size = trim(trim(str_replace('sz=','',$tmp),'"'));
										$size = trim(trim($size,'"'));
										$name = trim($name);
										$name = trim($name, $size);
										$name = trim($name);
										
									break;
									}
								}
								
								$price = 0;
								foreach($matches[0] as $tmp){
									if(strpos($tmp,'price1=') !== false){
									$price = trim(trim(str_replace('price1=','',$tmp),'"'));
									$price = (int)trim(trim($price, '"'));
									break;
									}
								}
								
								
								$color = '';
								foreach($matches[0] as $tmp){
									if(strpos($tmp,'clr=') !== false){
										$color = trim(trim(str_replace('clr=','',$tmp),'"'));
										$color = trim(trim($color, '"'));
										break;
									}
								}
								
		//echo '<br>========================================='.$artkl.'============================='.$price;
									
								//Найдем товар
								foreach($all_goods as $index => $value){
									if(isset($value['id']) AND strpos($value['id'],$id) !== false){
										//$artkl = $index;
										if(strlen($value['name']) < strlen($name)){
											$all_goods[$index]['name'] = $name;
										}
										break;
									}
								}
								
								//Получим наличие
								$item_tmp = $html->find('#gd'.$id.' .tbtOnStore');
								$item_tovar = 0;
								foreach($item_tmp as $item){
									$tmp = $item->innertext();
									if((int)$tmp > 0){
										$item_tovar += $tmp;
									}
									if($tmp == 'много'){
										$item_tovar += 100;
									}
									//echo '<br>+++'.$item->innertext().'--'.$item_tovar;
								}
								//echo '<br>==='.$item_tovar;
							
								$all_goods[$artkl]['color'] = $color; 
								$all_goods[$artkl]['size'][$size]['size'] = $size;
								$all_goods[$artkl]['size'][$size]['price'] = (int)$price;
								$all_goods[$artkl]['size'][$size]['yes'] = true;
								$all_goods[$artkl]['size'][$size]['items'] = $item_tovar;
								if($price > 0)$price_buff = (int)$price;
								
								if(isset($all_goods[$artkl]['size'])){
									$size_a = $all_goods[$artkl]['size'];
								}
							
							
							  }
							}else{
								$all_goods[$artkl]['color'] = $color; 
								$all_goods[$artkl]['size'][$size]['size'] = $size;
								$all_goods[$artkl]['size'][$size]['price'] = (int)$price;
								$all_goods[$artkl]['size'][$size]['yes'] = true;
								$all_goods[$artkl]['size'][$size]['items'] = $item_tovar;
								if($price > 0)$price_buff = (int)$price;
							}
						}
					}

				//еще одна проверка product_d_price
				if(!isset($size_a)){
					//Получим наличие
					$item_tmp = $html->find('.tbtOnStore');
					$item_tovar = 0;
					foreach($item_tmp as $item){
						$tmp = $item->innertext();
						if((int)$tmp > 0){
							$item_tovar += $tmp;
						}
						//echo '<br>+++'.$item->innertext().'--'.$item_tovar;
					}
					
					$price = (int)$html->find('.product_d_price',0)->innertext();
					//echo $price; die();
					
					$size_a['size'] = '';
					$size_a['price'] = (int)$price;
					$size_a['yes'] = true;
					$size_a['items'] = $item_tovar;
					if($price > 0)$price_buff = (int)$price;
				}
				
				foreach($all_goods as $index => $value){
					if(!isset($value['size'])){
						if(isset($size_a['size'])){
							$all_goods[$index]['size'][''] = $size_a;
						}else{
							$all_goods[$index]['size'] = $size_a;		
						}
					
					}
				}
//echo "<pre>";  print_r(var_dump( $all_goods )); echo "</pre>";
//die();					

					echo '<br>Артикл -> <b><font color="green">';
						foreach($all_goods as $good){
							
							//Если завтык с Артиклами - Останавливаем парс
							if(isset($good['artkl'])){
								echo $good['artkl'].', ';
							}else{
								?>
								<h2>Несовпадение по Имя-Артикл. Паср остановлен!</h2>
								<?php
							}
						}
					echo '</font></b>';
					//============================================================================================
					
					echo '<br>Название -> <b><font color="red">';
						foreach($all_goods as $good){
							echo '<br>'.$good['name'].'';
						}
					echo '</font></b>';
					//============================================================================================
					echo '<br>Цвета -> <b><font color="blue">';
						foreach($all_goods as $good){
							if(isset($good['color'])){
								echo '<br>'.$good['color'].'';
							}
						}
					echo '</font></b>';
					
					//============================================================================================
					echo '<br>Размеров -> <b><font color="blue">';
						foreach($all_goods as $good){
							echo '<br>'.$good['artkl'].' => '.count($good['size']).'<br>';
						}
					echo '</font></b>';
					//============================================================================================
					
					$tmps = $html->find('.productDescriptionTab');
					$memo = '';
					if($tmps){
						foreach($tmps as $tmp){
							$memo .= $tmp->innertext();
						}
						$memo = trim($memo);
					}
	
					//echo '<br>Описание ($memo) -> '.$memo.'';
					//============================================================================================
				
				
					$image = array();
					$image_small = array();
					$image_title = array();
					$image_main_load = array();
					
					$t = $html->find('.productViewPhoto');
					
					foreach($t as $tt){
						
						$tmp = ''.$tt->src;
						$title = ''.$tt->title;
						$tmp_t = explode(' -', $title);
						if(isset($tmp_t[0]) AND strlen($tmp_t[0]) > 1){
							$title = $tmp_t[0];
						}
						
						$tmp = $http.''.$tmp;$tmp = str_replace('//', '/', $tmp);
								$tmp = str_replace('http:/', 'http://', $tmp);
						$image[] = $tmp;
						$image_small[] = $tmp;
						$image_title[] = $title;
						$title = str_replace('tobacco','коричневый',$title);
						
						$image_main_load[$title]  = strtolower($tmp);
						
					}
					
					//============================================================================================
					echo '<br>Основная картинка -> <b><font color="blue"><br>';
						foreach($image_main_load as $index => $img){
							echo ''.$index.' => <img width="150" src="'.$img.'">';
						}
					echo '</font></b>';
					//============================================================================================
					echo '<br>Общие картинки -> <b><font color="black"><br>';
					echo '<br>';
					foreach($image_small as $index => $img){
						if(is_string($img)){
							echo '<img width="150" src="'.$img.'">';	
							//echo ''.$image_title[$index];	
						}
					}
					echo '</font></b>';
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
					if(strpos($href, $http) === false AND strpos($href, $http2) === false){
						
						$href = $http.$href;
						$href = str_replace('//', '/', $href);
						$href = str_replace('//', '/', $href);
						$href = str_replace('https:/', 'https://', $href);
						$href = str_replace('http:/', 'http://', $href);
						
					
					}
					
						//Отсекаем мусор
						if(strpos($href, "'") === false){
							$sql = 'SELECT id FROM '.$pars_table.' WHERE url = \''.$href.'\';';
							$t = $mysqli->query($sql) or die('==' . $sql);
							
							if($t->num_rows == 0){
								if(strpos($href, 'catalog')!==false OR strpos($href, 'goodsdetail')!==false){
									$sql = 'INSERT INTO '.$pars_table.' SET
												 `url` = \''.$href.'\',
												 `key` = "'.$name.'",
												 `view` = \''.$view.'\',
												 `date` = \''.date('Y-m-d H:i:s').'\',
												 `breadcrumbs` = \'\';';
									$mysqli->query($sql) or die('==' . $sql);
									echo ''.$href.'<br>';
								}
							}else{
								//echo '<font color=red>'.$href.'</font><br>';
							}
							
						}
					}
					
	 
				//}
				
				
				$sql = 'UPDATE '.$pars_table.' SET view = "0",
												`breadcrumbs` = "'.str_replace('"',"'",$breadcrumbs_txt).'",
												`artkl` = "'.var_dump($artkl).'",
												`brand` = "'.$brand.'",
												`price` = "'.$price.'",
												`size` = ""
												
												WHERE url = \''.$list['url'].'\';';
				//echo $sql;
				//die();
				$mysqli->query($sql) or die('==' . $sql);
				
				//=============================================================
				//Опеределяем данные
				$error = 0;
				
				//Бренд
				/*
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
				}*/
				
				//Категория
				$sql = 'SELECT category_id FROM tbl_category_alternative WHERE breadcrumbs = "'.str_replace('"',"'",$breadcrumbs_txt).'" AND postav_id = "'.$postav_id.'";';
//echo $sql;
				$br = $folder->query($sql) or die('==' . $sql);
				if($br->num_rows > 0){
					$tmp = $br->fetch_assoc();
					$category_id = $tmp['category_id'];
				}else{
					$error = 1;
				}

		
//echo "<pre>".$br->num_rows;  print_r(var_dump( $tmp )); echo "</pre>";
//echo $category_id; die();
				
		if( $name != 'category' and $error == 1){
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
		//============================================================================================================
		//============================== З А П И С Ь =================================================================
//echo "<pre>";  print_r(var_dump( $all_goods )); echo "</pre>";		
		//============================================================================================================
		//============================================================================================================
		
	
		if($name != 'category'){	
		
			if($error == 1 ){
				echo '<h2>НЕ ХВАТАЕТ ДАННЫХ!</h2></div>';
				$view = 0;
			}else{
					
				foreach($all_goods as $good){
		
					if(!isset($good['artkl'])) continue;
						if(!isset($good['name'])) continue;
					
					$name = $good['name'];
					$artkl = $good['artkl'];
					if(isset($good['color']) AND $good['color'] != ''){
						$color_name = $good['color'];
						$color_id = getColodInOnColorName($good['color'], $folder);
						if($color_id == '') unset($color_id);
					}
					$size = $good['size'];
					
				
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
						
						//Наценка на розницу! не на закуп!!!
						if($siz['price'] == 0)$siz['price'] = (int)$price_buff;
						$_zakup = (int)($siz['price'] * $kurs) * $skidka ;
						$_price = (int)($siz['price'] * $kurs) * $nacenka;
						$_items = '0';
						if($siz['yes'] == true){
							$_items = '10';
						}else
						if(isset($siz['items'])){
							$_items = $siz['items'];
						}
						
						
						
						$product_ids = $ProductEdit->getProductIdOnArtiklAndSupplier($str_artkl);
				
						if($product_ids AND count($product_ids) > 0){
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
						//echo $sql;
						//die();
						$folder->query($sql) or die('Остатки - '.$sql);
							
						//Загрузим фото - только если у товара нет фото
						include_once ('import/import_url_getfile.php');
						$noload = true;
						if(!file_exists(UPLOAD_DIR.$model.'/'.$model.'.0.small.jpg')) {
							
							$direct_load = true;
							$str_artkl = $model;
								
							//Если это фотка по цвету - загрузим ее первой	
							if(isset($color_name) AND isset($image_main_load[strtolower($color_name)])){
								$IMGPath = $image_main_load[$color_name];
								include 'import/import_url_photo.php';
							}
							if(isset($image)){
						
								foreach($image as $str_img){
									$IMGPath = $str_img;
									$str_artkl = $model;
									//Пропустим фото которое залетело первым по цвету
									//if(!isset($color_name) OR (isset($color_name) AND !isset($image_main_load[strtolower($color_name)]))){
										include 'import/import_url_photo.php';	
									//}

								}
							}else{
								echo '<br>Нет фото';    
							}
						
						}else{
							   //include '../import/import_url_getfile.php';
						}
					
						//Вот теперь когда все сделали - поставим этому линку статус вью 1
						$sql = 'UPDATE '.$pars_table.' SET `view` = \'1\' WHERE `url` = \''.$list['url'].'\';';
						$folder->query($sql) or die('==' . $sql);
						
					
					}
				}
			
			}	
		
			}else{
				
				//Если это была категория
				//Вот теперь когда все сделали - поставим этому линку статус вью 1
				$sql = 'UPDATE '.$pars_table.' SET `view` = \'1\' WHERE `url` = \''.$list['url'].'\';';
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
		console.log('11111');
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
					var breadcrumbs = "<?php echo str_replace('"',"'",$breadcrumbs_txt); ?>";
					
					if(confirm('Назначить '+$('#'+id).html()+' ?')){

						$.ajax({
							type: "POST",
							url: "parsing/edit_alternative.php",
							dataType: "text",
							data: "category_id="+category_id+"&postav_id="+postav_id+"&breadcrumbs="+breadcrumbs+"&key=add_category",
							beforeSend: function(){
							},
							success: function(msg){
								console.log( msg );
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
<?php
	function getColodInOnColorName($color_name, $folder){
		
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
			$up_name = mb_strtoupper(addslashes($color_name),'UTF-8');
			foreach($colors as $color => $id){
				if(!empty($color)){
					if(strpos($up_name, mb_strtoupper(addslashes($color),'UTF-8')) !== false){
						
						return $id;
						break;
					}
				}
			}
			
			return '';
	}

?>