<?php

include 'init.lib.php';
//include 'nakl.lib.php';
session_start();
connect_to_mysql();
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}


//========================================================================================================
header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

echo "\n<script type='text/javascript'>";
echo "function setdate(msg){
	 
	 alert(msg);
	 document.getElementById('info').style.height = '600px';
	  //document.getElementById('info').style.width = '400px';
	  document.getElementById('info').innerHTML = msg;
	  if(msg==''){
	  	  document.getElementById('info').style.display = 'none';
	  }else{
	  	  document.getElementById('info').style.display = 'block';
	  }
}

</script>
";




echo "<body><form>";
//if (isset($_GET['date*1'])) echo "выбрана дата 1 ".$_GET['date*1'];
//if (isset($_GET['date*2'])) echo "выбрана дата 2 ".$_GET['date*2'];

echo "<input type='text' name='date*1' id='date*1'><br>";
echo "<input type='text' name='date*2' id='date*2'><br>";

my_calendar(array(date("Y-m-d")),"*1");
my_calendar(array(date("Y-m-d")),"*2"); 


echo "</form></body>";


function my_calendar($fill=array(),$key) { 
  $month_names=array("январь","февраль","март","апрель","май","июнь",
  "июль","август","сентябрь","октябрь","ноябрь","декабрь"); 
  if (isset($_GET['y'.$key])) $y=$_GET['y'.$key];
  if (isset($_GET['m'.$key])) $m=$_GET['m'.$key]; 
  if (isset($_GET['date'.$key]) AND strstr($_GET['date'.$key],"-")) list($y,$m)=explode("-",$_GET['date'.$key]);
  if (!isset($y) OR $y < 1970 OR $y > 2037) $y=date("Y");
  if (!isset($m) OR $m < 1 OR $m > 12) $m=date("m");

  $month_stamp=mktime(0,0,0,$m,1,$y);
  $day_count=date("t",$month_stamp);
  $weekday=date("w",$month_stamp);
  if ($weekday==0) $weekday=7;
  $start=-($weekday-2);
  $last=($day_count+$weekday-1) % 7;
  if ($last==0) $end=$day_count; else $end=$day_count+7-$last;
  $today=date("Y-m-d");
  $prev=date('?\m'.$key.'=m&\y=Y',mktime (0,0,0,$m-1,1,$y));  
  $next=date('?\m'.$key.'=m&\y=Y',mktime (0,0,0,$m+1,1,$y));
  $i=0;

  
echo "
  <table border=1 cellspacing=0 cellpadding=2> 
  <tr>
  <td colspan=7> 
   <table width=\"100%\" border=0 cellspacing=0 cellpadding=0> 
    <tr> 
     <td align=\"left\"><a href=\"", $prev ,"\">&lt;&lt;&lt;</a></td> 
     <td align=\"center\">", $month_names[$m-1]," ",$y ,"</td> 
     <td align=\"right\"><a href=\"", $next ,"\">&gt;&gt;&gt;</a></td> 
    </tr> 
   </table> 
  </td> 
 </tr> 
 <tr><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td><tr>
"; 
  for($d=$start;$d<=$end;$d++) { 
    if (!($i++ % 7)) echo " <tr>\n";
    echo '  <td align="center">';
    if ($d < 1 OR $d > $day_count) {
      echo "&nbsp";
    } else {
      $now="$y-$m-".sprintf("%02d",$d);
     // if (is_array($fill) AND in_array($now,$fill)) {
        echo '<b><a href="'.$_SERVER['PHP_SELF'].'?date'.$key.'='.$now.'">'.$d.'</a></b>'; 
      /*} else {
        echo $d;
      }*/
    } 
    echo "</td>\n";
    if (!($i % 7))  echo " </tr>\n";
  } 

echo "</table> ";
}  
?>
