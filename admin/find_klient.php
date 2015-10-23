<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

//==================================SETUP===========================================
if (!isset($_SESSION[BASE.'lang'])){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
	  WHERE 
	  `setup_menu_name` like '%menu%' or
	  `setup_menu_name` like '%table%' or
	  `setup_menu_name` like '%sys%'
";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM `tbl_klienti` WHERE `klienti_ip`='".$_REQUEST['klienti_ip']."'");
echo "Result on IP -> ",$_REQUEST['klienti_ip'],"<br><br>";

$count=0;
while($count<mysql_num_rows($ver)){
      echo "<a href='edit_klient.php?klienti_id=", mysql_result($ver,$count,'klienti_id'),"' target='_blank'>", mysql_result($ver,$count,'klienti_name_1'),"(",mysql_result($ver,$count,'klienti_phone_1'), ")</a>
	      <a href='operation_list.php?iKlient=", mysql_result($ver,$count,'klienti_id'),"' target='_blank'> - nakl [+]</a>
	      <br>";
 
$count++;
}
if($count==0) echo "NOT USER FOUND!";

?>
