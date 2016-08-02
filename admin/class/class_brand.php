<?php
class Brand {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	public function getBrands($limit = 10000){
		$sql = "SELECT * FROM tbl_brand ORDER BY brand_code ASC LIMIT 0, $limit";
		
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return false;
		}else{
			$return = array();
			while($res = $r->fetch_assoc()){
				$return[] = $res;
			}
			return $return;
		}
		
	}
	
	
	/*
	 *Вернет полный массив по бренду, включая страну
	 */
	public function getBrandOnProductId($product_id){
		$sql = $this->base->query("SELECT B.brand_id,
						brand_code,
						brand_name,
						country_id,
						CountryName
						
						FROM tbl_brand B
						LEFT JOIN tbl_tovar T ON B.brand_id = T.brand_id
						LEFT JOIN tbl_country C ON country_id = CountryID
						WHERE tovar_id = '".$product_id."'");
		if($sql->num_rows == 0){
			return false;
		}else{
			$tmp = $sql->fetch_assoc();
			return $tmp;
		}
		
	}
	
	
	
	/*
	 *Вернет код бренда по ИД продукт
	 */
	public function getBrandCodeOnProductId($product_id){
		$q = "SELECT brand_code
			FROM tbl_brand B
			LEFT JOIN tbl_tovar T ON B.brand_id = T.brand_id
			WHERE tovar_id = '".$product_id."'";
		$sql = $this->base->query($q);
		
		//echo $q;
		
		if($sql->num_rows == 0){
			return false;
		}else{
			$tmp = $sql->fetch_assoc();
			return $tmp['brand_code'];
		}
		
	}
	
	/*
	 *Вернет код бренда по ИД продукт
	 */
	public function getBrandCodeOnProductArtkl($product_artkl){
		global $setup;
		
		$sql = $this->base->query("SELECT tovar_id
						FROM tbl_tovar						
						WHERE tovar_artkl = '".$product_artkl."' OR tovar_artkl LIKE '".$product_artkl.$setup['tovar artikl-size sep']."%'
						LIMIT 1");
		
		
		if($sql->num_rows == 0){
			return false;
		}
		$tmp = $sql->fetch_assoc();
		$product_id = $tmp['tovar_id'];
		
		
		$sql = $this->base->query("SELECT B.brand_id,
						brand_code,
						brand_name,
						country_id,
						CountryName
						FROM tbl_brand B
						LEFT JOIN tbl_tovar T ON B.brand_id = T.brand_id
						LEFT JOIN tbl_country C ON country_id = CountryID
						WHERE tovar_id = '".$product_id."'");
		if($sql->num_rows == 0){
			return false;
		}
		
		$tmp = $sql->fetch_assoc();
		return $tmp;
			
	}
	
	
	
}
?>