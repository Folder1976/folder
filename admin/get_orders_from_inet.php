<?php
//echo "Import ogders \n TURN OFF";
//exit();

include 'init.lib.php';
connect_to_mysql();
$time = 60;

session_start();
  if (isset($_REQUEST['lang'])){
    $_SESSION[BASE.'lang'] = $_REQUEST['lang'];
  }else if (!$_SESSION[BASE.'lang']){
    $_SESSION[BASE.'lang']=1;
  }
$a = 2;
$b = 4;
$c = 15;
  
//
$date_tmp = date("Y-m-d G:i:s",time()-$time);//-strtotime(60); 
//echo $date_tmp;//, " ",date("Y-m-d G:i:s");
//$date_tmp = $date_tmp - time(60);
  $log = mysql_query("SET NAMES utf8");
  $log = mysql_query("SELECT COUNT(log_date) as count FROM `tbl_log` WHERE `log_date`>'$date_tmp'");
//echo "SELECT COUNT(log_date) as count FROM `tbl_log` WHERE `log_date`>'$date_tmp'";


  $stat = mysql_query("SET NAMES utf8");
  $stat = mysql_query("SELECT `operation_status_id`, `operation_status_name` FROM `tbl_operation_status`
			WHERE `operation_status_id`='$a' or `operation_status_id`='$b' or `operation_status_id`='$c'
			ORDER BY `operation_status_id` ASC");
  
 $nakl = mysql_query("SELECT `operation_inet_id`,`operation_status` FROM `tbl_operation` WHERE `operation_dell`='0' and 
		      (`operation_status`='$a' or `operation_status`='$b' or `operation_status`='$c')");
 $comm_id = mysql_query("SELECT `klienti_last_comment` FROM `tbl_klienti` WHERE `klienti_id`='".$_SESSION[BASE.'userid']."'");
  $last_comment=0;
  if(mysql_result($comm_id,0,"klienti_last_comment")>=1)$last_comment=mysql_result($comm_id,0,"klienti_last_comment");
  $comm_count = mysql_query("SELECT `comments_id` FROM `tbl_comments` WHERE `comments_id`>'".$last_comment."'");
  
 $count_comm=0;
  while($count_comm < mysql_num_rows($comm_count)){
  $count_comm++;
  }

  
 $count=0;
 $count_a = 0;
 $count_b = 0;
 $count_c = 0;
 
  while($count < mysql_num_rows($nakl)){
      if(mysql_result($nakl,$count,"operation_status")==$a) $count_a++;
      if(mysql_result($nakl,$count,"operation_status")==$b) $count_b++;
      if(mysql_result($nakl,$count,"operation_status")==$c) $count_c++;
  $count++;
  }
header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
 //echo "<a href='get_orders_from_inet.php'>Reload</a>";
    echo "<a href='log_list.php' target='_blank'>";
    echo "<b>pages view : ",mysql_result($log,0,"count")," / ".($time)."s.</a>";
 user_menu_lang(); 
 if ($count>0){

    echo "<br><a href='operation_list.php?iStatus=$a' target='_blank'>";
    echo "<b>",mysql_result($stat,0,"operation_status_name"),":", $count_a,"</a>";

    echo "<br><a href='operation_list.php?iStatus=$b' target='_blank'>";
    echo "<b>",mysql_result($stat,1,"operation_status_name"),":", $count_b,"</a>";

    echo "<br><a href='operation_list.php?iStatus=$c' target='_blank'>";
    echo "<b>",mysql_result($stat,2,"operation_status_name"),":", $count_c,"</a>";
}else{
    echo "<b>NO ORDERS";
  }
 if ($count_comm>0){
    echo "<br><b><a href='edit_comment.php' target='_blank'
    >New comments (",$count_comm,")</a>";
   } 
  


header ("Refresh: ".$time."; url=get_orders_from_inet.php");
?>
