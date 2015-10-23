<?php
include "config.php";
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

//Новое соединение с базой
$folder= mysqli_connect(DB_HOST,DB_USER,DB_PASS,BASE) or die("Error " . mysqli_error($folder)); 
mysqli_set_charset($folder,"utf8");
//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}

if(!isset($_GET['func'])){
  echo "Нет ключа!";
  die();
}

$setup = mysqli_query($folder, "SET NAMES utf8");
$tQuery = "SELECT * FROM tbl_functions WHERE function_alias = '".mysql_real_escape_string($_GET['func'])."' ORDER BY function_sort ASC";
$res = mysqli_query($folder,  $tQuery);



//header ('Content-Type: text/html; charset=utf8');
echo "<header>
<link rel='stylesheet' type='text/css' href='".HOST_URL."/admin/sturm.css'>\n
<link rel='stylesheet' type='text/css' href='".HOST_URL."/admin/css/style.css'>\n
<script src='".HOST_URL."/js/jquery-2.1.4.min.js'></script>\n
\n
";

while($func = mysqli_fetch_assoc($res)){
  $title = $func['function_name'];
  include $func['function_patch'];
}

echo "<title>$title</title>\n
</header>\n";

?>
