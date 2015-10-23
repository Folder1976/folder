<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}

//echo "ggg";

header ('Content-Type: text/html; charset=utf8');
echo "<title>Get BANK</title>";
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
// PRIVAT ================================================================
$text="";
if(isset($_REQUEST["text"]))$text = $_REQUEST["text"];

echo "
\n<form method='post' action='get_bank.php'>
  \n<input type='submit' name='action' value='LOAD'/><br>
  <input type='hidden' name='individual' value='individual'/> 
  Data:<input type='text' name='date' value='".date("Y-m-d")."'/><br>
  Time:<input type='text' name='time' value='".date("G:i:s")."'/><br>
  Summ:<input type='text' name='summ' value=''/><br>
  Memo:<textarea cols='80' rows='2' name='memo'>",$text,"</textarea><br>
\n</form>
<br><bl><br>
";


echo "
\n<form method='post' action='get_bank.php'>
  \n<input type='submit' name='action' value='LOAD'/><br>
  \n<textarea cols='80' rows='20' name='text'>",$text,"</textarea><br>
\n</form>
";

if(isset($_REQUEST['individual'])){
    $date_add = $_REQUEST['date'];
    $time = $_REQUEST['time'];
    $sum = $_REQUEST['summ'];
    $curr = "UAH";
    $row_str = $_REQUEST['memo'];
    $row_str = str_replace("'","\"",$row_str);
    
    $Query = "INSERT INTO `tbl_bank` VALUES (
	      '',
	      '1',
	      '".$date_add." ".$time."',
	      '".$sum."',
	      '".$curr."',
	      '".$row_str."',
	      '0'
	      )";
    $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query($Query);
      if (!$ver)
      {
	echo "Query error - WRITE";
	echo "<br>",$Query;
	exit();
      }else{
	echo "- ADDED.<br>";
      }
  //echo "ggg";

}else{
$text = str_replace("\n","||",$text);
$text = str_replace("\t","|",$text);
$text = str_replace("||Дата обработки транзакции","",$text);
//echo $text;
$row = explode("||",$text);

$date_add= date("Y-m-d");
$count=0;

while ($row[$count]){
  $row_str = explode("|",$row[$count]);
  
  if (strpos($row[$count],"|")<1){//"Сегодня "){
 // echo strpos($row_str[0],"|"),strpos($row_str[0],"чера"),"<br>";
      if (strpos($row_str[0],"годн")>0){
	  $date_add=date("Y-m-d");
	  $count++;
	  $row_str = explode("|",$row[$count]);
    }elseif (strpos($row_str[0],"чора")>0){
	  $date_add=date("Y-m-d",strtotime('-1 day'));
	  $count++;
	  $row_str = explode("|",$row[$count]);
    }elseif (strpos($row_str[0],"чера")>0){
	  $date_add=date("Y-m-d",strtotime('-1 day'));
	  $count++;
	  $row_str = explode("|",$row[$count]);
     }else{
	  $date_tmp = explode(".",$row_str[0]);
	  $date_add = $date_tmp[0].".".$date_tmp[1].".20".$date_tmp[2];
	  $date_add=date("Y-m-d",strtotime($date_add));
	  $count++;
	  $row_str = explode("|",$row[$count]);
      }
  }
  
  echo $date_add." 1-".$row_str[1]." 2-".$row_str[2]." 3-".$row_str[3]." 4-".$row_str[4]." <br>"; //".$row_str[3]."
 $sum = str_replace(" ","",$row_str[3]);
    if (strpos($sum,"UAH")>0){
      $curr="UAH";
    }elseif (strpos($sum,"USD")>0){
      $curr="USD";
    }elseif (strpos($sum,"PLN")>0){
      $curr="PLN";
    }elseif (strpos($sum,"RUB")>0){
      $curr="RUB";
    }elseif (strpos($sum,"ГРН")>0){
      $curr="ГРН";
    }
 // echo $sum," -- ";
  $sum=str_replace($curr,"",$sum);
 // echo $sum," -- ";
 //  $sum = number_format($sum,2,".","");
 // echo $sum," -- ";
  
  $time =str_replace(" ","",$row_str[0]);
  $row_str[1] =str_replace("'","\"",$row_str[1]);
  echo $date_add," ",$row_str[0]," ",$sum," ",$curr," - finding... ";
  
  $Query = "SELECT `bank_id` FROM `tbl_bank` WHERE 
      `bank_date`='".$date_add." ".$time."' 
  and `bank_description`='".$row_str[1]."' 
  and `bank_sum`='".$sum."'
    ";
  $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query($Query);
    if (!$ver)
    {
      echo "Query error - SELECT<br>",$Query,"<br>";
      exit();
    }
  echo mysql_num_rows($ver);
  //echo $Query;
  if($sum<=0){
    echo "- NEGATIWE! not write.<br>";
  }else{  
    if(mysql_num_rows($ver)){
      echo "- OK.<br>";
    }else{
    $Query = "INSERT INTO `tbl_bank` VALUES (
	      '',
	      '1',
	      '".$date_add." ".$time."',
	      '".$sum."',
	      '".$curr."',
	      '".$row_str[1]."',
	      '0'
	      )";
    $ver = mysql_query("SET NAMES utf8");
  $ver = mysql_query($Query);
      if (!$ver)
      {
	echo "Query error - WRITE";
	exit();
      }else{
	echo "- ADDED.<br>";
      }
    
    }
  }
  
  
  
  $count++;
  }
}// END PRIVAT -==================================================================  
  
  


?>
