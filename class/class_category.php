<?php
class Category {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	
	public function getProductCountInCategory($parent_id){
		
		$sql = "SELECT count(tovar_id) as products
			FROM tbl_tovar
			LEFT JOIN tbl_parent_inet_path ON path_id = '".$parent_id."'
			WHERE tovar_inet_id_parent = category_id";
		$sql = $this->base->query($sql);
		
		if($sql->num_rows == 0){
			return 0;
		}else{
			$res = $sql->fetch_assoc();
			return $res['products'];
		}
		
	}
	
	public function getCategoryChildren($parent_id){
		$sql = "SELECT category_id FROM tbl_parent_inet_path WHERE path_id = '".$parent_id."'";
		$sql = $this->base->query($sql);
		
		if($sql->num_rows == 0){
			return false;
		}else{
			while($res = $sql->fetch_assoc()){
				$parent[] = $res['category_id'];
			}
			return $parent;	
		}
		
	}
	
	public function reloadCategoryPatch(){
		$sql = $this->base->query("SELECT parent_inet_id, parent_inet_parent FROM tbl_parent_inet") or die (mysql_error());
		$this->base->query("DELETE FROM tbl_parent_inet_path");
		
		if($sql->num_rows == 0){
			return false;
		}else{
			while($res = $sql->fetch_assoc()){
				$parents[$res['parent_inet_id']] = $res['parent_inet_parent'];
			}
			
			$parents_tmp = $parents;
			foreach($parents_tmp as $index => $parent){
			
				if($index > 0){
					$level = 0;
					$step = $path[$level] = $index;
					while($parents[$step] > 0){
						$level++;
						$step = $parents[$step];
						$path[$level] = $step;
						
					}
					$level++;
					$step = $parents[$step];
					$path[$level] = $step;
					$revers_level = $level;
					while($level >= 0){
						$sql = "INSERT INTO tbl_parent_inet_path SET
								category_id = '".$index."',
								path_id = '".$path[$level]."',
								level = '".($revers_level - $level)."'";
								//echo "<br>".$sql;
						$this->base->query($sql) or die ("<br>".$sql.mysql_error());
						$level--;
						
					}
				}
			}
			return true;	
		
		}
	}
	
	//Вернет Ид Группы Атрибутов по первой в списке родительской категории у которой назначена группа атрибутов
	public function getCategoryAttributeGroupID($category_id){
		
		$sql = 	"SELECT path_id FROM tbl_parent_inet_path
			WHERE category_id = '" . (int)$category_id . "'
			ORDER By path_id DESC;";
		$path = $this->base->query($sql);
				
		if($path->num_rows == 0){
			return false;		
		}else{
			while($category_id = $path->fetch_assoc()){
				$sql = 	"SELECT attribute_group_id FROM tbl_parent_inet
				WHERE parent_inet_id = '" . (int)$category_id['path_id'] . "' AND attribute_group_id > 0;";
				
				$query = $this->base->query($sql);
				
				if($query->num_rows > 0){
					$tmp = $query->fetch_assoc();
					return $tmp['attribute_group_id'];
				}
			}
		}
		
	return false;

	}
	
	//Вернет имя Группы атрибутов по ее ИД
	public function getAttributeGroupNameOnCategoryID($attribute_group_id){
		$sql = 	"SELECT attribute_group_name FROM tbl_attribute_group
			WHERE attribute_group_id = '" . (int)$attribute_group_id . "';";
		$grp_name = $this->base->query($sql);
		
		if($grp_name->num_rows == 0){
			return false;		
		}else{
			$tmp = $grp_name->fetch_assoc();
			return $tmp['attribute_group_name'];
		}
		
	return false;

	}

	//Вернет подкаталоги для указаной категории
	public function getCategoriesInfo($parents_id){
		global $Alias;
		$return = array();
				
		foreach($parents_id as $id){
			$sql = 'SELECT parent_inet_id,
					parent_inet_1 as name,
					parent_inet_memo_1 as memo,
					parent_inet_info as info
				FROM 
				`tbl_parent_inet` 
					WHERE 
					`parent_inet_type`=\'1\' and 
					`parent_inet_id`=\''.$id.'\' and
					`parent_inet_view` < "'.$_SESSION[BASE.'userlevel'].'"';
			$parents = $this->base->query($sql);

			while($parent = $parents->fetch_assoc()){
				$return[$parent['parent_inet_id']]['id'] = $parent['parent_inet_id'];
				$return[$parent['parent_inet_id']]['name'] = $parent['name'];
				$return[$parent['parent_inet_id']]['memo'] = $parent['memo'];
				$return[$parent['parent_inet_id']]['info'] = $parent['info'];
				$return[$parent['parent_inet_id']]['alias'] = $Alias->getCategoryAlias($parent['parent_inet_id']);
				$return[$parent['parent_inet_id']]['id'] = $parent['parent_inet_id'];
			}
		}
		
		return $return;	  
	}

}
?>