<?php

include 'init.lib.php';
connect_to_mysql();
include "../class/class_alias.php";
$Alias = new Alias($folder);

include "class/class_product_edit.php";
$ProductEdit = new ProductEdit($folder);

header ('Content-Type: text/html; charset=utf-8');
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}


$count = 0;
//$iKey_add = $_REQUEST["_add"];
//$iKey_save = $_REQUEST["_save"];
//$iKey_dell = $_REQUEST["_dell"];
$id_value = $_REQUEST["_id_value"];
$page_to_return = $_REQUEST["_page_to_return"];


$ver = mysql_query("SET NAMES utf8");

if (isset($_REQUEST["_dell"]))
{
 
 
 
    if(!isset($_REQUEST["_dell_yes"])){
      

	  $sql = 'SELECT operation_detail_operation FROM tbl_operation_detail WHERE operation_detail_tovar = "'.$id_value.'"';
        $r = $folder->query($sql);
      
	    if($r->num_rows == 0){ ?>
    
            <h2>УДАЛИТЬ?</h2>
	
				<a href="edit_tovar_save.php?_id_value=<?php echo $id_value; ?>&_dell_yes=true&_dell&_page_to_return=<?php echo $page_to_return; ?><?php echo ((int)$id_value - 1); ?>">ДА</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a HREF="javascript:" onclick="history.back();">НЕТ</a>
	
      <?php
        }else{
            echo '<h2><a HREF="javascript:" onclick="history.back();">Вернуться в редактор</a></h2>';
            echo '<h2>Этот товар есть в следующих накладных:</h2>';
            while($tmp = $r->fetch_assoc()){
                echo '<br> = <h4>'.$tmp['operation_detail_operation'].'</h4>';
            }
        }

      die();
    }

    $product = $ProductEdit->getProductInfo($id_value);
    
    $sql = 'DELETE FROM `tbl_tovar_postav_artikl` WHERE `tovar_artkl` ="'.$product['tovar_artkl'].'"';
    $folder->query($sql) or die('alternative artikles dell ((');
    
    $ver = mysql_query("DELETE FROM `tbl_tovar` WHERE `tovar_id` ='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_tovar` WHERE `tovar_id` ='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_description` WHERE `description_tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_seo_url` WHERE `seo_url`='tovar_id=".$id_value."'");
	$sql = 'DELETE FROM `tbl_tovar_links` WHERE `product_id` ="'.$id_value.'"';
	$folder->query($sql) or die('links dell ((');

    $result_string = "<br><br>Nom -> " . $id_value . " - DELETED OK";
    
    $Alias->dellAliasOnProductID($id_value);
    $id_value = $id_value - 1;
}

if (isset($_REQUEST["_save"]))
{
//Update tovar ================================================================

//Обновляем Алиас
$Alias->saveProductAlias($_REQUEST['tovar_alias'],$id_value);

	  //Сохраняем альтернативные артиклы
	  $sql = 'DELETE FROM `tbl_tovar_postav_artikl` WHERE `tovar_artkl` ="'.$_REQUEST['tovar_artkl'].'"';
	  $folder->query($sql) or die('alternative artikles dell ((');
	  
	  foreach($_REQUEST as $key => $value){
	      //Если найден  альтернативный артикл
	      if(strpos($key,'alt_artkl*') !== false AND strpos($key,'alt_artkl*') == 0){
		//И если он не пустой
		if($_REQUEST[$key] != ''){
		    $sql = 'INSERT INTO `tbl_tovar_postav_artikl` 
			      SET `tovar_artkl` ="'.$_REQUEST['tovar_artkl'].'",
			       `tovar_postav_artkl` ="'.$_REQUEST[$key].'",
			       `postav_id` ="'.$_REQUEST['postav_'.$key].'"
			      ';
			$folder->query($sql) or die('alternative artikles SAVE ((');
	
		}
	      }
	  }
   
       
	//Сохраняем Линки
	$sql = 'DELETE FROM `tbl_tovar_links` WHERE `product_id` ="'.$id_value.'"';
	$folder->query($sql) or die('links dell ((');

    foreach($_REQUEST as $key => $value){
	//Если найден  альтернативный артикл
	  if(strpos($key,'postav_url*') !== false AND strpos($key,'postav_url*') == 0){
		//И если он не пустой
		if($_REQUEST[$key] != ''){
			$key = str_replace('postav_url*', '', $key);
			if($_REQUEST['url*'.$key] != '' AND $_REQUEST['postav_url*'.$key] != 0 ){
				$sql = 'INSERT INTO `tbl_tovar_links` 
				  SET `product_id` ="'.$id_value.'",
				   `postav_id` ="'.$_REQUEST['postav_url*'.$key].'",
				   `url` ="'.$_REQUEST['url*'.$key].'"
				  ';
			//echo $sql;
				$folder->query($sql) or die('alternative Links SAVE ((');
			}
		}
	  }
    }
   
   
  //Сохраняем детали по поставщику
	  $sql = 'DELETE FROM `tbl_tovar_suppliers_items` WHERE `tovar_id` ="'.$id_value.'"';
	  $folder->query($sql) or die('alternative postav dell ((');
  
	  foreach($_REQUEST as $key => $value){
	      //Если найден поставщик
	      if(strpos($key,'row_postav*') !== false AND strpos($key,'row_postav*') == 0){
		//И если он не пустой
		
		if($_REQUEST[$key] != '' AND $_REQUEST[$key] != '0'){
		//echo $_REQUEST[$key].' ==== ';
		$x = str_replace('row_postav*','',$key);
		    $sql = 'INSERT INTO `tbl_tovar_suppliers_items` 
			      SET `tovar_id` ="'.$id_value.'",
			       `postav_id` ="'.$_REQUEST[$key].'",
			       `zakup` ="'.$_REQUEST['zakup_'.$key].'",
			       `zakup_curr` ="'.$_REQUEST['zakup_curr_'.$x].'",
			       `price_1` ="'.$_REQUEST['price_'.$key].'",
			       `items` ="'.$_REQUEST['items_'.$key].'"
			       ';
				   
		//echo $key.$sql;	die();	   
		    $folder->query($sql) or die(' Чтото не так намучено с альтернативными поставщиками (( Возможно дубляж');
	
		}
	      }
	  }
   


$date = date("Y-m-d G:i:s");
    $sql_str = "UPDATE `tbl_tovar` SET ";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_code')>0) $sql_str .= "`tovar_code`='".$_REQUEST['tovar_code']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_barcode')>0) $sql_str .= "`tovar_barcode`='".$_REQUEST['tovar_barcode']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_parent')>0) $sql_str .= "`tovar_parent`='".$_REQUEST['tovar_parent']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_artkl')>0) $sql_str .= "`tovar_artkl`	='".$_REQUEST['tovar_artkl']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_model')>0) $sql_str .= "`tovar_model`	='".$_REQUEST['tovar_model']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_size')>0) 	$sql_str .= "`tovar_size`	='".$_REQUEST['tovar_size']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_name_1')>0) $sql_str .= "`tovar_name_1`='".$_REQUEST['tovar_name_1']."',";
								    $sql_str .= "`time_to_kill`='".date("Y-m-d G:i:s",strtotime($_REQUEST['time_to_kill']))."',";
								    $sql_str .= "`parsing`='".$_REQUEST['parsing']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'on_ware')>0) $sql_str .= "`on_ware`	='".$_REQUEST['on_ware']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'original_supplier_link')>0) $sql_str .= "`original_supplier_link`	='".$_REQUEST['original_supplier_link']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_memo')>0) $sql_str .= "`tovar_memo`	='".$_REQUEST['tovar_memo']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_dimension')>0) $sql_str .= "`tovar_dimension`='".$_REQUEST['tovar_dimension']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'brand_id')>0) $sql_str .= "`brand_id`='".$_REQUEST['brand_id']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_min_order')>0) $sql_str .= "`tovar_min_order`='".$_REQUEST['tovar_min_order']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_seazon')>0) $sql_str .= "`tovar_seazon`	='".$_REQUEST['tovar_seazon']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_size_table')>0) $sql_str .= "`tovar_size_table`	='".$_REQUEST['tovar_size_table']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_video_url')>0) $sql_str .= "`tovar_video_url`	='".$_REQUEST['tovar_video_url']."',";
								
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_inet_id')>0) $sql_str .= "`tovar_inet_id`	='".$_REQUEST['tovar_inet_id']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_last_edit')>0) $sql_str .= "`tovar_last_edit`='".$date."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_inet_id_parent')>0) $sql_str .= "`tovar_inet_id_parent`='".$_REQUEST['tovar_inet_id_parent']."',";
	
	$sql = substr($sql_str,0,-1)." WHERE `tovar_id`='".$id_value."'
		";
    $ver = mysql_query($sql) or die($sql.'<br>'.mysql_error());

	//Установим дату и пользователя редактировавшего
	$date = date("Y-m-d G:i:s");
	$sql = 'UPDATE tbl_tovar SET
			tovar_last_edit = \''.$date.'\',
			tovar_last_edit_user = \''.$_SESSION[BASE.'userid'].'\'
			WHERE tovar_id = \''.$id_value.'\';';
	$folder->query($sql) or die('add product - ' . $sql);

//Update price ================================================================
    /*
	 $sql_str = "UPDATE `tbl_price_tovar` SET ";
     $count=0;
     while($count++ < $_REQUEST['_price_count']){
	    if(strpos($_SESSION[BASE.'usersetup'],"price_tovar_".$count)>0){
		$sql_str .= "`price_tovar_".$count."`	 ='".$_REQUEST['price_tovar_'.$count]."',";
		$sql_str .= "`price_tovar_curr_".$count."`='".$_REQUEST['price_tovar_curr_'.$count]."',";
		$sql_str .= "`price_tovar_cof_".$count."`='".$_REQUEST['price_tovar_cof_'.$count]."',";
	    }    
      }
     $sql_str = substr($sql_str,0,-1)." WHERE `price_tovar_id`='".$id_value."'";
     $ver = mysql_query($sql_str);
*/
//Update description ================================================================
    $sql = 'INSERT INTO `tbl_description` SET
			`description_1`	 ="'.str_replace('"',"'", $_REQUEST['description_1']).'",
			`description_tovar_id`="'.$id_value.'"
			ON DUPLICATE KEY UPDATE
			`description_1`	 ="'.str_replace('"',"'", $_REQUEST['description_1']).'"
			
			;';
    $ver = mysql_query($sql) or die($sql.'<br>'.mysql_error());
     
  //Перезапись аттрибутов
 
 /*
    $ver = mysql_query("DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id`='".$id_value."'");
    foreach($_POST as $index => $value){
	  echo '<br>'.$index;
      if(strpos($index,'attr*') !== false){
		  if($value != ''){
			$attr_id = explode('*',$index);
			$sql = "INSERT INTO tbl_attribute_to_tovar SET attribute_id = '".$attr_id[1]."', attribute_value = '$value', tovar_id = '$id_value';";
			$folder->query($sql);	  
		  }
      }
    }
   */ 
//die();
}
if (isset($_REQUEST["_add"]))
{
  
if(!isset($_REQUEST['tovar_supplier'])) $_REQUEST['tovar_supplier'] = 1;
if(!isset($_REQUEST['tovar_min_order'])) $_REQUEST['tovar_min_order'] = 1;
if(!isset($_REQUEST['tovar_seazon'])) $_REQUEST['tovar_seazon'] = 1;
if(!isset($_REQUEST['original_supplier_link'])) $_REQUEST['original_supplier_link'] = '';

if(!isset($_REQUEST['price_tovar_3'])) $_REQUEST['price_tovar_3'] = 1;
if(!isset($_REQUEST['price_tovar_curr_3'])) $_REQUEST['price_tovar_curr_3'] = 1;
if(!isset($_REQUEST['price_tovar_cof_3'])) $_REQUEST['price_tovar_cof_3'] = 1;


//Insert tovar ================================================================
$date = date("Y-m-d G:i:s");
$date_kill = date("Y-m-d G:i:s",strtotime($_REQUEST['time_to_kill']));
    $sql_str = "INSERT INTO `tbl_tovar` (
		`tovar_barcode`,
		`on_ware`,
		`tovar_parent`,
		`tovar_artkl`,
		`tovar_model`,
		`tovar_size`,
		`tovar_name_1`,
		`time_to_kill`,
		`parsing`,
		`tovar_memo`,
		`tovar_dimension`,
		`tovar_supplier`,
		`tovar_min_order`,
		`tovar_seazon`,
		`tovar_last_edit`,
		`tovar_inet_id_parent`,
		`original_supplier_link`,
		`tovar_inet_id`,
		`tovar_purchase_currency`,
		`tovar_sale_currency`,
		`tovar_video_url`,
		`tovar_size_table`,
		`brand_id`
		
		)VALUES(
		'".$_REQUEST['tovar_barcode']."',
		'".$_REQUEST['on_ware']."',
		'".$_REQUEST['tovar_parent']."',
		'".$_REQUEST['tovar_artkl']."',
		'".$_REQUEST['tovar_model']."',
		'".$_REQUEST['tovar_size']."',
		'".$_REQUEST['tovar_name_1']."',
		'".$date_kill."',
		'".$_REQUEST['parsing']."',
		'".$_REQUEST['tovar_memo']."',
		'".$_REQUEST['tovar_dimension']."',
		/*'".$_REQUEST['tovar_supplier']."'*/1,
		'".$_REQUEST['tovar_min_order']."',
		'".$_REQUEST['tovar_seazon']."',
		'".$date."',
		'".$_REQUEST['tovar_inet_id_parent']."',
		'".$_REQUEST['original_supplier_link']."',
		'1',
		'2',
		'4',
		'".$_REQUEST['tovar_video_url']."',
		'".$_REQUEST['tovar_size_table']."',
		'".$_REQUEST['brand_id']."');";
    $ver = mysql_query($sql_str) or die('Не удалось добавить товар! Проверьте - возможно вы не изменили артикл!<hr>'.$sql_str. '<br>' . mysqli_error($folder));
    $id_value=mysql_insert_id();
    
	//Установим дату и пользователя редактировавшего
	$date = date("Y-m-d G:i:s");
	$sql = 'UPDATE tbl_tovar SET
			tovar_last_edit = \''.$date.'\',
			tovar_last_edit_user = \''.$_SESSION[BASE.'userid'].'\'
			WHERE tovar_id = \''.$id_value.'\';';
	$folder->query($sql) or die('add product - ' . $sql);

    //Обновляем Алиас
    $Alias->saveProductAlias($_REQUEST['tovar_alias'],$id_value);
    
    $sql_str = "UPDATE `tbl_tovar` SET `tovar_inet_id`='".$id_value."' WHERE `tovar_id`='".$id_value."'";
    $ver = mysql_query($sql_str);
 //Clear subtable if is present ==================================================================  
    $ver = mysql_query("DELETE FROM `tbl_warehouse_unit` WHERE `warehouse_unit_tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_description` WHERE `descripton_tovar_id`='".$id_value."'");
  
//Insert price ================================================================
    /*
	 $sql_str = "INSERT INTO `tbl_price_tovar` (";
     $count=0;
     while($count++ < $_REQUEST['_price_count']){
	$sql_str .= "`price_tovar_".$count."`,`price_tovar_curr_".$count."`,`price_tovar_cof_".$count."`,";
      }
      $sql_str .= "`price_tovar_id`)VALUES(";
     
     $count=0;
     while($count++ < $_REQUEST['_price_count']){
	$sql_str .= "'".$_REQUEST['price_tovar_'.$count]."','".$_REQUEST['price_tovar_curr_'.$count]."','".$_REQUEST['price_tovar_cof_'.$count]."',";
      }
      $sql_str .= "'".$id_value."')";
     
     $ver = mysql_query($sql_str) or die($sql_str .'<br>'.mysql_error());
*/
//Insert description ================================================================
    $sql = 'INSERT INTO `tbl_description` SET
			`description_1`	 ="'.str_replace('"',"'", $_REQUEST['description_1']).'",
			`description_tovar_id`="'.$id_value.'"
			ON DUPLICATE KEY UPDATE
			`description_1`	 ="'.str_replace('"',"'", $_REQUEST['description_1']).'"
			
			;';
    $ver = mysql_query($sql) or die($sql.'<br>'.mysql_error());
   

//Insert description ================================================================
     $sql_str = "INSERT INTO `tbl_warehouse_unit` (`warehouse_unit_tovar_id`) VALUES ('".$id_value."')";
     $ver = mysql_query($sql_str);
      
//Перезапись аттрибутов
  /*
    $ver = mysql_query("DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id`='".$id_value."'");
    foreach($_POST as $index => $value){
      if(strpos($index,'attr*') !== false){
	if($value != ''){
	  $attr_id = explode('*',$index);
	  $folder->query("INSERT INTO tbl_attribute_to_tovar SET attribute_id = '".$attr_id[1]."', attribute_value = '$value', tovar_id = '$id_value';");	  
	}
      }
    }
    */
    //Сохраняем альтернативные артиклы
    foreach($_REQUEST as $key => $value){
	//Если найден  альтернативный артикл
	  if(strpos($key,'alt_artkl*') !== false AND strpos($key,'alt_artkl*') == 0){
		//И если он не пустой
		if($_REQUEST[$key] != ''){
			$sql = 'INSERT INTO `tbl_tovar_postav_artikl` 
			  SET `tovar_artkl` ="'.$_REQUEST['tovar_artkl'].'",
			   `tovar_postav_artkl` ="'.$_REQUEST[$key].'",
			   `postav_id` ="'.$_REQUEST['postav_'.$key].'"
			  ';
			$folder->query($sql) or die('alternative artikles Add ((');
	
		}
	  }
    }
    
	//Сохраняем Линки
    foreach($_REQUEST as $key => $value){
	//Если найден  альтернативный артикл
	  if(strpos($key,'postav_url*') !== false AND strpos($key,'postav_url*') == 0){
		//И если он не пустой
		if($_REQUEST[$key] != '' AND $_REQUEST[$key] != '0'){
			$key = str_replace('postav_url*', '', $key);
			if($_REQUEST['url*'.$key] != '' AND $_REQUEST['postav_url*'.$key] != 0 ){
			  $sql = 'INSERT INTO `tbl_tovar_links` 
				SET `product_id` ="'.$id_value.'",
				 `postav_id` ="'.$_REQUEST['postav_url*'.$key].'",
				 `url` ="'.$_REQUEST['url*'.$key].'"
				';
			  //echo $sql;
			  $folder->query($sql) or die('alternative Links Add ((');
			}
		}
	  }
    }
    
    
      //Сохраняем детали по поставщику
      foreach($_REQUEST as $key => $value){
	  //Если найден поставщик
	  if(strpos($key,'row_postav*') !== false AND strpos($key,'row_postav*') == 0){
	    //И если он не пустой
	    if($_REQUEST[$key] != '' AND $_REQUEST[$key] != '0'){
		  $x = str_replace('row_postav*','',$key);
		$sql = 'INSERT INTO `tbl_tovar_suppliers_items` 
			  SET `tovar_id` ="'.$id_value.'",
			   `postav_id` ="'.$_REQUEST[$key].'",
			   `zakup` ="'.$_REQUEST['zakup_'.$key].'",
			   `zakup_curr` ="'.$_REQUEST['zakup_curr_'.$x].'",
			   `price_1` ="'.$_REQUEST['price_'.$key].'",
			   `items` ="'.$_REQUEST['items_'.$key].'"
			   ';
		$folder->query($sql) or die(' Чтото не так намучено с альтернативными поставщиками (( Возможно дубляж');
    
	    }
	  }
      }
   
     
}

header ('Refresh: 1; url=edit_tovar.php?tovar_id='.$id_value);

?>
