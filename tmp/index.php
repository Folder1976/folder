<?php
//echo phpinfo();
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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


  
//======================================================================================================================

//==Блок если нам чтото прилетело============================================================
if(isset($_POST['reviewer-comment'])){

	if(isset($_POST["g-recaptcha-response"]) AND $_POST["g-recaptcha-response"] != ''){
		include_once('class/class_comment.php');
		$Comment = new Comment($folder);
		$Comment->addComment($_POST);
		unset($_POST);
		unset($_SESSION['comment']);
	}else{
		$_SESSION['comment'] = $_POST;
		$_SESSION['comment']['error'] = true;
	}
	
	//Перезагрузим страницу нафиг
	header('Location: '.$_SERVER['REQUEST_URI'].'#capcha');
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


//Если на главную
if(isset($_GET['_route_']) AND $_GET['_route_'] == 'index'){
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: /", true, 301);
		return true;
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
	
	$data = $System->getCash($_SERVER['REQUEST_URI']);
	
	if(!$data){
		include_once("libs/products_control.php");
		$data = user_item_list_view($parent,$setup);
		$System->setCash($_SERVER['REQUEST_URI'], $data);
	}
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

save_log($_SERVER['REMOTE_ADDR'],$_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);

?>
