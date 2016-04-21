<?php

include_once("class/class_alias.php");
include_once("class/class_category.php");
include_once("class/class_alias.php");
include_once("class/class_attribute.php");
include_once("class/class_main.php");
include_once("class/class_product.php");
include_once("admin/class/class_brand.php");
include_once 'class/class_info.php';
include_once 'class/class_order.php';  
include_once 'class/class_user.php';
include_once 'class/class_banner.php';

$Banner = new Banner($folder);
$User = new User($folder);
$Order = new Order($folder);
$Info = new Info($folder);
$Alias = new Alias($folder);
$Category = new Category($folder);
$Attribute = new Attribute($folder);
$Main = new Main($folder);
$Product = new Product($folder);
$Brand = new Brand($folder);

//include_once("libs/product_control.php");
//include_once("libs/products_control.php");

if(!isset($_SESSION[BASE.'lang'])) $_SESSION[BASE.'lang'] = 1;

//Взять остальные настройки
$setup_sql = $folder->query("SELECT `setup_menu_name`, `setup_menu_".$_SESSION[BASE.'lang']."` FROM `tbl_setup_menu`;");
while ($tmp = $setup_sql->fetch_assoc()){
 $setup[$tmp['setup_menu_name']] = $tmp['setup_menu_'.$_SESSION[BASE.'lang']];
}

//Взять курсы валют
$setup_sql = $folder->query("SELECT `currency_id`, `currency_name_shot`, `currency_ex` FROM `tbl_currency`;");
while ($tmp = $setup_sql->fetch_assoc()){
 $currency[$tmp['currency_id']] = $tmp['currency_ex'];
 $currency_name[$tmp['currency_id']] = $tmp['currency_name_shot'];
}


?>