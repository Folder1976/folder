<?php
//echo phpinfo();

include_once 'config/config.php';
include_once 'class/class_system.php';

$System = new System($folder);

$System->saveLog();

include_once 'config/core.php';
include 'init.lib.php';

$ip = $_SERVER['REMOTE_ADDR'];
$sql = 'SELECT black_ip_id FROM tbl_black_ip_list WHERE black_ip_ip = "'.$ip.'" LIMIT 0,1';
$r = $folder->query($sql);
if($r->num_rows > 0){
		die('disabled, ban on IP');
}


//include 'init.lib.user.php';
//include 'init.lib.user.tovar.php';
include 'seo_url.php';

require_once 'libs/SocialAuther/autoload.php';

connect_to_mysql();
//echo "<pre>";  print_r(var_dump( $_SESSION )); echo "</pre>";  
//Список главных категорий
$categories = $Category->getCategoryMain(); 

//В самом начале - не прилетел ли нам логин из социалки!!! ===========================================
  $adapters = array();
  global $adapterConfigs;
  
  foreach ($adapterConfigs as $adapter => $settings) {
		  $class = 'SocialAuther\Adapter\\' . ucfirst($adapter);
		  $adapters[$adapter] = new $class($settings);
  }
 
  if (isset($_GET['provider']) && array_key_exists($_GET['provider'], $adapters)) {
	  $auther = new SocialAuther\SocialAuther($adapters[$_GET['provider']]);
  
	if ($auther->authenticate()) {
		$user = array(
		  'provider' 	=> $auther->getProvider(),
		  'id' 			=> $auther->getSocialId(),
		  'name' 		=> $auther->getName(),
		  'email' 		=> $auther->getEmail(),
		  'page' 		=> $auther->getSocialPage(),
		  'sex' 		=> $auther->getSex(),
		  'birthday' 	=> $auther->getBirthday(),
		  'avatar' 		=> $auther->getAvatar()
		);
	  
	  if(isset($user['id']) AND $user['id'] != ''){
		  include_once 'libs/user_social.php';
		  include 'skin/index.php';
		  die();
	  }
	}
  }
  
//======================================================================================================================

//==Блок если нам чтото прилетело============================================================
if(isset($_POST['reviewer-comment'])){
	include_once('class/class_comment.php');
	$Comment = new Comment($folder);
  
	$Comment->addComment($_POST);
	
	//Перезагрузим страницу нафиг
	header('Location: '.$_SERVER['REQUEST_URI'].'?reviewer-comment=true');
}


//==============================================================

//Если прилетел логин
if(isset($_POST['login']) AND $_POST['login'] == true){
  $User->userLogin();
//Если есть куки этого юзера но он не залогинен - залогинем его
}elseif(isset($_COOKIE[BASE.'userid']) AND !isset($_SESSION[BASE.'login'])){
  $User->userLoginOnCookie();
}
//Если пользователь подлогинен - возьмем его параметры
if(isset($_SESSION[BASE.'userid'])){
  $User->loadUserSetting($_SESSION[BASE.'userid']);
}

if(!isset($_SESSION[BASE.'userprice'])){
  $_SESSION[BASE.'userprice'] = $setup['web default price'];
}

//Если регистрация
if(isset($_GET['registration'])){

  include_once("skin/registration.php");
  die();
}

if(!isset($_SESSION[BASE.'userlevel'])) $_SESSION[BASE.'userlevel'] = 10;


//Если прилетела ПОИСК
if(isset($_GET['search'])){
 	
	$search = mysqli_escape_string($folder, $_GET['search']);
	
	$banners = $Banner->getMediumBanners('find');
	
	if(mb_strlen($search) >= 3){
	  include_once("libs/products_control.php");
	  $data = user_item_list_view($search,$setup, 'FIND');
	  include 'skin/search_result.php';
	}else{
	  include 'skin/search_result.php';
	}
	
	die();
	

}


//Если пользователь вылогинился
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'logout'){
  $User->logoutUser();
  unset($_GET['_route_']);
}

//Если пользователь зашел в кабинет
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'account_personal'){
  
  if($user = $User->getLoginedUserInfo()){
	include 'skin/account_personal.php';
	die();
  }else{
	include 'skin/index.php';
    die();
  }

}

//Если пользователь зашел в корзину
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'account_cart'){

  include 'class/class_control_cart.php';
  $ControlCart = new ControlCart($folder);
  
  $cart = $ControlCart->getLoginedUserCart($User->getActiveUserKey(), $Product);

  //echo "<pre>";  print_r(var_dump( $cart )); echo "</pre>";

  include 'skin/account_cart.php';
  die();

}

//Если пользователь зашел в список заказов
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'account_orders'){
  
  if($user = $User->getLoginedUserInfo()){
	
	$orders = $Order->getUserOrders($user['klienti_id']);
	
	//echo "<pre>";  print_r(var_dump( $orders )); echo "</pre>";
	
	include 'skin/account_orders.php';
	die();
  }else{
	include 'skin/index.php';
    die();
  }

}

//Если пользователь зашел в пароль
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'restore_pass'){
 
	include 'skin/restore_pass.php';
	die();
 }

//Напоминалка пароля
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'account_password'){
  
  if($user = $User->getLoginedUserInfo()){
	include 'skin/account_password.php';
	die();
  }else{
	include 'skin/index.php';
    die();
  }
}

//Бренды
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'brands'){
  $brand_limit = 10000;
		include 'skin/brands.php';
		die();
}

//Формирование заказа - Адрес доставки
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'cart'){
  include 'class/class_transport.php';
  $Transport = new Transport($folder);
  
  include 'class/class_control_cart.php';
  $ControlCart = new ControlCart($folder);
  
  $user = $User->getLoginedUserInfo();
  $user['key'] = $User->getActiveUserKey();
  $cart = $ControlCart->getLoginedUserCart($user['key'], $Product);
  $transport['city'] = $Transport->getTranspComp();

	include 'skin/cart_2.php';
	die();
}

//Подтверждение отправки заказа
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'order'){
  include 'class/class_control_cart.php';
  $ControlCart = new ControlCart($folder);
  
  $user = $User->getLoginedUserInfo();
  $user['key'] = $User->getActiveUserKey();
  $cart = $ControlCart->getLoginedUserCart($user['key'], $Product);
 
  $order = $Order->createUserOrder($_POST, $user, $cart);

  if($order){
	if (isset($_GET["kredit"])) {
		include 'skin/cart_3_kredit.php';
	}
	else {
		include 'skin/cart_3.php';
	}
	die();
  }

}

//Если пользователь зашел в гардероб
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'account_wardrobe'){
  
  if($user = $User->getLoginedUserInfo()){
	include 'skin/account_wardrobe.php';
	die();
  }else{
	include 'skin/index.php';
    die();
  }

}


//Если прилетели на главную страницу = Запустим скин главной
if(!isset($_GET['_route_'])){
		
		$large_banners = $Banner->getMainPageBanners();
		
		include 'skin/index.php';
		die();
}


//Если прилетела категория
if(isset($_GET['parent'])){
  $categ_id_list = '*';
  foreach($categories as $index => $tmp){
	  $categ_id_list .= $index . '*';
  }
 
  $parent = mysqli_escape_string($folder, $_GET['parent']);

  //Если это главная категория - тогда первый шаблон
  if(strpos($categ_id_list, '*' . $_GET['parent'] . '*') !== false){
	
	$categ_selected = $Category->getCategoryInfo((int)$parent);
	$ids = $Category->getCategoryChildrenCateg((int)$parent);
	$category_children = $Category->getCategoriesInfo($ids);
	
	$banners = $Banner->getMediumBanners('catalog1');
	
	include 'skin/catalog_1.php';
	
	die();
  }else{
	//Если подкатегория - тогда второй шаблон
	
	$banners = $Banner->getMediumBanners('catalog2');
	
	include_once("libs/products_control.php");
	$data = user_item_list_view($parent,$setup);
	include 'skin/catalog_2.php';
	die();
  }
}

//Если прилетела НОВИНКИ
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'lates_products'){
 	
	$banners = $Banner->getMediumBanners('news');
	
	include_once("libs/products_control.php");
	$data = user_item_list_view(0,$setup, 1000);
	include 'skin/lates_products.php';
	die();

}



//Если прилетел товар
if(isset($_GET['tovar_id'])){
    $product_id = mysqli_escape_string($folder, $_GET['tovar_id']);

	if(!isset($Comment)){
	  include_once('class/class_comment.php');
	  $Comment = new Comment($folder);
	}
	
	include_once("libs/product_control.php");
	
	$product = user_item_view($product_id);
	
	$product['comments'] = $Comment->getComments($product['artkl']);
	
	include 'skin/product.php';
	die();
}


// ========================================= ВСЯКИЕ РАЗНЫЕ СТРАНИЧКИ =================
//Если Новости
if(isset($_GET['_route_']) AND ($_GET['_route_'] == 'news' OR $_GET['_route_'] == 'press')){
	
	$sql = 'SELECT * FROM tbl_info WHERE info_link="'.$_GET['_route_'].'" ORDER BY info_date DESC, info_id DESC;';
	$r = $folder->query($sql);
	//echo $sql;
	$news = array();
	while($tmp = $r->fetch_assoc()){
	
		$news[$tmp['info_id']]['h1'] 			= $tmp['info_header_1'];
		$news[$tmp['info_id']]['title'] 		= $tmp['info_header_2'];
		$news[$tmp['info_id']]['description'] 	= $tmp['info_header_3'];
		$news[$tmp['info_id']]['text'] 		= str_replace('elFinder-master','/admin/elFinder-master',$tmp['info_memo_1']);
		$news[$tmp['info_id']]['date'] 		= $tmp['info_date'];

		$breadcrumb = $tmp['info_header_1'];
		$h1 		= $tmp['info_header_1'];
		$title 		= $tmp['info_header_2'];
		$description = $tmp['info_header_3'];
		
	}
	
	include_once("skin/info_news.php");
	die();
}

//Если информация
if(isset($_GET['_route_']) AND ($_GET['_route_'] == 'dostavka' OR
								$_GET['_route_'] == 'contact' OR
								$_GET['_route_'] == 'zakaz' OR
								$_GET['_route_'] == 'kredit' OR
								$_GET['_route_'] == 'size'
							   )	
									){

	$sql = 'SELECT * FROM tbl_info WHERE info_link="'.$_GET['_route_'].'" ORDER BY info_sort ASC;';
	$r = $folder->query($sql);
	//echo $sql;
	$tmp = $r->fetch_assoc();
	
	$h1 		= $tmp['info_header_1'];
	$title 		= $tmp['info_header_2'];
	$description = $tmp['info_header_2'];
	$text 		= str_replace('elFinder-master', '/admin/elFinder-master', $tmp['info_memo_1']);


	include_once("skin/info_info.php");
	die();
}

//Если регистрация
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'help'){

  include_once("skin/info_help.php");
  die();
}


//Если мы дошли до этого места - значит мы не нашли нуждной нам страницы
	include 'skin/404.php';
	die();

echo "<pre>";  print_r(var_dump( $_GET )); echo "</pre>";





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



//header ('Content-Type: text/html; charset=utf-8');
$find = '';
if(isset($_GET['find'])) $find = $_GET['find'];

echo '
<!DOCTYPE html>
<html>
<head>
<title>'.$Info->getTitle().'</title>    
<!--link rel="shortcut icon" href="'.HOST_URL.'/resources/fa11vicon.png" type="image/png" искать в heder_main-->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<!--script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script-->
 	<script type="text/javascript" src="'.HOST_URL.'/js/jquery-2.1.4.min.js"></script>
 	<script language="javascript" src="'.HOST_URL.'/script.js"></script>
        <script language="javascript" src="'.HOST_URL.'/admin/JsHttpRequest.js"></script>
	<!--script type="text/javascript" src="'.HOST_URL.'/lightbox/js/lightbox.js"></script-->
	
	<link rel="stylesheet" type="text/css" media="all" href="'.HOST_URL.'/css/styles.css">
	<link rel="stylesheet" media="screen" type="text/css" href="'.HOST_URL.'/css/sturm.css">
	<link rel="stylesheet" media="screen" type="text/css" href="'.HOST_URL.'/css/main.css">
	
	<!--link rel="stylesheet" href="'.HOST_URL.'/lightbox/css/lightbox.css"-->
	<link rel="stylesheet" media="all"    type="text/css" href="'.HOST_URL.'/css/sturm.css">
	<link rel="stylesheet" media="all"    type="text/css" href="'.HOST_URL.'/css/demo.css">
	
	<!--script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script-->
 	<!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script-->
	
</head>
';

echo "<script>
    function getObj(objID)
    {
      if (document.getElementById) {return document.getElementById(objID);}
      else if (document.all) {return document.all[objID];}
      else if (document.layers) {return document.layers[objID];}
    }
      function clear_field(){
	 // alert('gg');
	  document.getElementById('comment_txt').value='';
      }
      function hidde_elem(){
	if(getObj('city').value == '')
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
echo "
<div id=\"fb-root\"></div>
<!--script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1582359991987210',
      xfbml      : true,
      version    : 'v2.2'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = \"//connect.facebook.net/en_US/sdk.js\";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script-->

";

  echo "</head>  
  <body onload=\"start();\" onmouseover=\"hidde_elem();\">";

$body = "";
$find="";
if(isset($_GET['find']))$find=mysql_real_escape_string($_GET['find']);


$parent=0;
if(isset($_REQUEST['parent']))
  $_REQUEST['parent'];

  if(isset($_REQUEST['parent'])&&!isset($_REQUEST['tovar_id'])&&(!isset($_GET['find']))){
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
  }elseif(isset($_GET['find'])){
    
	$body = find_result(mysql_real_escape_string($_GET['find']));
  }elseif(isset($_REQUEST['operation_id'])){
	    if(isset($_SESSION[BASE.'usersetup'])){
		if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
		    $body = user_operation_list_view(mysql_real_escape_string($_REQUEST['operation_id']),$setup);
	    }}
  }elseif(isset($_REQUEST['user'])){
	if($_REQUEST['user']=="new" and !isset($_SESSION[BASE.'userid'])){
	    $body = user_registration($setup,$_REQUEST,$setup);
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
	    $body = user_registration($setup,$_REQUEST,$setup);
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
      $body .= user_last_item_list_view(20,$setup);
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

include_once(".assets/analyticstracking.php");
include_once(".assets/analytics_yandex.php");

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
