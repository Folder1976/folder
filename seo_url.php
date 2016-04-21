<?php


if(isset($_GET['_route_'])){
  
  $_GET['_route_'] = mysqli_escape_string($folder, $_GET['_route_']);
  
	//Влетел фильтр по брендам!
	if(strpos($_GET['_route_'], 'brend/') !== false){
		  
		  $brand_code = str_replace('brend/','',$_GET['_route_']);
		  
		  $route = $folder->query("SELECT brand_id, brand_name FROM tbl_brand WHERE brand_code = '".$brand_code."'");
		  $rout = $route->fetch_assoc();
		  
		  $_GET['parent'] = 0;
		  $_GET['brand_code'] = $brand_code;
		  $_GET['brand_name'] = $rout['brand_name'];
		  $_GET['brand_id'] = $rout['brand_id'];
		  
		  $_POST['parent'] = 0;
		  $_POST['brand_code'] = $brand_code;
		  $_POST['brand_name'] = $rout['brand_name'];
		  $_POST['brand_id'] = $rout['brand_id'];
		  
		  $_REQUEST['parent'] = 0;
		  $_REQUEST['brand_code'] = $brand_code;
		  $_REQUEST['brand_name'] = $rout['brand_name'];
		  $_REQUEST['brand_id'] = $rout['brand_id'];
		  
		  return true;
		  
	}
	
  
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