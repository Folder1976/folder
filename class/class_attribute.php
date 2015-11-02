<?php
class Attribute {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	
	//Удалит группу атрибутов и всвязи с ней по ИД
	public function dellAttributeGroup($attribute_group_id){
		
		$this->base->query("DELETE FROM `tbl_attribute_to_group` WHERE `attribute_group_id` = '".$attribute_group_id."';") or die (mysql_error());
		$this->base->query("DELETE FROM `tbl_attribute_group` WHERE `attribute_group_id` = '".$attribute_group_id."';") or die (mysql_error());
		
	}
	
	/*
	 *Принимает массив
	 *Сохранит группу атрибутов и всвязи с ней
	 */
	public function saveAttributeGroup($data){
		
		$sql = 'UPDATE tbl_attribute_group SET attribute_group_name = "' . $data['name'] . '" WHERE attribute_group_id = "' . $data['id'] . '";';
		$this->base->query($sql) or die (mysql_error());
		
		$attr = array();
		foreach($data as $index => $value){
			if(strpos($index,'attr') !== false){
				$attr[(int)str_replace('attr=','',$index)]['attr'] = '1';
			}
			if(strpos($index,'filter') !== false){
				$attr[(int)str_replace('filter=','',$index)]['filter'] = '1';
			}
			if(strpos($index,'sort') !== false){
				$attr[(int)str_replace('sort=','',$index)]['sort'] = $value;
			}
		}
		
		$this->base->query("DELETE FROM `tbl_attribute_to_group` WHERE `attribute_group_id` = '".$data['id']."';") or die (mysql_error());
		foreach($attr as $index => $value){
			$char = $filter = $sort = 0;
			
			if(isset($value['attr'])) $char = 1;
			if(isset($value['filter'])) $filter = 1;
			if(isset($value['sort'])) $sort = $value['sort'];
			
			$sql = 'INSERT INTO tbl_attribute_to_group SET 
				attribute_id = "' . $index . '",
				attribute_group_id = "' . $data['id'] . '",
				attribute_char = "' . $char . '",
				attribute_filter = "' . $filter . '",
				attribute_sort = "' . $sort . '"
				;';
			if($char == 1 OR $filter == 1 OR $sort > 0 ){
				$this->base->query($sql) or die (mysql_error());
			}
		}
	
	}
	
	/*
	 *Принимает массив
	 *Добавляем группу атрибутов и всвязи с ней
	 */
	public function addAttributeGroup($data){
		
		$sql = 'INSERT INTO tbl_attribute_group SET attribute_group_name = "' . $data['name'] . '";';
		$r = $this->base->query($sql) or die (mysql_error());
		$last_id = $this->base->insert_id;
		
				$attr = array();
		foreach($data as $index => $value){
			if(strpos($index,'attr') !== false){
				$attr[(int)str_replace('attr=','',$index)]['attr'] = '1';
			}
			if(strpos($index,'filter') !== false){
				$attr[(int)str_replace('filter=','',$index)]['filter'] = '1';
			}
			if(strpos($index,'sort') !== false){
				$attr[(int)str_replace('sort=','',$index)]['sort'] = $value;
			}
		}
		
		foreach($attr as $index => $value){
			$char = $filter = $sort = 0;
			
			if(isset($value['attr'])) $char = 1;
			if(isset($value['filter'])) $filter = 1;
			if(isset($value['sort'])) $sort = $value['sort'];
			
			$sql = 'INSERT INTO tbl_attribute_to_group SET 
				attribute_id = "' . $index . '",
				attribute_group_id = "' . $last_id . '",
				attribute_char = "' . $char . '",
				attribute_filter = "' . $filter . '",
				attribute_sort = "' . $sort . '"
				;';
			if($char == 1 OR $filter == 1 OR $sort > 0 ){	
				$this->base->query($sql) or die (mysql_error());
			}
		}
	
	}
	
	/*
	 *Принимает string имя нового аттрибута
	 *Добавляем новый аттрибут
	 */
	public function addAttribute($attribute_name){
	
		$sql = 'INSERT INTO tbl_attribute SET attribute_name = "' . $attribute_name . '";';
		$this->base->query($sql) or die (mysql_error());	
				
	}
	
	/*
	 *Принимает Группу атрибутов и Ид товара
	 *
	 *Вернет массив атрибутов и их значения
	 */
	public function getAttributesOnTovarID($id){
		
		if((int)$id == 0) return false;
		
		$Product = new Product($this->base);
		$Category = new Category($this->base);
		
		$category_id =  $Product->getCategoryID($id);
		
		$attr_group_id = $Category->getCategoryAttributeGroupID($category_id);
		
		$sql = "SELECT A.attribute_id, A.attribute_name, T.attribute_value, G.attribute_filter, G.attribute_char FROM tbl_attribute A
			LEFT JOIN tbl_attribute_to_group G ON A.attribute_id = G.attribute_id
			LEFT JOIN tbl_attribute_to_tovar T ON T.attribute_id = A.attribute_id AND T.tovar_id = '$id'
			WHERE G.attribute_group_id = '$attr_group_id' AND T.attribute_value != ''
			ORDER BY A.attribute_name ASC;";
		$group = $this->base->query($sql) or die(mysql_error());
		
		if($group->num_rows > 0){
			$attribute = array();
			while($tmp = $group->fetch_assoc()){
				$attribute[$tmp['attribute_id']]['attribute_name'] = $tmp['attribute_name'];
				$attribute[$tmp['attribute_id']]['attribute_value'] = $tmp['attribute_value'];
				$attribute[$tmp['attribute_id']]['filter'] = $tmp['attribute_filter'];
				$attribute[$tmp['attribute_id']]['char'] = $tmp['attribute_char'];
			}
			return $attribute;
		}else{
			return false;
		}
		
	}
	
	/*
	 *Принимает ид атрибута
	 *
	 *возвращает массив список значений для этого атрибута
	 */
	public function getAttributeValues($attribute_id){
		
		$return = array();
		
		$sql = 'SELECT DISTINCT attribute_value FROM tbl_attribute_to_tovar WHERE attribute_id = \''.$attribute_id.'\'';
		
		$res = $this->base->query($sql);
		
		while($tmp = $res->fetch_assoc()){
			$return[] = $tmp['attribute_value'];
		}
		
		return $return;
		
	}
	
}
?>