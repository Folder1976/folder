<?php
class Product {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	/*
	 *Вернет ИД категории по ИД продукт
	 */
	public function getCategoryID($product_id){
		$sql = $this->base->query("SELECT tovar_inet_id_parent FROM tbl_tovar WHERE tovar_id = '".$product_id."'");
		if($sql->num_rows == 0){
			return false;
		}else{
			$tmp = $sql->fetch_assoc();
			return $tmp['tovar_inet_id_parent'];
		}
		
	}

	/*
	 *Вернет главную картинку продутка
	 */
	public function getProductPicOnArtkl($artkl){
	
		$sql = $this->base->query("SELECT pic_name FROM tbl_tovar_pic WHERE tovar_artkl = '".$artkl."'");
		if($sql->num_rows == 0){
			return HOST_URL.'/resources/img/no_photo.png';
		}else{
			$tmp = $sql->fetch_assoc();
			return HOST_URL.'/resources/products/'.$tmp['pic_name'];
		}
		
	}
	
	/*
	 *Вернет короткое описание продукта
	 */
	public function getProductMemoShort($product_id){
		$sql = $this->base->query("SELECT description_1 FROM tbl_description WHERE description_tovar_id = '".$product_id."'");
		if($sql->num_rows == 0){
			return false;
		}else{
			$tmp = $sql->fetch_assoc();
			$string = $tmp['description_1'];
			$string = strip_tags($string);
			$string = substr($string, 0, 100);
			$string = rtrim($string, "!,.-");
			$string = substr($string, 0, strrpos($string, ' '));
			return $string."… ";
		}
		
	}
	
	/*
	 *Вернет описание продукта
	 */
	public function getProductMemo($product_id){
		$sql = $this->base->query("SELECT description_1 FROM tbl_description WHERE description_tovar_id = '".$product_id."'");
		if($sql->num_rows == 0){
			return false;
		}else{
			$tmp = $sql->fetch_assoc();
			return $tmp['description_1'];
		}
		
	}
	
	/*
	 *Вернет цену продукта в валюте сайта
	 */
	public function getProductPrice($product_id){
		global $setup, $currency;
		
		$i = $setup['price default price'];
		
		$sql = "SELECT
				price_tovar_{$i} as price,
				price_tovar_curr_{$i} as valuta
				FROM tbl_price_tovar WHERE price_tovar_id = '".$product_id."'";
		$res = $this->base->query($sql) or die('osaiduytflijksdgfl<br>'.$sql.'<br>'.mysql_error());
		
		if($res->num_rows == 0){
			return false;
		}else{
			$tmp = $res->fetch_assoc();
			return ($tmp['price'] *
				$currency[$tmp['valuta']]);
		}
		
	}
	
	
	/*
	 *Принимает артикл продукта
	 *
	 *Вернет массив братьев продукта
	 */
	public function getProductBrotherOnArtikl($product_artkl){
		
		global $setup;
		
		$separator = $setup['tovar artikl-size sep'];
		
		$artkl = $product_artkl;
		if(strpos($product_artkl, $separator) !== false){
			list($artkl,$size) = explode($separator, $product_artkl);
		}
		
		$sql = "SELECT tovar_id,
				tovar_artkl,
				tovar_name_1
			FROM tbl_tovar WHERE tovar_artkl LIKE '".$artkl.$separator."%'";
		$res = $this->base->query($sql) or die('osaiduytfdsgflijksdgfl<br>'.$sql.'<br>'.mysql_error());
		
		if($res->num_rows == 0){
			return false;
		}else{
			$return = array();
			while($tmp = $res->fetch_assoc()){
				$return[] = $tmp;	
			}
			
			return $return;
		}
		
	}
	

}
?>