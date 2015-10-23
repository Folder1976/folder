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
	  WHERE 
	  `setup_menu_name` like '%menu%'

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

$operation_id=0;
if(isset($_REQUEST['operation_id'])) $operation_id=$_REQUEST['operation_id'];
//echo $_REQUEST['operation_id'];
//==================================SETUP=MENU==========================================
header ('Content-Type: text/html; charset=utf8');
echo "<html><title>Internet prn ",$operation_id,"</title>
<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
echo "<script>
    function PrintFrame(){
    alert('gg');
	frames[\"script_window\"].focus();
	frames[\"script_window\"].print();
    }

</script>";


  echo "<table width=100%><tr><td width=\"200px\">
	<a href='edit_nakl_print.php?tmp=print&operation_id=",$operation_id,"' target='script_window'>",$m_setup['menu print sale'],"</a>
	--> <a href='#none' onClick='PrintFrame();'>",$m_setup['menu print'],"</a>";
  echo "</td><td>
	<a href='edit_nakl_delive.php?operation_id=",$operation_id,"' target='script_window'>
	    <font color=\"red\">new </font>",$m_setup['menu delivery'],"</a>";
  echo "</td><td>
	<a href='edit_nakl_print.php?tmp=warehouse&next=bay&operation_id=",$operation_id,"' target='script_window'>
	    <font color=\"red\">new </font>",$m_setup['menu print']," PLUS+</a>";
  echo "</td><td>
  	<a href='send_mail.php?operation_id=",$operation_id,"' target='script_window'>
	     <font color=\"red\">sms </font>",$m_setup['menu send mail'],"</a>
  ";
  echo "</td><td>
	<a href='edit_nakl_print.php?tmp=warehouse&operation_id=",$operation_id,"' target='script_window'>",$m_setup['menu print ware'],"</a>";
  echo "</td><td>
	<a href='edit_nakl_print.php?tmp=bay&operation_id=",$operation_id,"' target='script_window'>",$m_setup['menu print bay'],"</a>";
  echo "</td><td>
	<a href='edit_nakl_print.php?tmp=analytics&operation_id=",$operation_id,"' target='script_window'>",$m_setup['menu print analytics'],"</a>";
 // echo "</td><td>
//	<a href='send_mail.php?operation_id=",$operation_id,"' target='script_window'>",$m_setup['menu send mail'],"</a>";
  echo "</td></tr></table>";

  
  /*<frameset rows='50,*' cols='*' border='1'>
   <frame src='script_internet_keys?operation_id=",$operation_id,".php' name='script_key' id='script_key' scrolling='no' noresize>
   <frame src='edit_nakl_oplata.php?operation_id=",$operation_id," name='script_window' id='script_window' scrolling='yes'>
 </frameset>
</html>";*/
?>
