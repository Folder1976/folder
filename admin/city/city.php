<?php
		//Если прилетело добавление - добавляем
		if(isset($_POST['key']) AND $_POST['key'] == 'add'){
			$name		= htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
			$translite	= htmlspecialchars($_POST['translite'], ENT_QUOTES, 'UTF-8');
			$kuda_name	= htmlspecialchars($_POST['kuda_name'], ENT_QUOTES, 'UTF-8');
			$gde_name	= htmlspecialchars($_POST['gde_name'], ENT_QUOTES, 'UTF-8');
			$days		= htmlspecialchars($_POST['days'], ENT_QUOTES, 'UTF-8');
			$phone		= htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
	
			if($name != '' AND $translite != ''){
				//if(isset($_POST['select_menu']) AND $_POST['select_menu'] == 'editbody'){
					$sql = "INSERT INTO tbl_citys SET
							CityTranslite='$translite',
							CityLable='$name',
							KudaLable='$kuda_name',
							GdeLable='$gde_name',
							DeliveryDays='$days',
							Localphone='$phone';";
				//}
				
				$r1 = $folder->query($sql) or die ("Добавление города :(");
				
				header('Location: main.php?func=city');
			}
			
		}


?>
	
	<!--script src='js/jquery/jquery-1.8.3.js' type='text/javascript'></script-->
	<div class="wrapper">

		<div class="menuleft">
		<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h2>
		<h2>Города и доставка</h2>
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
	</style>
	<div class = "msg">Сохраняю</div>
		
	
<?php  //Зачитаем таблицу
		$all = array();
		$r = $folder->query("SELECT *
				FROM tbl_citys
				ORDER BY CityLable ASC;") or die ("Города :(");
		while($tCS = $r->fetch_assoc()) {
			$all[$tCS["CityID"]] = $tCS;
		} ?>
	
	
	<font color="red">!!!- Редактирование и удалени по аяксу - моментальное!</font></p>
		<table border="1" cellspacing="0" cellpadding="3" align="center">
		<tr bgcolor="silver" align="center">
			<th><b>ID</b></th>
			<th><b>Транслит*</b></th>
			<th><b>Название*</b></th>
			<th><b>Назв. Куда?</b></th>
			<th><b>Назв. Где?</b></th>
			<th><b>Телефон</b></th>
			<th><b>Доставка дн.</b></th>
			<th><b></b></th>
		</tr>

		<form method="POST" action="main.php?func=city">
		<tr bgcolor="yellow">
			<td>&nbsp;
			<input type="hidden" name="key" value="add" /></td>
			<td><input type="text" name="translite" maxlength="100" size="20" /></td>
			<td><input type="text" name="name" maxlength="100" size="20" /></td>
			<td><input type="text" name="kuda_name" maxlength="100" size="20" /></td>
			<td><input type="text" name="gde_name" maxlength="100" size="20" /></td>
			<td><input type="text" name="phone" maxlength="100" size="10" /></td>
			<td><input type="text" name="days" maxlength="100" size="5" /></td>
			<td><input class="add" type="submit" value="Add" /></td>
		</tr>
		</form>
		
	<?php if(count($all) == 0) {
		   echo '<tr><td colspan="6" align="center">Нет данных</td></tr>';
		}else{
			$i = 1;
			foreach($all as $Edit) {
				$i = $Edit["CityID"];
				echo '<tr id="'.$Edit["CityID"].'">
					<td align="center">'.$Edit["CityID"].'</td>
					<input type=hidden class="field"  id="id'.$i.'" value="'.$Edit["CityID"].'">
					<td><input type="text" class="field" id="translite'.$i.'" maxlength="100" size="20" value="'.$Edit["CityTranslite"].'"/></td>
					<td><input type="text" class="field" id="name'.$i.'" maxlength="100" size="20" value="'.$Edit["CityLable"].'"/></td>
					<td><input type="text" class="field" id="kuda_name'.$i.'" maxlength="100" size="20" value="'.$Edit["KudaLable"].'"/></td>
					<td><input type="text" class="field" id="gde_name'.$i.'" maxlength="100" size="20" value="'.$Edit["GdeLable"].'"/></td>
					<td><input type="text" class="field" id="phone'.$i.'" maxlength="100" size="10" value="'.$Edit["Localphone"].'"/></td>
					<td><input type="text" class="field" id="days'.$i.'" maxlength="100" size="5" value="'.$Edit["DeliveryDays"].'" /></td>
					<td><a href="javascript:" class="dell" id="'.$Edit["CityID"].'">Удалить</a></td>
				</tr>';
				$i += 1;
			}
		}
		echo '</table>';?>
			<script>
				$(document).on('change','.field', function(){
					
					var id = $(this).parent('td').parent('tr').attr('id');
					var translite = $('#translite'+id).val();
					var name = $('#name'+id).val();
					var kuda_name = $('#kuda_name'+id).val();
					var gde_name = $('#gde_name'+id).val();
					var phone = $('#phone'+id).val();
					var days = $('#days'+id).val();
					var key = 'edit';
					
					$.ajax({
						url: 'city/ajax-save-city.php',
						dataType: 'text',
						data: 'id='+id+'&translite='+translite+'&name='+name+'&kuda_name='+kuda_name+'&gde_name='+gde_name+'&phone='+phone+'&days='+days+'&'+key,
						beforeSend: function(){
							$('.msg').css('display','block');
						},
						success: function(json){
							$('.msg').css('display','none');
							//console.log(json);
						}
					});
					
				});
				
				$(document).on('click','.dell', function(){
					console.log($(this).parent('td').parent('tr').attr('id'));
					
					var id = $(this).parent('td').parent('tr').attr('id');
					var key = 'dell';
					
					if(confirm('Удалить?')){ 
						$.ajax({
							url: 'city/ajax-save-city.php',
							dataType: 'text',
							data: 'id='+id+'&'+key,
							beforeSend: function(){
								$('.msg').css('display','block');
							},
							success: function(json){
								$('.msg').css('display','none');
								//console.log(json);
								$('#'+id).remove();
							}
						});
					}
				});
				
				
			</script>
