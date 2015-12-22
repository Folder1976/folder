<?php
header ('Content-Type: text/html; charset=utf8');
include 'init.lib.php';
include 'class/class_localisation.php';

$Localisation = new Localisation($folder);
$country = $Localisation->getCountry();


connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';

echo "\n<script src='../js/jquery-2.1.4.min.js'></script>";

// Если прилетела форма - сохраним===============================
if(isset($_POST['key']) AND $_POST['key'] == 'save'){
    //$sql = 'DELETE FROM tbl_brand;';
    //$r = $folder->query($sql);
    
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


$sql = 'SELECT * FROM tbl_brand ORDER BY brand_name ASC;';
$r = $folder->query($sql);

  echo '<form method=post><table>';
  echo '<tr><td colspan="5" align="center"><input type="submit" name="key" value="save" style="width:200px;"></td></tr>';

  echo '
      <tr>
      <th>#</th>
      <th>Код</th>
      <th>Название</th>
      <th>Страна</th>
      <th></th>
      </tr>';
      
      //Новая позиция
  echo '<tr>
      <td>новый<input type="hidden" name="id0" value=""></td>
      <td><input type="text" name="code0" class="brand" data-id="0" style="width:200px;" value="" placeholder="новый код"></td>
      <td><input type="text" name="name0" class="brand" data-id="0" style="width:200px;" value="" placeholder="новое название"></td>
      <td><select name="country0" class="brand" data-id="0" style="width:200px;">';
	
	foreach($country as $id => $value){
	      echo '<option value="'.$id.'">'.$value.'</option>';	    
	}
      
  echo '</select></td>
      <td></td>
      </tr>';
      
      
while($brand = $r->fetch_assoc()){
  
  echo '<tr class="row_'.$brand['brand_id'].'">
      <td>'.$brand['brand_id'].'<input type="hidden" name="id'.$brand['brand_id'].'" value="'.$brand['brand_code'].'"></td>
      <td><input type="text" name="code'.$brand['brand_id'].'" class="brand" data-id="'.$brand['brand_id'].'" style="width:200px;" value="'.$brand['brand_code'].'"></td>
      <td><input type="text" name="name'.$brand['brand_id'].'" class="brand" data-id="'.$brand['brand_id'].'" style="width:200px;" value="'.$brand['brand_name'].'"></td>
      <td><select name="country'.$brand['brand_id'].'" class="brand" data-id="'.$brand['brand_id'].'" style="width:200px;">';
	
	foreach($country as $id => $value){
	  if($id == $brand['country_id']){
	      echo '<option value="'.$id.'" selected>'.$value.'</option>';	    
	  }else{
	      echo '<option value="'.$id.'">'.$value.'</option>';	    
	  }
	}
      
  echo '</select></td>
      <td><a href="javascript:" class="dell" id="dell_'.$brand['brand_id'].'">dell</a></td>
      </tr>';
  
}

  echo '<tr><td colspan="5" align="center"><input type="submit" name="key" value="save" style="width:200px;"></td></tr>';

  echo '</table></form>';
  ?>
  <script>
   $(document).on('click','.dell', function(){
      var id = $(this).attr('id');
      id = id.replace('dell', 'row');
      
      $('.'+id).remove();
      
    });
  </script>