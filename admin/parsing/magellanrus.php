<?php
include 'constants.php';

include '../class/class_category.php';
$Category = new Category($folder);

$categs = $Category->getAllCategoryIdAndUrl();

include 'class/class_brand.php';
$Brand = new Brand($folder);

//$categs = $Category->getAllCategoryIdAndUrl();
$parents = $Category->getParents();
$brands = $Brand->getBrands();

    //header("Content-Type: text/html; charset=UTF-8");
    //echo "<pre>";  print_r(var_dump( $categs )); echo "</pre>";	
	?>
	<!--script src='js/jquery/jquery-1.8.3.js' type='text/javascript'></script-->
	<div class="wrapper">

		<div class="menuleft">
		<h3><a href='/admin/setup.php'>>> Настройки</a></h2>
		<h2>Парсинг сайта - http://magellanrus.ru/</h2>
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
	
<div class="form">
<table>
	<!--tr>
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
	</tr-->
	<tr>
		<td>
			Сюда URL <b>на товар</b> со magellanrus			
		</td>
		<td>
			<input type="text" class="sturminform" maxlength="100" size="20" />
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
					<option value="<?php echo $value['parent_inet_id']; ?>"><?php echo $value['name']. ' -> '.$value['seo_alias']; ?></option>
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
					<option value="<?php echo $value['tovar_parent_id']; ?>"><?php echo $value['tovar_parent_name']; ?></option>
			<?php } ?>
		</select>
		</td>
	</tr>
	
	
	<tr>
		<td colspan="4">Альтернативные поля (будут всталены вместо найденых)</td>
	</tr>
	<!--tr>
		<td><b>Бренд</b></td>
		<td colspan="3"><select class="brand">
					<option value="0">Выбрать бренд</option>
			<?php foreach($brands as $value){ ?>
					<option value="<?php echo $value['brand_id']; ?>"><?php echo $value['brand_name']; ?></option>
			<?php } ?>
		</select>
		</td>
	</tr-->
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


<!--div class="help">
	<b><font color="red">Для бренда 5,11 будет поиск цвета в названии!!!</font></b><br>
	<b>Найденый цвет добавит кодом в артикл.</b>
	<?php
		foreach($sturmuniform_511_color as $id => $name){
			
			echo '<br><b>' . $id . '</b> - ' . $name;
			
		}
	?>
	
</div-->
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
	
	function parce(key) {
        
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
			url: 'parsing/ajax_parsing_magellanrus.php',
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
