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
		
		//setcookie('medium_banner', '0', time() + 3600 * 24 * 30);
		$first = 1;
		$next = 2;
		
		if(isset($_COOKIE['medium_banner'])){
			$first = (int)$_COOKIE['medium_banner'];
			$next = $first + 1;
		}	
		
		$sql = 'SELECT * FROM tbl_baner WHERE baner_type="medium" AND is_view="1" AND baner_place LIKE "%'.$page.'%";';
		$r = $this->base->query($sql);
	
		//echo $_COOKIE['medium_banner'].' '.$r->num_rows;
		
		if($r->num_rows == 0){
			return false;
		}else{
			
			if($first > $r->num_rows){
				$first = 1;
				$next = $first + 1;
			}
			if($next > $r->num_rows){
				$next = 1;
			}
			
			
			$return = array();
			$count = 1;
			while($tmp = $r->fetch_assoc()){
				if($count == $first){
					$return[$tmp['baner_id']]['img'] 	= HOST_URL.'/resources/banners/catalog/'.$tmp['baner_pic'];
					$return[$tmp['baner_id']]['url'] 	= $tmp['baner_url'];;
					$return[$tmp['baner_id']]['title'] 	= $tmp['baner_title'];
				}
				
				if($count == $next){
					$return[$tmp['baner_id']]['img'] 	= HOST_URL.'/resources/banners/catalog/'.$tmp['baner_pic'];
					$return[$tmp['baner_id']]['url'] 	= $tmp['baner_url'];;
					$return[$tmp['baner_id']]['title'] 	= $tmp['baner_title'];
				}
				
				$count++;
			}
			
			setcookie('medium_banner', ($first + 1), time() + 3600 * 24 * 30);
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
				$return[$tmp['baner_id']]['url'] 	= $tmp['baner_url'];
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