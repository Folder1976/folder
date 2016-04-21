<?php
class Order {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	
	public function getUserOrders($user_id){
		
		global $Product;
		
		$sql = "SELECT
					O.operation_id,
					O.operation_data,
					O.operation_customer_memo,
					B.brand_code,
					T.tovar_artkl,
					T.tovar_id,
					T.tovar_name_1,
					O.operation_customer_memo,
					OD.operation_detail_item AS item,
					OD.operation_detail_price AS price,
					OD.operation_detail_summ AS summ,
					O.operation_summ AS operation_summ,
					/*SUM(OD.operation_detail_item) AS items,*/
			
					S.operation_status_id,
					S.operation_status_name
				FROM  tbl_operation_detail OD
				LEFT JOIN tbl_tovar T ON T.tovar_id = OD.operation_detail_tovar
				LEFT JOIN tbl_brand B ON B.brand_id = T.brand_id
				LEFT JOIN tbl_operation O ON O.operation_id = OD.operation_detail_operation
				LEFT JOIN tbl_operation_status S ON S.operation_status_id = O.operation_status
				WHERE O.operation_klient = '$user_id'
					AND O.operation_dell = 0
					AND OD.operation_detail_dell = 0
					
				ORDER BY O.operation_id DESC, T.tovar_name_1 ASC
				;";
		$r = $this->base->query($sql);
		
		$return = array();
		if($r->num_rows == 0){
			return 0;
		}else{
			
			while($res = $r->fetch_assoc()){
				
				$res['pic'] = $Product->getProductPicOnArtkl($res['tovar_artkl']);
				
				$return[$res['operation_id']][] = $res;
				
				//if(isset($return[$res['operation_id']]['summ'])){
					//$return[$res['operation_id']]['summ'] += $res['operation_summ'];
				//}else{
					$return[$res['operation_id']]['summ'] = $res['operation_summ'];
				//}

				if(isset($return[$res['operation_id']]['items'])){
					$return[$res['operation_id']]['items'] += $res['item'];
				}else{
					$return[$res['operation_id']]['items'] = $res['item'];
				}
				
			}
			//return $return;
		}
		
		//Оплаты
			$sql = "SELECT
					O.operation_id,
					O.operation_data,
					O.operation_customer_memo,
					O.operation_summ AS operation_summ,
					S.operation_status_id,
					S.operation_status_name
				FROM  tbl_operation O
				LEFT JOIN tbl_operation_status S ON S.operation_status_id = O.operation_status
				WHERE O.operation_klient = '$user_id'
					AND O.operation_status = 9
				ORDER BY O.operation_id DESC
				;";
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return 0;
		}else{
			while($res = $r->fetch_assoc()){
				$return[$res['operation_id']][] = $res;
			}
			
		}
		krsort($return);
		return $return;
		
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

	public function getOrderItems(){
		$user_agent = $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'];
		$user_agent = md5($user_agent);
		
		$sql = "SELECT
				order_item 
			FROM tbl_orders
			WHERE order_customer = '$user_agent'";
		
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return 0;
		}else{
			$summ = 0;
			while($res = $r->fetch_assoc()){
				$summ += $res['order_item'];
			}
			return $summ;
		}
		
	}
	
	public function createUserOrder($post, $user, $cart){
		if(isset($post['buyer-email']) AND $post['buyer-email'] == ''){
			$post['buyer-email']  = 'Без емайла!';
		}
	
		$return = array();
		
		$email = mysqli_real_escape_string($this->base, $post['buyer-email']);
		
		//Если пользователь не залогинен Найдем его по емайлу или же создадим нового
		if(!isset($user['klienti_id'])){
			
			$sql = "SELECT klienti_id FROM `tbl_klienti` WHERE `klienti_email` = '".$email."';";
			$r = $this->base->query($sql);
			
			//Данные из формы
			$data = array();
				$data['name'] = $post['buyer-name'];
				$data['email'] = $post['buyer-email'];
				$data['phone'] = $post['buyer-phone'];
				$data['TranspComp'] = $post['buyer-delivery-system'];
				$data['city'] = $post['buyer-city'];
				$data['address'] = $post['buyer-address'];
				$data['pass'] = DEFAULT_USER_PASS;
			
			
			//Найден чувак - создадим заказ на него
			if($r->num_rows > 0){
				$tmp = $r->fetch_assoc();	
				$klient_id = $tmp['klienti_id'];
			}else{
					
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
		
		$customer_memo = 'Получатель: ' . $post['buyer-name']. '*' .
				'email: ' . $post['buyer-email'] . '*' .
				'телефон: ' . $post['buyer-phone'] . '*' .
				'город: ' . $post['buyer-city'] . '*' .
				'адрес: ' . $post['buyer-address'] . '*' .
				'Оплата: '.$pay[mysqli_real_escape_string($this->base, $post['payments'])] . '*' .
				'Доставка: '.$deliv[mysqli_real_escape_string($this->base, $post['delivery'])] . '*' .
				'Коментарий пользователя: '.mysqli_real_escape_string($this->base, $post['buyer-komment']);
		
		$sql = 'INSERT INTO tbl_operation SET
				operation_data = \''.date('Y-m-d H:i:s').'\',
				operation_klient = \''.$klient_id.'\',
				operation_prodavec = \'1\',
				operation_sotrudnik = \'1\',
				operation_data_edit = \''.date('Y-m-d H:i:s').'\',
				operation_status = \'15\',
				operation_summ = \'0\',
				operation_memo = \''.$memo.'\',
				operation_customer_memo = \''.$customer_memo.'\';';
		
		$this->base->query($sql);
		$operation_id = $this->base->insert_id;
	
		$total = 0;
		global $User;
		
		if(!$cart) return false;
		
		$products_html = '';
		
		foreach($cart as $val){
			
			$summ = $val['order_item'] * $val['product_price'];
			$total += $summ;
			
			$sql = 'INSERT INTO tbl_operation_detail SET
						operation_detail_operation = \''.$operation_id.'\',
						operation_detail_tovar = \''.$val['id'].'\',
						operation_detail_item = \''.$val['order_item'].'\',
						operation_detail_price = \''.$val['product_price'].'\',
						operation_detail_zakup = (SELECT zakup FROM tbl_tovar_suppliers_items WHERE tovar_id=\''.$val['id'].'\' AND postav_id=\''.$val['product_postav_id'].'\'),
						operation_detail_summ = \''.$summ.'\',
						operation_detail_memo = \' Доставка: '.$val['delivery_days'].' дн., Поставщик: '.$User->getKlientName($val['product_postav_id']).'\',
						delivery_days = \''.$val['delivery_days'].'\',
						product_postav_id = \''.$val['product_postav_id'].'\',
						operation_detail_from = \'0\',
						operation_detail_to = \'0\';';
			$this->base->query($sql);
			
			$sql = 'SELECT tovar_artkl, tovar_name_1 FROM tbl_tovar WHERE tovar_id = \''.$val['id'].'\';';
			$tov = $this->base->query($sql);
			
			//Тут создается тело накладной для мыла
			if($tov->num_rows > 0){
				$tovar = $tov->fetch_assoc();
				
				$products_html .= '
						<tr>
							<td>'.$tovar['tovar_artkl'].'</td>
							<td>'.$tovar['tovar_name_1'].'</td>
							<td>'.$val['order_item'].'</td>
							<td>'.$val['product_price'].'</td>
							<td>'.$summ.'</td>
						</tr>';
			}
		}
		
		$sql = 'UPDATE tbl_operation SET operation_summ = \''.$total.'\' WHERE operation_id = \''.$operation_id.'\';';
		$this->base->query($sql);
	
		$this->clearCart($user['key']);

		$return['id'] 	= $operation_id;
		$return['sum'] 	= $total;
		
		if(isset($email) AND $email != '' AND strpos($email, '@') !== false){
			global $setup;
			$html = '<style>
						table tr td {
						   border: 1px solid gray;
						   margin: 0;
						   border-spacing: 0;
						   border-collapse: collapse;
						   padding: 10px 5px 10px 5px;
						}
						table tr th {
						   border: 1px solid gray;
						   margin: 0;
						   border-spacing: 0;
						   border-collapse: collapse;
						}
						table {
						   border: 1px solid gray;
						   margin: 0;
						   border-spacing: 0;
						   border-collapse: collapse;
						}
					</style>';
					
			$html .= '<h1>Заказ на Armma.ru</h1>
                    <h3>Заказ № '.$operation_id.', на сумму: '.$total.' руб.</h3>
					<p>'.str_replace('*','<br>',$customer_memo).'</p>
					<p>
					<table>
						<tr>
							<th style="width:100px;">Артикл</th>
							<th style="width:300px;">Наименование</th>
							<th style="width:70px;">К-во</th>
							<th style="width:100px;">Цена</th>
							<th style="width:100px;">Сумма</th>
						</tr>';
			$html .= $products_html;
			$html .= '</table>
					</p>';
            include 'admin/libmail.php';
            $m = new Mail("UTF-8");
            $m->From($setup['email name'].";".$setup['email']);
            $m->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
            $m->Priority(2);
            $m->Body($html);
            $m->text_html="text/html";
            $m->Subject('Заказ на Armma.ru');
            $m->To($email);
			$m->Cc('AlexMbox1@gmail.com');
            $error = $m->Send();
		}
		
		
		return $return;
	}
	
	public function clearCart($user_key){
		
		$sql = 'DELETE FROM tbl_orders WHERE order_customer = \''.$user_key.'\';';
		$this->base->query($sql);

	}
	
	
	
}
?>