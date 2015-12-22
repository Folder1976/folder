<?php


if(isset($_GET['_route_'])){
  
	$_GET['_route_'] = str_replace('.html', '', $_GET['_route_']);
	$_GET['_route_'] = str_replace('\'', '"', $_GET['_route_']);
  
  $route = $folder->query("SELECT seo_url FROM tbl_seo_url WHERE seo_alias = '".$_GET['_route_']."'");
  
  
  if($route->num_rows == 0){
    //header ('Refresh: 0; url='.HOST_URL.'/page_not_found_404');
    //exit();
  }else{
  
    $rout = $route->fetch_assoc();
    
    if(strpos($rout['seo_url'], '&')){
      $values = explode("&", $rout['seo_url']);
    }else{
      $values[] = $rout['seo_url'];
    }
    
    
      foreach($values as $value){
		  $to_get = explode("=", $value);
		  $_GET[$to_get[0]] = $to_get[1];
		  $_POST[$to_get[0]] = $to_get[1];
		  $_REQUEST[$to_get[0]] = $to_get[1];
      }
  }
  
}


?>