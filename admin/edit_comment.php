<?php
header ('Content-Type: text/html; charset=utf8');
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';

$reset_user_count=0;
//==================================SETUP===========================================
if (!isset($_SESSION[BASE.'lang'])){
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

//======================== BAN IP=========================================================
if(isset($_REQUEST['klient_ip']) and isset($_REQUEST['klient_ip']))
{
$ip=$_REQUEST['klient_ip'];
  
  $sql = 'INSERT INTO tbl_black_ip_list SET
					black_ip_ip = \''.$ip.'\';';
  $tmp = mysql_query($sql);
  
  $tmp = mysql_query("DELETE FROM `tbl_comments` WHERE `comments_ip`='".$ip."'");
  
					
}
//======================== DELL=========================================================
if(isset($_REQUEST['_dell']) and isset($_REQUEST['sellect']))
{
$dell=$_REQUEST['sellect'];
  if(!empty($dell))
  {
    $count=0;
    while($count<count($dell))
    {
      $id = $dell[$count];
      $tmp = mysql_query("SET NAMES utf8");
      $tmp = mysql_query("DELETE FROM `tbl_comments` WHERE `comments_id`='".$id."'");
    $count++;  
    }
  }
}
//======================== DELL=========================================================

$tQuery="SELECT
	  `Klienti_id`,
	  `klienti_name_1`,
	  `comments_tovar`,
	  `comments_memo`,
	  `comments_id`,
	  `comments_ip`
	  FROM
	  `tbl_comments`
	  LEFT JOIN `tbl_klienti` ON `klienti_id` = `comments_klient`
	   ";
 
$tovar_id="";
if(isset($_REQUEST["tovar_id"]))
{ 
  $reset_user_count++;
  $tovar_id = $_REQUEST["tovar_id"];
  $tQuery .= " and `comments_tovar` LIKE '" . $tovar_id . "'";
}

$klient_id="";
if(isset($_REQUEST["klient_id"]))
{ 
  $reset_user_count++;
  $klient_id = $_REQUEST["klient_id"];
  $tQuery .= " and `comments_klient` = '" . $klient_id . "'";
}
$tQuery .= " ORDER BY `comments_id` DESC";
$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query($tQuery);
  if (!$ver)
  {
    echo "Query error " , $tQuery;
    exit();
  }

  

echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>
<title>Edit Comment</title>";
echo "<script src='JsHttpRequest.js'></script>";
echo "<script type='text/javascript'>";
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
      req.open(null,'save_table_field.php',true);
      req.send({table:table,name:name,value:value,w_id:id,w_value:tovar});
    }
    
   </script>";
//========================================================================================================
echo "<body>";
echo "<form method='post' action='edit_comment.php'>";
echo "<input type='submit' name='_dell' value='",$m_setup['menu dell'],"'/>
      <a href='edit_comment.php'> ",$m_setup['menu view all']," </a>
      ";

echo "<table border = 0 cellspacing='1' cellpadding='1'>";

$count=0;
$comment_id=0;
global $folder;
while($count<mysql_num_rows($ver))
{
  $r = $folder->query('SELECT tovar_id, tovar_artkl, tovar_model, tovar_name_1 FROM tbl_tovar WHERE tovar_artkl LIKE "'.mysql_result($ver,$count,"comments_tovar").'%"');
  $tovar = $r->fetch_assoc();
  
  $comment_id = mysql_result($ver,$count,"comments_id");
  echo "<tr><td><input type='checkbox' name='sellect[]' value='",$comment_id,"'></td>";
  echo "<td>
	<a href='edit_comment.php?klient_id=",mysql_result($ver,$count,"klienti_id"),"'>[sort] </a>
	<a href='edit_klient.php?klienti_id=",mysql_result($ver,$count,"klienti_id"),"'>",mysql_result($ver,$count,"klienti_name_1"),"</a></td>";
  echo "<td>
	<a href='edit_comment.php?tovar_id=",$tovar['tovar_model'],"'>[sort] </a>
	<a href='/search_result.html?search=",$tovar['tovar_name_1'],"' target='_blank'>".$tovar['tovar_artkl']. ' '. $tovar['tovar_name_1']."</a></td>";
  echo "<td><textarea cols='60' rows='2' 
	  id='$comment_id' 
	  name='comment_txt' 
	  OnChange='update(\"tbl_comments\",\"comments_memo\",this.value,\"comments_id\",this.id);'
	  >".mysql_result($ver,$count,"comments_memo")."
	</textarea></td>";
  echo "<td><a href='edit_comment.php?klient_ip=",mysql_result($ver,$count,"comments_ip"),"'>[в бан засранца!] </a></td></tr>";

 $count++;
}
echo "\n</table>"; 
echo "\n</body>";

if($reset_user_count==0)
{
$tQuery = "UPDATE `tbl_klienti` SET `klienti_last_comment`='".mysql_result($ver,0,"comments_ip")."'
	    WHERE `klienti_id`='".$_SESSION[BASE.'userid']."'";
	    
$clear = mysql_query("SET NAMES utf8");
$clear = mysql_query($tQuery);
}

?>
