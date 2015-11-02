<?php
class ProductEdit {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	public function addProduct($data){
		
		$sql = "INSERT INTO tbl_tovar ";
		$fild = '';
		$fildvalue = '';
		foreach($data as $index => $value){
			$fild .= $index . ',';
			$fildvalue .= '\''.$value . '\',';
		}
		$fild = trim($fild, ' ,');
		$fildvalue = trim($fildvalue, ' ,');
		
		$sql .= '('.$fild.') VALUES ('.$fildvalue.')';
		
		$this->base->query($sql);
		
		return $this->base->insert_id;
	}
	
	public function updatePrice($data){
		
		$sql = "INSERT INTO tbl_price_tovar SET ";
		$set = '';
		$fild = '';
		$fildvalue = '';
		
		foreach($data as $index => $value){
			$fild .= $index . ',';
			$fildvalue .= '\''.$value . '\',';
			$set .= $index . ' = ' . $value . ',';
		}
		$fild = trim($fild, ' ,');
		$fildvalue = trim($fildvalue, ' ,');
		$set = trim($sql, ' ,');
		
		$sql .= ' ('.$fild.') VALUES ('.$fildvalue.') ON DUPLICATE KEY UPDATE SET '. $set;
		
		$this->base->query($sql);
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
	 *Вернет массив продукта
	 */
	public function getProductInfo($product_id){
		$sql = $this->base->query("SELECT * FROM tbl_tovar WHERE tovar_id = '".$product_id."'");
		if($sql->num_rows == 0){
			return false;
		}else{
			return $sql->fetch_assoc();
		}
		
	}
	
	/*
	 *Принимает стринг Артикл товара
	 *
	 *Вернет массив альтернативных артикулов и поставщиков
	 */
	public function getProductAlternativeArtikles($product_artkl){
		
		$sql = $this->base->query("SELECT * FROM tbl_tovar_postav_artikl WHERE tovat_artkl = '".$product_artkl."'");
		if($sql->num_rows == 0){
			return false;
		}else{
			$return = array();
			while($tmp = $sql->fetch_assoc()){
				$return[] = $tmp;
			}
			return $return;
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
			return ($tmp['price'] * $currency[$tmp['valuta']]);
		}
		
	}
	

}
?>