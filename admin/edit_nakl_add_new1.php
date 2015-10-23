<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"])){
  exit();
}






$date = date("Y-m-d G:i:s");

  $ver = mysql_query("SET NAMES utf8");
  $tQuery = "INSERT INTO `tbl_operation` SET ";
  $tQuery .= "`operation_data`='".$date."',";
  $tQuery .= "`operation_klient`='1',";
  $tQuery .= "`operation_prodavec`='1',";
  $tQuery .= "`operation_sotrudnik`='1',";
  $tQuery .= "`operation_data_edit`='".$date."',";
  $tQuery .= "`operation_status`='0',";
  $tQuery .= "`operation_summ`='0',";
  $tQuery .= "`operation_memo`='-',";
  $tQuery .= "`operation_inet_id`='0',";
  $tQuery .= "`operation_dell`='0'";

  $ver = mysql_query($tQuery);
    if (!$ver){
      echo "Query error";
      exit();
    }
 $iKlient_id=mysql_insert_id();
 
echo "Add new nakl #",$iKlient_id; 
//echo $tQuery;


header ('Refresh: 1; url=' . 'edit_nakl.php?operation_id=' . $iKlient_id . '&_klienti_group=0');



?>
