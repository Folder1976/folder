<?php
session_start();
header ('Content-Type: text/html; charset=utf8');
include 'init.lib.php';
include 'class/class_localisation.php';

$Localisation = new Localisation($folder);
$country = $Localisation->getCountry();
?>
  <header>
	<title>Бренды</title>
  </header>

  
<?php

connect_to_mysql();

if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';

echo "\n<script src='../js/jquery-2.1.4.min.js'></script>";

// Если прилетела форма - сохраним===============================
if(isset($_POST['key'])){
    
	  //Если это пустышка - пропустим ее
	  if('code0' == '') continue;
	  
	  //Если это код 0 - новый
	    $sql = 'INSERT INTO tbl_brand SET
				  brand_code = "'.$_POST['code0'].'",
		    	  brand_name = "'.$_POST['name0'].'",
				  country_id = "'.$_POST['country0'].'";';
	  //echo '<br>'.$sql;
	    $r = $folder->query($sql);

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

$sql = 'SELECT * FROM tbl_brand ORDER BY brand_name ASC;';
$r = $folder->query($sql);

  echo '
	<table class="table">
	<form method=post>
	';
  echo '<tr><td colspan="7" align="center"><input type="submit" name="key" value="Добавить" style="width:200px;"></td></tr>';

  echo '
      <tr>
      <th>#</th>
      <th>Код</th>
      <th>Название</th>
      <th>Страна</th>
      <th></th>
      <th>Лого</th>
      <th>brand_code.png</th>
      </tr>';
      
      //Новая позиция
  echo '
	<tr>
      <td>новый<input type="hidden" name="id0" value=""></td>
      <td><input type="text" name="code0" class="brand" data-id="0" style="width:200px;" value="" placeholder="новый код"></td>
      <td><input type="text" name="name0" class="brand" data-id="0" style="width:200px;" value="" placeholder="новое название"></td>
      <td><select name="country0" class="brand" data-id="0" style="width:200px;">';
	
	foreach($country as $id => $value){
	      echo '<option value="'.$id.'">'.$value.'</option>';	    
	}
      
  echo '</select></td>
      <td colspan="3"></td>
      </tr>
	  </form>
	  
	  <tr><td colspan="7"><font color="red">! Редактирование по аяксу.</font></td></tr>
	  ';
      
      
while($brand = $r->fetch_assoc()){
  
  echo '<tr class="row_'.$brand['brand_id'].'">
      <td>'.$brand['brand_id'].'<input type="hidden" data-id="'.$brand['brand_id'].'" value="'.$brand['brand_code'].'"></td>
      <td><input type="text" data-name="brand_code" class="edit" data-id="'.$brand['brand_id'].'" style="width:200px;" value="'.$brand['brand_code'].'"></td>
      <td><input type="text" data-name="brand_name" class="edit" data-id="'.$brand['brand_id'].'" style="width:200px;" value="'.$brand['brand_name'].'"></td>
      <td><select data-name="country_id" class="edit" data-id="'.$brand['brand_id'].'" style="width:200px;">';
	
	foreach($country as $id => $value){
	  if($id == $brand['country_id']){
	      echo '<option value="'.$id.'" selected>'.$value.'</option>';	    
	  }else{
	      echo '<option value="'.$id.'">'.$value.'</option>';	    
	  }
	}
      
  echo '</select></td>

	  <td>
		<a href="javascript:" class="dell" data-id="'.$brand['brand_id'].'">dell</a>
	  </td>
	
	  <td style="background-color: gray;">
		<img src="' . HOST_URL . '/resources/brends/' . $brand['brand_code'] . '.png" alt="' . $brand['brand_name'] . '">
	  </td>
  
	  <td>
		<form enctype="multipart/form-data" method="post" action="load_photo/load_photo_brand.php">
		  <input type="hidden" name="MAX_FILE_SIZE" value="'.(1048*1048*1048).'">
		  <input type="hidden" name="brand_code"  value="' . $brand['brand_code'] . '">
		  <input type="file" min="1" max="999" multiple="false" style="width:250px"  name="brandfile" OnChange="submit();"/>
		</form>
	  </td>
	  
      </tr>';
  
}

  echo '</table>';
  ?>
  <script>
   
   $(document).on('change','.edit', function(){
		var id = $(this).data('id');
		var fild = $(this).data('name');
		var value = $(this).val();
		
		$.ajax({
		type: "POST",
		url: "brand/ajax_edit_brand.php",
		dataType: "text",
		data: "id="+id+"&fild="+fild+"&value="+value+"&key=edit",
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
      var id = $(this).data('id');
      
      $('.row_'+id).remove();
      $.ajax({
		type: "POST",
		url: "brand/ajax_edit_brand.php",
		dataType: "text",
		data: "id="+id+"&key=dell",
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