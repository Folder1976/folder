<?php

//include 'init.lib.php';
//session_start();
//if (!session_verify($_SERVER["PHP_SELF"])){
//  exit();
//}
//connect_to_mysql();
 
 $count = 0;
$this_page_name = "edit_nakl.php";
$this_table_id_name = "operation_id";
$this_table_name_name = "operation_id"; //"`operation_data`, `operation_klient`, `operation_summ`";

$this_table_name = "tbl_operation";
$sort_find = 0;
if(isset($_REQUEST["_sort_find"])) $sort_find=$_REQUEST["_sort_find"];
$iKlient_id = $_GET[$this_table_id_name];
$get_klient_group = 0;
if(isset($_REQUEST['_klienti_group'])) $get_klient_group=$_REQUEST['_klienti_group'];
$iKlient_count = 0;

//echo "gg";
echo "\n<html><head><title>";
echo "Nakl " ,  $iKlient_id , " edit";
echo "</title>";

echo "\n<script type='text/javascript'>";
echo "\nfunction res(a,b){";
echo "\nvar fs = document.getElementById('frameset_" , $iKlient_id , "');";
  echo "\nif (fs.rows == '120,10,*'){";
    echo "\n fs.rows = '120,70%,*';";
    echo "\n }else{";
    echo "\n fs.rows = '120,10,*';
	parent.frames.edit_nakl_fields_",$iKlient_id,".document.location.reload();
    ";
    echo "\n }";
echo "\n}";
echo "\n</script></head>";

echo "\n<frameset id='frameset_" , $iKlient_id , "' rows='120,10,*'>";
echo "\n<frame name='edit_nakl_header_" , $iKlient_id , "' src='edit_nakl_header.php?operation_id=" , $iKlient_id , "&_klienti_group=" , $get_klient_group , "'>";
echo "\n<frame name='edit_tovar_find_" , $iKlient_id , "' src='edit_tovar_find.php?operation_id=" , $iKlient_id , "&_find1=find-str&_find2=find-str'>";
echo "\n<frame name='edit_nakl_fields_" , $iKlient_id , "' src='edit_nakl_fields.php?operation_id=" , $iKlient_id , "'>";
echo "\n</frameset></html>";

?>
