<?php

include 'nakl.lib.php';
test();

$dbhost = "62.149.10.251/pma/";
$dbname = "sturm_com_ua";
$dbuser = "sturm_com_ua";
$dbpasswd = "v7PZqzrnGENwzp";
$count = 0;

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

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM `e_orders_items`");

if (!$ver)
{
  echo "Query error";
  exit();
}

header ('Content-Type: text/html; charset=utf8');
echo "\n<body>\n";

echo "\n<table border=1>";
echo "<tr><th>Date</th><th>Nakl</th><th>Summ</th><th>Status</th><th>Klient</th><th>Memo</th></tr>";
#echo mysql_num_rows($ver);
/*
while ($count < mysql_num_rows($ver))
{
  echo "\n<tr>";
  echo "<td>", mysql_result($ver,$count,'operation_data'), "</td>";
  echo "<td>", mysql_result($ver,$count,'operation_id'), "</td>";
  echo "<td>", mysql_result($ver,$count,'operation_summ'), "</td>";
  echo "<td>", mysql_result($ver,$count,'operation_status_name'), "</td>";
  echo "<td>", mysql_result($ver,$count,'klienti_name_1'),"(",mysql_result($ver,$count,'klienti_phone_1'), ")</td>";
  echo "<td>", mysql_result($ver,$count,'operation_memo'), "</td>";
  #echo "<td>", mysql_result($ver,$count,6), "</td>";
  #echo "<td>", mysql_result($ver,$count,7), "</td>";
  #echo "<td>", mysql_result($ver,$count,8), "</td>";
  echo "</tr>";

$count++;
}

echo "\n</table>";

echo "\n</body>";
//print_r("test");

//print_r(phpinfo());
?>
