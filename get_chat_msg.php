<?php
include 'init.lib.php';
//session_start();
connect_to_mysql();

header ('Content-Type: text/html; charset=utf8');
require("JsHttpRequest.php");
$JsHttpRequest=new JsHttpRequest("utf8");//"windows-1251");

if(verify_black_list($_SERVER['REMOTE_ADDR']))
{
  echo "Your IP - ",$_SERVER['REMOTE_ADDR']," blocked!";
  exit();
}

if(isset($_REQUEST['tmp'])){
   $chat = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT `chat_tmp_ans`
	      FROM `tbl_chat_tmp`
	      WHERE `chat_tmp_id`='".$_REQUEST['tmp']."'	
	      ";
	     
    $chat = mysql_query($tQuery);
    echo mysql_result($chat,0,0);
    exit();
}elseif(isset($_REQUEST['block'])){
   $chat = mysql_query("SET NAMES utf8");
      $tQuery = "
	      INSERT INTO `tbl_black_ip_list` 
	      (`black_ip_ip`) VALUES
	      ('".$_REQUEST['block']."')	
	      ";
	     
    $chat = mysql_query($tQuery);

}elseif(isset($_REQUEST['dell'])){
   $chat = mysql_query("SET NAMES utf8");
      $tQuery = "
	      DELETE FROM `tbl_chat`
	      WHERE `chat_id` = '".$_REQUEST['dell']."'	
	      ";
    $chat = mysql_query($tQuery);

}elseif(isset($_REQUEST['msg'])){// ADD MSG ===============================================
  if(!empty($_REQUEST['msg'])){
      $msg = $_REQUEST['msg'];
      $userid=-2; //guest
      if(isset($_SESSION[BASE.'userid'])) $userid = $_SESSION[BASE.'userid'];
      $user_to=-2; //guest
      if(isset($_REQUEST['usr'])) $user_to = $_REQUEST['usr'];
      
      $dell = array("<",
		    ">",
		    "img",
		    "src",
		    "script",
		    "php",
		    "\"",
		    "'",
		    "href"
		    );
      $msg = str_replace($dell,"",$msg);
     
     $chat = mysql_query("SET NAMES utf8");
      $tQuery = "
	  INSERT INTO `tbl_chat`
	  (`chat_user_from`,
	  `chat_user_to`,
	  `chat_msg`,
	  `chat_ip`,
	  `chat_date`)
	  VALUES
	  ('$userid',
	  '$user_to',
	  '$msg',
	  '".$_SERVER['REMOTE_ADDR']."',
	  '".date("Y-m-d G:i:s")."'
	  )
	";
      $chat = mysql_query($tQuery);
   }

      $chat_tmp = mysql_query("SET NAMES utf8");
      $tQuery = "
	  SELECT `chat_tmp_find`,`chat_tmp_ans`
	  FROM `tbl_chat_tmp`
	  ";
      $chat_tmp = mysql_query($tQuery);
    $count=0;
    $pos = -1;
    while($count < mysql_num_rows($chat_tmp)){
	
	$msg = mb_strtoupper(addslashes($msg),'UTF-8');
	$find = mb_strtoupper(addslashes(mysql_result($chat_tmp,$count,"chat_tmp_find")),'UTF-8');
	$pos = strpos($msg,$find);
	
	if($pos === false){
	}else{
	    $msg = mysql_result($chat_tmp,$count,"chat_tmp_ans");
	    $chat = mysql_query("SET NAMES utf8");
		$tQuery = "
			INSERT INTO `tbl_chat`
			(`chat_user_from`,
			  `chat_user_to`,
			  `chat_msg`,
			  `chat_ip`,
			  `chat_date`)
			  VALUES
			  ('-1',
			  '$userid',
			  '$msg',
			  '".$_SERVER['REMOTE_ADDR']."',
			  '".date("Y-m-d G:i:s")."'
			  )
			  ";
		$chat = mysql_query($tQuery);
 	}
    $count++;
    }
   
   
}//END === ADD MSG ==========================================================================

$chat = mysql_query("SET NAMES utf8");
$user_msg = "";
if(isset($_SESSION[BASE.'userid']))$user_msg = " or (`chat_user_to`='".$_SESSION[BASE.'userid']."'  or `chat_user_from`='".$_SESSION[BASE.'userid']."') ";

$tQuery = "SELECT 	`tbl_chat`.*,
			  (SELECT `klienti_name_1` FROM `tbl_klienti` WHERE `klienti_id`=`chat_user_from`) as name_from,
			  (SELECT `klienti_name_1` FROM `tbl_klienti` WHERE `klienti_id`=`chat_user_to`) as name_to
			FROM 
			`tbl_chat`
			
			WHERE 
			`chat_user_to`<'1' 
			$user_msg
			ORDER BY `chat_id` DESC
			LIMIT 0,100
			";
//echo $tQuery;
$chat = mysql_query($tQuery);

$html = "";
$count = mysql_num_rows($chat);
while ($count > 0){
  $count--;
  $name_to ="";
  $name_from="";
    $tmp1 = explode(" ",mysql_result($chat,$count,"name_from"));
    if(empty($tmp1[1])) 
      {$name_from = $tmp1[0];}
      else
      {$name_from = $tmp1[1];}
    //if(mysql_result($chat,$count,"chat_user_from") > 0){
      $name_from = "<font color='red'>
		  <a href='Javascript:chat_set_user(".mysql_result($chat,$count,"chat_user_from").",\"$name_from\")'> ".$name_from."</a></font>";
    //}
    
    if(mysql_result($chat,$count,"chat_user_to") > 0){
	  $tmp2 = explode(" ",mysql_result($chat,$count,"name_to"));
	    if(empty($tmp2[1])) 
	      {$name_to = $tmp2[0];}
	    else
	      {$name_to = $tmp2[1];}
	
	    if(!empty($name_to)){
	      $name_to = " -> <font color='red'>
		<a href='Javascript:chat_set_user(".mysql_result($chat,$count,"chat_user_to").",\"$name_to\")'> ".$name_to."</a></font>";
	    }
     }
    $msg = mysql_result($chat,$count,"chat_msg");
	
	  $newDate = date("G:i:s",strtotime(mysql_result($chat,$count,"chat_date")));
	
	$html .= $newDate." ".$name_from.$name_to;
	
	if(isset($_SESSION[BASE.'usersetup'])){
	  if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
	    $html .= "<a href='Javascript:chat_msg_dell(".mysql_result($chat,$count,"chat_id").")'> [dell]</a>";
	    $html .= "<a href='Javascript:chat_user_block(\"".mysql_result($chat,$count,"chat_ip")."\")'> [block]</a>";
	    if(mysql_result($chat,$count,"chat_user_from")>0){
		  $html .= "<a href='admin/edit_klient.php?klienti_id=".mysql_result($chat,$count,"chat_user_from")."' target='_blank'> [user]</a>";
		  $html .= "<a href='admin/find_klient.php?klienti_ip=".mysql_result($chat,$count,"chat_ip")."' target='_blank'> [find]</a>";
	      }else{
		  $html .= "<a href='admin/find_klient.php?klienti_ip=".mysql_result($chat,$count,"chat_ip")."' target='_blank'> [find]</a>";
	      }
	  }
	}

	
	
	
	
	$html .= "<br>&nbsp&nbsp<b> ".$msg."</b>";
	
	
	$html .= "<br>";


}



echo $html;
?>
