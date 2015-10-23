<?php

function reset_warehouse_on_operation($operation_id,$flag_id){
//echo " - ", $operation_id, " - " , $flag_id;
 $ver = mysql_query("SELECT 
		    `operation_detail_tovar`,
		     `operation_detail_from`,
		     `operation_detail_to` 
		     FROM `tbl_operation_detail` 
		     WHERE 
		     `operation_detail_operation`='".$operation_id."' 
		     and `operation_detail_dell`='".$flag_id."'");
		     
if (!$ver)
{
  echo "Query error ";
  exit();
}
 
reset_warehouse_on_query_result($ver);

}

function reset_warehouse_on_tovar_from_to($tovar,$from,$to){

  $update_str =		"UPDATE 
			`tbl_warehouse_unit` 
			SET 
			`warehouse_unit_".$from."`=
			(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end
			  -(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_from`='".$from."')
			    FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_to`='".$from."')
			,
			`warehouse_unit_".$to."`=
			(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end
			  -(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_from`='".$to."')
			    FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_to`='".$to."')
			WHERE 
			`warehouse_unit_tovar_id`='".$tovar."' ";
  $update = mysql_query($update_str);

}
function reset_warehouse_on_tovar_id($tovar){
$warehouse = mysql_query("SELECT `warehouse_id` FROM `tbl_warehouse`");
$count = 0;

while($count < mysql_num_rows($warehouse)){
    $from = mysql_result($warehouse,$count,0);
    $update_str =		"UPDATE 
			`tbl_warehouse_unit` 
			SET 
			`warehouse_unit_".$from."`=
			(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end
			  -(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_from`='".$from."')
			    FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_to`='".$from."')
			WHERE 
			`warehouse_unit_tovar_id`='".$tovar."' ";
    $update = mysql_query($update_str);
$count++;
//echo $tovar," ",$count,"<br>";
  }
}

function reset_warehouse_on_query_result($ver){
//echo $ver;
 
 $count=0;
  while ($count < mysql_num_rows($ver))
  {
  
  $from = mysql_result($ver,$count,"operation_detail_from");
  $to = mysql_result($ver,$count,"operation_detail_to");
  $tovar = mysql_result($ver,$count,"operation_detail_tovar");
  //echo $tovar,"=",$to,"-",$from,"<br>";
  
  $update_str =		"UPDATE 
			`tbl_warehouse_unit` 
			SET 
			`warehouse_unit_".$from."`=
			(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end
			  -(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_from`='".$from."')
			    FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_to`='".$from."')
			,
			`warehouse_unit_".$to."`=
			(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end
			  -(SELECT case when SUM(`operation_detail_item`) is null then '0' else SUM(`operation_detail_item`) end FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_from`='".$to."')
			    FROM `tbl_operation_detail` WHERE `operation_detail_dell`='0' and `operation_detail_tovar`='".$tovar."' and `operation_detail_to`='".$to."')
			WHERE 
			`warehouse_unit_tovar_id`='".$tovar."' ";
  $update = mysql_query($update_str);
 // echo "<br><br>",$update_str;
   $count++;
  }	

}

function mb_ucfirst($text){
//return mb_strtoupper(mb_substr($text,0,1)).mb_substr($text,1);
}
function set_operation_summ($operation_id){

$sql_str = "UPDATE `tbl_operation` 
		  SET  
		  `operation_sotrudnik`='".$_SESSION[BASE.'userid']."',
		  `operation_summ`=(
				      SELECT SUM(`operation_detail_summ`) as op_sum 
				      FROM `tbl_operation_detail` 
				      WHERE `operation_detail_dell`='0' and `operation_detail_operation`='".$operation_id."'
				      ),
		  `operation_data_edit`='".date("Y-m-d G:i:s")."' 
		  WHERE `operation_id`='".$operation_id."'";
echo $sql_str;
$ver = mysql_query($sql_str);

}

?>
