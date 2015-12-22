<?php
class Localisation {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	public function getCountry(){
		
		$sql = "SELECT * FROM tbl_country ORDER BY CountryName ASC;";
		$res = $this->base->query($sql) or die('osadiduytfld3ijksdgfl<br>'.$sql.'<br>'.mysql_error());
		$return = array();
		
		if($res->num_rows == 0){
			return false;
		}else{
			while($tmp = $res->fetch_assoc()){
				$return[$tmp['CountryID']] = $tmp['CountryName'];
			}
			return $return;
		}
	}


}
?>