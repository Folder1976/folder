<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}


//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================


header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'>
<title>Setup</title>
</header>";

echo '<div style="float: left;width:32%;"><ul class="setup_menu"><h2>Настройки</h2>
    ';
echo "<li><a href='edit_klient.php' target='_blank'>",$m_setup['setup klient'],"</a></li>";
echo "<li><a href='edit_klienti_group.php' target='_blank'>",$m_setup['setup klient group'],"</a></li>";
echo "<li><a href='edit_delivery.php' target='_blank'>",$m_setup['setup delivery'],"</a></li>";
echo "<li><a href='edit_nakl-group.php' target='_blank'>Редактировать внутрение группы товаров</a></li>";
echo "<li><a href='edit_inet_parent_table.php' target='_blank'>",$m_setup['setup nakl inet'],"</a></li>";
echo "<li><a href='edit_status.php' target='_blank'>",$m_setup['setup status'],"</a></li>";
echo "<li><a href='edit_warehouse.php' target='_blank'>",$m_setup['setup warehouse'],"</a></li>";
echo "<li><a href='edit_shop.php' target='_blank'>",$m_setup['setup shop'],"</a></li>";
echo "<li><a href='none.php' target='_blank'>",$m_setup['setup price'],"</a></li>";
echo "<li><a href='edit_curr.php' target='_blank'>",$m_setup['setup curr'],"</a></li>";
echo "<li><a href='edit_info.php?info_id=0&info_list_sort=' target='_blank'>Страницы информации</a></li>";
echo "<li><a href='edit_brands.php' target='_blank'>Редактировать бренды</a></li>";
echo "<li><a href='tools/translate_edit.php' target='_blank'>Словосочетания автозамены. (псевдопереводчик)</a></li>";
echo "</ul>";

echo "<ul class=\"setup_menu\"><h2>Работа</h2>";

$r = $folder->query('SELECT function_id, function_alias, function_name, function_patch, function_level FROM tbl_functions ORDER BY function_sort ASC');
while($func = $r->fetch_assoc()){
  echo "<li><a href='main.php?func=".$func['function_alias']."' target='_blank'>".$func['function_name']."</a></li>";
}
if (strpos($_SESSION[BASE.'usersetup'],"analitics")>0){
echo "<li><a href='get_analitics.php' target='_blank'>",$m_setup['menu klient analitics'],"</a></li>";
}
echo "<li><a href='get_findlog.php' target='_blank'>",$m_setup['menu findlog'],"</a></li>";
echo "</ul>";

echo "<br><ul class=\"setup_menu\"><h2>Система</h2>";
echo "<li><a href='set_all_ostatki.php' target='_blank'>",$m_setup['menu set_all_ostatki'],"</a></li>";
echo "<li><a href='clear_op_det.php' target='_blank'>",$m_setup['menu clear_op_det'],"</a></li>";
echo "<li><a href='restore_nakl.php' target='_blank'>",$m_setup['menu restore_nakl'],"</a></li>";
echo "<li><a href='restore_nakl_field.php' target='_blank'>",$m_setup['menu restore_nakl_field'],"</a></li>";
echo "<li>* * * * * * * * * *</li>";
echo "<li><a href='edit_info.php' target='_blank'>",$m_setup['menu news']," - ",$m_setup['menu help'],"</a></li>";
echo "<li><a href='send_mail_all.php' target='_blank'>",$m_setup['menu send to all'],"</a></li>";
echo "<li>* * * * * * * * * *</li>";
echo "<li><a href='import_sql.php' target='_blank'>",$m_setup['setup import SQL'],"</a></li>";
echo "<li><a href='import_file.php' target='_blank'>",$m_setup['setup import file'],"</a></li>";
//echo "<tr><td><a href='import_bane.php' target='_blank'>",$m_setup['setup import file'],"</a></td></tr>";
echo "<li><a href='import_pic.php' target='_blank'>",$m_setup['setup import pic'],$_SESSION[BASE.'userlevel'],"</a></li>";
echo "<li>* * * * * * * * * *</li>";
if($_SESSION[BASE.'userlevel']>9000){
  echo "<li><a href='admin_edit.php' target='_blank'>",$m_setup['setup admin'],"</a></li>";
  echo "<li><a href='translate_name.php?tovar_id=all' target='_blank'>",$m_setup['menu setup translate'],"</a></li>";
}
echo "<br><ul class=\"setup_menu\">Старое";
if (strpos($_SESSION[BASE.'usersetup'],"habibulin")>0){
    echo "<li><a href='edit_habibulin_parent.php?habibulin_parent_id=last' target='_blank'>",$m_setup['setup habibulin parent'],"</a></li>";
    echo "<li><a href='get_habibulin.php' target='_blank'>",$m_setup['setup habibulin'],"</a></li>";
}
echo "</ul></div>";
?>

<div style="float: left;width:32%;"><ul class="setup_menu"><h2>Товары</h2>
	<li><a href="main.php?func=import_universal">Импорт УНИВЕРСАЛ</a></li>
	<li><a href="main.php?func=import_price">Импорт Остатки и Цены</a></li>
	<li><a href="main.php?func=import_on_model">Импорт по моделям товаров</a></li>
	<li><a href="main.php?func=alternative_artikles">Универсальные артиклы товаров</a></li>
	<li><a href="main.php?func=relink_photo">Починить линки товар-фото</a></li>
	<li><a href="main.php?func=import_photo">Импорт Фотографий файлы</a></li>
	<li><a href="main.php?func=import_url_photo_excel">Импорт Фотографий Excel</a></li>
	<li><a href="watermark/">Установка водяных знаков на большие изображения</a></li>
</ul>
  <ul class="setup_menu"><h2>Города и доставки</h2>
	<li><a href="main.php?func=city">Города</a></li>
	
  </ul>
 <ul class="setup_menu"><h2>Инструменты</h2>
	<li><a href="main.php?func=find_on_sturm">Поиск товаров на Штурме (Excel)</a></li>
	<li><a href="main.php?func=add_products">Автомат добавления продуктов</a></li>
	
  </ul>
 <ul class="setup_menu"><h2>Аналитика и отчеты</h2>
	<li><a href="main.php?func=analitik_sale">Аналитика продаж</a></li>
	
  </ul>
</div>

<div style="float: left;width:32%;">
  <ul class="setup_menu"><h2>Реклама</h2>
	<li><a href="main.php?func=banner">Банеры</a></li>
	<li><a href='edit_info.php?info_list_sort=information' target='_blank'>Страницы информации</a></li>
  </ul>



</div>
