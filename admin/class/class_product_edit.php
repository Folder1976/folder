<?php
class ProductEdit {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	public function dellAllSupplierItems($supplier_id){
		$sql = 'DELETE FROM tbl_tovar_suppliers_items WHERE postav_id = \''.$supplier_id.'\';';
		$this->base->query($sql);
	}
	
	public function addNewSupplierItem($data){
		
		$sql = "INSERT INTO tbl_tovar_suppliers_items SET
				tovar_id = '".$data['id']."',
				postav_id = '".$data['postav_id']."',
				zakup = '".$data['zakup']."',
				zakup_curr = '".$data['zakup_curr']."',
				price_1 = '".(int)$data['price_1']."',
				items = '".$data['items']."';";
		
		
		$this->base->query($sql);
		
		return $this->base->insert_id;
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
	
	public function getProductIdOnArtiklAndSupplier($tovar_artkl, $postavID = 0){
		
		//Если поставщик ( 0 ) - ищем сначала в основной базе. А если указан - начинем с альтернативных артикулов
		if($postavID == 0){

			$sql = 'SELECT tovar_id FROM tbl_tovar WHERE tovar_artkl = "'.$tovar_artkl.'";';
			$tovar = $this->base->query($sql);
			
			if($tovar->num_rows == 0){
				$sql = 'SELECT tovar_artkl FROM tbl_tovar_postav_artikl WHERE tovar_postav_artkl = "'.$tovar_artkl.'";';
				$tovar = $this->base->query($sql);
			
				//если чтото нашли
				if($tovar->num_rows > 0){
					$tmp = $tovar->fetch_assoc();
					$sql = 'SELECT tovar_id FROM tbl_tovar WHERE tovar_artkl = "'.$tmp['tovar_artkl'].'";';
					$tovar = $this->base->query($sql);
					
				}	
			}

		}else{

			$sql = 'SELECT tovar_artkl FROM tbl_tovar_postav_artikl WHERE tovar_postav_artkl = "'.$tovar_artkl.'" AND postav_id = "'.$postavID.'";';
			$tovar = $this->base->query($sql);
			
			//если чтото нашли
			if($tovar->num_rows > 0){
				$tmp = $tovar->fetch_assoc();
				$sql = 'SELECT tovar_id FROM tbl_tovar WHERE tovar_artkl = "'.$tmp['tovar_artkl'].'";';
				$tovar = $this->base->query($sql);
				
			}else{
				$sql = 'SELECT tovar_id FROM tbl_tovar WHERE tovar_artkl = "'.$tovar_artkl.'";';
				$tovar = $this->base->query($sql);
			}
			
		}
		
		//Если нашли чтото
		if($tovar->num_rows > 0){
			$tmp = $tovar->fetch_assoc();
			return $tmp['tovar_id'];
		}else{
			return false;
		}
		
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
	 *Вернет артикл продукта
	 */
	public function getProductArtkl($product_id){
		global $setup;
		
		$sql = $this->base->query("SELECT tovar_artkl FROM tbl_tovar WHERE tovar_id = '".$product_id."'");
		
		if($sql->num_rows == 0){
			return false;
		}else{
			$artkl = $sql->fetch_assoc();
			$artkl = $artkl['tovar_artkl'];
			
			if(strpos($artkl, $setup['tovar artikl-size sep']) !== false){
				$tmp = explode($setup['tovar artikl-size sep'], $artkl);
				$artkl = $tmp[0];
			}
			
			return $artkl;
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
	 *Вернет массив цен и остатков поставщиков
	 */
	public function getProductPostavInfo($product_id){
		
		$sql = $this->base->query("SELECT * FROM  tbl_tovar_suppliers_items WHERE tovar_id = '".$product_id."'");
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
	 *Принимает стринг Артикл товара
	 *
	 *Вернет массив альтернативных артикулов и поставщиков
	 */
	public function getProductAlternativeArtikles($product_artkl){
		
		$sql = $this->base->query("SELECT * FROM tbl_tovar_postav_artikl WHERE tovar_artkl = '".$product_artkl."'");
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