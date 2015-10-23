
<?php
include 'init.lib.php';

connect_to_mysql();
session_start();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");


header ('Content-Type: text/html; charset=utf8');


$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `operation_detail_id`,`operation_detail_operation` 
	    FROM `tbl_operation_detail` 
	    WHERE `operation_detail_dell`='0' and
	    `operation_detail_operation`>'10'
	   ";
$ver = mysql_query($tQuery);
//echo $tQuery;
$count =0;
while($count < mysql_num_rows($ver)){

$detail_id = mysql_result($ver,$count,"operation_detail_id");
$operation_id = mysql_result($ver,$count,"operation_detail_operation");
$restore_id = 0;

     
  	      $oper = mysql_query("SET NAMES utf8");
	      $tQuery = "SELECT `operation_id`,
				`operation_dell`
			  FROM `tbl_operation` 
			  WHERE `operation_id`='$operation_id'
			  ";
	      $oper = mysql_query($tQuery);
	      
	      if(empty($oper)){
		  echo $operation_id," is not found<br>";
	      }
	      if(mysql_result($oper,0,"operation_dell")==1){
		  echo $operation_id," is deleted<br>";
		  
		  $rest = mysql_query("SET NAMES utf8");
		  $tQuery = "UPDATE `tbl_operation_detail`
			SET `operation_detail_dell`='1'
			WHERE `operation_detail_operation`='$operation_id'";
		  $rest = mysql_query($tQuery);	      
	      
	      }
	      /*$restore_id = mysql_result($det,0,"operation_detail_id");
	      
	      if($restore_id>10){
		  //echo "(",$restore_id,")";
*/

     
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
