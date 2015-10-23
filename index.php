<div>
</div> 
<?php
if(session_id()){
}else{
  session_start();
}

include_once 'config/config.php';
echo '
<!DOCTYPE html>
<html>
<head>
<!-- Chrome, Safari, IE -->
<link rel="shortcut icon" href="http://folder.com.ua/favicon.ico">
 
<!-- Firefox, Opera (Chrome и Safari могут но не будут) -->
<link rel="icon" href="http://folder.com.ua/favicon.ico">
<link rel="shortcut icon" href="http://folder.com.ua/favicon.ico" type="image/png">

<title>'.MAIN_TITLE.'</title>    


<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<!--script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script-->
 	<script type="text/javascript" src="'.HOST_URL.'/js/jquery-2.1.4.min.js"></script>
 	<script language="javascript" src="'.HOST_URL.'/script.js"></script>
        <script language="javascript" src="'.HOST_URL.'/admin/JsHttpRequest.js"></script>
	<script type="text/javascript" src="lightbox/js/lightbox-plus-jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" media="all" href="'.HOST_URL.'/css/styles.css">
	<link rel="stylesheet" media="screen" type="text/css" href="'.HOST_URL.'/css/sturm.css">
	<link rel="stylesheet" media="screen" type="text/css" href="'.HOST_URL.'/css/main.css">
	
	<link rel="stylesheet" href="lightbox/css/lightbox.css">
	<link rel="stylesheet" media="all"    type="text/css" href="'.HOST_URL.'/css/sturm.css">
	<link rel="stylesheet" media="all"    type="text/css" href="'.HOST_URL.'/css/demo.css">
	
	<!--script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script-->
 	<!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script-->
	
</head>
';

//echo '<pre>'.print_r(var_dump($_GET));

include_once 'config/core.php';
include 'init.lib.php';
include 'init.lib.user.php';
include 'init.lib.user.tovar.php';
include 'seo_url.php';
include_once("servises/analyticstracking.php");
//if(isset($_GET['find'])) unset($_GET['_route_']);

//Если вход по алиас - подставим переменные в ГЕТ


if(isset($_GET['_route_'])){
    $Alias->resetGET($_GET['_route_']);
}

connect_to_mysql();

if(!isset($_SESSION[BASE.'chat'])){
  $_SESSION[BASE.'chat'] = "small";
}

if(strpos(mb_strtoupper($_SERVER['REQUEST_URI']),"SELECT")>0) exit();
if(strpos(mb_strtoupper($_SERVER['REQUEST_URI']),"+UNION")>0) exit();
if(strpos(mb_strtoupper($_SERVER['REQUEST_URI']),"+ALL")>0) exit();
//if(strpos($_SERVER['REQUEST_URI'],"%")>0) exit(); jquery-1.11.3.min.js






if(verify_black_list($_SERVER['REMOTE_ADDR']))
{
  echo "Your IP - ",$_SERVER['REMOTE_ADDR']," blocked!";
  exit();
}
save_log($_SERVER['REMOTE_ADDR'],$_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);

//$menu_information = "";
$temp_header = "admin/template/main.html";
$tmp_header = file_get_contents($temp_header);  
$user_level=10;
//if(!isset($_SESSION[BASE.'userlevel']))$_SESSION[BASE.'userlevel']=1;
if(!isset($_SESSION[BASE.'username']))$_SESSION[BASE.'username']=null; 
if(!isset($_SESSION[BASE.'usersetup']))$_SESSION[BASE.'usersetup']=null; 
if(!isset($_SESSION[BASE.'userlevel']))$_SESSION[BASE.'userlevel']=$user_level; 
if(!isset($_SESSION[BASE.'userdiscount']))$_SESSION[BASE.'userdiscount']=0; 
if(!isset($_SESSION[BASE.'usergroup_setup']))$_SESSION[BASE.'usergroup_setup']=null; 



$key = "";
if(isset($_REQUEST['key'])) $key=(string)$_REQUEST['key'];
if($key=="exit"){
$_SESSION[BASE.'login']=null;
$_SESSION[BASE.'userid']=null;
$_SESSION[BASE.'username']=null;
$_SESSION[BASE.'usersetup']=null;
$_SESSION[BASE.'pass']=null;
$_SESSION[BASE.'userorder']=null;
$_SESSION[BASE.'userordersumm']=null;
$_SESSION[BASE.'userlevel']=$user_level;
$_SESSION[BASE.'userprice']=null;
$_SESSION[BASE.'userdiscount']=null;
$_SESSION[BASE.'usergroup_setup']=null;
//Перечитаем меню каталогов если человек залогинился. Для этого обнулим переменную сессии.
$_SESSION[BASE.'user menu'] = null;
}

  if(!isset($_SESSION[BASE.'lang']))$_SESSION[BASE.'lang']=1;
  
  if (isset($_REQUEST['lang'])){
    $_SESSION[BASE.'lang'] = (int)mysql_real_escape_string($_REQUEST['lang']);
    $_SESSION[BASE.'user menu']=null;
	if ($_SESSION[BASE.'lang'] <1){
	      $_SESSION[BASE.'lang']=1;
	}
	if ($_SESSION[BASE.'lang'] >3){
	      $_SESSION[BASE.'lang']=1;
	}
  }
   

if(isset($_REQUEST['comment_txt'])){
  $comment_txt = (string)mysql_real_escape_string($_REQUEST['comment_txt']);
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
  $comment_txt = str_replace($dell,"*",$comment_txt);
  
  $tmp = mysql_query("SET NAMES utf8");
  $tmp = mysql_query("SELECT `comments_klient` FROM `tbl_comments` WHERE `comments_memo`='".$comment_txt."' and `comments_tovar`='".mysql_real_escape_string($_REQUEST['tovar_id'])."'");
 
  if(mysql_num_rows($tmp)==0){
      $tmp = mysql_query("SET NAMES utf8");
      $tmp = mysql_query("INSERT INTO `tbl_comments` 
		      (`comments_tovar`,`comments_klient`,`comments_memo`)
		      VALUES
		      ('".(int)mysql_real_escape_string($_REQUEST['tovar_id'])."',
			'".$_SESSION[BASE.'userid']."',
			'".$comment_txt."')
		      ");
  }
}
//==================================================================
//echo $_REQUEST["login"],$_REQUEST["pass"],$_REQUEST["logining"]=='1',"<br>";

if(isset($_REQUEST["pass"]) and !empty($_REQUEST["login"]) and $_REQUEST["logining"]=='1'){
$ver = mysql_query("SET NAMES utf8");
$sqlStr = "SELECT 
		    `klienti_name_1`,
		    `klienti_id`,
		    `klienti_setup`,
		    `klienti_email`,
		    `klienti_discount`,
		    `klienti_inet_id`,
		    `klienti_price`,
		    `klienti_group_setup`
		    FROM `tbl_klienti`,`tbl_klienti_group` 
		    WHERE upper(`klienti_email`)='".mb_strtoupper(addslashes(mysql_real_escape_string($_REQUEST["login"])),'UTF-8')."' 
		    and `klienti_pass`='".md5((string)mysql_real_escape_string($_REQUEST["pass"]))."'
		    and `klienti_group`=`klienti_group_id`
		    ";
		    
$ver = mysql_query($sqlStr);
if (!$ver)
{

//echo "1 User not found or login+pass not corect!";
if(strpos($_REQUEST['web'],"key=exit")) $web = "index.php?user=new";
if(!isset($_SESSION[BASE.'userid'])) $web = "index.php?user=new";
header ('Refresh: 0; url='.$web);
}
$curr = mysql_query("SET NAMES utf8");
$curr = mysql_query("SELECT 
		    `currency_name_shot` FROM `tbl_currency` 
		    WHERE `currency_id`='1'");
		    
if(mysql_num_rows($ver)==0){
//echo "<b> User not found or login+pass not corect!</b>";
$web = $_REQUEST['web'];
if(strpos($_REQUEST['web'],"key=exit")) $web = "".HOST_URL."/index.php";
if(!isset($_SESSION[BASE.'userid'])) $web = "".HOST_URL."/index.php?user=new";
header ('Refresh: 0; url='.$web);
}else{
    if(empty($_SESSION[BASE.'userorder'])){
	$oper = mysql_query("SET NAMES utf8");
	$oper = mysql_query("SELECT 
		    `operation_id`,
		    `operation_summ`
		    FROM `tbl_operation` 
		    WHERE `operation_klient`='".mysql_result($ver,0,"klienti_id")."' 
		    and `operation_status`='16'
		    and `operation_dell`='0'");
	if(mysql_num_rows($oper)>0){
	      $_SESSION[BASE.'userorder']=mysql_result($oper,0,"operation_id");
	      $_SESSION[BASE.'userordersumm']=mysql_result($oper,0,"operation_summ");
	}else{
	      $_SESSION[BASE.'userorder']=null;
	      $_SESSION[BASE.'userordersumm']=null;
	}
    }

$_SESSION[BASE.'login']=mysql_result($ver,0,"klienti_email");
$_SESSION[BASE.'userlevel']=mysql_result($ver,0,"klienti_inet_id");
$_SESSION[BASE.'username']=mysql_result($ver,0,"klienti_name_1");
$_SESSION[BASE.'userid']=mysql_result($ver,0,"klienti_id");
$_SESSION[BASE.'usersetup']=mysql_result($ver,0,"klienti_setup");
$_SESSION[BASE.'userdiscount']=mysql_result($ver,0,"klienti_discount");
$_SESSION[BASE.'userprice']=mysql_result($ver,0,"klienti_price");
$_SESSION[BASE.'usercurr']=mysql_result($curr,0,0);
$_SESSION[BASE.'usergroup_setup']=mysql_result($ver,0,"klienti_group_setup");

$sSQL = "UPDATE `tbl_klienti` SET `klienti_ip`='".$_SERVER['REMOTE_ADDR']."' WHERE `klienti_id`='".$_SESSION[BASE.'userid']."'";
$ver = mysql_query($sSQL);

//Перечитаем меню каталогов если человек залогинился. Для этого обнулим переменную сессии.
$_SESSION[BASE.'user menu'] = null;
}
$web = mysql_real_escape_string($_REQUEST['web']);
if(strpos($_REQUEST['web'],"key=exit")) $web = "index.php";
if(!isset($_SESSION[BASE.'userid'])) $web = "index.php?user=new";
header ('Refresh: 0; url='.$web);
}
//==================================================================

if (!isset($_SESSION[BASE.'userprice'])){
  $_SESSION[BASE.'userprice'] = $setup['web default price'];
}


//header ('Content-Type: text/html; charset=utf-8');

echo "<script>
      function clear_field(){
	 // alert('gg');
	  document.getElementById('comment_txt').value='';
      }
      function hidde_elem(){
	if(getObj('city').value == '' || getObj('city').value == '",$setup['menu find-str'],"')
	  getObj('info').style.visibility = 'hidden';
     }
   
      function start(){
	get_chat_msg();";
	if($_SESSION[BASE.'chat']=="close"){
	  echo "chat_set_size('close');";
	}else{
	  echo "document.getElementById('chat_key').style.display = 'none';";
	}
echo "	
      }
    
       //=============SET NAKL FIELD====================================
      function catalog_view(parent){
      var div_mas =  document.getElementById('parent*'+parent);
      var div_mas1 = document.getElementById('parent_open*'+parent);
      var str = div_mas1.innerHTML;
      str = str.split(']');
          
	if(str[0]=='[+'){
	  div_mas1.innerHTML='[-]'+str[1];

	  var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	 var responce=req.responseText;
	  div_mas.innerHTML=responce;
	  catalog_view_items(parent);
    }}
    req.open(null,'".HOST_URL."/catalog_view.php',true);
    req.send({id:parent});	
	
	}else{
	  div_mas1.innerHTML='[+]'+str[1];
	  div_mas.innerHTML='';
	}

    }
      //=============CHAT====================================sent_chat_msg
 function sent_chat_msg(e){
      e=e||window.event;
	if(e.keyCode==13||e.keyCode==39){
		
	    var chat =  document.getElementById('chat');
	    var msg_txt =   document.getElementById('chat_txt').value;
	    document.getElementById('chat_txt').value = '';
	    var usr_to=document.getElementById('chat_user_id').value;
	    
	    var req=new JsHttpRequest();
	    req.onreadystatechange=function(){
 	      if(req.readyState==4){
		  var responce=req.responseText;
		  chat.innerHTML=responce;
		}}
	      req.open(null,'".HOST_URL."/get_chat_msg.php',true);
	      req.send({msg:msg_txt,usr:usr_to});	
	}
      }
 function chat_set_size(e){
	
	if(e == 'large'){
	  document.getElementById('chat').style.height = '600px';
	  document.getElementById('chat_main').style.width = '450px';
	  document.getElementById('chat_tmp').style.display = 'block';
  	  document.getElementById('chat_key').style.display = 'none';
	}
	if(e == 'small'){
	//alert('gg');
	  document.getElementById('chat_main').style.display = 'block';
	  document.getElementById('chat').style.height = '50px';
	  document.getElementById('chat_main').style.width = '300px';
	  document.getElementById('chat_tmp').style.display = 'none';
	  document.getElementById('chat_key').style.display = 'none';
	  }
	if(e == 'close'){
	 // document.getElementById('chat').style.height = '0px';
	  //document.getElementById('chat_main').style.width = '0px';
	  document.getElementById('chat_main').style.display = 'none';
	  document.getElementById('chat_key').style.display = 'block';
	 // document.getElementById('chat_tmp').style.display = 'none';
	  //document.getElementById('chat_main').remove();
	}
	
	      var req=new JsHttpRequest();
	      req.onreadystatechange=function(){
	      if(req.readyState==4){
		  var responce=req.responseText;
		 // alert(responce);
	      }}	
	    req.open(null,'".HOST_URL."/set_session.php',true);
	    req.send({set:e});
 
      }
 function chat_set_user(id,name){
  	document.getElementById('chat_user_id').value = id;
  	
  	if(id > 0)
  	{
	  document.getElementById('chat_user_name').value = '".$setup['chat personal']." '+name;
	}else{
	  document.getElementById('chat_user_name').value = ''+name;
	}
      }
 function get_chat_msg(){
      var chat =  document.getElementById('chat');
       // alert('hh');    
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      
      if(req.readyState==4){
	 var responce=req.responseText;
	  chat.innerHTML=responce;
      }}
    req.open(null,'".HOST_URL."/get_chat_msg.php',true);
    req.send(null);
    
    chat.scrollTop = 99999;
    setTimeout(get_chat_msg,10000);
    }";
	  if(isset($_SESSION[BASE.'usersetup'])){
	      if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
		      echo "function set_chat_tmp(id){
			    var chat =  document.getElementById('chat_txt');
			      var req=new JsHttpRequest();
			      req.onreadystatechange=function(){
			      if(req.readyState==4){
				  var responce=req.responseText;
				  chat.value=responce;
			      }}
			      req.open(null,'".HOST_URL."/get_chat_msg.php',true);
			      req.send({tmp:id});
			    }";
	      }
	  }   
echo "function chat_user_block(ip){
      var req=new JsHttpRequest();
      req.open(null,'".HOST_URL."/get_chat_msg.php',true);
      req.send({block:ip});
}
    
function chat_msg_dell(id){
      var req=new JsHttpRequest();
      req.open(null,'".HOST_URL."/get_chat_msg.php',true);
      req.send({dell:id});
}
   function info(msg){
	  document.getElementById('info2').innerHTML = msg;
	  if(msg==''){
	  	  document.getElementById('info2').style.display = 'none';
	  }else{
	  	  document.getElementById('info2').style.display = 'block';
	  	  //alert(msg);
	  }
}

//======================================================================      
    function addtovar(id){
    var id=id.split('*');
    var value = document.getElementById(id[1]);
      
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      //alert(req.readyState);
      if(req.readyState==4){
 	 var responce=req.responseText;
	 if(responce=='nouser'){
	    window.location.replace('index.php?user=new');
	 }else{
	    alert(responce);
	    window.location.reload();
	 }
    }}
    req.open(null,'".HOST_URL."/user_order.php',true);
    req.send({value:value.value,id:id[1],order:id[0]});
    }  


 
     </script>
      

";

//====================ADMIN SCRIPT ================================================
if (isset($_SESSION[BASE.'base']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
//echo "\n<script src='/admin/JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
//================================SET COLOR=====================================
//================================SET PRICE===============kogda vibor konkretnoj ceni
echo "\nfunction update(table,name,value,id,tovar){
      //alert('ggg');
      
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
        if(req.readyState==4){
	var responce=req.responseText;
	document.getElementById('test').innerHTML=responce; //table,name,value,id,tovar
	
      }}
      req.open(null,'".HOST_URL."/admin/save_table_field.php',true);
      req.send({table:table,name:name,value:value,w_id:id,w_value:tovar});
    //'tbl_parent_inet','parent_inet_sort',this.value,'parent_inet_id',this.id  
    }
   </script>
   
  ";

}

//=================================================================================

  echo "</head>  
  <body onload=\"start();\" onmouseover=\"hidde_elem();\">";

$body = "";
$find="";
if(isset($_REQUEST['find']))$find=mysql_real_escape_string($_REQUEST['find']);

$parent=0;
if(isset($_REQUEST['parent']))
  $_REQUEST['parent'];

  if(isset($_REQUEST['parent'])&&!isset($_REQUEST['tovar_id'])){
      if($_REQUEST['parent']=='last' or $_REQUEST['parent']=='-1'){
	$body = user_last_item_list_view(120,$setup);
      }elseif($_REQUEST['parent']=='-2'){ //last operation list tovar
	$body = last_operation_list_tovar($setup);
      }elseif($_REQUEST['parent']=='-3'){ //no photo list tovar
	$body = no_photo_list_tovar($setup);
      }elseif($_REQUEST['parent']=='watch'){
	  if(isset($_REQUEST['dell']) and isset($_SESSION[BASE.'userid'])){
	      $dell = mysql_query("SET NAMES utf8");
	      $dell = mysql_query("DELETE FROM `tbl_opa` 
				  WHERE 
				  `opa_tovar`='".mysql_real_escape_string($_REQUEST['dell'])."' and 
				  `opa_klient`='".$_SESSION[BASE.'userid']."'");

	  }
	    $body = user_selected_item_list_view($setup,$setup);
      }else{
	
	$body .= "".user_catalog_path(mysql_real_escape_string($_REQUEST['parent'])). "";
	//$body .= user_subcatalog_view(mysql_real_escape_string($_REQUEST['parent']));
	$body .= user_item_list_view(mysql_real_escape_string($_REQUEST['parent']),$setup);
      }
  }elseif(isset($_REQUEST['find'])){
	$body = find_result(mysql_real_escape_string($_REQUEST['find']),$setup,$setup);
  }elseif(isset($_REQUEST['operation_id'])){
	    if(isset($_SESSION[BASE.'usersetup'])){
		if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
		    $body = user_operation_list_view(mysql_real_escape_string($_REQUEST['operation_id']),$setup);
	    }}
  }elseif(isset($_REQUEST['user'])){
	if($_REQUEST['user']=="new" and !isset($_SESSION[BASE.'userid'])){
	    $body = user_registration($_REQUEST);
	}elseif($_REQUEST['user']=="edit"){
	    $body = user_edit($setup,$_REQUEST);
	}elseif($_REQUEST['user']=="rem_pass"){
	    $body = user_rem_pass($setup,$_REQUEST);
	}elseif($_REQUEST['user']=="send_order"){
	    $body = send_order($setup,$_REQUEST);
	}elseif($_REQUEST['user']=="order_list"){
	    $body = user_order_list($setup);
	}elseif($_REQUEST['user']=="price_excel"){
	    $body = user_price_excel($setup);
	}elseif($_REQUEST['user']=="utilites"){
	    $body = utilites($setup);
	}elseif($_REQUEST['user']=="view_order"){
	    $body = view_order($setup,$_REQUEST);
	}
  }elseif(isset($_REQUEST['submit'])){
	if($_REQUEST['submit']==$setup['menu user register']){
	    $body = user_registration($_REQUEST);
	}elseif($_REQUEST['submit']==$setup['menu user edit']){
	    $body = user_edit($setup,$_REQUEST);
	}
	
  }elseif(isset($_REQUEST["tovar_id"])) {
	$parent=0;
	if(isset($_REQUEST['parent'])) {
	      $perent = mysql_real_escape_string($_REQUEST['parent']);
	  }else{
	      $parentSQL = mysql_query("SET NAMES utf8");
	      $parentSQL = mysql_query("SELECT `tovar_inet_id_parent` FROM `tbl_tovar` WHERE `tovar_id`='".mysql_real_escape_string($_REQUEST["tovar_id"])."'");
	      if(mysql_num_rows($parentSQL)>0){
		$parent = mysql_result($parentSQL,0,0);
	      }
	  }
	    $body .= user_catalog_path($parent). "";
	if(is_numeric($_GET["tovar_id"])){
	    $body .= user_item_view((int)mysql_real_escape_string($_REQUEST["tovar_id"]));
	}
  }else{
	if(isset($_REQUEST['error'])){
	  
	     $body .= error_msg(mysql_real_escape_string($_REQUEST['error']),$setup);
	}
	    /*
	    $body .= "<div style=\"Z-INDEX:0;\"> <table width=\"100%\" class=\"baner\" border=\"0px\">
		    <tr><td align=center>
		    <object width=\"900\" height=\"300\" >";
	    $body .= banerMain("BanerLargeLeft",350,300);
	    $body .= banerMain("BanerLargeCenter",600,300);
	    $body .= "</object>
		    </td></tr></table>
		    </div>";
	      */
	if(isset($_REQUEST["key"])){
	    
	    $body .= info(100,$setup,mysql_real_escape_string($_REQUEST["key"]));
	    
	}
      $body .= user_last_item_list_view(8,$setup);
  }
  
$korzina="";  
if(isset($_SESSION[BASE.'userorder'])) {
    $korzina .= "<div id='order_item' class='order_items'> ";
    $korzina .= "".$setup['menu order']." #".$_SESSION[BASE.'userorder'];
    $korzina .= "<BR><b>".$_SESSION[BASE.'userordersumm']." ".$_SESSION[BASE.'usercurr']."</b>";
    $korzina .= "<br><table>
			<tr><td><a href='".HOST_URL."/user_order_view' class='middle_link'>
				   <img src=\"".HOST_URL."/resources/carts.png\"></a></td><td>
		    <b><a href='".HOST_URL."/user_order_view' class='middle_link'>".$setup['menu order send']."</a></b></td></tr></table>";
    $korzina .= "<br>".order_item_view();
    //$korzina .= "<br><br><b><a href='index.php?user=view_order' class='big_link'>".$setup['menu order add']."</a></b>";
    $korzina .= "</div>";
}

$tmp_header = str_replace("&chat",chat(),$tmp_header);
$tmp_header = str_replace("&body",$body,$tmp_header);
$tmp_header = str_replace("&order",$korzina,$tmp_header);
$tmp_header = str_replace("&user_menu_catalog",user_menu_catalog($setup, $Alias),$tmp_header);
//$tmp_header = str_replace("&user_menu_catalog",user_menu_catalog2()."<br>".user_menu_catalog(),$tmp_header);
$tmp_header = str_replace("&find",find_tovar($find,$setup,$setup),$tmp_header);
$tmp_header = str_replace("&user_menu_logo",user_menu_logo(),$tmp_header);
$tmp_header = str_replace("&user_menu_phone",user_menu_phone(),$tmp_header);
$social_key=  "";
$tmp_header = str_replace("&social_key",$social_key,$tmp_header);

$tmp_header = str_replace("&user_menu_lang",user_menu_lang(),$tmp_header);

$tmp_header = str_replace("&user_menu_top",user_menu_top(),$tmp_header);
$tmp_header = str_replace("&news",info(1,$setup,"news"),$tmp_header);
$tmp_header = str_replace("&top_main_info",info(1,$setup,"top_main_info"),$tmp_header);

if(isset($_REQUEST['parent'])){
  $tmp = "".user_catalog_path_memo(mysql_real_escape_string($_REQUEST['parent']))."";
  }else{
  $tmp = "".user_catalog_path_memo(0)."";
  }
$tmp_header = str_replace("&sturm_memo",$tmp,$tmp_header);

$tmp_header = str_replace("&menu_main",$setup['menu main'],$tmp_header);
$tmp_header = str_replace("&menu_news",$setup['menu news'],$tmp_header);
$tmp_header = str_replace("&menu_forum",$setup['menu forum'],$tmp_header);
$tmp_header = str_replace("&menu_pay",$setup['menu pay'],$tmp_header);
$tmp_header = str_replace("&menu_deliv",$setup['menu deliv'],$tmp_header);
$tmp_header = str_replace("&menu_help",$setup['menu help'],$tmp_header);
$tmp_header = str_replace("&menu_adr",$setup['menu adr'],$tmp_header);
$tmp_header = str_replace("&menu_phone",$setup['menu phone nom'],$tmp_header);
$tmp_header = str_replace("&menu_work_time",$setup['menu work time'],$tmp_header);

$tmp_header = str_replace("&user_login",session_verify($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']),$tmp_header);
$tmp_header .= "<div id='info2' class='info'></div></body>
		";
echo $tmp_header;
echo "";
echo $_SESSION[BASE.'chat'];
echo "</html>";
?>
