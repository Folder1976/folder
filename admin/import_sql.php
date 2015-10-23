<?php
include 'init.lib.php';
//phpinfo();
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
$this_page_name = "import_sql.php";

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
echo "<title>SQL file upload</title>";
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
echo "</body>";
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
  if($_FILES['userfile']['type'] != 'text/plain')
  {
    echo "not txt file<br>";
  }
  $uploaddir = "/var/www/admin/uploads/";
  $newFileName="sql_import.txt";//$_FILES['userfile']['tmp_name'];
  $uploadfile = $uploaddir.$newFileName;
   if (move_uploaded_file($_FILES['userfile']['tmp_name'],$uploadfile)) {
      echo "File loaded - OK<br>";
	$file = fopen($uploaddir.$newFileName,"r");
	$setup = mysql_query("SET NAMES utf8");
	  while(!feof($file)){
	  $line=fgets($file);
	  $line = iconv('CP1251','UTF-8',$line);
	  $line = str_replace("|+|","<br>",$line);
	  //echo $line,"<br>==================<br>";
	    $setup = mysql_query($line);
	  }
	fclose($file);
	
  /*    $tQuery="INSERT INTO `tbl_klienti` (`klienti_id`,`klienti_name_1`)
				 VALUES ('-1','mag.STURM')";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);

     $tQuery="INSERT INTO `tbl_klienti` (`klienti_id`,`klienti_name_1`)
				 VALUES ('-2','guest')";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);

     $tQuery="UPDATE `tbl_parent` SET `tovar_parent_id`='1' WHERE `tovar_parent_id`='240'";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);

     $tQuery="UPDATE `tbl_delivery` SET `delivery_id`='0' WHERE `delivery_id`='1450'";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);

     $tQuery="UPDATE `tbl_operation_status` SET `operation_status_id`='0' WHERE `operation_status_memo`='d'";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);
      
     $tQuery="UPDATE `tbl_klienti` SET `klienti_inet_id`='10'";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);
    
  
     $tQuery="INSERT INTO `tbl_klienti` (`klienti_name_1`,`klienti_price`,`klienti_email`,`klienti_pass`,`klienti_setup`,`klienti_inet_id`,`klienti_phone_1`)
				 VALUES ('Folder','2','kottem@mail.ru','folder1976',' STURM ','1000','0672586999')";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);

 
      $tQuery="UPDATE `tbl_operation_detail` SET `operation_detail_from`='14' WHERE `operation_detail_from`='0'";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);

      $tQuery="SELECT `tovar_id` FROM `tbl_tovar`";
      echo $tQuery,"<br>";
      $setup = mysql_query($tQuery);
      $count=0;
      while($count<mysql_num_rows($setup))
      {
	$wer = mysql_query("SELECT `description_tovar_id` FROM `tbl_description` WHERE `description_tovar_id`='".mysql_result($setup,$count,0)."'");
	if(mysql_num_rows($wer)>0)
	{
	}else{
	  //echo mysql_result($setup,$count,0),"add<br>";
	    $ins = mysql_query("INSERT INTO `tbl_description` SET `description_tovar_id`='".mysql_result($setup,$count,0)."'");
	}
	$count++;
      }*/
      
    }else{
      echo "Not load!!!<br>";
    }
  


function str_rus_upper($string){
$string = strtr($string,"qwertyuiopasdfghjklzxcvbnm","44");
return $string;
}
?>
