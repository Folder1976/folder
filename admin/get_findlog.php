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
$ver = mysql_query("SELECT * FROM `tbl_find` ORDER BY `find_status` DESC");

header ('Content-Type: text/html; charset=utf8');

echo "
    <!DOCTYPE html>
  <html>
  <header><link rel='stylesheet' type='text/css' href='sturm.css'>
  <title>",$m_setup['menu findlog'],"</title>
  </header>
  
  <body>
  <table><tr><th>Количество поисков</th><th>Запрос</th>";

$count=0;
while($count<mysql_num_rows($ver)){
echo "<tr><td>11";
echo    mysql_result($ver,$count,"find_status");
echo "</td><td>";
echo    mysql_result($ver,$count,"find_user");
echo "</td></tr>";
$count++;
}

echo "</table>";
echo "</body>
      </html>";

?>

