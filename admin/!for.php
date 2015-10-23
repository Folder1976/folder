<?php

$dbhost = "127.0.0.1";
$dbname = "STURM";
$dbuser = "root";
$dbpasswd = "folder1976";
$count = 0;
$this_page_name = "edit_nakl.php";
$this_table_id_name = "operation_id";
$this_table_name_name = "operation_id"; //"`operation_data`, `operation_klient`, `operation_summ`";

$this_table_name = "tbl_operation";
$sort_find = $_GET["_sort_find"];
$iKlient_id = $_GET[$this_table_id_name];
$get_klient_group = $_GET['_klienti_group'];
$iKlient_count = 0;

$dbcnx = mysql_connect($dbhost, $dbuser, $dbpasswd);
if (!$dbcnx)
{
  echo "Not connect to MySQL";
  exit();
}

if (!mysql_select_db($dbname,$dbcnx))
{
  echo "No base present";
  exit();
}
#echo $tQuery;

$date = date("Y-m-d G:i:s");
if ($iKlient_id==0){
  $ver = mysql_query("SET NAMES utf8");
  $tQuery = "INSERT INTO `tbl_operation` SET ";
  $tQuery .= "`operation_data`='".$date."',";
  $tQuery .= "`operation_klient`='1',";
  $tQuery .= "`operation_prodavec`='1',";
  $tQuery .= "`operation_sotrudnik`='1',";
  $tQuery .= "`operation_data_edit`='".$date."',";
  $tQuery .= "`operation_status`='0',";
  $tQuery .= "`operation_summ`='0',";
  $tQuery .= "`operation_memo`='new',";
  $tQuery .= "`operation_inet_id`='0',";
  $tQuery .= "`operation_dell`='0'";

  $ver = mysql_query($tQuery);
    if (!$ver){
      echo "Query error";
      exit();
    }
}
echo $tQuery," -> ", mysql_insert_id();
?>
