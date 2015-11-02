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

	
}