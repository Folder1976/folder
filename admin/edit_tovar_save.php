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
//connect_to_mysql();

$count = 0;
//$iKey_add = $_REQUEST["_add"];
//$iKey_save = $_REQUEST["_save"];
//$iKey_dell = $_REQUEST["_dell"];
$id_value = $_REQUEST["_id_value"];
$page_to_return = $_REQUEST["_page_to_return"];


//echo $iKey_add, $iKey_dell, $iKey_save, "- > <br>";



$ver = mysql_query("SET NAMES utf8");
//echo "<br>",$iKey_dell,"<br>";
if (isset($_REQUEST["_dell"]))
{
    if(!isset($_REQUEST["_dell_yes"])){
      ?>
	<h2>УДАЛИТЬ?</h2>
	
	<a href="edit_tovar_save.php?_id_value=<?php echo $id_value; ?>&_dell_yes=true&_dell&_page_to_return=<?php echo $page_to_return; ?><?php echo ((int)$id_value - 1); ?>">ДА</a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a HREF="javascript:" onclick="history.back();">НЕТ</a>
	
      <?php
      die();
    }

    $product = $ProductEdit->getProductInfo($id_value);
    
    $sql = 'DELETE FROM `tbl_tovar_postav_artikl` WHERE `tovat_artkl` ="'.$product['tovar_artkl'].'"';
    $folder->query($sql) or die('alternative artikles dell ((');
    
    $ver = mysql_query("DELETE FROM `tbl_tovar` WHERE `tovar_id` ='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_tovar` WHERE `tovar_id` ='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_description` WHERE `descripton_tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_seo_url` WHERE `seo_url`='tovar_id=".$id_value."'");
    $result_string = "<br><br>Nom -> " . $id_value . " - DELETED OK";
    
    $Alias->dellAliasOnProductID($id_value);
    echo '<h1>Удалил</h1>';
    $id_value = $id_value - 1;
}

if (isset($_REQUEST["_save"]))
{
//Update tovar ================================================================
//echo $_REQUEST['tovar_inet_id'];

//Обновляем Алиас
$Alias->saveProductAlias($_REQUEST['tovar_alias'],$id_value);

	  //Сохраняем альтернативные артиклы
	  $sql = 'DELETE FROM `tbl_tovar_postav_artikl` WHERE `tovat_artkl` ="'.$_REQUEST['tovar_artkl'].'"';
	  $folder->query($sql) or die('alternative artikles dell ((');
	  
	  foreach($_REQUEST as $key => $value){
	      //Если найден  альтернативный артикл
	      if(strpos($key,'alt_artkl*') !== false AND strpos($key,'alt_artkl*') == 0){
		//И если он не пустой
		if($_REQUEST[$key] != ''){
		    $sql = 'INSERT INTO `tbl_tovar_postav_artikl` 
			      SET `tovat_artkl` ="'.$_REQUEST['tovar_artkl'].'",
			       `tovar_postav_artkl` ="'.$_REQUEST[$key].'",
			       `postav_id` ="'.$_REQUEST['postav_'.$key].'"
			      ';
			      echo '<br>'.$sql;
		    $folder->query($sql) or die('alternative artikles Add ((');
	
		}
	      }
	  }
   


$date = date("Y-m-d G:i:s");
    $sql_str = "UPDATE `tbl_tovar` SET ";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_code')>0) $sql_str .= "`tovar_code`='".$_REQUEST['tovar_code']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_barcode')>0) $sql_str .= "`tovar_barcode`='".$_REQUEST['tovar_barcode']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_parent')>0) $sql_str .= "`tovar_parent`='".$_REQUEST['tovar_parent']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_artkl')>0) $sql_str .= "`tovar_artkl`	='".$_REQUEST['tovar_artkl']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_size')>0) 	$sql_str .= "`tovar_size`	='".$_REQUEST['tovar_size']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_name_1')>0) $sql_str .= "`tovar_name_1`='".$_REQUEST['tovar_name_1']."',";
								    $sql_str .= "`time_to_kill`='".date("Y-m-d G:i:s",strtotime($_REQUEST['time_to_kill']))."',";
								    $sql_str .= "`parsing`='".$_REQUEST['parsing']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'on_ware')>0) $sql_str .= "`on_ware`	='".$_REQUEST['on_ware']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'original_supplier_link')>0) $sql_str .= "`original_supplier_link`	='".$_REQUEST['original_supplier_link']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_memo')>0) $sql_str .= "`tovar_memo`	='".$_REQUEST['tovar_memo']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_dimension')>0) $sql_str .= "`tovar_dimension`='".$_REQUEST['tovar_dimension']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_supplier')>0) $sql_str .= "`tovar_supplier`='".$_REQUEST['tovar_supplier']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_min_order')>0) $sql_str .= "`tovar_min_order`='".$_REQUEST['tovar_min_order']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_seazon')>0) $sql_str .= "`tovar_seazon`	='".$_REQUEST['tovar_seazon']."',";
								
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_inet_id')>0) $sql_str .= "`tovar_inet_id`	='".$_REQUEST['tovar_inet_id']."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_last_edit')>0) $sql_str .= "`tovar_last_edit`='".$date."',";
	if(strpos($_SESSION[BASE.'usersetup'],'tovar_inet_id_parent')>0) $sql_str .= "`tovar_inet_id_parent`='".$_REQUEST['tovar_inet_id_parent']."',";
	
	$sql = substr($sql_str,0,-1)." WHERE `tovar_id`='".$id_value."'
		";
    $ver = mysql_query($sql) or die($sql.'<br>'.mysql_error());
  // echo $sql_str." - ", $ver,"<br>";die();

//Update price ================================================================
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
     //echo $sql_str." - ", $ver,"<br>";

//Update description ================================================================
   $sql_str = "UPDATE `tbl_description` SET ";
     $count=0;
     while($count++ < $_REQUEST['_description_count']){
	$sql_str .= "`description_".$count."`	 ='".str_replace('\'','"', $_REQUEST['description_'.$count])."',";
    }
     $sql = substr($sql_str,0,-1)." WHERE `description_tovar_id`='".$id_value."'";

     $ver = mysql_query($sql) or die($sql.'<br>'.mysql_error());
     //echo $sql_str." - ", $ver,"<br>";
     
  //Перезапись аттрибутов
    $ver = mysql_query("DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id`='".$id_value."'");
    foreach($_POST as $index => $value){
      if(strpos($index,'attr*') !== false){
	if($value != ''){
	  $attr_id = explode('*',$index);
	  $folder->query("INSERT INTO tbl_attribute_to_tovar SET attribute_id = '".$attr_id[1]."', attribute_value = '$value', tovar_id = '$id_value';");	  
	}
      }
    }
echo '<h1>Сохранил</h1>';
    
}
if (isset($_REQUEST["_add"]))
{
  


//Insert tovar ================================================================
//echo $_REQUEST['tovar_inet_id'];
$date = date("Y-m-d G:i:s");
$date_kill = date("Y-m-d G:i:s",strtotime($_REQUEST['time_to_kill']));
    $sql_str = "INSERT INTO `tbl_tovar` (
		`tovar_barcode`,
		`on_ware`,
		`tovar_parent`,
		`tovar_artkl`,
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
		`tovar_sale_currency`
		)VALUES(
		'".$_REQUEST['tovar_barcode']."',
		'".$_REQUEST['on_ware']."',
		'".$_REQUEST['tovar_parent']."',
		'".$_REQUEST['tovar_artkl']."',
		'".$_REQUEST['tovar_size']."',
		'".$_REQUEST['tovar_name_1']."',
		'".$date_kill."',
		'".$_REQUEST['parsing']."',
		'".$_REQUEST['tovar_memo']."',
		'".$_REQUEST['tovar_dimension']."',
		'".$_REQUEST['tovar_supplier']."',
		'".$_REQUEST['tovar_min_order']."',
		'".$_REQUEST['tovar_seazon']."',
		'".$date."',
		'".$_REQUEST['tovar_inet_id_parent']."',
		'".$_REQUEST['original_supplier_link']."',
		'1',
		'2',
		'4'
		)";
    $ver = mysql_query($sql_str);
    $id_value=mysql_insert_id();
    //echo $sql_str." - ", $ver," Inserted = ",$id_value,"<br>";
    
    //Обновляем Алиас
    $Alias->saveProductAlias($_REQUEST['tovar_alias'],$id_value);
    
    $sql_str = "UPDATE `tbl_tovar` SET `tovar_inet_id`='".$id_value."' WHERE `tovar_id`='".$id_value."'";
    $ver = mysql_query($sql_str);
    //echo $sql_str." - ", $ver," Inserted = ",$id_value,"<br>";
//Clear subtable if is present ==================================================================  
    $ver = mysql_query("DELETE FROM `tbl_warehouse_unit` WHERE `warehouse_unit_tovar_id`='".$id_value."'");
    //echo "DELETE FROM `tbl_warehouse_unit` WHERE `warehouse_unit_tovar_id`='".$id_value."' - ",$ver,"<br>";
    $ver = mysql_query("DELETE FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id_value."'");
    //echo "DELETE FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id_value."' - ",$ver,"<br>";
    $ver = mysql_query("DELETE FROM `tbl_description` WHERE `descripton_tovar_id`='".$id_value."'");
    //echo "DELETE FROM `tbl_description` WHERE `description_tovar_id`='".$id_value."' - ",$ver,"<br>";
  
//Insert price ================================================================
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
     //echo $sql_str." - ", $ver,"<br>";
//Insert description ================================================================
     $sql_str = "INSERT INTO `tbl_description` (";
     $count=0;
     while($count++ < $_REQUEST['_description_count']){
	$sql_str .= "`description_".$count."`,";
      }
      $sql_str .= "`description_tovar_id`)VALUES(";
     
     $count=0;
     while($count++ < $_REQUEST['_description_count']){
	 $sql_st .= str_replace('\'','"', "'".$_REQUEST['description_'.$count]."',");
       }
      $sql_str .= "'".$id_value."')";
      
      
     $ver = mysql_query($sql_str) or die($sql_str.'<br>'.mysql_error());
     //echo $sql_str." - ", $ver,"<br>";

//Insert description ================================================================
     $sql_str = "INSERT INTO `tbl_warehouse_unit` (`warehouse_unit_tovar_id`) VALUES ('".$id_value."')";
     $ver = mysql_query($sql_str);
     //echo $sql_str." - ", $ver,"<br>";
     
//Перезапись аттрибутов
    $ver = mysql_query("DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id`='".$id_value."'");
    foreach($_POST as $index => $value){
      if(strpos($index,'attr*') !== false){
	if($value != ''){
	  $attr_id = explode('*',$index);
	  $folder->query("INSERT INTO tbl_attribute_to_tovar SET attribute_id = '".$attr_id[1]."', attribute_value = '$value', tovar_id = '$id_value';");	  
	}
      }
    }
    
    //Сохраняем альтернативные артиклы
    foreach($_REQUEST as $key => $value){
	//Если найден  альтернативный артикл
	if(strpos($key,'alt_artkl*') !== false AND strpos($key,'alt_artkl*') == 0){
	  //И если он не пустой
	  if($_REQUEST[$key] != ''){
	      $sql = 'INSERT INTO `tbl_tovar_postav_artikl` 
			SET `tovat_artkl` ="'.$_REQUEST['tovar_artkl'].'",
			 `tovar_postav_artkl` ="'.$_REQUEST[$key].'",
			 `postav_id` ="'.$_REQUEST['postav_'.$key].'"
			';
			echo '<br>'.$sql;
	      $folder->query($sql) or die('alternative artikles Add ((');
  
	  }
	}
    }

echo '<h1>Добавил</h1>';
     
}
//echo "<br>---",$id_value;
header ('Refresh: 1; url=edit_tovar.php?tovar_id='.$id_value);

?>
