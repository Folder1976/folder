<?php
include 'init.lib.php';
connect_to_mysql();
session_start();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");


header ('Content-Type: text/html; charset=utf8');


$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `tbl_operation_detail`.*, 
		    `tovar_artkl`,
		    `tovar_name_1`
	    FROM `tbl_operation_detail`,`tbl_tovar` 
	    WHERE `operation_detail_operation`>'10' and
	    `tovar_id`=`operation_detail_tovar` and
	    `operation_detail_dell`='0' and
	    (`operation_detail_summ` <> (`operation_detail_item` * `operation_detail_price`) / 100 * (100 -`operation_detail_discount`))
	    ORDER BY `operation_detail_operation` ASC
	   ";
$ver = mysql_query($tQuery);
//echo $tQuery;
$count =0;
$count_find = 0;
$html = "";
//echo "<br>Find ",mysql_num_rows($ver)," - errors!!!<br>";
while($count < mysql_num_rows($ver)){

$operation_id = mysql_result($ver,$count,"operation_detail_operation");
$operation_summ = ceil(abs(number_format(mysql_result($ver,$count,"operation_detail_summ"),0,".","")));
$operation_item = ceil(abs(number_format(mysql_result($ver,$count,"operation_detail_item"),0,".","")));
$operation_disc = ceil(abs(number_format(mysql_result($ver,$count,"operation_detail_discount"),0,".","")));
$operation_price = ceil(abs(number_format(mysql_result($ver,$count,"operation_detail_price"),0,".","")));
$summ_tmp = ceil(abs(number_format((($operation_item * $operation_price) / 100 * (100 -$operation_disc)),0,".","")));
$restore_id = 0;

   if($summ_tmp <> $operation_summ)  {
    if(($summ_tmp - $operation_summ)>2)  {
      if($operation_summ > 0){
	$html .= "<a href='operation_list.php?operation_id=$operation_id' target='_blank'>Nakl:
		      $operation_id field summ = $operation_summ ($summ_tmp) 
		      // real field = $operation_item * $operation_price ( $operation_disc %) </a> ".
		      mysql_result($ver,$count,"tovar_artkl")." ".mysql_result($ver,$count,"tovar_name_1")."<br>";
	$count_find ++;      
	}
      }
  }
$count++;
}
echo "Find $count_find errors<br>";
echo $html;

//header ('Refresh: 300; url=restore_nakl.php');
/*
function get_summ($operation_id) {
      $ver = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT SUM(`operation_detail_summ`) as oper_summ
	    FROM `tbl_operation_detail` 
	    WHERE `operation_detail_operation`='$operation_id' and
		  `operation_detail_dell`='0'";
      $ver = mysql_query($tQuery);

  if($ver){      
    return ceil(abs(number_format(mysql_result($ver,0,"oper_summ"),2,".","")));
  }else{
    return 0;
  }
}*/



?>
