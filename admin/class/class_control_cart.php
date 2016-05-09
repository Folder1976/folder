<?php
class ControlCart {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	
	public function getLoginedUserCart($user_key, $Product){
		
		$sql = 'SELECT
				order_id as line_id,
				order_product_id as id,
				order_item,
				product_price,
				product_postav_id,
				delivery_days,
				seo_alias,
				tovar_artkl,
				tovar_model,
				tovar_name_1 as name
				FROM tbl_orders
				LEFT JOIN tbl_seo_url ON seo_url = CONCAT(\'tovar_id=\', order_product_id)
				LEFT JOIN tbl_tovar ON tovar_id = order_product_id
				
				WHERE order_customer = \''.$user_key.'\'
				GROUP BY order_product_id
				ORDER BY tovar_name_1 ASC
				;';
		$r = $this->base->query($sql);
		
		if($r->num_rows > 0){
			
			$return = array();
			
			while($row = $r->fetch_assoc()){
				
				$row['img'] = $Product->getProductPicOnArtkl($row['tovar_artkl']);
				$return[] = $row;
				
			}
			
			return $return;
			
		}
		
		return false;
		
	}
	
}
?>