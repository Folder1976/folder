<?php
class Main {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	public function main($url){
		
            ob_start();
            require($url);
            $sResult = ob_get_contents();
            ob_end_clean();
            return $sResult;    
		
	}
}

?>