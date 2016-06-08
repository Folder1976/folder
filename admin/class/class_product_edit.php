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
			$fildvalue .= '\''.str_replace("'",'"',$value) . '\',';
		}
		$fild = trim($fild, ' ,');
		$fildvalue = trim($fildvalue, ' ,');
		
		$sql .= '('.$fild.') VALUES ('.$fildvalue.')';
		
		$this->base->query($sql) or die('add product - ' . $sql);
		
		$product_id = $this->base->insert_id;
		
		//header("Content-Type: text/html; charset=UTF-8");
		if(!isset($_SESSION[BASE.'userid'])){
			@session_start();
		}
		//Установим дату и пользователя редактировавшего
		$date = date("Y-m-d G:i:s");
		$sql = 'UPDATE tbl_tovar SET
				tovar_last_edit = \''.$date.'\',
				tovar_last_edit_user = \''.$_SESSION[BASE.'userid'].'\'
				WHERE tovar_id = \''.$product_id.'\';';
		$this->base->query($sql) or die('add product - ' . $sql);
	
	//echo '';	
		//Описание
		$sql = 'INSERT INTO tbl_description SET
					description_tovar_id = \''.$product_id.'\',
					description_1 = \''.str_replace("'",'"',$data['tovar_memo']).'\';';
		$tovar = $this->base->query($sql) or die('add product - ' . $sql);
	
		
		$alias = '';
		
		//Получим алиас категории
		$sql = 'SELECT seo_alias FROM tbl_seo_url WHERE seo_url = \'parent='.$data['tovar_inet_id_parent'].'\';';
		$tovar = $this->base->query($sql);
		if($tovar->num_rows > 0){
			$tmp = $tovar->fetch_assoc();
			$alias .= ''.$tmp['seo_alias'];
		}
		
		//Получим код бренда
		$sql = 'SELECT brand_code FROM tbl_brand WHERE brand_id = \''.$data['brand_id'].'\';';
		$tovar = $this->base->query($sql) or die('add product - ' . $sql);
		if($tovar->num_rows > 0){
			$tmp = $tovar->fetch_assoc();
			$alias .= '/'.$tmp['brand_code'];
		}
		
		$alias .= '/'.$this->getProductArtkl($product_id);
		
		$sql = 'INSERT INTO tbl_seo_url SET
					seo_url = \'tovar_id='.$product_id.'\',
					seo_alias = \''.$alias.'\';';
		$tovar = $this->base->query($sql) or die('add product - ' . $sql);
		//echo '<br>'.$sql;
		return $product_id;
	}
	
	//Найти цыет в названии и прописать его
	public function setColorOnProductName($tovar_id){
	
		$name = $this->getProductName($tovar_id);
		
		//$sql = 'SELECT ';
	
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
	
		$dir = __DIR__.'/../../resources/products/'.$artkl.'/';
		$dir_img = '/resources/products/'.$artkl.'/';
		$return = '';
		$dh  = opendir($dir);
		
		while (false !== ($filename = readdir($dh))) {
			$files[] = $filename;
			if(strpos($filename, 'small') !== false){
				$return .= '<a href="javascript:" class="glav_photo_links" data-img="'.$artkl.'/'.$filename.'" >
						<img src="'.$dir_img.$filename.'" data-img="'.$artkl.'/'.$filename.'" width="100px" class="glav_photo_th"></a>';
			}
		}
		
		return $return;
	}
	
	/*
	 *Вернет главную картинку продутка
	 */
	public function getProductPicsOnArtkl($artkl){
	
	
	
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
	 *Вернет Название продукта
	 */
	public function getProductName($product_id){
		$sql = $this->base->query("SELECT tovar_name_1 AS name FROM tbl_tovar WHERE tovar_id = '".$product_id."'");
		if($sql->num_rows == 0){
			return false;
		}else{
			$tmp = $sql->fetch_assoc();
			return $tmp['name'];
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
	
	public function getCategoryTree(){
		
		$sql = "SELECT parent_inet_id AS id,
						parent_inet_parent AS parent,
						parent_inet_1 AS name
				FROM tbl_parent_inet
				WHERE parent_inet_parent = '0'
				;";
		$rs = $this->base->query($sql) or die('osaiduytflijksdgfl<br>'.$sql.'<br>'.mysql_error());
	
		$body = "<div id=\"container-carfit\" class = \"product-carfit-tree\"><ul  id=\"celebTree-carfit\"><li><span id=\"span_0\"><a class = \"tree-carfit\" href=\"javascript:\" id=\"0\">Категории</a></span><ul>";
		while ($Type = $rs->fetch_assoc()) {
		if($Type['id'] != 0){
			$body .=  "<li><span id=\"span_".$Type['id']."\"> <a class = \"tree-carfit\" href=\"javascript:\" id=\"".$Type['id']."\">".
					$Type['name']. "</a>";
			$body .= "</span>".$this->getCategoryTreeNext($Type['id']);
			$body .= "</li>";
		}
		//$Types[$Type['id']]['id'] = $Type['id'];
		//$Types[$Type['id']]['name'] = $Type['name'];
		}
		$body .= "</ul>
			</li></ul></div>";
	
	
	
		return $body;	
	}

	
	private function getCategoryTreeNext($parent){
		$sql = "SELECT parent_inet_id AS id,
						parent_inet_parent AS parent,
						parent_inet_1 AS name
				FROM tbl_parent_inet
				WHERE parent_inet_parent = '".$parent."';";
		$rs = $this->base->query($sql) or die('osaiduytflijksdgfl<br>'.$sql.'<br>'.mysql_error());
	
		$body = "";
	
		 while ($Type = $rs->fetch_assoc()) {
			$body .=  "<li><span id=\"span_".$Type['id']."\"><a class = \"tree-carfit\" href=\"javascript:\" id=\"".$Type['id']."\">";
			
				$body .= $Type['name'];
			
			$body .= "</a></span>".$this->getCategoryTreeNext($Type['id']);
			$body .= "</li>";
		}
		if($body != "") $body = "<ul>$body</ul>";
		
		return $body;
	
	}
}
?>