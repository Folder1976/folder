<?php
class Order {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	
	public function getOrderSumm(){
		$user_agent = $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'];
		$user_agent = md5($user_agent);
		
		$sql = "SELECT
				(order_item * product_price) as su
			FROM tbl_orders
			WHERE order_customer = '$user_agent'";
		
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return 0;
		}else{
			$summ = 0;
			while($res = $r->fetch_assoc()){
				$summ += $res['su'];
			}
			return $summ;
		}
		
	}
	
	public function createUserOrder($post, $user, $cart){
		if(isset($post['buyer-email']) AND $post['buyer-email'] == ''){
			$post['buyer-email']  = 'Без емайла!';
		}
	
		$return = array();
		
		//Если пользователь не залогинен Найдем его по емайлу или же создадим нового
		if(!isset($user['klienti_id'])){
			$email = mysqli_real_escape_string($this->base, $post['buyer-email']);
			
			$sql = "SELECT klienti_id FROM `tbl_klienti` WHERE `klienti_email` = '".$email."';";
			$r = $this->base->query($sql);
			
			//Найден чувак - создадим заказ на него
			if($r->num_rows > 0){
				$tmp = $r->fetch_assoc();	
				$klient_id = $tmp['klienti_id'];
			}else{
				$data = array();
				$data['name'] = $post['buyer-name'];
				$data['email'] = $post['buyer-email'];
				$data['phone'] = $post['buyer-phone'];
				$data['TranspComp'] = $post['buyer-delivery-system'];
				$data['city'] = $post['buyer-city'];
				$data['address'] = $post['buyer-address'];
				$data['pass'] = DEFAULT_USER_PASS;
				
				global $User;
				$klient_id = $User->userAddNew($data);
			}
			
		}else{
			$klient_id = $user['klienti_id'];
		}
		
		$deliv = array('delivery-courier' => 'Курьерская доставка',
					 'delivery-pickup' => 'Самовывоз из авторизированной точки',
					 'delivery-post' => 'Служба доставки');
		
		$pay = array('payment-courier' => 'Наличными курьеру',
					 'payment-pickup' => 'Наличными при самовывозе',
					 'payment-post' => 'Электронные системы оплаты');
		
		
		$memo = 'Оплата: '.$pay[mysqli_real_escape_string($this->base, $post['payments'])];
		$memo .= ', Доставка: '.$deliv[mysqli_real_escape_string($this->base, $post['delivery'])];
		$memo .= ', Коментарий пользователя: '.mysqli_real_escape_string($this->base, $post['buyer-komment']);
		
		$sql = 'INSERT INTO tbl_operation SET
				operation_data = \''.date('Y-m-d H:i:s').'\',
				operation_klient = \''.$klient_id.'\',
				operation_prodavec = \'1\',
				operation_sotrudnik = \'1\',
				operation_data_edit = \''.date('Y-m-d H:i:s').'\',
				operation_status = \'15\',
				operation_summ = \'0\',
				operation_memo = \''.$memo.'\';';
		
		$this->base->query($sql);
		$operation_id = $this->base->insert_id;
	
		$total = 0;
		global $User;
		foreach($cart as $val){
			
			$summ = $val['order_item'] * $val['product_price'];
			$total += $summ;
			
			$sql = 'INSERT INTO tbl_operation_detail SET
						operation_detail_operation = \''.$operation_id.'\',
						operation_detail_tovar = \''.$val['id'].'\',
						operation_detail_item = \''.$val['order_item'].'\',
						operation_detail_price = \''.$val['product_price'].'\',
						operation_detail_summ = \''.$summ.'\',
						operation_detail_memo = \' Доставка: '.$val['delivery_days'].' дн., Поставщик: '.$User->getKlientName($val['product_postav_id']).'\',
						delivery_days = \''.$val['delivery_days'].'\',
						product_postav_id = \''.$val['product_postav_id'].'\',
						operation_detail_from = \'0\',
						operation_detail_to = \'0\';';
			$this->base->query($sql);
		}
		
		$sql = 'UPDATE tbl_operation SET operation_summ = \''.$total.'\' WHERE operation_id = \''.$operation_id.'\';';
		$this->base->query($sql);
	
		$this->clearCart($user['key']);

		$return['id'] 	= $operation_id;
		$return['sum'] 	= $total;
		
		return $return;
	}
	
	public function clearCart($user_key){
		
		$sql = 'DELETE FROM tbl_orders WHERE order_customer = \''.$user_key.'\';';
		$this->base->query($sql);

	}
	
	
	
}
?>