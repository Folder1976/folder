<?php
class Transport {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	
	public function getTranspComp(){
	
		$sql = "SELECT *
			FROM tbl_transp_comp
			ORDER BY TranspSmNazv ASC;";
		$sql = $this->base->query($sql);
		
		if($sql->num_rows == 0){
			return false;
		}else{
			$return = array();
			
			while($res = $sql->fetch_assoc()){
			
				$return[$res['TranspID']] = $res;
			}
			return $return;
		}
		
	}

}
?>