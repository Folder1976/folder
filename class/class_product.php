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
		
		global $setup;
		$sep =  $setup['tovar artikl-size sep'];
		
		if(strpos($artkl, $sep) !== false){
			$tmp = explode($sep, $artkl);
			$artkl = $tmp[0];
		}
	
		$sql = $this->base->query("SELECT pic_name FROM tbl_tovar_pic WHERE tovar_artkl = '".$artkl."'");
		if($sql->num_rows == 0){
			return HOST_URL.'/resources/img/no_photo.png';
		}else{
			$tmp = $sql->fetch_assoc();
			return HOST_URL.'/resources/products/'.$tmp['pic_name'];
		}
		
	}
	
	/*
	 *Вернет количество возможное к заказу
	 */
	public function getProductOnWare($id){
	
		$sql = $this->base->query("SELECT SUM(items) as item FROM tbl_tovar_suppliers_items WHERE tovar_id = '$id'");
		if($sql->num_rows == 0){
			return 0;
		}else{
			$tmp = $sql->fetch_assoc();
			return $tmp['item'];
		}
		
	}
	
	/*
	 *Вернет массив детального по наличию цене и доставке товара
	 */
	public function getProductDelivInfo($id){
	
		$sql = "SELECT items, price_1, delivery_days, postav_id
									FROM tbl_tovar_suppliers_items
									LEFT JOIN tbl_klienti ON postav_id = klienti_id
									WHERE tovar_id = '$id' AND items > 0
									ORDER BY delivery_days ASC;";
					
		$r = $this->base->query($sql);
		if($r->num_rows == 0){
			return false;
		}else{
			$return = array();
			while($tmp = $r->fetch_assoc()){
				$return[] = $tmp;
			}
			return $return;
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
	//	global $setup, $currency;
		$min_price = 7777777;
		
		$sql = "SELECT price_1 as price FROM tbl_tovar_suppliers_items WHERE tovar_id = '$product_id';";
		$r = $this->base->query($sql);
		if($r->num_rows == 0){
			return 0;
		}else{
			while($tmp = $r->fetch_assoc()){
				if($min_price > $tmp['price'] AND $tmp['price'] > 0) $min_price = $tmp['price'];
				if($min_price == 7777777) $min_price = 0;
			}
			return $min_price;
		}
	
		
		/*
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
		*/
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
	
	/*
	 *Вернет массив ид всех продуктов
	 */
	public function getAllProductsId(){
		
		$sql = "SELECT tovar_id
			FROM tbl_tovar;";
		$res = $this->base->query($sql) or die('osaidueqadytfdsgflijksdgfl<br>'.$sql.'<br>'.mysql_error());
		
		if($res->num_rows == 0){
			return false;
		}else{
			$return = array();
			while($tmp = $res->fetch_assoc()){
				$return[$tmp['tovar_id']] = $tmp['tovar_id'];	
			}
			
			return $return;
		}
		
	}
	

	/*
	 *Вернет массив ид всех продуктов без алиасов
	 */
	public function getAllNoAliasProductsId(){
		
		$array = $this->getAllProductsId();
		
		$sql = 	"SELECT seo_url FROM  tbl_seo_url WHERE seo_url like 'tovar_id=%' AND seo_alias <> '';";
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return $array;		
		}else{
			$tmp = array();
			while($parent = $r->fetch_assoc()){
				$parent['seo_url'] = str_replace('tovar_id=','',$parent['seo_url']);
				//echo '<br>'.$parent['seo_url'];
				if(isset($array[$parent['seo_url']])){
					unset($array[$parent['seo_url']]);
				}
			}
			
			return $array;
		}
		
	return false;
		
	}
	
	
}
?>