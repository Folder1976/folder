<?php
$type = 'large';
if(isset($_GET['type'])){
  $type = $_GET['type'];
}



?>
  <header>
	<title>Банеры</title>
  </header>

<br><a href="main.php?func=banner&type=large">Большие банеры</a>
<br><a href="main.php?func=banner&type=medium">Средние банеры</a>
<br><font color="red">!Загружайте картинки только *.png! И не используйте в названиях файлов спецсимволы и пробелы.</font>
<?php
if($type == 'large'){
  echo '<h1>Большие банеры = 1920 x 1200 px</h1>';
  include 'reklama/large_banners.php';
}
if($type == 'medium'){
  echo '<h1>Средние банеры = 740 x 283 px</h1>';
  include 'reklama/medium_banners.php';
}
die();

//==================================================== Удалить позже 2016.01.17



header("Content-Type: text/html; charset=UTF-8");
echo "<pre>";  print_r(var_dump( $_GET )); echo "</pre>";







connect_to_mysql();

if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';

echo "\n<script src='../js/jquery-2.1.4.min.js'></script>";

// Если прилетела форма - сохраним===============================
if(isset($_POST['key']) AND $_POST['key'] == 'save'){
     
    foreach($_POST as $index => $value){
	if(strpos($index,'code') !==false){
	  $id = (int)str_replace('code','',$index);
	  
	  //Если это пустышка - пропустим ее
	  if($value == '') continue;
	  
	  //Если это код 0 - новый
	    $sql = 'INSERT INTO tbl_brand SET
			  brand_id = "'.$_POST['id'.$id].'",
				  brand_code = "'.$_POST['code'.$id].'",
		    	  brand_name = "'.$_POST['name'.$id].'",
				  country_id = "'.$_POST['country'.$id].'"
			  ON DUPLICATE KEY UPDATE
				  brand_code = "'.$_POST['code'.$id].'",
		    	  brand_name = "'.$_POST['name'.$id].'",
				  country_id = "'.$_POST['country'.$id].'"
			  ;';
//echo '<br>'.$sql;
	    $r = $folder->query($sql);
	}
    }
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

  echo '<form method=post>
	<table class="table">';
  echo '<tr><td colspan="8" align="center"><input type="submit" name="key" value="save" style="width:200px;"></td></tr>';

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
      
      
  echo '<tr>
      <td>новый<input type="hidden" name="id0" value=""></td>
      <td></td>
      <td><input type="checkbox" name="is_view" class="brand" data-id="0"></td>
      <td><input type="text" name="name" class="brand" data-id="0" style="width:200px;" value="" placeholder="новое название"></td>
      <td><input type="text" name="url" class="brand" data-id="0" style="width:200px;" value="" placeholder="url"></td>
      <td>';
	
            foreach($place as $id => $value){
                  echo '<input type="checkbox" name="'.$id.'" class="brand" data-id=""'.$id.'">' . $value . '<br>';	    
            }
              
    echo '</td>
      <td colspan="3"></td>
      </tr>';
      
      
while($value = $r->fetch_assoc()){
  //UPLOAD_DIR
  echo '<tr class="row_'.$value['baner_id'].'">
      <td>'.$value['baner_id'].'</td>
	  <td><img src="../resources/banners/'.$value['baner_pic'].'" width="100"></td>
	  <td><input type="checkbox" name="is_view" class="brand" data-id="0" ';
		if($value['is_view'] == 1) echo ' checked ';
	  echo ' ></td>
      <td><input type="text" name="name'.$value['baner_id'].'" class="brand" data-id="'.$value['baner_id'].'" style="width:200px;" value="'.$value['baner_name'].'"></td>
      <td><input type="text" name="url'.$value['baner_id'].'" class="brand" data-id="'.$value['baner_id'].'" style="width:200px;" value="'.$value['baner_url'].'"></td>
      <td>';
	
		foreach($place as $id => $val){
			echo '<input type="checkbox" name="'.$id.'" class="brand" data-id=""'.$id.'"';
			if(strpos($value['baner_place'], $id) !== false) echo ' checked ';
			echo '>' . $val . '<br>';	    
		}

      
  echo '</td>

	  <td>
		<a href="javascript:" class="dell" id="dell_'.$value['baner_id'].'">dell</a>
	  </td>
	
	  <td>
		<form enctype="multipart/form-data" method="post" action="load_photo/load_photo_brand.php">
		  <input type="hidden" name="MAX_FILE_SIZE" value="'.(1048*1048*1048).'">
		  <input type="hidden" name="brand_code"  value="' . $value['baner_id'] . '">
		  <input type="file" min="1" max="999" multiple="false" style="width:250px"  name="brandfile" OnChange="submit();"/>
		</form>
	  </td>
	  
      </tr>';
  
}

  echo '<tr><td colspan="8" align="center"><input type="submit" name="key" value="save" style="width:200px;"></td></tr>';

  echo '</table></form>';
  ?>
  <script>
   $(document).on('click','.dell', function(){
      var id = $(this).attr('id');
      id = id.replace('dell', 'row');
      
      $('.'+id).remove();
      
    });
  </script>