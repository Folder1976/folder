<?php
class System {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}

	public function saveLog(){
		
		$str = str_replace('\'', '"', $_SERVER['REQUEST_URI']);
		$user = 0;
		if(isset($_SESSION[BASE.'userid'])) $user = $_SESSION[BASE.'userid'];
		
		$sql = 'INSERT INTO tbl_log SET
					log_date = \''.date("Y-m-d H:i:s").'\',
					log_ip = \''.$_SERVER['REMOTE_ADDR'].'\',
					log_user_id = \''.$user .'\',
					log_web = \''.$_SERVER['SERVER_NAME'].$str.'\';';
		$r = $this->base->query($sql) or die (mysql_error().$sql);
		
	}
	
}