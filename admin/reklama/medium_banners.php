<?php
$type = 'large';
if(isset($_GET['type'])){
  $type = $_GET['type'];
}

connect_to_mysql();

if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}

// Если прилетела форма - сохраним===============================

if(isset($_POST['key']) AND $_POST['key'] == 'add'){
     
    $is_view = 0;
	  if(isset($_POST['is_view'])) $is_view = '1';
	  
	  $baner_place = '';
	  if(isset($_POST['catalog1'])) $baner_place .= 'catalog1*';
	  if(isset($_POST['catalog2'])) $baner_place .= 'catalog2*';
	  if(isset($_POST['news'])) $baner_place .= 'news*';
	  if(isset($_POST['find'])) $baner_place .= 'find*';
	  
	  $baner_place = trim($baner_place, '*');
	  
	  //Если это код 0 - новый
	    $sql = 'INSERT INTO tbl_baner SET
				  baner_name = "'.$_POST['name'].'",
		    	  baner_url = "'.$_POST['url'].'",
				  baner_type = "medium",
				  baner_place = "'.$baner_place.'",
				  is_view = "'.$is_view.'"
			    ;';

	    $r = $folder->query($sql);
		
	echo "<H2>Добавлено!</H2><SCRIPT>\n\nvar i = setTimeout(\"window.location.href='main.php?func=banner&type=medium'\", 500);\n</SCRIPT>";
	
}

echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';
echo "\n<script src='../js/jquery-2.1.4.min.js'></script>";

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
  <font color="red">Помни! На странице ДВА банера!</font>
	<table class="table">';
  echo '<tr><td colspan="8" align="center" id="msg"></td></tr>';

  echo '
      <tr>
      <th>#</th>
      <th> + + + </th>
      <th>Показывать</th>
      <th>Название</th>
      <th>URL</th>
      <th>Где показать</th>
      <th colspan="2"></th>
      </tr>';
      
      //Новая позиция
    $place = array(
                   'catalog1' => 'Категория первого уровня',
                   'catalog2' => 'Категория второго уровня',
                   'news' => 'Новый товар',
                   'find' => 'Поиск'
                   );  
      
      
  echo '<form method=post><tr>
      <td>новый<input type="hidden" name="id" value=""></td>
      <td></td>
      <td><input type="checkbox" name="is_view" class="brand" data-id="0"></td>
      <td><input type="text" name="name" class="brand" data-id="0" style="width:200px;" value="" placeholder="новое название"></td>
      <td><input type="text" name="url" class="brand" data-id="0" style="width:200px;" value="" placeholder="url"></td>
      <td>';
	
            foreach($place as $id => $value){
                  echo '<input type="checkbox" name="'.$id.'" class="brand" data-id=""'.$id.'">' . $value . '<br>';	    
            }
              
    echo '</td>
      <td colspan="1"><input type="submit" name="key" value="add" style="width:50px;"></td>
	  <td colspan="2">Фото после добавляния</td>
      </tr></form>';
      
      
while($value = $r->fetch_assoc()){
  //UPLOAD_DIR
  echo '<tr class="row_'.$value['baner_id'].'">
      <td>'.$value['baner_id'].'</td>
	  <td><img src="../resources/banners/catalog/'.$value['baner_pic'].'" width="100"></td>
	  <td><input type="checkbox" name="is_view" class="brand edit" id="is_view'.$value['baner_id'].'" data-id="'.$value['baner_id'].'" ';
		if($value['is_view'] == 1) echo ' checked ';
	  echo ' ></td>
      <td><input type="text" name="name'.$value['baner_id'].'" class="brand edit" id="name'.$value['baner_id'].'" data-id="'.$value['baner_id'].'" style="width:200px;" value="'.$value['baner_name'].'"></td>
      <td><input type="text" name="url'.$value['baner_id'].'" class="brand edit" id="url'.$value['baner_id'].'" data-id="'.$value['baner_id'].'" style="width:200px;" value="'.$value['baner_url'].'"></td>
      <td>';
	
		foreach($place as $id => $val){
			echo '<input type="checkbox" name="'.$id.'" class="brand edit" id="view_'.$id.'_'.$value['baner_id'].'" data-id="'.$value['baner_id'].'"';
			if(strpos($value['baner_place'], $id) !== false) echo ' checked ';
			echo '>' . $val . '<br>';	    
		}

      
  echo '</td>

	  <td>
		<a href="javascript:" class="dell" id="dell_'.$value['baner_id'].'">dell</a>
	  </td>
	
	  <td>
		<form enctype="multipart/form-data" method="post" action="reklama/load_photo.php">
		  <input type="hidden" name="type" value="medium">
		  <input type="hidden" name="MAX_FILE_SIZE" value="'.(1048*1048*1048).'">
		  <input type="hidden" name="filename"  value="' . $value['baner_id'] . '">
		  <input type="file" min="1" max="999" multiple="false" style="width:250px"  name="userfile" OnChange="submit();"/>
		</form>
	  </td>
	  
      </tr>';
  
}

  echo '<!--tr><td colspan="8" align="center"><input type="submit" name="key" value="save" style="width:200px;"></td></tr-->';

  echo '</table>';
  ?>
  <script>
	$(document).on('change','.edit', function(){
		var id = $(this).data('id');
		var view = '0';
		var name = $('#name'+id).val();
		var url = $('#url'+id).val();
		var place = '';
		
		if ($('#is_view'+id).prop('checked')) {
            view = '1' ;
        }
		
		if ($('#view_catalog1_'+id).prop('checked')) {
            place = place + 'catalog1*' ;
        }
		
		if ($('#view_catalog2_'+id).prop('checked')) {
            place = place + 'catalog2*' ;
        }
		
		if ($('#view_news_'+id).prop('checked')) {
            place = place + 'news*' ;
        }
		
		if ($('#view_find_'+id).prop('checked')) {
            place = place + 'find*' ;
        }
		
		 $.ajax({
		type: "POST",
		url: "reklama/ajax_edit_baner.php",
		dataType: "text",
		data: "id="+id+"&view="+view+"&name="+name+"&url="+url+"&place="+place+"&type=medium",
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