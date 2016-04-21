<?php

// Загрузка штампа и фото, для которого применяется водяной знак (называется штамп или печать)
$stamp = imagecreatefrompng('vod_Znak.png');
$im = imagecreatefromjpeg('1large.jpg');
 
// Установка полей для штампа и получение высоты/ширины штампа
$marge_right = 10;
$marge_bottom = 10;
$sx = imagesx($stamp);
$sy = imagesy($stamp);
// Копирование изображения штампа на фотографию с помощью смещения края
// и ширины фотографии для расчета позиционирования штампа. 
imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, 
imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), 
imagesy($stamp));
 
// Вывод и освобождение памяти
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);


header("Content-Type: text/html; charset=UTF-8");
echo "<pre>";  print_r(var_dump( $_GET )); echo "</pre>";
?>

<title>Слайдер витрины</title>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<link href="/admin/css/main_menu.css" rel="stylesheet" type="text/css">
<?php

		$period = array(
				'1' => '1 час',
				'10' => '10 часов',
				'24' => '1 день',
				'120' => '5 дней',
				'148' => '1 неделя',
				'720' => '1 месяц',
				);
		
		$sql = 'SELECT * FROM showcase ORDER BY sort ASC;';
        //echo $sql;            
		$rm = $folder->query($sql) or die('Ошибка в меню ' . mysqli_error());
?>
		<table>
				<tr>
					<th>id</th>
					<th>Название</th>
					<th>Name</th>
					<th>Nume</th>
					<th>к-во</th>
					<th>Период</th>
					<th>Продукты</th>
					<th>Сорт</th>
					<th>*</th>
				</tr>
		
				<tr id="new">
                    <td align="left">*</td>
                    <td align="center"><input type="text"   id="name"  style="width:150px;" value='' placeholder="Название"></td>
                    <td align="center"><input type="text"   id="name_en"  style="width:150px;" value='' placeholder="Name"></td>
                    <td align="center"><input type="text"   id="name_rm"  style="width:150px;" value='' placeholder="Nume"></td>
                    <td align="center"><input type="text"   id="limit" style="width:50px;" value='' placeholder="12"></td>
				    <td align="center">
						<select type="text" id="period" style="width:100px;">
							<option value="0">Выбрать период</option>
							<?php foreach($period as $id => $value){ ?>
								<option value="<?php echo $id; ?>"><?php echo $value; ?></option>
							<?php } ?>
						</select>
					</td>
					<td align="center"><input type="text"   id="products" style="width:50px;" value='' placeholder="0"></td>
					<td align="center"><input type="text"   id="sort" style="width:50px;" value='' placeholder="0"></td>
					<td align="center"><a href='javascript:' id="add" class="add"><b>Добавить</b></a></td>
                </tr>

                <tr>
                    <td colspan="8" style="color: red;">Изменение моментальное!  <span class="msg"></span></td>
                </tr>
            
                <?php while($tmp = $rm->fetch_assoc()){ ?>
                    <tr id="<?php echo $tmp['id']; ?>">
                        <td align="left"><input type="text" disabled class="id" value='<?php echo $tmp['id']; ?>' style="width:50px;" ></td>
				        <td align="center"><input type="text" class="edit" id="name<?php echo $tmp['id']; ?>" style="width:150px;" value='<?php echo $tmp['name']; ?>'></td>
                        <td align="center"><input type="text" class="edit" id="name_en<?php echo $tmp['id']; ?>"  style="width:150px;" value='<?php echo $tmp['name_en']; ?>'></td>
                        <td align="center"><input type="text" class="edit" id="name_rm<?php echo $tmp['id']; ?>" style="width:150px;" value='<?php echo $tmp['name_rm']; ?>'></td>
						<td align="center"><input type="text" class="edit" id="limit<?php echo $tmp['id']; ?>" style="width:50px;" value='<?php echo $tmp['limit']; ?>'></td>
						<td align="left">
							<select type="text" class="edit" id="period<?php echo $tmp['id']; ?>" style="width:100px;">
							<option value="0">Выбрать период</option>
							<?php foreach($period as $id => $value){ ?>
									<?php if($tmp['period'] == $id){?>
											<option value="<?php echo $id; ?>" selected><?php echo $value; ?></option>
									<?php }else{ ?>
											<option value="<?php echo $id; ?>"><?php echo $value; ?></option>
									<?php } ?>
							<?php } ?>
							</select>
						</td>
						<td align="center"><input type="text" class="edit" id="products<?php echo $tmp['id']; ?>" style="width:50px;" value='<?php echo $tmp['products']; ?>'></td>
						<td align="center"><input type="text" class="edit" id="sort<?php echo $tmp['id']; ?>" style="width:50px;" value='<?php echo $tmp['sort']; ?>'></td>
						<td align="center"><a href='javascript:' id="<?php echo $tmp['id']; ?>" class="dell"><b>X</b></a></td>
                    </tr>
                <?php } ?>                
            </table>    
				
		<?php } ?>
		</table>
		
		   <script>
    	//Сохранение элемента
        $(document).on('change', '.edit', function(){
        var elem = $(this);
        var target = elem.parent('td').parent('tr').attr('id');
        
        var name = $('#name'+target).val();
        var name_en = $('#name_en'+target).val();
        var name_rm = $('#name_rm'+target).val();
        var limit = $('#limit'+target).val();
        var period = $('#period'+target).val();
        var products = $('#products'+target).val();
        var sort = $('#sort'+target).val();
        
        //console.log("id="+target+"&filter="+filter+"&disable="+disable+"&sort="+sort);
        
        $.ajax({
            type: "GET",
            url: "ajax/ajax_save_showcase.php",
            dataType: "text",
            data: "id="+target+"&group="+group+"&name="+name+"&alias="+alias+"&file="+file+"&dostup="+dostup+"&sort="+sort+"&key=edit",
            beforeSend: function(){
            },
            success: function(msg){
                
                $('.msg').html(msg);
                $('.msg').show();
                $('.msg').hide(1000);
                
                console.log( msg );
            }
        });
        
    });
        
    $(document).on('click', '#add', function(){
        var name = $('#name').val();
        var name_en = $('#name_en').val();
        var name_rm = $('#name_rm').val();
        var limit = $('#limit').val();
        var period = $('#period').val();
        var products = $('#products').val();
        var sort = $('#sort').val();
   
		$.ajax({
			type: "GET",
			url: "ajax/ajax_save_showcase.php",
			dataType: "text",
			data: "group="+group+"&name="+name+"&alias="+alias+"&file="+file+"&dostup="+dostup+"&sort="+sort+"&key=add",
			beforeSend: function(){
			},
			success: function(msg){
				location.reload();
				console.log( msg );
			}
		});
	});
    
    $(document).on('click', '.dell', function(){
        var elem = $(this);
        var target = elem.parent('td').parent('tr').attr('id');
         
        //console.log("id="+target+"&filter="+filter+"&disable="+disable+"&sort="+sort);
        
        $.ajax({
            type: "GET",
            url: "ajax/ajax_save_showcase.php",
            dataType: "text",
            data: "id="+target+"&key=dell",
            beforeSend: function(){
            },
            success: function(msg){
                $('#'+target).hide(1000);
                
                $('.msg').html(msg);
                $('.msg').show();
                $('.msg').hide(1000);
                
                console.log( msg );
            }
        });
        
    });
    
    
   </script>
		<style>
			 .msg{
					 color: #0e7100;
					 display: none;
				 }
		</style>
			                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            