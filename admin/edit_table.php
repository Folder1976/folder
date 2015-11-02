<?php

include 'init.lib.php';

connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
//print_r ($_REQUEST);
$id_to_return="";

//echo $_REQUEST['_dell'];
//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
	  WHERE 
	  `setup_menu_name` like '%menu%'

";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

$count = 0;

$page_to_return = $_REQUEST["_page_to_return"];
$table_name = $_REQUEST["_table_name"];
$id_name = $_REQUEST["_id_name"];
$id_value = $_REQUEST["_id_value"];
$result_string="";

//echo " ",$table_name," ",$id_name," ",$id_value," ",$iKey_dell;
//exit();

echo $table_name, "<br>";
//echo $iKey_add, $iKey_dell, $iKey_save, "- > <br>";

$s_name="";
$s_value="";
$s_vhere="";
$s_sql_string="";
$s_sql_string_where = "";

$ver = mysql_query("SET NAMES utf8");

$empty = $post = array();
if (isset($_REQUEST["_save"]))
{
    foreach ($_POST as $varname => $varvalue){
	    if(substr($varname,0,1) != "_"){
		    $post[$varname] = $varvalue;
		    //  echo $post[$varname], " = > " , $varname, "<br>";
		    if ($s_value == "") {
			if($varname=="klienti_pass"){ 
			    if($post[$varname] != "")$s_value .=  "`" .  $varname . "` = '" . md5($post[$varname]) . "'";
			 }else{
			    $s_value .=  "`" .  $varname . "` = '" . $post[$varname] . "'";
			 }
		    }else{
			if($varname=="klienti_pass"){ 
			    if($post[$varname] != "")$s_value .=  ",`" .  $varname . "` = '" . md5($post[$varname]) . "'";
			 }else{
			    $s_value .=  ",`" .  $varname . "` = '" . $post[$varname] . "'";
			 }
		    }
    
	    }
    }
  $id_to_return =   $id_value;
}else{
    foreach ($_POST as $varname => $varvalue){
	    if(substr($varname,0,1) != "_"){
		  $post[$varname] = $varvalue;
		  //  echo $post[$varname], " = > " , $varname, "<br>";
		  if ($s_value == "") {
			if($varname=="klienti_pass"){ 
			    if($post[$varname] != "")$s_value .=  "'" . md5($post[$varname]) . "'";
			}else{
			    $s_value .=  "'" . $post[$varname] . "'";
			}
		  }else{
		      if($varname=="klienti_pass"){ 
			    if($post[$varname] != "")$s_value .=  ",'" . md5($post[$varname]) . "'";
			}else{
			    $s_value .=  ",'" . $post[$varname] . "'";
			}
		  }
    
		  if($varname=="klienti_pass" and $post[$varname] == ""){
		  }else{
		      if ($s_name == ""){
			  $s_name  .=  "`" .  $varname . "`";
		      }else{
			  $s_name  .=  ",`" .  $varname . "`";
		      }
		  }
		      
	    }
    }
}

//$s_sql_string .= "INSERT INTO `" . $table_name . "`(`" . $id_name . "`" . $s_name . ") VALUES ('" . $id_value . "'" . $s_value . ")";
$s_sql_string_where = " WHERE `" . $id_name . "` = '" . $id_value . "'";
//echo $s_sql_string_where; //$s_sql_string_where, $iKey_save;

if (isset($_REQUEST["_return"]))
{
echo $_REQUEST["_return"];
}


if (isset($_REQUEST["_dell"]))
{
  
    if(!isset($_REQUEST["_dell_yes"])){
      ?>
	<h2>УДАЛИТЬ?</h2>
	<a href="edit_table.php?_id_value=<?php echo $id_value; ?>&_dell_yes=true&_dell&_page_to_return=<?php echo $page_to_return; ?>&_id_name=<?php echo $id_name; ?>&_table_name=<?php echo $table_name; ?>">ДА</a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a HREF="javascript:" onclick="history.back();">НЕТ</a>
	
      <?php
      die();
    }
  
  $ver = mysql_query("DELETE FROM `" . $table_name . "`" . $s_sql_string_where);
  
  // esli eto tablica tovara
  if($table_name=="tbl_tovar"){
    $ver = mysql_query("DELETE FROM `tbl_warehouse_unit` WHERE `warehouse_unit_tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id_value."'");
    $ver = mysql_query("DELETE FROM `tbl_description` WHERE `descripton_tovar_id`='".$id_value."'");
    }
  
  echo '<h1>Удалил</h1>';
  $id_value = (int)$id_value - 1;
  $id_to_return = $id_value;
  
  //$result_string = "<br><br>Nom -> " . $id_value . " - DELETED OK";
}

if (isset($_REQUEST["_save"]))
{
  $s_sql_string .= "UPDATE `" . $table_name . "` SET " . $s_value . " ".$s_sql_string_where;
  $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query($s_sql_string);// . $s_sql_string_where);
  //echo "<br>",$s_sql_string;
  $result_string = "<br>Nom -> " . mysql_insert_id() . " - SAVE OK";
}
if (isset($_REQUEST["_add"]))
{
  $s_sql_string = "INSERT INTO `" . $table_name . "`(`" . $id_name . "`," . $s_name . ") VALUES (''," . $s_value . ")";
  echo $s_sql_string;
  $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query($s_sql_string);
  $result_string = "<br><br>Nom -> " . mysql_insert_id() . " - ADDED OK";
  //echo "<br><br> ------ ",$table_name," ",$_REQUEST["_bank"];
      // esli eto tablica tovara
    if($table_name=="tbl_tovar"){
      $ver = mysql_query("INSERT INTO `tbl_warehouse_unit` (`warehouse_unit_tovar_id`) VALUES ('".mysql_insert_id()."')");
      $ver = mysql_query("INSERT INTO `tbl_price_tovar` (`price_tovar_id`) VALUES ('".mysql_insert_id()."')");
      $ver = mysql_query("INSERT INTO `tbl_description` (`description_tovar_id`) VALUES ('".mysql_insert_id()."')");
      }
      
    if(isset($_REQUEST["_bank"])){
	// set oplata to hidden
	$bank=explode("*",$_REQUEST["_bank"]);
	echo "<br> Set bank ID",$bank[0]," to NAKL ",$id_value;
	$ver = mysql_query("UPDATE `tbl_bank` SET `bank_operation`='".$id_value."' WHERE `bank_id`='".$bank[0]."'");
	$id_to_return=$id_value;
    }
	//write to habibulin
    if(isset($_REQUEST["_habibulin"])){
	$tmp = $_REQUEST['operation_summ'];
	if($tmp > 0){
	    $tmp = $tmp-($tmp*2);
	}else{
	    $tmp =  $tmp-$tmp-$tmp;
	}
    
	$tQuery = "INSERT INTO `tbl_habibulin`
		    (`habibulin_id`, 
		      `habibulin_data`, 
		      `habibulin_sotrudnik`, 
		      `habibulin_operation`, 
		      `habibulin_money`, 
		      `habibulin_operation_description`, 
		      `habibulin_user`, 
		      `habibulin_user_usd`,
		      `habibulin_parent`,
		      `habibulin_description`) 
		      VALUES (
		      '',
		      '".date("Y-m-d G:i:s")."',
		      '".$_SESSION[BASE.'userid']."',
		      '".$_REQUEST['_operation_nakl']."',
		      '".$tmp."',
		      '".$_REQUEST['operation_memo']."',
		      '".$_REQUEST['_habibulin']."',
		      '5',
		      '".$_REQUEST['_habibulin_parent']."',
		      '".$_REQUEST['_habibulin_description']."')
		      ";
	//echo $tQuery;
	$ver = mysql_query($tQuery);
	$id_to_return=$id_value; //sbivaem indeks na tot chto nam nuzen a ne na habibulinskij
     }
}

if (isset($_REQUEST["_copy"]))
{
 // $s_sql_string = "INSERT INTO `" . $table_name . "`(`" . $id_name . "`," . $s_name . ") VALUES (''," . $s_value . ")";
  echo "CREATE TEMPORARY TABLE `tbl_tmp` AS SELECT * FROM `".$table_name."` WHERE `".$id_name."`='".$id_value."'<br>";
   echo "UPDATE `tbl_tmp` SET `".$id_name."`=NULL<br>";
   echo "INSERT INTO `".$table_name."` SELECT * FROM `tbl_tmp`<br>";
  
 $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query("CREATE TEMPORARY TABLE `tbl_tmp` AS SELECT * FROM `".$table_name."` WHERE `".$id_name."`='".$id_value."'");
  $ver = mysql_query("UPDATE `tbl_tmp` SET `".$id_name."`=NULL");
  $ver = mysql_query("INSERT INTO `".$table_name."` SELECT * FROM `tbl_tmp`");
   
   $last_insert_id = mysql_insert_id(); 
    $ver = mysql_query("DROP TABLE `tbl_tmp`");
   
   if($table_name=="tbl_tovar"){
      $ver = mysql_query("INSERT INTO `tbl_warehouse_unit` (`warehouse_unit_tovar_id`) VALUES ('".$last_insert_id."')");
      
      $ver = mysql_query("CREATE TEMPORARY TABLE `tbl_tmp` AS SELECT * FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id_value."'");
      $ver = mysql_query("UPDATE `tbl_tmp` SET `price_tovar_id`='$last_insert_id'");
      $ver = mysql_query("INSERT INTO `tbl_price_tovar` SELECT * FROM `tbl_tmp`");
      $ver = mysql_query("DROP TABLE `tbl_tmp`");
      
      $ver = mysql_query("CREATE TEMPORARY TABLE `tbl_tmp` AS SELECT * FROM `tbl_description` WHERE `description_tovar_id`='".$id_value."'");
      $ver = mysql_query("UPDATE `tbl_tmp` SET `description_tovar_id`='$last_insert_id'");
      $ver = mysql_query("INSERT INTO `tbl_description` SELECT * FROM `tbl_tmp`");
      $ver = mysql_query("DROP TABLE `tbl_tmp`");
     //$ver = mysql_query("INSERT INTO `tbl_description` (`description_tovar_id`) VALUES ('".$last_insert_id."')");
      }

 $result_string = "<br><br>Nom `".$table_name."` -> " . $last_insert_id. " - ADDED OK";
    // esli eto tablica tovara 
 /*   if($table_name=="tbl_tovar"){ 
    $ver = mysql_query("INSERT INTO `tbl_warehouse_unit` (`warehouse_unit_tovar_id`) VALUES ('".$last_insert_id."')");
    $result_string .= "<br><br>Nom `tbl_warehouse_unit` -> " . $last_insert_id. " - ADDED OK";
    
    //  $ver = mysql_query("INSERT INTO `tbl_price_tovar` (`price_tovar_id`) VALUES ('".mysql_insert_id()."')");
      $ver = mysql_query("CREATE TEMPORARY TABLE `tbl_tmp` AS SELECT * FROM `tbl_price_tovar` WHERE `price_tovar_id`='".$id_value."'");
      $ver = mysql_query("UPDATE `tbl_tmp` SET `price_tovar_id`='".$last_insert_id."'");
      $ver = mysql_query("INSERT INTO `tbl_price_tovar` SELECT * FROM `tbl_tmp`");
      $ver = mysql_query("DROP TABLE `tbl_tmp`");
    $result_string .= "<br><br>Nom `tbl_price_tovar` -> " . $last_insert_id. " - ADDED OK";
 
    //  $ver = mysql_query("INSERT INTO `tbl_description` (`description_tovar_id`) VALUES ('".mysql_insert_id()."')");
      $ver = mysql_query("CREATE TEMPORARY TABLE `tbl_tmp` AS SELECT * FROM `tbl_description` WHERE `description_tovar_id`='".$id_value."'");
      $ver = mysql_query("UPDATE `tbl_tmp` SET `description_tovar_id`='".$last_insert_id."'");
      $ver = mysql_query("INSERT INTO `tbl_description` SELECT * FROM `tbl_tmp`");
      $ver = mysql_query("DROP TABLE `tbl_tmp`");
    $result_string .= "<br><br>Nom `tbl_description` -> " . $last_insert_id. " - ADDED OK";
 
    }*/
}

echo "<title>" . $result_string . "</title>";
echo "<body>" . $result_string . "</body>";

if (mysql_insert_id()==0 or $id_to_return > 1){
 // $id_to_return = "";
}else{
  $id_to_return = mysql_insert_id();
}
echo "<br> page - ";
//echo  $page_to_return,"<br>";
//echo  $id_to_return,"<br>";

if(isset($_REQUEST["_page_to_return"])) header ('Refresh: 1; url=' . $_REQUEST["_page_to_return"] . $id_to_return);

?>
