<?php
class Comment {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	
	public function getComments($product_artkl){
		
		$sql = "SELECT *
			FROM tbl_comments
			WHERE comments_tovar = '$product_artkl'
			ORDER BY comments_date ASC;";
		//echo $sql;
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
	
	public function addComment($data){
		$sql = 'INSERT INTO tbl_comments SET
					comments_tovar = \''.$data['reviewer-product'].'\',
					comments_ip = \''.$_SERVER['REMOTE_ADDR'].'\',
					comments_date = \''.date('Y-m-d H:i:s').'\',
					comments_name = \''.$data['reviewer-name'].'\',
					comments_email = \''.$data['reviewer-email'].'\',
					comments_memo = \''.$data['reviewer-comment'].'\'
					';
		$this->base->query($sql);
		
	}
public function addToBlackList($ip){
		$sql = 'INSERT INTO tbl_black_ip_list SET
					black_ip_ip = \''.$ip.'\'
					';
		$this->base->query($sql);
		
	}
	
	/*
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
	
	
	public function getCategoryChildrenFull($parent_id, $level = 0){
		
		$sql = "SELECT parent_inet_id, parent_inet_1, seo_alias, parent_inet_parent
						FROM tbl_parent_inet 
						LEFT JOIN tbl_seo_url ON seo_url = CONCAT('parent=', parent_inet_id)
						WHERE parent_inet_parent = '".$parent_id."'";
		$sql = $this->base->query($sql);
		
		if($sql->num_rows == 0){
			return false;
		}else{
			$count = 1;
			while($res = $sql->fetch_assoc()){
				$parent[$res['parent_inet_id']]['id'] = $res['parent_inet_id'];
				$parent[$res['parent_inet_id']]['name'] = $res['parent_inet_1'];
				$parent[$res['parent_inet_id']]['url'] = $res['seo_alias'];
				if($level == 0){
					$parent[$res['parent_inet_id']]['children'] = $this->getCategoryChildrenFull($res['parent_inet_id'], 1);
					$count = $count + $parent[$res['parent_inet_id']]['children']['count'];
				}
				
				$count++;
			}
			$parent['count'] = $count;
			return $parent;	
		}

	}
	
	
	public function getCategoryMain(){
		
		$level = 10;
		if(isset($_SESSION[BASE.'userlevel'])) $level = $_SESSION[BASE.'userlevel'];
		
		$sql = "SELECT parent_inet_id, parent_inet_1, seo_alias
					FROM tbl_parent_inet
					LEFT JOIN tbl_seo_url ON seo_url = CONCAT('parent=', parent_inet_id)
					WHERE `parent_inet_view`<='".$level."'
							AND parent_inet_parent = 0
					ORDER BY `parent_inet_sort` ASC";
		//echo $sql;
		$sql = $this->base->query($sql);
		
		if($sql->num_rows == 0){
			return false;
		}else{
			while($res = $sql->fetch_assoc()){
				$parent[$res['parent_inet_id']]['name'] = $res['parent_inet_1'];
				$parent[$res['parent_inet_id']]['url'] = $res['seo_alias'];
			}
	
			return $parent;	
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

	//Вернет ИД всех категорий
	public function getAllCategoryId(){
		$sql = 	"SELECT parent_inet_id FROM tbl_parent_inet;";
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return false;		
		}else{
			$tmp = array();
			while($parent = $r->fetch_assoc()){
				$tmp[$parent['parent_inet_id']] = $parent['parent_inet_id'];	
			}
			
			return $tmp;
		}
		
	return false;

	}
	
	//Вернет ИД всех категорий без алиасов
	public function getNoAliasCategoryId(){
		$categorys = $this->getAllCategoryId();
		
		$sql = 	"SELECT seo_url FROM  tbl_seo_url WHERE seo_url like 'parent=%' AND seo_alias <> '';";
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return $categorys;		
		}else{
			$tmp = array();
			while($parent = $r->fetch_assoc()){
				$parent['seo_url'] = str_replace('parent=','',$parent['seo_url']);
				//echo '<br>'.$parent['seo_url'];
				if(isset($categorys[$parent['seo_url']])){
					unset($categorys[$parent['seo_url']]);
				}
			}
			
			return $categorys;
		}
		
	return false;

	}

	//Вернет информацию для указаной категории
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
				$alias = $Alias->getCategoryAlias($parent['parent_inet_id']);
				$return[$parent['parent_inet_id']]['id'] = $parent['parent_inet_id'];
				$return[$parent['parent_inet_id']]['name'] = $parent['name'];
				$return[$parent['parent_inet_id']]['memo'] = $parent['memo'];
				$return[$parent['parent_inet_id']]['info'] = $parent['info'];
				$return[$parent['parent_inet_id']]['alias'] = $alias;
				$return[$parent['parent_inet_id']]['url'] = $alias;
				$return[$parent['parent_inet_id']]['id'] = $parent['parent_inet_id'];
			}
		}
		
		return $return;	  
	}
	
	//Вернет информацию для указаной категории
	public function getCategoryInfo($id){
		global $Alias;
		$return = array();
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

		if($parents->num_rows == 0){
			return false;
		}
		
		while($parent = $parents->fetch_assoc()){
			$alias = $Alias->getCategoryAlias($parent['parent_inet_id']);
			$return['id'] = $parent['parent_inet_id'];
			$return['name'] = $parent['name'];
			$return['memo'] = $parent['memo'];
			$return['info'] = $parent['info'];
			$return['alias'] = $alias;
			$return['url'] = $alias;
			$return['id'] = $parent['parent_inet_id'];
		}
	
		return $return;	  
	}

	//Вернет путь для категории
	public function getCategoryPath($id){
		global $Alias;
		$return = array();
		$sql = 'SELECT path_id,
				parent_inet_1 as name
			FROM `tbl_parent_inet_path`
			LEFT JOIN tbl_parent_inet ON path_id = parent_inet_id
			WHERE `category_id`=\''.$id.'\'
			ORDER BY level ASC;';
		$parents = $this->base->query($sql);

		if($parents->num_rows == 0){
			return false;
		}
		
		while($parent = $parents->fetch_assoc()){
			if($parent['path_id'] != '0') {
				$return[$parent['path_id']] = $parent['name'];
			}
		}
	
		return $return;	  
	}
	*/
}
?>