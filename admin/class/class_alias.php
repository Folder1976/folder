<?php
class Alias {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	public function getAlias($url){
		$sql = $this->base->query("SELECT seo_alias FROM tbl_seo_url WHERE seo_url = '$url'") or die (mysql_error());
		if($sql->num_rows == 0){
			return false;
		}else{
			$res = $sql->fetch_assoc();
			return $res['seo_alias'];	
		}
		
	}
	public function getProductAlias($id){
		$sql = $this->base->query("SELECT seo_alias FROM tbl_seo_url WHERE seo_url = 'tovar_id=$id'") or die (mysql_error());
		if($sql->num_rows == 0){
			return false;
		}else{
			$res = $sql->fetch_assoc();
			return $res['seo_alias'];	
		}
		
	}
	
	public function saveProductAlias($alias,$id){
		$sql = $this->base->query("INSERT INTO tbl_seo_url SET seo_url = 'tovar_id=$id', seo_alias='$alias'
					  ON DUPLICATE KEY UPDATE seo_alias='$alias';") or die (mysql_error());
		
	}

	public function dellAliasOnProductID($id){
		$sql = "DELETE FROM `tbl_seo_url` WHERE `seo_url` = 'tovar_id=$id';";
		$this->base->query($sql) or die ('dell alias<br>'.$sql.mysql_error());
	}

	public function resetGET($alias){
		$sql = $this->base->query("SELECT seo_url FROM tbl_seo_url WHERE seo_alias = '$alias';") or die (mysql_error());
		if($sql->num_rows != 0){
			$tmp = $sql->fetch_assoc();
			list($Name, $Value) = explode('=', $tmp['seo_url']);
			$_GET[$Name] = $Value;
		}
	}

	public function getCategoryAlias($id){
		$sql = $this->base->query("SELECT seo_alias FROM tbl_seo_url WHERE seo_url = 'parent=$id'") or die (mysql_error());
		if($sql->num_rows == 0){
			return false;
		}else{
			$res = $sql->fetch_assoc();
			return $res['seo_alias'];	
		}
		
	}

	public function getAliasFromStr($str){

		$rus = array('@','-','+','/','\\','<','>','?','!','[',']','*',',','{','}',')','(',' ','и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		$lat = array('','','','','','','','','','','','','','','','','','_','u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
		
		return strtolower(str_replace($rus, $lat, $str));
	}
	
	public function generateAlias($tovar_id){
		
		include_once ("class_product.php");
		if(!isset($Product)) $Product = new Product($this->base);
		include_once ("../admin/class/class_product_edit.php");
		if(!isset($ProductEdit)) $ProductEdit = new ProductEdit($this->base);
		include_once ("../admin/class/class_brand.php");
		if(!isset($Brand)) $Brand = new Brand($this->base);
		  
		$category_id = $Product->getCategoryID($tovar_id);
	 
	 	$category_alias = $this->getCategoryAlias($category_id);
	      
		$tovar_artkl = $ProductEdit->getProductArtkl($tovar_id);
	      
		$brand = $Brand->getBrandCodeOnProductId($tovar_id);
	      
		$alias = $category_alias.'/'.$brand.'/'.$tovar_artkl;
	      
		$alias = str_replace('//','/',$alias);
	      
		return $alias;
	}
	
	
	
	public function updateCategoryAlias($id){
		
		include_once ("class_category.php");
		if(!isset($Category)) $Category = new Category($this->base);
		  
		$category = $Category->getCategoryPath($id);
		
		if($category){

			$alias = '';
			
			foreach($category as $val){
				$alias .= $this->getAliasFromStr($val) . '/';
			}
		
			$alias = trim($alias, ' /');
			
			$sql = "INSERT INTO `tbl_seo_url`(`seo_id`, `seo_url`, `seo_alias`, `seo_main`)
					VALUES ('','parent=$id','$alias','0')
					ON DUPLICATE KEY UPDATE `seo_alias` = '$alias'";
			
			$sql = $this->base->query($sql);
			
		}
	 	
	}

	public function updateProductAlias($tovar_id){
		
		include_once ("class_product.php");
		if(!isset($Product)) $Product = new Product($this->base);
		include_once ("../admin/class/class_product_edit.php");
		if(!isset($ProductEdit)) $ProductEdit = new ProductEdit($this->base);
		include_once ("../admin/class/class_brand.php");
		if(!isset($Brand)) $Brand = new Brand($this->base);
		  
		$category_id = $Product->getCategoryID($tovar_id);
	 
	 	$category_alias = $this->getCategoryAlias($category_id);
	      
		$tovar_artkl = $ProductEdit->getProductArtkl($tovar_id);
	      
		$brand = $Brand->getBrandCodeOnProductId($tovar_id);
	      
		$alias = $category_alias.'/'.$brand.'/'.$tovar_artkl;
	      
		$alias = str_replace('//','/',$alias);
	      
		$sql = "INSERT INTO `tbl_seo_url`(`seo_id`, `seo_url`, `seo_alias`, `seo_main`)
				VALUES ('','tovar_id=$tovar_id','$alias','0')
				ON DUPLICATE KEY UPDATE `seo_alias` = '$alias'";
		
		$sql = $this->base->query($sql);
		
	}


	
}