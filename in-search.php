
<?php

$searchq = $_GET['name'];
//echo  $searchq,strlen($searchq),"<br>";
if(strlen(addslashes($searchq))<4) 
  exit();

include 'init.lib.php';
connect_to_mysql();
//upper(".$name.")like'%".mb_strtoupper($find,'UTF-8')."%'";


$getName = mysql_query("SET NAMES utf8");
$SgetName = "SELECT 
			`tovar_name_1`,
			`tovar_artkl`,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_2`,
			`currency_name_shot`,
			`tovar_inet_id_parent`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_currency`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`price_tovar_curr_2`=`currency_id` and (
			upper(`tovar_artkl`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`tovar_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`tovar_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%')";

$getName = mysql_query($SgetName);

if (!$getName)
{
  echo "Query error - tbl_price - ",$SgetName;
  exit();
}

$count=0;
$str[0]="";
$str1[0]="";
echo "<table  class='menu_top'>";
while($count<mysql_num_rows($getName)){
  $str = explode("||",mysql_result($getName,$count,"tovar_name_1"));
  $artkl = explode("/",mysql_result($getName,$count,"tovar_artkl"));
  
  if($count>0) 
      $str1 =explode("||",mysql_result($getName,$count-1,"tovar_name_1"));
  
  if ($str[0]!=$str1[0]){
      $link=mysql_result($getName,$count,"tovar_id");
      $price=mysql_result($getName,$count,"price_tovar_2");
      $pricename=mysql_result($getName,$count,"currency_name_shot");
      
      $full_link = "resources/products/".$link."/".$link.".0.small.jpg";
      if(@fopen($full_link,"r")){
      	//$parent =mysql_result($tovar,$count,"tovar_inet_id_parent");
      }else{
	$link="GR".mysql_result($getName,$count,"tovar_inet_id_parent");
      }

      echo "<tr><td><a href='/index.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      <img src='resources/products/",$link,"/",$link,".0.small.jpg' width='70' height='70'></a>
      
      </td><td valign='middle'><font size='4'><b>
      <a href='/index.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      ",
	$artkl[0], " ",
	$str[0],
	"<b></font></a>
 
     </td><td valign='middle'><font size='4'><b>
      <a href='/index.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      ",
      $price," ",$pricename,"<b></font></a>
 
      </td></tr>";
     }
  $count++;
}
echo "</table>";

//while ($row = mysql_fetch_array($getName))
//      echo $row['tovar_name_1'] . '<br/>';

    //echo $row['name'] . '<br/>';

?>
