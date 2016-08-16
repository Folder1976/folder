<?php
class ControllerInformationInformation extends Controller {
	public function index() {
		$this->load->language('information/information');

		$this->load->model('catalog/information');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => '/'/*$this->url->link('common/home')*/
		);

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}
	
		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$this->document->setTitle($information_info['meta_title']);
			$this->document->setDescription($information_info['meta_description']);
			$this->document->setKeywords($information_info['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $information_info['title'],
				'href' => $this->url->link('information/information', 'information_id=' .  $information_id)
			);

			$data['heading_title'] = $information_info['title'];
	
			$data['button_continue'] = $this->language->get('button_continue');

			$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

			$data['description'] = str_replace('http://', '', $data['description']);
			
			$data['continue'] = '/';//$this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			
			if(isset($this->request->get['_route_']) AND $this->request->get['_route_'] == 'designers'){

				$this->load->model('catalog/manufacturer');
				$data['brands'] = $this->model_catalog_manufacturer->getManufacturers();
				
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/brands.tpl', $data));

			}elseif(isset($this->request->get['_route_']) AND $this->request->get['_route_'] == 'shops'){

				$this->load->model('catalog/shops');
				$data['shops'] = $this->model_catalog_shops->getShops();
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/shops.tpl', $data));

			}elseif(isset($this->request->get['_route_']) AND $this->request->get['_route_'] == 'brands_and_shops'){

				$this->load->model('catalog/shops');
				$this->load->model('catalog/manufacturer');
				$data['shops'] = $this->model_catalog_shops->getShops();
				$data['brands'] = $this->model_catalog_manufacturer->getManufacturers();
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/brands_and_shops.tpl', $data));

			}elseif(isset($this->request->get['_route_']) AND $this->request->get['_route_'] == 'vidzhety'){

				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/vidzhety.tpl', $data));

			}elseif(isset($this->request->get['_route_']) AND $this->request->get['_route_'] == 'add_shop'){

				if(isset($this->request->post['email'])){
				
					$email = $this->request->post['email'];
					if (!preg_match("|^([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is", strtolower($email)) AND $email == ''){ 
						$error = 'Не верный формат email';
					}else{
						$success = true;
					}
				
					$adress = $this->request->post['adress'];
					$name = $this->request->post['name'];
					$phone = $this->request->post['phone'];
					//$file = $this->request->post['file'];
								
					$txt = 'ЗАЯВКА ОТ МАГАЗИНА.
						Имя : '.$name . '
						Телефон : '.$phone. '
						Адрес : '.$adress . '
						Емаил : '.$email. '
						
						Фаил для иморта находится в прикреплении.
					';
					$html = '<b>ЗАЯВКА ОТ МАГАЗИНА.</b>
							<br><b>Имя :</b> '.$name . '
							<br><b>Телефон :</b> '.$phone . '
							<br><b>Адрес :</b> '.$adress. '
							<br><b>Емаил :</b> '.$email. '
							<br>
							<br><b>Фаил для иморта находится в прикреплении.</b>
					';	
						
					$this->mail = new Mail();

					if(isset($_FILES['file']['tmp_name'])){
						
						$uploadfile = DIR_DOWNLOAD . basename($_FILES['file']['name']);
			
						if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
							$this->mail->addAttachment($uploadfile);
							$dell[] = $uploadfile;
						}
						
					}
					
					$form_mail[] = 'folder.list@gmail.com';
					$form_mail[] = 'kottem@mail.ru';
					
					global $setup;
					include 'backend/libs/libmail.php';
					
					foreach($form_mail as $to){
			
							/*
							$m = new MailCustom("UTF-8");
							$m->From($setup['email name'].";".$setup['email']);
							$m->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
							$m->Priority(2);
							$m->Body($html);
							$m->text_html="text/html";
							$m->Subject('Armma.ru, смена пароля');
							$m->To($to);
							$error = $m->Send();
							*/
			
						$this->mail->setTo($to);//USER);
						$this->mail->setFrom($email);
						$this->mail->setSender($email);
						$this->mail->setSubject('Fashion Заявка от магазина');
						$this->mail->setText($txt);
						$this->mail->setHtml($html);
						$test = $this->mail->send();
						
						
						echo $uploadfile;
					}	
		
				}
				
			
				if(isset($success)){
					$data['success'] = true;
				}
			
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/add_shop.tpl', $data));

			}else{
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/information.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/information/information.tpl', $data));
				}
			
			}
			
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/information', 'information_id=' . $information_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = '/'; //$this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function agree() {
		$this->load->model('catalog/information');

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$output = '';

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
		}

		$this->response->setOutput($output);
	}
}