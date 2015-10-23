<?php
include 'init.lib.php';
connect_to_mysql();
session_start();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");


header ('Content-Type: text/html; charset=utf8');


$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `operation_id`,`operation_summ` 
	    FROM `tbl_operation` 
	    WHERE `operation_id`>'10' and
	    `operation_status` <> '9' and
	    `operation_dell`='0'
	    ORDER BY `operation_id` DESC
	   ";
$ver = mysql_query($tQuery);
//echo $tQuery;
$count =0;
while($count < mysql_num_rows($ver)){

$operation_id = mysql_result($ver,$count,"operation_id");
$operation_summ = ceil(abs(number_format(mysql_result($ver,$count,"operation_summ"),2,".","")));
$restore_id = 0;

     
      if(get_summ($operation_id)!=ceil(abs($operation_summ))){
	  echo "<a href='operation_list.php?operation_id=$operation_id' target='_blank'>Nakl:",$operation_id," nakl summ = ",$operation_summ," // real summ = ".get_summ($operation_id)."</a><br>";
	    
	  /*    $det = mysql_query("SET NAMES utf8");
	      $tQuery = "SELECT `operation_detail_id`
			  FROM `tbl_operation_detail` 
			  WHERE `operation_detail_operation`='$operation_id' and
			  `operation_detail_dell`='1'
			  ORDER BY `operation_detail_id` DESC
			  LIMIT 0,1";
	      $det = mysql_query($tQuery);
	      
	      $restore_id = mysql_result($det,0,"operation_detail_id");
	      
	      if($restore_id>10){
		  //echo "(",$restore_id,")";
		  $rest = mysql_query("SET NAMES utf8");
		  $tQuery = "UPDATE `tbl_operation_detail`
			SET `operation_detail_dell`='0'
			WHERE `operation_detail_id`='$restore_id'";
		  $rest = mysql_query($tQuery);
	      }*/
      }
$count++;
}
//header ('Refresh: 300; url=restore_nakl.php');

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
}



?>
