<?php
//include 'lib_ouf.php';
include 'PHPExcel.php';

echo $_SERVER["QUERY_STRING"],"<BR>";
ECHO $_SERVER["PHP_SELF"],"<BR>";
ECHO $_SERVER["REQUEST_URI"],"<BR>";
//include_once 'PHPExcel/IOFactory.php';
echo "<br>start";
$excel = new PHPExcel();
$sheet->getActiveSheetIndex(0);
$sheet=$excel->getActiveSheet();
$excel;

//$sheet->setCellValue('A1','ZALUPA');




echo "<br>end";


// PRIVAT ================================================================
/*$text=$_REQUEST["text"];
echo "
\n<form method='post' action='test.php'>
  \n<input type='submit' name='action' value='Send'/>
  \n<textarea cols='40' rows='20' name='text'>",$text,"</textarea><br>
\n</form>
";

$text = str_replace("\n","||",$text);
$text = str_replace("\t","|",$text);
$row = explode("||",$text);

$date_add= "111111";//new DateTime;
$count=0;
while ($row[$count]){
  echo $row[$count],"<br>";
  if ($row[$count][0]=="Сегодня"){
  $date_add="11.12.12";
  $count++;}
  
  echo $date_add," ",$row[$count][1]," ",$row[$count][2]," ",$row[$count][3],"<br>";
  
  
  
  $count++;
  }
  */
// END PRIVAT -==================================================================  
  
  
//$test = parent.document.getelementsbytagname('frameset')[0];
/*echo  "Tvoj IP -> ";// , $test;

$ip = getenv(HTTP_X_FORWARDED_FOR);
if(!$ip){
  $ip = getenv(REMOTE_ADDR);
  }else{
  $tmp = ",";
    if(strlen(strstr($ip,$tmp))!=0){
    $ips = explode($tmp,$ip);
    $ip = $ips[count($ips)-1];
    }
}
header ('Content-Type: text/html; charset=utf8');

echo trim($ip);

$tmp = file_get_contents("http://sturm.com.ua/get_orders.php?pass=KLJGbsfgv8y9JKbhlis&orders_status=1");
$tmpmas = explode("||",$tmp);

$count=0;
while ($tmpmas[$count]){
echo "<br>", $tmpmas[$count];

$count++;
}*/


?>
