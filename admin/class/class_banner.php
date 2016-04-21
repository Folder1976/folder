<?php
class Banner {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	/*
	 *Принимает название страницы
	 *Отдает банеры
	 */
	public function getMediumBanners($page){
		
		$sql = 'SELECT * FROM tbl_baner WHERE baner_type="medium" AND is_view="1" AND baner_place LIKE "%'.$page.'%";';
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return false;
		}else{
			$return = array();
			$count = 1;
			while($count < 3){
				$tmp = $r->fetch_assoc();
				$return[$tmp['baner_id']]['img'] 	= HOST_URL.'/resources/banners/catalog/'.$tmp['baner_pic'];
				$return[$tmp['baner_id']]['url'] 	= $tmp['baner_url'];;
				$return[$tmp['baner_id']]['title'] 	= $tmp['baner_title'];
				$count++;
			}
			return $return;
		}
		
		return $return;
			
	}

	/*
	 *Отдает большие банеры на главную страницу
	 */
	public function getMainPageBanners(){
		
		$return = array();
		
		$sql = 'SELECT * FROM tbl_baner WHERE baner_type="large" AND is_view="1";';
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return false;
		}else{
			$return = array();
			while($tmp = $r->fetch_assoc()){
				$return[$tmp['baner_id']]['img'] 	= HOST_URL.'/resources/banners/mainpage/'.$tmp['baner_pic'];
				$return[$tmp['baner_id']]['url'] 	= '';
				$return[$tmp['baner_id']]['header'] = $tmp['baner_header'];
				$return[$tmp['baner_id']]['title'] 	= $tmp['baner_title'];
				$return[$tmp['baner_id']]['price'] 	= $tmp['baner_price'];
				$return[$tmp['baner_id']]['slogan'] = $tmp['baner_slogan'];
			}
			return $return;
		}
		
		return $return;
			
	}


}
?>