<?php
include 'init.lib.php';
include 'init.lib.user.php';
include 'init.lib.user.tovar.php';

session_start();

$user_level=10;
//if(!isset($_SESSION[BASE.'userlevel']))$_SESSION[BASE.'userlevel']=1;
if(!isset($_SESSION[BASE.'username']))$_SESSION[BASE.'username']=null; 
if(!isset($_SESSION[BASE.'usersetup']))$_SESSION[BASE.'usersetup']=null; 
if(!isset($_SESSION[BASE.'userlevel']))$_SESSION[BASE.'userlevel']=$user_level; 
if(!isset($_SESSION[BASE.'userdiscount']))$_SESSION[BASE.'userdiscount']=0; 
 
$key = "";
if(isset($_REQUEST['key'])) $key=$_REQUEST['key'];
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
//header ('Refresh: 0; url=index.php');
}

$_SESSION[BASE.'lang']=1;
  if (isset($_REQUEST['lang'])){
    $_SESSION[BASE.'lang'] = $_REQUEST['lang'];
  }
 connect_to_mysql();  

if(isset($_REQUEST['comment_txt'])){
  $comment_txt = $_REQUEST['comment_txt'];
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
  $tmp = mysql_query("SELECT `comments_klient` FROM `tbl_comments` WHERE `comments_memo`='".$comment_txt."' and `comments_tovar`='".$_REQUEST['tovar']."'");
 
  if(mysql_num_rows($tmp)==0){
      $tmp = mysql_query("SET NAMES utf8");
      $tmp = mysql_query("INSERT INTO `tbl_comments` 
		      (`comments_tovar`,`comments_klient`,`comments_memo`)
		      VALUES
		      ('".$_REQUEST['tovar']."',
			'".$_SESSION[BASE.'userid']."',
			'".$comment_txt."')
		      ");
  }
}
//==================================================================
if(isset($_REQUEST["pass"]) and $_REQUEST["logining"]=='1'){
$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT 
		    `klienti_name_1`,
		    `klienti_id`,
		    `klienti_setup`,
		    `klienti_email`,
		    `klienti_discount`,
		    `klienti_inet_id`,
		    `klienti_price`
		    FROM `tbl_klienti` 
		    WHERE `klienti_email`='".$_REQUEST["login"]."' 
		    and `klienti_pass`='".$_REQUEST["pass"]."'");
if (!$ver)
{
echo "User not found or login+pass not corect!";
header ('Refresh: 0; url=index.php');
}
$curr = mysql_query("SET NAMES utf8");
$curr = mysql_query("SELECT 
		    `currency_name_shot` FROM `tbl_currency` 
		    WHERE `currency_id`='1'");
		    
if(mysql_num_rows($ver)==0){
echo "User not found or login+pass not corect!";
header ('Refresh: 0; url=index.php');
}
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
}
//==================================================================



header ('Content-Type: text/html; charset=utf8');
echo "<header>
<title>sturm.com.ua</title>
      <script language='javascript' src='ajax_framework.js'></script>
      <link rel='stylesheet' type='text/css' href='admin/sturm.css'></header>
      <script src='admin/JsHttpRequest.js'>    </script>
      
      <script>
      function clear_field(){
	 // alert('gg');
	  document.getElementById('comment_txt').value='';
      }
      function start(){
	//alert('gggg');
	catalog_view(0);
	setclear(0);
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
    req.open(null,'catalog_view.php',true);
    req.send({id:parent});	
	
	}else{
	  div_mas1.innerHTML='[+]'+str[1];
	  div_mas.innerHTML='';
	}

    }
       //=============SET NAKL FIELD====================================
    /*  function catalog_view_items(parent){
      var div_mas =  document.getElementById('search-result');
      
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	 var responce=req.responseText;
	  div_mas.innerHTML=responce+' '+parent;
    }}
    req.open(null,'catalog_view_items.php',true);
    req.send({id:parent});	
    }*/
      
    function addtovar(id){
    var id=id.split('*');
    var value = document.getElementById(id[1]);
      
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	 var responce=req.responseText;
	 alert(responce);
	 window.location.reload();
    }}
    req.open(null,'user_order.php',true);
    req.send({value:value.value,id:id[1],order:id[0]});
    }  
    
   function setclear(a){
      if(a==1&&document.getElementById('city').value=='",$setup['menu find-str'],"')
	{document.getElementById('city').value='';}
      else if(a==0&&document.getElementById('city').value=='')
      {
	document.getElementById('city').value='",$setup['menu find-str'],"';
      }
	    
    }
      </script>
";
//====================ADMIN SCRIPT ================================================
if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
echo "\n<script src='/admin/JsHttpRequest.js'></script>";
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
      req.open(null,'admin/save_table_field.php',true);
      req.send({table:table,name:name,value:value,w_id:id,w_value:tovar});
    //'tbl_parent_inet','parent_inet_sort',this.value,'parent_inet_id',this.id  
    }
    
   </script>";

}
//=================================================================================
  echo "<body onload=start();>
 
";

echo "<table width=100% class='menu_top' cellspacing='0' cellpadding='0'><tr>";
echo "<td align=left>";
user_menu_logo();
echo "</td><td align=center>";
user_menu_top();
echo "</td><td align=right>";
user_menu_lang();
echo "</td><td width=360px align=center>";
session_verify($_SERVER["PHP_SELF"]);
echo "</td></tr></table>";    

echo "<table width=100%  class='menu_top'><tr><td width=250px valign='top'>";
//=========================CATALOG AJAX
echo "
<div id='parent_open*0'>[+] Catalog</div>
<div id='parent*0'></div>";
echo "</td><td valign=top>";
//=========================SERCH AJAX
echo "<table width=100%  class='menu_top'><tr><td align=center>";

$find="";
if(isset($_REQUEST['find']))$find=$_REQUEST['find'];
find_tovar($find);

if(!empty($_SESSION[BASE.'userorder'])){
    echo "</td><td width=70 valign=top align=center>";
    echo "#",$_SESSION[BASE.'userorder']," <b>",$_SESSION[BASE.'userordersumm']," ",$_SESSION[BASE.'usercurr'],"</b>";
    echo "<br><a href='send_order.php'>",$setup['menu order'],"</a>";
}

echo "</td></tr><tr><td  valign=top align=left>
<div id='search-result'>";
//if(isset($_REQUEST["parent"])){
    $parent='0';
    if(isset($_REQUEST["parent"]))
      {$parent=$_REQUEST["parent"];}
    
      if($parent=='last'){
 	  user_last_item_list_view();
      }elseif($parent=='selected'){
 	  user_selected_item_list_view();
      }else{
	  if($parent>0){
	    echo "<table class='menu_top'><tr><td>
	    <a href='/index.php?parent=",$parent,"'>
	    <img src='/resources/products/GR",$parent,"/GR",$parent,".0.small.jpg' width='50' height='50'></a>
	    </td><td valign='top'>";
	   }else{
	    echo "<table class='menu_top'><tr><td>
	    <img src='/resources/katalog.gif' height='50'>
	    </td><td valign='top'>";
	   }
	//echo $parent;
	user_catalog_path($parent);
	echo "</td><td valign='top' align='left'>";
	user_catalog_path_memo($parent);
	echo "</td><tr></table>";
      }
//}
  if(isset($_REQUEST["tovar"])) {
      user_item_view($_REQUEST["tovar"]);
    }else{
      user_subcatalog_view($parent);
      user_item_list_view($parent);
    }
//}
echo "</div>";

if(!empty($_SESSION[BASE.'userorder'])){
    echo "</td><td width=70 valign=top align=center>";
    order_item_view();
}

echo "</td></tr></table>
";
//============================================

echo "</td></tr></table>";

?>
