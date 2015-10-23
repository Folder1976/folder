<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

$reload = 30;
$minut = 300;
$time = 60*$minut;
$date_tmp = date("Y-m-d G:i:s",time()-$time);
//==================================SETUP===========================================
$ver = mysql_query("SET NAMES utf8");
  $tQuery = "SELECT 
		`log_ip`,
		`log_web`,
		`log_user_id`,
		`log_date`
		FROM `tbl_log` 
		WHERE `log_date`>'$date_tmp' and
		`log_ip`='".$_REQUEST['ip']."'
		
		ORDER BY `log_date` DESC
		";
$ver = mysql_query($tQuery);

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'>
      <title>LOG</title>
      </header>";

echo "<body><br>
History - $minut min<br>
<table>";
//echo $tQuery;
$count=0;
while($count<mysql_num_rows($ver)){

	
    echo "<tr>
	  <td>",mysql_result($ver,$count,"log_date"),"
	  </td><td><a href='..",mysql_result($ver,$count,"log_web"),"' target='logview_",$_REQUEST['ip'],"'>
		pages: <b>",
		mysql_result($ver,$count,"log_web"),"</b></a>
	 </td><td>",
	 mysql_result($ver,$count,"log_ip"),"
	 </td><td> user:",
	 mysql_result($ver,$count,"log_user_id"),"
	 </td><td> - 
	 
	 </td></tr>";

$count++;
}
echo "</table>";

echo "</body>";
header ("Refresh: ".$reload."; url=log_list_page_header.php?ip=".$_REQUEST['ip']);
?>
