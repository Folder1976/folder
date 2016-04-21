<?php
$type = 'large';
if(isset($_GET['type'])){
  $type = $_GET['type'];
}

connect_to_mysql();

if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';

echo "\n<script src='../js/jquery-2.1.4.min.js'></script>";

// Если прилетела форма - сохраним===============================
if(isset($_POST['key']) AND $_POST['key'] == 'add'){
     
	  $is_view = 0;
	  if(isset($_POST['is_view'])) $is_view = '1';
	  
	  //Если это код 0 - новый
	    $sql = 'INSERT INTO tbl_baner SET
				  baner_name = "'.$_POST['name'].'",
		    	  baner_header = "'.$_POST['header'].'",
				  baner_title = "'.$_POST['title'].'",
				  baner_url = "'.$_POST['url'].'",
				  baner_price = "'.$_POST['price'].'",
				  baner_slogan = "'.$_POST['slogan'].'",
				  baner_type = "large",
				  is_view = "'.$is_view.'"
			    ;';

	    $r = $folder->query($sql);
		
	echo "<H2>Добавлено!</H2><SCRIPT>\n\nvar i = setTimeout(\"window.location.href='main.php?func=banner&type=large'\", 500);\n</SCRIPT>";
}
//===============================
?>
<style>
 .table tr td {
	border: 1px solid gray;
	margin: 0;
	border-spacing: 0;
	border-collapse: collapse;
	padding: 10px 5px 10px 5px;
 }
 .table tr th {
	border: 1px solid gray;
	margin: 0;
	border-spacing: 0;
	border-collapse: collapse;
 }
 .table {
	border: 1px solid gray;
	margin: 0;
	border-spacing: 0;
	border-collapse: collapse;
 }

</style>
<?php

$sql = 'SELECT * FROM tbl_baner WHERE baner_type="'.$type.'" ORDER BY is_view DESC, baner_name ASC;';
$r = $folder->query($sql);

  echo '
	<table class="table">';
  echo '<tr></td></tr>';

  echo '
      <tr>
      <th>#</th>
      <th> + + + </th>
      <th>Показывать</th>
      <th>Тексты</th>
      <th colspan="2"></th>
      </tr>';
      
      //Новая позиция
    $place = array(
                   'catalog1' => 'Категория первого уровня',
                   'catalog2' => 'Категория второго уровня',
                   'news' => 'Новый товар',
                   'find' => 'Поиск'
                   );  
      
      
  echo '<tr><form method=post>
      <td>новый<input type="hidden" name="id0" value=""></td>
      <td><img src="reklama/img/large_banner_help.jpg" width="300"></td>
      <td><input type="checkbox" name="is_view" class="brand" data-id="0"></td>
      <td>
		<br>-</b> : <input type="text" name="name" class="brand" data-id="0" style="width:400px;" value="" placeholder="Имя - для памятки">
		<br>url</b> : <input type="text" id="url" class="brand" data-id="0" style="width:400px;" value="" placeholder="URL">
		<br>1</b> : <input type="text" name="header" class="brand" data-id="0" style="width:400px;" value="" placeholder="Заголовок банера">
		<br>2</b> : <input type="text" name="title" class="brand" data-id="0" style="width:400px;" value="" placeholder="Текст">
		<br>3</b> : <input type="text" name="price" class="brand" data-id="0" style="width:400px;" value="" placeholder="Прайс">
		<br>4</b> : <input type="text" name="slogan" class="brand" data-id="0" style="width:400px;" value="" placeholder="Подпись">
	  </td>
      <td colspan="1"><input type="submit" name="key" value="add" style="width:50px;"></td>
	  <td colspan="2">Фото после добавляния</td>
      </tr></form>';
      
while($value = $r->fetch_assoc()){
  //UPLOAD_DIR
  echo '<tr class="row_'.$value['baner_id'].'">
      <td>'.$value['baner_id'].'</td>
	  <td><img src="../resources/banners/mainpage/'.$value['baner_pic'].'" width="300">
	  </td>
	  <td><input type="checkbox" id="is_view'.$value['baner_id'].'" class="brand edit" data-id="'.$value['baner_id'].'" ';
		if($value['is_view'] == 1) echo ' checked ';
	  echo ' ></td>
      <td>
		  <br>+</b> : <input type="text" id="name'.$value['baner_id'].'" class="brand edit" data-id="'.$value['baner_id'].'" style="width:400px;" value="'.$value['baner_name'].'">
		  <br>url</b> : <input type="text" id="url'.$value['baner_id'].'" class="brand edit" data-id="'.$value['baner_id'].'" style="width:400px;" value="'.$value['baner_url'].'">
		  <br>1</b> : <input type="text" id="header'.$value['baner_id'].'" class="brand edit" data-id="'.$value['baner_id'].'" style="width:400px;" value="'.$value['baner_header'].'">
		  <br>2</b> : <input type="text" id="title'.$value['baner_id'].'" class="brand edit" data-id="'.$value['baner_id'].'" style="width:400px;" value="'.$value['baner_title'].'">
		  <br>3</b> : <input type="text" id="price'.$value['baner_id'].'" class="brand edit" data-id="'.$value['baner_id'].'" style="width:400px;" value="'.$value['baner_price'].'">
		  <br>4</b> : <input type="text" id="slogan'.$value['baner_id'].'" class="brand edit" data-id="'.$value['baner_id'].'" style="width:400px;" value="'.$value['baner_slogan'].'">
	  </td>
  
	  <td>
		<a href="javascript:" class="dell" id="dell_'.$value['baner_id'].'">dell</a>
	  </td>
	
	  <td>
		<form enctype="multipart/form-data" method="post" action="reklama/load_photo.php">
		  <input type="hidden" name="type" value="large">
		  <input type="hidden" name="MAX_FILE_SIZE" value="'.(1048*1048*1048).'">
		  <input type="hidden" name="filename"  value="' . $value['baner_id'] . '">
		  <input type="file" min="1" max="999" multiple="false" style="width:250px"  name="userfile" OnChange="submit();"/>
		</form>
	  </td>
	  
      </tr>';
  
}

  echo '<tr><td colspan="8" align="center"><input type="submit" name="key" value="save" style="width:200px;"></td></tr>';

  echo '</table>';
  ?>
  <script>
	  $(document).on('change','.edit', function(){
		var id = $(this).data('id');
		var view = '0';
		var name = $('#name'+id).val();
		var burl = $('#url'+id).val();
		var header = $('#header'+id).val();
		var title = $('#title'+id).val();
		var price = $('#price'+id).val();
		var slogan = $('#slogan'+id).val();
		
		if ($('#is_view'+id).prop('checked')) {
            view = '1' ;
        }
		
		
		 $.ajax({
		type: "POST",
		url: "reklama/ajax_edit_baner.php",
		dataType: "text",
		data: "id="+id+"&view="+view+"&burl="+burl+"&name="+name+"&header="+header+"&title="+title+"&price="+price+"&slogan="+slogan+"&type=large",
		beforeSend: function(){
		},
		success: function(msg){
		  console.log(  msg );
		  //$('#msg').html('Изменил');
		  //setTimeout($('#msg').html(''), 1000);
		}
	  });
		
	});
	
   	//Удаление
   $(document).on('click','.dell', function(){
      var id = $(this).attr('id');
      id = id.replace('dell', 'row');
      
      $('.'+id).remove();
      $.ajax({
		type: "POST",
		url: "reklama/ajax_dell_baner.php",
		dataType: "text",
		data: "id="+id,
		beforeSend: function(){
		  
		},
		success: function(msg){
		  console.log(  msg );
		  //$('#msg').html('Удалил');
		  //setTimeout($('#msg').html(''), 1000);
		}
	  });
	  
    });
  </script>