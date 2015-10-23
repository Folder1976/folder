<?php
//include 'init.lib.php';
include_once 'config/config.php';

/*
SetCookie("My_Cookie","Value",time()+3600);
if (SetCookie("Test","Value")) echo "<h3>Cookies успешно установлены!</h3>";
if (isset($_COOKIE['Mortal'])) $cnt=$_COOKIE['Mortal']+1;
*/

function connect_to_mysql(){
  
  $dbcnx = mysql_connect(DB_HOST, DB_USER, DB_PASS);
  if (!$dbcnx)
  {
  echo "Not connect to MySQL";
  exit();
  }

  if (!mysql_select_db(BASE,$dbcnx))
  {
  echo "No base present";
  exit();
  }
//echo "HA HA";
}
function Get_IP() {
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
return trim($ip);
}
function session_verify($web) {
  global $setup;
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$html="";
  
  $username="";
   if(isset($_SESSION[BASE.'username']))$username = $_SESSION[BASE.'username'];
  if ($username==""){
//==================================SETUP===========================================
//$web = "index.php";
   if(isset($_REQUEST['web']))$web=mysql_real_escape_string($_REQUEST['web']);
 //==================================SETUP=MENU==========================================
 $html = "
      <script>
      function clear_login(id){
	  document.getElementById(id).value='';
      }
      </script>
      ";
 
    $html .=  "<form method='get' action='".HOST_URL."/index.php'>
	<table cellspacing='0px' cellpadding='0px'><tr><td valign='middle'>
	  <input type='hidden' name='web' value='"  . $web  . "'>
 	  <input type='hidden' name='logining' value='1'>
     <input class='user_login' type='text' style='width:100px' name='login' id='login' placeholder='e-mail'>";// onfocus='clear_login(this.id);'>
    $html .= "<input class='user_login' type='password' style='width:100px' name='pass' id='pass' placeholder='пароль'>";// onfocus='clear_login(this.id);'>
    $html .="</td>
	  <td>
	    <input class='user_login' type='image' value='login' onClick='submit();' src='resources/login.gif' height='25'>
	  </td>
	</tr><tr><td>  
    ";
    $html .= "<font size=1><a href='index.php?user=new'>".$setup['login registration']."</a>
	  - <a href='".HOST_URL."/index.php?user=rem_pass'>".$setup['login remembe pass']."</a></font>
	</td></tr></table>  
	  </form>";
    }else{
$discount="";
if($_SESSION[BASE.'userdiscount']>0)$discount="(-".$_SESSION[BASE.'userdiscount']."%)";
    $html .= "<table class=\"main_header\"><tr><td>
	      <p align='right' color='blue' style='color:#610b0b'><b>".$username.$discount."</b>
	      <br><font size=1>";
      if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'].'can_shop')>0){
	$html .= "<a href='".HOST_URL."/admin/shop.php' target='_blank'> (shop) </a>";
	}
 
      if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
	$html .= "<a href='".HOST_URL."/admin/index.php' target='_blank'> (admin)</a>";
	}
       if (isset($_SESSION[BASE.'userorder'])){
		  $html .= "<a href='".HOST_URL."/index.php?user=view_order'> (".$setup['menu carts'].")</a>";
		    }
    $html .= "
	  <a href='index.php?user=edit'> (".$setup['menu edit'].")</a>
	  <a href='index.php?key=exit'> (".$setup['menu exit'].")</a>
	  </font></td>";
	  
		if (isset($_SESSION[BASE.'userorder'])){
		  $html .= "<td width=\"30px\" align=\"right\"><a href='index.php?user=view_order'>  
		  <img src=\"".HOST_URL."/resources/carts.png\"></a></td>";
		}
	  
    $html .= "<td width=\"14px\"></td></tr></table>
	  ";
        
    }
    
   // echo $web;
return $html;
}
function verify_black_list($ip){
$ver = mysql_query("SELECT * FROM `tbl_black_ip_list` WHERE `black_ip_ip`='$ip'");
//echo "SELECT * FROM `tbl_black_ip_list` WHERE `black_ip`='$ip'";
if (!$ver)
{
  echo "Query error - Black List";
  exit();
}
if(mysql_num_rows($ver) > 0)
{
  return 1;
}else{
  return 0;
}


}
function save_log($ip,$web){
//session_start();

$date = date("Y-m-d G:i:s"); 
$user = 0;  
if(isset($_SESSION[BASE.'userid']))$user = $_SESSION[BASE.'userid'];
  $ver = mysql_query("INSERT INTO `tbl_log`
		    (`log_date`,
		      `log_ip`,
		      `log_web`,
		      `log_user_id`)
		      VALUES
		      (
		      '$date',
		      '$ip',
		      '$web',
		      '$user'
		      )
		      ");
}
function user_menu_lang(){
$html="";

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM `tbl_web_lang` WHERE `web_lang_view`='1' ORDER BY `web_lang_id` ASC ");
if (!$ver)
{
  $html .= "Query error - LANG";
  exit();
}

if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}

$width = 0;
$html .= "<!--table  class=\"main_phone\"><tr><td width=\"70\">
	  <a href=\"http://lviv.virtual.ua/index.php?cat=infopolis_shops&id=53#\">
	      <img src=\"".HOST_URL."/resources/img/3d.jpg\" width=\"50px\" alt=\"3D тур\">
                                                      </a>
	  </td-->";

$count=0;
while ($count < mysql_num_rows($ver))
{
  $id = mysql_result($ver,$count,"web_lang_id");
  $name = mysql_result($ver,$count,"web_lang_shot");
  $html .= "<td width='".$width."' align='center' valign='middle'>";
  
  $html .= "<a href='".$_SERVER["PHP_SELF"]."?lang=$id'>";
  $html .= "<img src='".HOST_URL."/resources/img/lang".mysql_result($ver,$count,"web_lang_id").".jpg' width='24' height='18' alt='phone'></a></td><td>";
  $html .= "<a href='".$_SERVER["PHP_SELF"]."?lang=$id'>";
	if ($_SESSION[BASE.'lang'] == mysql_result($ver,$count,"web_lang_id")) $html .= "<b>";
	  $html .= $name;
	if ($_SESSION[BASE.'lang'] == mysql_result($ver,$count,"web_lang_id")) $html .= "</b>";
  $html .= "</a>";
  $html .= "</td><td width='".$width."'></td>";
  $count++;
}
$html .= "</tr></table>";
return  $html;

}

function user_menu_phone(){
 $html = MY_EMAIL.MY_PHONE;
 
 /* $html = "<table class='main_phone'><tr><td>
	  <img src='resources/img/phone.jpg' width='30' height='30' alt='phone'></td><td>".MY_EMAIL.
	  "</td></tr></table>";
*/	  
  return  $html;

}

?>
