<?php
class User {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	public function getUsers(){
		$sql = "SELECT klienti_id AS id, klienti_name_1 AS name FROM tbl_klienti WHERE klienti_group = '6' ORDER BY name ASC;";
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

}
?>