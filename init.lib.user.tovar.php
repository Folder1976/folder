<?php

function tovar_on_ware_name($count,$setup){
$str = "";
    if ($count <0) $str .= $setup['tovar wait'];
     elseif ($count ==0) $str .= $setup['tovar none'];
     elseif ($count >5) $str .= $setup['tovar more'];
      else $str .= $setup['tovar many'];
      
return $str;

}

function tovar_on_ware($id){
 
      $ver = mysql_query("SET NAMES utf8");
 
      $tQuery = "SELECT 
		    `tovar_parent_nom`
		  FROM 
		     `tbl_tovar`
		     LEFT JOIN `tbl_parent` ON `tovar_parent`=`tovar_parent_id`
		  WHERE
			 `tovar_id`='".$id."'";
			 
    $ver = mysql_query($tQuery) or die($tQuery.'<br>'.mysql_error());
    //echo $id,"<br>";
   
$war_key = mysql_result($ver,0,0);

$tmp="";
$zakaz_ware_id = 9;
$sum = 0;
$zakaz = 0;
$count=0;
//echo $id; die();
  while(isset($war_key[$count])){
   if($war_key[$count]=="1" and ($count+1) <> $zakaz_ware_id) $tmp .= " `warehouse_unit_".($count)."` +"; //ne sczitaem 9 sklad ZAKAZ
     $count++;
  }
  
  
     $ver = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT 
		    (".substr($tmp,0,-1).") as war_sum
		  FROM 
		     `tbl_warehouse_unit`
		  WHERE
		      `warehouse_unit_tovar_id`='".$id."'
";
if($tmp=="")
{
  $sum = 0;
}else{
  $ver = mysql_query($tQuery);
  $sum = mysql_result($ver,0,0);
  if($sum < 0) $sum = 0;
}
      $ver = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT 
		    `warehouse_unit_".$zakaz_ware_id."`
		  FROM 
		     `tbl_warehouse_unit`
		  WHERE
		      `warehouse_unit_tovar_id`='".$id."'";
      $ver = mysql_query($tQuery);
      $zakaz = 0;
      if(mysql_num_rows($ver) > 0){
       $zakaz = mysql_result($ver,0,0);
      }
 // echo $zakaz," - ",$sum;
  if($sum == 0){
      if($zakaz > 0){
	  return -1;
      }else{
	  return 0;
      }
  }else{
      return $sum;
  }
      
}


function verify_order_and_send_email($order) {
  $nakl = mysql_query("SET NAMES utf8");
    $tQuery = "SELECT 
	  `operation_detail_tovar`
	  FROM
	  `tbl_operation_detail`
	  WHERE
	  `operation_detaol_dell`='0' and
	  `operation_detail_operation`='".$order."'
	  ";
  $nakl = mysql_query($tQuery);
  
$count=0;
while($count<mysql_num_rows($nakl)){
  
  echo tovar_on_ware(mysql_result($nakl,$count,0));

$count++;
}
  
  
}
?>
