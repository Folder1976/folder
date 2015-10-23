<?php
include 'init.lib.php';
connect_to_mysql();
session_start();

header ('Content-Type: text/html; charset=utf8');
echo "<header><title>PIC</title></header>";

 echo "	Copy file and dir from Job format<br>
	FROM: /var/www/admin/resources/products/*<br>
	TO:   /var/www/resources/products/*<br>";



$ver = mysql_query("SET NAMES utf8");
  $sql="SELECT 
	`tovar_id`,
	`tovar_inet_id`
	FROM 
	`tbl_tovar` 
	WHERE 
	`tovar_inet_id`>'0' ";
	
$ver = mysql_query($sql);
if (!$ver)
{
  echo "Query error - ",$sql;
  exit();
}
my_copy($ver,0);

//==================================================================================
$ver = mysql_query("SET NAMES utf8");
  $sql="SELECT 
	`parent_inet_id`,
	`parent_inet_type`
	FROM 
	`tbl_parent_inet`
	WHERE 
	";
	
$ver = mysql_query($sql);
if (!$ver)
{
  echo "Query error - ",$sql;
  exit();
}
my_copy($ver,1);
//==================================================================================


function my_copy($ver,$parent) {
$last="";
$path_to="";
$count=-0;
echo "Rows - ",mysql_num_rows($ver),"<br>";
while($count < mysql_num_rows($ver)){

  if($parent)
     {	
	$id = "GR".mysql_result($ver,$count,"parent_inet_id");
	$id_inet = mysql_result($ver,$count,"parent_inet_id");
      }else{
	$id = mysql_result($ver,$count,"tovar_id");
	$id_inet = mysql_result($ver,$count,"tovar_inet_id");
      }
  if($last <> $id)
    {
    $path_from = "resources/products/".$id_inet;
    $path_to = "../resources/products/".$id;
    echo "<br>copy - ",$path_from," -> ",$path_to;
    
     if(file_exists($path_from))
     {
	echo " exist ";
	if(!is_dir($path_to)){
	    if(mkdir($path_to,0777,true))echo " - dir mk";
	  }
	chmod($path_to,0777);
      
	$files= scandir($path_from);
	
	$file_to="";
	$file_from="";
	$file_count=2;
	  while(!empty($files[$file_count]))
	  {
	    //echo "<br>";
	    $file_from= $path_from.'/'.$files[$file_count];
	    $file_to=$path_to.'/'.$files[$file_count];
	    $file_to = str_replace('/'.$id_inet.'.','/'.$id.'.',$file_to);
	    //echo $file_from," -> ",$file_to;
	    if(copy($file_from,$file_to)) 
	      {
		echo ".";
		chmod($file_to,0777);
	      }
	    $file_count++;
	  }
      //echo "<br>",$id," ",$id_inet," ",$type," - ",$name;
    
	  }
     }
  $last = $id;
  $count++;
}

}







?>
