<?php
class Info {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	/*
	 *Достает тайтл страницы
	 */
	public function getTitle(){
		
		if(isset($_GET)){
			
			//Если товар
			if(isset($_GET['tovar_id'])){
			
				$sql = $this->base->query("SELECT tovar_name_1 FROM tbl_tovar WHERE tovar_id = '".$_GET['tovar_id']."'");
				
				if($sql->num_rows == 0){
					return MAIN_TITLE;
				}else{
					$tmp = $sql->fetch_assoc();
					
					if($tmp == ''){
						return MAIN_TITLE;
					}else{
						return $tmp['tovar_name_1'];	
					}
					
				}	
				
			}
			
			//Если категория
			if(isset($_GET['parent'])){

				$sql = $this->base->query("SELECT parent_inet_1 FROM tbl_parent_inet WHERE parent_inet_id = '".$_GET['parent']."'");
	
				if($sql->num_rows == 0){
					
					return MAIN_TITLE;
				
				}else{
					
					$tmp = $sql->fetch_assoc();
					
					if($tmp == ''){
						return MAIN_TITLE;
					}else{
						return $tmp['parent_inet_1'];	
					}
					
				}	
				
			}
			
			//Если инфо страничка
			if(isset($_GET['key'])){
				
				return MAIN_TITLE;
				
			}
			
		}
		
		return MAIN_TITLE;
			
	}


}
?>