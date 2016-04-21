<?php

include 'init.lib.php';
include 'SxGeo22_API/SxGeo.php';
connect_to_mysql();
$SxGeo = new SxGeo('SxGeo22_API/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY); // Самый быстрый режим 

session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

$reload = 30;
$minut = 20;

if(isset($_GET['minut'])) $minut=$_GET['minut'];

$time = 60*$minut;
$date_tmp = date("Y-m-d G:i:s",time()-$time);
//==================================SETUP===========================================
$ver = mysql_query("SET NAMES utf8");
  $tQuery = "SELECT 
		`klienti_name_1`,
		`log_ip`,
		`log_web`,
		`log_user_id`,
		COUNT(log_date) as count 
		FROM `tbl_log`, `tbl_klienti` 
		WHERE `log_date`>'$date_tmp'
		and `log_user_id`<>'13828'
		and `klienti_id`=`log_user_id`
		GROUP BY `log_ip`
		ORDER BY `log_id` DESC";
		
		//ORDER BY count DESC";
$ver = mysql_query($tQuery);

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'>
      <title>LOG</title>
      </header>";

echo "<body><br>
History - $minut min (can change param [minut])<br>
<table>";

//echo $tQuery;
$count=0;
while($count<mysql_num_rows($ver)){
$ip = mysql_result($ver,$count,"log_ip");
//$SxGeo->getCityFull($ip); // возвращает полную информацию о городе и регионе
$location  = $SxGeo->get($ip);

$city = $location['city'];
$country = $location['country'];
//print_r($city['name_ru']);
//print_r($country['iso']);
echo "
  <script src='JsHttpRequest.js'></script>
  <script type=\"text/javascript\">

function chat_user_block(ip){
      var req=new JsHttpRequest();
      alert('added');
      req.open(null,'../get_chat_msg.php',true);
      req.send({block:ip});
}
</script>";
    echo "<tr>
	 <td> pages: <b>",
	 mysql_result($ver,$count,"count"),"</b>
	 </td><td><a href='log_list_page.php?ip=",mysql_result($ver,$count,"log_ip"),"' target='_blank'>log view: ",
	 mysql_result($ver,$count,"log_ip"),"</a>
	 </td><td> ";
	 
	 if(mysql_result($ver,$count,"log_user_id")>0){
	    echo "<a href='edit_klient.php?klienti_id=",mysql_result($ver,$count,"log_user_id"),"' target='_blank'>",mysql_result($ver,$count,"klienti_name_1"),"</a>";
	 }
	 echo "
	 </td><td> - ",
	 $country['iso']," ",$city['name_ru'],"
	 </td>
	 <td>
	 <a href='javascript:chat_user_block(\"",mysql_result($ver,$count,"log_ip"),"\");'>black ip list</a>
	 </td>
	 </tr>";

$count++;
}
echo "</table>";
unset($SxGeo);

echo "</body>";

ini_set('display_errors','Off');
if(isset($_GET['minut'])){ 
    header ("Refresh: ".$reload."; url=log_list.php?minut=".$_GET['minut']);
  }else{
    header ("Refresh: ".$reload."; url=log_list.php");
  }
?>
