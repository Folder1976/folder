<?php

include 'init.lib.php';
connect_to_mysql();
error_reporting(0);
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}
if($_SESSION[BASE.'userlevel']<900000){
echo "Poshel na XYN!!!";
exit();
}

$tmp_header="";
$file = "";
if(isset($_REQUEST['file'])){
    $file = $_REQUEST['file'];
    $tmp_header = file_get_contents($file);    
}

if(isset($_REQUEST['save'])){
/*    $file_txt = $_REQUEST['file_txt'];
    $file = $_REQUEST['file_name'];
    
    $fp = fopen("http://192.168.0.109/test.hhh","a");
    $test = fwrite($fp,$file_txt);
    echo $test;
    fclose($fp);


    
    $tmp_header = file_get_contents($file);    */
}

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
echo "<title>File upload</title>";
echo "<body>";
//========================================================================================================
//========================================================================================================
echo "<form enctype='multipart/form-data' method='post' action='admin_edit.php'>";
echo "<table border = 0 cellspacing='0' cellpadding='0'>";
echo "<tr><td width=\"150px\">File to open:</td><td>"; # Group name 1
//echo "<input type='hidden' name='MAX_FILE_SIZE' value='",1048*1048*1048,"'>";
echo "<input type='text' style='width:400px' name='file'></td><td>";
echo "<input type='submit'></td></tr>";
echo "</table></form>"; 
//========================================================================================================
//========================================================================================================
echo "<form enctype='multipart/form-data' method='post' action='admin_edit.php'>";
echo "<table border = 0 cellspacing='0' cellpadding='0'>";
echo "<tr><td width=\"150px\">Opened:</td><td>"; # Group name 1
echo "<input type='hidden' name='MAX_FILE_SIZE' value='",1048*1048*1048,"'>";
echo "<input type='text' style='width:400px'  name='file_name' value=\"$file\">
      <input type='submit' value='save' name='save' onclick='submit();'>
      </td>
      </tr><tr><td colspan=\"2\">";
echo "<textarea cols='150' rows='50' name='file_txt' >".$tmp_header."</textarea></td></tr><tr><td>";
echo "<input type='button' value='save' name='save' onclick='submit();'></td></tr>";
echo "</table></form></body>"; 

?>
