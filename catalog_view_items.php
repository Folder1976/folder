<?php
include 'init.lib.php';
connect_to_mysql();

require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");
//$_REQUEST["parent"];

//echo "ffffffffffffffffffffffff";
//exit();

session_start();
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}

$getName = mysql_query("SET NAMES utf8");
$SgetName = "SELECT 
			`tovar_name_1`,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_2`,
			`currency_name_shot`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_currency`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`price_tovar_curr_2`=`currency_id` and
			`tovar_inet_id_parent`='".$_REQUEST["id"]."'
			LIMIT 0,50
			";

$getName = mysql_query($SgetName);

if (!$getName){
  echo "Query error - tbl_price - ",$SgetName;
  exit();
}

echo "-->",mysql_result($ver,0,"tovar_name_1");
$count=1;
$str="";
$str1="";
echo "<table>";
while($count<mysql_num_rows($getName)){
  $str = explode("||",mysql_result($getName,$count,"tovar_name_1"));
  
  if($count>0) 
      $str1 =explode("||",mysql_result($getName,$count-1,"tovar_name_1"));
  
  if ($str[0]!=$str1[0]){
      $link=mysql_result($getName,$count,"tovar_inet_id");
      $price=mysql_result($getName,$count,"price_tovar_2");
      $pricename=mysql_result($getName,$count,"currency_name_shot");

      echo "<tr><td><a href='/tovar.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      <img src='/resources/products/",$link,"/",$link,".0.small.jpg' width='70' height='70'></a>
      
      </td><td valign='middle'><font size='4'><b>
      <a href='/tovar.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      ",
      $str[0],"</b></font></a>
 
     </td><td valign='middle'><font size='4'><b>
      <a href='/tovar.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      ",
      $price," ",$pricename,"</b></font></a>
 
      </td></tr>";
     }
  $count++;
}
echo "</table>";


?>
