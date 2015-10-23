<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
//if (!session_verify($_SERVER["PHP_SELF"])){
//  exit();
//}

 
 $count = 0;
$this_page_name = "edit_nakl_delivery.php";
$this_table_id_name = "operation_id";
//$this_table_name_name = "operation_id"; //"`operation_data`, `operation_klient`, `operation_summ`";

$this_table_name = "tbl_operation";
//$sort_find = $_GET["_sort_find"];
$iKlient_id = $_GET[$this_table_id_name];
//$get_klient_group = $_GET['_klienti_group'];
$iKlient_count = 0;

$ver = mysql_query("SET NAMES utf8");
$tQuery1 = "SELECT * FROM `tbl_delivery_link`";
//echo $tQuery1;
$ver = mysql_query($tQuery1);
if (!$ver)
{
  echo "Query error `tbl_delivery_link`";
  exit();
}

$deliv = mysql_query("SET NAMES utf8");
$tQuery1 = "SELECT `delivery_name`,`delivery_id` FROM `tbl_delivery`,`tbl_operation`,`tbl_klienti` 
		    WHERE 
		    `operation_id`='".$iKlient_id."'
		    and `operation_klient`=`klienti_id`
		    and `klienti_delivery_id`=`delivery_id`";
//echo $tQuery1;
$deliv = mysql_query($tQuery1);
if (!$deliv)
{
  echo "Query error `tbl_delivery_link`";
  exit();
}

//echo "gg";
echo "\n<html><head><title>";
echo "Nakl " ,  $iKlient_id , " edit";
echo "</title>";

//echo mysql_result($ver,0,0);
//echo "<br>";
//echo mysql_result($deliv,0,0);
$count=0;
$link_str="";
$deliv_name="";
$str_find=" ".mysql_result($deliv,0,0);
  while($count<mysql_num_rows($ver)){
      if (strpos($str_find,mysql_result($ver,$count,"delivery_link_find"))>0){
	$link_str=mysql_result($ver,$count,"delivery_link_route");
	$deliv_name=mysql_result($ver,$count,"delivery_link_find");
      }
    $count++;
  }
  
$deliv_name=str_replace(" ","+",$deliv_name);

//echo "src='edit_nakl_delivery_header.php?operation_id=" , $iKlient_id , "&delivery=" , $deliv_name , "<br>";
//echo $link_str;


echo "\n<frameset id='frameset_" , $iKlient_id , "' rows='160,*'>";
echo "\n<frame name='edit_nakl_delivey_" , $iKlient_id , "' src='edit_nakl_delivery_header.php?operation_id=" , $iKlient_id , "&delivery=" , $deliv_name , "'>";
echo "\n<frame name='delivery_page_" , $iKlient_id , "' src='" , $link_str , "'>";
echo "\n</frameset></html>";

?>
