<?php
include 'init.lib.php';
include 'init.class.upload_0.31.php';

connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
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
	  `setup_menu_name` like '%setup%'

";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_name`, 
	  `setup_param`
	  FROM `tbl_setup`
	  ";
//echo $tQuery;
$setup = mysql_query($tQuery);
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
$this_page_name = "import_file.php";

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
echo "<title>File upload</title>";
echo "<body>";
//========================================================================================================
//========================================================================================================
echo "<form enctype='multipart/form-data' method='post' action='" , $this_page_name , "'>";
echo "<table border = 0 cellspacing='0' cellpadding='0'>";
echo "<tr><td>Load SQL file for base:</td><td>"; # Group name 1
echo "<input type='hidden' name='MAX_FILE_SIZE' value='",1048*1048*1048,"'>";
echo "<input type='file' style='width:200px'  name='userfile' OnChange='submit();'/></td><td>";
echo "<input type='submit'></td></tr>";
echo "</table></form>"; 
//========================================================================================================
//========================================================================================================


  if($_FILES['userfile']['error']>0)
  {
    echo "file error = ",$_FILES['userfile']['error'];
      switch($_FILES['userfile']['error'])
      {
	case 1: echo "UPLOAD_MAX_FILE_SIZE error!<br>";break;
	case 2: echo "MAX_FILE_SIZE erroe!<br>";break;
	case 3: echo "Not file loading, breaking process.<br>";break;
	case 4: echo "Not load<br>";break;
	case 6: echo "tmp folder error<br>";break;
	case 7: echo "write file error<br>";break;
	case 8: echo "php stop your load<br>";break;
      }
  }
  $uploaddir = $m_setup['load patch'];//"/var/www/resources/";
  $newFileName="sql_import.txt";//$_FILES['userfile']['tmp_name'];
  $uploadfile = $uploaddir.$_FILES['userfile']['name'];
  echo $_FILES['userfile']['name'],"<br>";
  echo $uploadfile,"<br>";

   
   if (move_uploaded_file($_FILES['userfile']['tmp_name'],$uploadfile)) {
      echo "File loaded - OK<br>";
    }
   
   echo "<img src='../resources/test.jpg'>";
echo "</body>";

?>
