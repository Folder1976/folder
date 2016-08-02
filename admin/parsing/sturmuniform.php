<?php
include 'constants.php';

include '../class/class_category.php';
$Category = new Category($folder);

$categs = $Category->getAllCategoryIdAndUrl();

include 'class/class_brand.php';
$Brand = new Brand($folder);
$mysqli = $folder;
//$categs = $Category->getAllCategoryIdAndUrl();
$parents = $Category->getParents();
$brands = $Brand->getBrands();

	?>
	<!--script src='js/jquery/jquery-1.8.3.js' type='text/javascript'></script-->
	<div class="wrapper">

		<div class="menuleft">
		<h3><a href='/admin/setup.php'>>> Настройки</a></h2>
		<h2>Парсинг сайта - http://sturmuniform.ru/</h2>
		</div>
	</div>
	
	<style>
		.msg{
			background-color: #FFE4B5;
			width: 250px;
			height: 50px;
			position: absolute;
			text-align: center;
			padding-top: 20px;
			font-size: 20px;
			top: 40%;
			border: 2px solid red;
			display: none;
			left: 50%;
			margin: -125px 0 0 -125px;
		}
		.form{
			float: left;
		}
		.help{
			margin-left: 10px;
			margin-top: -50px;
			float: left;
		}
	</style>
<?php
$pars_table = 'tbl_parsing_sturmuniform';
$postav_id = 3; //STURMUNIFORM
$pausa = 2;
?>
<a href="/admin/main.php?func=add_products&supplier=sturmuniform&scan" class="key_a">Сканировать на новые товары</a>
<a href="/admin/main.php?func=add_products&supplier=sturmuniform&parsing" class="key_a">Парсить новые товары</a>
<a href="/admin/main.php?func=add_products&supplier=sturmuniform" class="key_a">Остановить</a>
	<style>
		.key_a{
			padding: 10px;
			margin: 10px;
			background-color: #E0CECE;
			border: 1px solid gray;
			margin-bottom: 30px;
		}
	</style>
<?php
//========АВТОПАРС===================================================================================================================
if(isset($_GET['parsing'])){
	
		//тупо посчитаем все запись
		$sql = 'SELECT count(id) AS id FROM '.$pars_table.' WHERE artkl = \'yes\' AND breadcrumbs = "product";';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$products = $tmp['id'];
		
		$sql = 'SELECT count(id) AS id FROM '.$pars_table.' WHERE breadcrumbs = "product";';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$products_all = $tmp['id'];
		echo '<br><br><br><b>Всего продуктов - '.$products_all.'. Новых - '.($products_all - $products).'</b><br>';
	
		if($products_all - $products == 0){
			echo '<br>Сканирование завершено';
			return false;
		}
	
	
	
		$sql = 'SELECT url FROM '.$pars_table.' WHERE breadcrumbs = "product" AND view <= "1" AND artkl = "" LIMIT 0,1;';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$list = $tmp->fetch_assoc();
	
	
		$sql = 'UPDATE '.$pars_table.' SET
					`view` = "2",
					`artkl` = "yes"
					WHERE url = \''.$list['url'].'\';';			
		$mysqli->query($sql) or die('==' . $sql);
		?>
		<script>
			$(document).ready(function(){
				setTimeout(parce, 5000);
				 });
		</script>
		<?php
	
}
//========SCAN===================================================================================================================
if(isset($_GET['scan'])){
		
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
	
		$sql = 'SELECT count(id) AS id FROM '.$pars_table.' WHERE artkl = \'yes\' AND breadcrumbs = "product";';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$products = $tmp['id'];
		
		$sql = 'SELECT count(id) AS id FROM '.$pars_table.' WHERE breadcrumbs = "product";';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$products_all = $tmp['id'];
		echo '<br><br><br><b>Всего ликов - '.$all.'. Пропарсено - '.($all - $none).'. Осталось - '.$none.'.</b><br>';
		echo '<br><b>Всего продуктов - '.$products_all.'. Новых - '.($products_all - $products).'</b><br>';
		
		$sql = 'SELECT url FROM '.$pars_table.' WHERE artkl <> \'yes\' AND breadcrumbs = "product";';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		while($tmp1 = $tmp->fetch_assoc()){
			echo '<br>'.$tmp1['url'];
		}
		
		if($none == 0){
			echo '<br>Сканирование завершено';
			return false;
		}
	
		$sql = 'SELECT * FROM '.$pars_table.' WHERE view = \'0\' LIMIT 1;';
		$url = $mysqli->query($sql) or die('==' . $sql);
		
		
		$list = $url->fetch_assoc();
		
		$html = @file_get_html($list['url']);
		$catalog = 'catalog';
		$artkl = '';
		
		if($html){
				$t = $html->find('.ProductInfoLeft');
				if($t){
					$catalog = 'product';
				}
		
				$t = $html->find('a');
				foreach($t as $option){
					$href = $option->href;
					$name = $option->innertext();
					
					if(strpos($href, 'sturm') !== false
						AND strpos($href, '@') === false
						AND strpos($href, 'javas') === false
						AND strpos($href, 'action') === false
						AND strpos($href, 'images') === false
					  	AND strpos($href, 'sort=') === false
					   ){
						$sql = 'SELECT id FROM '.$pars_table.' WHERE url = \''.$href.'\';';
						$t = $mysqli->query($sql) or die('==' . $sql);
						
						if($t->num_rows == 0){
								$sql = 'INSERT INTO '.$pars_table.' SET
											 `url` = \''.$href.'\',
											 `key` = "'.strip_tags(str_replace('"', "",$name)).'",
											 `view` = \'0\',
											 `date` = \''.date('Y-m-d H:i:s').'\',
											 `breadcrumbs` = \'\';';
								$mysqli->query($sql) or die('==' . $sql);
								echo '<br>'.$href.'';
						}							
						
					}
				}
				
				$sql = 'SELECT * FROM tbl_tovar_links WHERE url = \''.$list['url'].'\'  LIMIT 1;';
				echo '<br>'.$sql;
				$r = $mysqli->query($sql) or die('==' . $sql);
			
				if($r->num_rows > 0){
					$artkl = 'yes';
				}
				echo '<br>'.$artkl.'<br>';
		}
		
		$sql = 'UPDATE '.$pars_table.' SET
					`breadcrumbs` = "'.$catalog.'",
					`view` = "1",
					`artkl` = "'.$artkl.'"
					WHERE url = \''.$list['url'].'\';';			
		$mysqli->query($sql) or die('==' . $sql);
		
		?>
		<script>
			$(document).ready(function(){
				setTimeout(reload, <?php echo $pausa; ?>000);
				 });
			
			function reload() {
				location.reload();
			}
		</script>
		
	<?php
	return true;
}
//===========================================================================================================================

?>
<div class="form">
<br><br>		
<table style="">
	<tr>
		<td width="400">
			Сюда URL <b>на список товаров</b> со STURMINFORM<br>(!!! Правильно делай выборку! Весь товар из этого списка попадет в одну категорию под один бренд)			
		</td>
		<td>
			<input type="text" class="sturminform_list" maxlength="100" size="20" />
		</td>
		<td>
			<a href="javascript:" class="sturminformreload">перечитать</a>
		</td>
		<td class="sturminforinfo">
			
		</td>
	</tr>
	<tr>
		<td>
			Сюда URL <b>на товар</b> со STURMINFORM			
		</td>
		<td>
			<input type="text" class="sturminform" maxlength="100" size="20" <?php if(isset($list['url'])) echo 'value="'.$list['url'].'"';?>/>
		</td>
		<td>
			<a href="javascript:" class="sturminformreload">перечитать</a>
		</td>
		<td class="sturminforinfo">
			
		</td>
	</tr>
	
	<tr>
		<td><b>Категория на сайте</b></td>
		<td colspan="3"><select class="category">
					<option value="0">Выбрать категорию для добавления</option>
			<?php foreach($categs as $value){ ?>
				<?php if($value['parent_inet_id'] > 0){ ?>
					<?php if(isset($list['url']) AND $value['parent_inet_id'] = 457) {?>
                        <option value="<?php echo $value['parent_inet_id']; ?>" selected><?php echo $value['name']. ' -> '.$value['seo_alias']; ?></option>
                    <?php }else{ ?>
						<option value="<?php echo $value['parent_inet_id']; ?>"><?php echo $value['name']. ' -> '.$value['seo_alias']; ?></option>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</select>
		</td>
	</tr>
	
	<tr>
		<td><b>Внутренняя папка</b></td>
		<td colspan="3"><select class="parent">
					<option value="0">Внутренняя папка</option>
			<?php foreach($parents as $value){ ?>
					<option value="<?php echo $value['tovar_parent_id']; ?>" <?php if($value['tovar_parent_id'] == 2) echo 'selected'; ?>><?php echo $value['tovar_parent_name']; ?></option>
			<?php } ?>
		</select>
		</td>
	</tr>
	
	
	<tr>
		<td colspan="4">Альтернативные поля (будут всталены вместо найденых)</td>
	</tr>
	<tr>
		<td><b>Бренд</b></td>
		<td colspan="3"><select class="brand">
					<option value="0">Выбрать бренд</option>
			<?php foreach($brands as $value){ ?>
					<option value="<?php echo $value['brand_id']; ?>"><?php echo $value['brand_name']; ?></option>
			<?php } ?>
		</select>
		</td>
	</tr>
	<tr>
		<td>Артикл</td>
		<td colspan="3"><input type="text" class="artkl" value="" placeholder="Артикул"></td>
	</tr>
	<tr>
		<td>Название</td>
		<td colspan="3"><input type="text" class="name" value="" placeholder="Название"></td>
	</tr>
	<tr>
		<td>Цена</td>
		<td colspan="3"><input type="text" class="price" value="" placeholder="Цена"></td>
	</tr>
	<tr>
		<td>Картинка url</td>
		<td colspan="3"><input type="text" class="image" value="" placeholder="Картинка"></td>
	</tr>
	<tr>
		<td>Размеры (в строку, без пробелов, разделитель *)</td>
		<td colspan="3"><input type="text" class="size" value="" placeholder="S*M*L*XL*2XL"></td>
	</tr>
	<tr>
		<td>Описание</td>
		<td colspan="3"><input type="text" class="memo" value="" placeholder="Описание"></td>
	</tr>
</table>
</div>


<div class="help">
	<b><font color="red">Для бренда 5,11 будет поиск цвета в названии!!!</font></b><br>
	<b>Найденый цвет добавит кодом в артикл.</b>
	<?php
		foreach($sturmuniform_511_color as $id => $name){
			
			echo '<br><b>' . $id . '</b> - ' . $name;
			
		}
	?>
	
</div>
<div style="clear: both;"></div>
<div class="result">
	
</div>
<script>
 
	$(document).on('click', '.sturminformreload', function(){
		//$('.sturminform').trigger('change');
		parce();
	});
	
	$(document).on('change','.sturminform', function(){
	});
	
	function parce() {
        
		var list = $('.sturminform_list').val();
		var url = $('.sturminform').val();
		var artkl = $('.artkl').val();
		var name = $('.name').val();
		var price = $('.price').val();
		var image = $('.image').val();
		var size = $('.size').val();
		var memo = $('.memo').val();
		var category = $('.category').val();
		var brand = $('.brand').val();
		var parent = $('.parent').val();
			
		$.ajax({
			url: 'parsing/ajax_parsing_sturminfo.php',
			type: "POST",
			dataType: 'text',
			data: 'url='+url+'&list='+list+'&artkl='+artkl+'&name='+name+'&price='+price+'&parent='+parent+'&image='+image+'&size='+size+'&category='+category+'&brand='+brand+'&memo='+memo,
			beforeSend: function(){
				$('.sturminforinfo').html('Читаю страницу');
			},
			success: function(json){
				$('.sturminforinfo').html('Прочитано');
				$('.result').html(json);
				console.log(json);
				<?php if(isset($_GET['parsing'])){ ?>
					//location.href = '/admin/main.php?func=add_products&supplier=sturmuniform&parsing';
					setTimeout(function(){location.reload();}, 10000);
					
				<?php } ?>
			}
		});
		
	}
	
</script>
<style>
	input{
		width: 400px;
	}
	select{
		width: 400px;
	}
</style>
