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

echo "<br><ul class=\"setup_menu\">Настройки
    ";
echo "<li><a href='edit_klient.php' target='_blank'>",$m_setup['setup klient'],"</a></li>";
echo "<li><a href='edit_klienti_group.php' target='_blank'>",$m_setup['setup klient group'],"</a></li>";
echo "<li><a href='edit_delivery.php' target='_blank'>",$m_setup['setup delivery'],"</a></li>";
echo "<li><a href='edit_nakl-group.php' target='_blank'>",$m_setup['setup nakl'],"</a></li>";
echo "<li><a href='edit_inet_parent_table.php' target='_blank'>",$m_setup['setup nakl inet'],"</a></li>";
echo "<li><a href='edit_status.php' target='_blank'>",$m_setup['setup status'],"</a></li>";
echo "<li><a href='edit_warehouse.php' target='_blank'>",$m_setup['setup warehouse'],"</a></li>";
echo "<li><a href='edit_shop.php' target='_blank'>",$m_setup['setup shop'],"</a></li>";
echo "<li><a href='none.php' target='_blank'>",$m_setup['setup price'],"</a></li>";
echo "<li><a href='edit_curr.php' target='_blank'>",$m_setup['setup curr'],"</a></li>";
echo "</ul>";

echo "<br><ul class=\"setup_menu\">Работа";

$r = $folder->query('SELECT function_id, function_alias, function_name, function_patch, function_level FROM tbl_functions ORDER BY function_sort ASC');
while($func = $r->fetch_assoc()){
  echo "<li><a href='main.php?func=".$func['function_alias']."' target='_blank'>".$func['function_name']."</a></li>";
}
if (strpos($_SESSION[BASE.'usersetup'],"analitics")>0){
echo "<li><a href='get_analitics.php' target='_blank'>",$m_setup['menu klient analitics'],"</a></li>";
}
echo "<li><a href='get_findlog.php' target='_blank'>",$m_setup['menu findlog'],"</a></li>";
echo "</ul>";

echo "<br><ul class=\"setup_menu\">Система";
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
echo "</ul>";


?>
