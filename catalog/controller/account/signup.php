<?php 
class ControllerAccountSignUp extends Controller {
	private $error = array();
	      
  	public function index() {

    	$this->language->load('account/signup');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('account/signup');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && ($this->config->get('sign_up'))) {
			
			if ($this->request->post['singup_plan']) {
				$singup_plan = explode(':',$this->request->post['singup_plan']);
				if ($this->encryption->decrypt($singup_plan[1]) == '4' || $this->encryption->decrypt($singup_plan[1]) == '5') { 
					$this->model_account_signup->addVendorSignUp($this->request->post);
					if (!file_exists(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']))) {
						mkdir(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']), 0777);
					}
					$this->send();				
				} else {			
					if ($this->config->get('signup_auto_approval')) {
						if (!file_exists(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']))) {
							mkdir(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']), 0777);
						}
					}				
					$this->model_account_signup->addVendorSignUp($this->request->post);
					$this->redirect($this->url->link('account/signupsuccess'));
				}
			} else {
				if ($this->config->get('signup_auto_approval')) {
					if (!file_exists(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']))) {
						mkdir(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']), 0777);
					}					
				}				
				$this->model_account_signup->addVendorSignUp($this->request->post);
				$this->redirect($this->url->link('account/signupsuccess'));
			}
    	} 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/signup', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_register'),
			'href'      => $this->url->link('account/signup', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
    	$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), HTTP_SERVER . 'admin');
    	$this->data['text_your_details'] = $this->language->get('text_your_details');
    	$this->data['text_your_address'] = $this->language->get('text_your_address');
    	$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_close_sign_up'] = $this->language->get('text_close_sign_up');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_paypal'] = $this->language->get('entry_paypal');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_company'] = $this->language->get('entry_company');
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_store_url'] = $this->language->get('entry_store_url');
		$this->data['entry_store_description'] = $this->language->get('entry_store_description');
		$this->data['entry_plan'] = $this->language->get('entry_plan');
		
		$this->data['entry_bank_name'] = $this->language->get('entry_bank_name');
		$this->data['entry_iban'] = $this->language->get('entry_iban');
		$this->data['entry_swift_bic'] = $this->language->get('entry_swift_bic');
		$this->data['entry_bank_address'] = $this->language->get('entry_bank_address');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');

		$this->data['button_sign_up'] = $this->language->get('button_sign_up');
		
    	$this->data['action'] = $this->url->link('account/signup', '', 'SSL');
    
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else { 
			$this->data['error_firstname'] = '';
		}	
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}		
	
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['paypal'])) {
			$this->data['error_paypal'] = $this->error['paypal'];
		} else {
			$this->data['error_paypal'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
  		if (isset($this->error['address_1'])) {
			$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
   		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
		
		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
		
		if (isset($this->request->post['username'])) {
    		$this->data['username'] = $this->request->post['username'];
		} else {
			$this->data['username'] = '';
		}
		
		if (isset($this->request->post['firstname'])) {
    		$this->data['firstname'] = $this->request->post['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
    		$this->data['lastname'] = $this->request->post['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->request->post['email'])) {
    		$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['bank_name'])) {
    		$this->data['bank_name'] = $this->request->post['bank_name'];
		} else {
			$this->data['bank_name'] = '';
		}
		
		if (isset($this->request->post['iban'])) {
    		$this->data['iban'] = $this->request->post['iban'];
		} else {
			$this->data['iban'] = '';
		}
		
		if (isset($this->request->post['swift_bic'])) {
    		$this->data['swift_bic'] = $this->request->post['swift_bic'];
		} else {
			$this->data['swift_bic'] = '';
		}
		
		if (isset($this->request->post['bank_address'])) {
    		$this->data['bank_address'] = $this->request->post['bank_address'];
		} else {
			$this->data['bank_address'] = '';
		}
		
		if (isset($this->request->post['company_id'])) {
    		$this->data['company_id'] = $this->request->post['company_id'];
		} else {
			$this->data['company_id'] = '';
		}
		
		if (isset($this->request->post['tax_id'])) {
    		$this->data['tax_id'] = $this->request->post['tax_id'];
		} else {
			$this->data['tax_id'] = '';
		}
		
		if (isset($this->request->post['paypal'])) {
    		$this->data['paypal'] = $this->request->post['paypal'];
		} else {
			$this->data['paypal'] = '';
		}
		
		if (isset($this->request->post['telephone'])) {
    		$this->data['telephone'] = $this->request->post['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		
		if (isset($this->request->post['fax'])) {
    		$this->data['fax'] = $this->request->post['fax'];
		} else {
			$this->data['fax'] = '';
		}
		
		if (isset($this->request->post['company'])) {
    		$this->data['company'] = $this->request->post['company'];
		} else {
			$this->data['company'] = '';
		}
		
		if (isset($this->request->post['address_1'])) {
    		$this->data['address_1'] = $this->request->post['address_1'];
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
    		$this->data['address_2'] = $this->request->post['address_2'];
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
    		$this->data['postcode'] = $this->request->post['postcode'];
		} else {
			$this->data['postcode'] = '';
		}
		
		if (isset($this->request->post['city'])) {
    		$this->data['city'] = $this->request->post['city'];
		} else {
			$this->data['city'] = '';
		}

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} else {	
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id']; 	
		} else {
      		$this->data['zone_id'] = '';
    	}
		
		$this->data['singup_plans'] = $this->model_account_signup->getCommissionLimits();
			
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		if (isset($this->request->post['password'])) {
    		$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) {
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}

		if ($this->config->get('signup_policy')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('signup_policy'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('signup_policy'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		if (isset($this->request->post['agree'])) {
      		$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = false;
		}
		
		if ($this->config->get('signup_commission')) {
			$dfcom = explode(':',$this->config->get('signup_commission'));
			$this->data['default_commission'] = $this->encryption->encrypt($dfcom[0]) . ':' . $this->encryption->encrypt($dfcom[1]) . ':' . $this->encryption->encrypt($dfcom[2]) . ':' . $this->encryption->encrypt($dfcom[3]);
		} else {
			$this->data['default_commission'] = '0';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/signup.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/signup.tpl';
		} else {
			$this->template = 'default/template/account/signup.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());	
  	}
	
	public function send() {

		$this->language->load('account/signup');
		$this->load->model('account/signup');
		
		$custom_id = $this->model_account_signup->getUserID($this->request->post['username']);
		
		$splan = explode(':',$this->request->post['singup_plan']);
		$signup_amount = $this->model_account_signup->getSignUpRate($this->encryption->decrypt($splan[0]),$this->encryption->decrypt($splan[1]));				
		
		$request = 'cmd=_xclick';		
		$request .= '&business=' . $this->config->get('signup_paypal_email');
		$request .= '&item_name=' . html_entity_decode($this->language->get('text_signup_plan') . $this->request->post['hsignup_plan'], ENT_QUOTES, 'UTF-8');			
		$request .= '&notify_url=' . $this->url->link('account/signup_callback/signup_callback', '', 'SSL');
		$request .= '&cancel_return=' . $this->url->link('account/signup', '', 'SSL');
		$request .= '&return=' . $this->url->link('account/signupsuccess');
		$request .= '&currency_code=' . $this->config->get('config_currency');
		$request .= '&amount=' . $signup_amount;
		$request .= '&custom=' . $custom_id;

		if ($this->config->get('signup_paypal_sandbox')) {
    		$this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?' . $request);
  		} else {
			$this->redirect('https://www.paypal.com/cgi-bin/webscr?' . $request);
		}
	
	}
	
	public function signup_callback() {
		if (isset($this->request->post['custom'])) {
			$user_id = $this->request->post['custom'];
		} else {
			$user_id = 0;
		}

		$this->load->model('account/signup');
		$user_info = $this->model_account_signup->ValidateUserID($user_id);	
		
		if ($user_info) {
			$request = 'cmd=_notify-validate';
		
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}
			
			if (!$this->config->get('signup_paypal_sandbox')) {
				$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
			} else {
				$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}
			
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					
			$response = curl_exec($curl);
	
			if (!$response) {
				$this->log->write('PP_SIGNUP :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}
			
			$this->log->write('PP_SIGNUP :: IPN REQUEST: ' . $request);
			$this->log->write('PP_SIGNUP :: IPN RESPONSE: ' . $response);
			
			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
				
				switch($this->request->post['payment_status']) {
					case 'Completed':
					$user_status = '1';
					break;
					
					default:
					$user_status = '5';
					break;
				}		
				
				if ($user_status == '1') {
					$this->model_account_signup->UpdateUserStatus($user_id);
				}
			}
			curl_close($curl);
		}	
	}
	
	public function renew_callback() {
		if (isset($this->request->post['custom'])) {
			$renew_id = explode(':',$this->request->post['custom']);
			$user_id = $renew_id[0];
		} else {
			$user_id = 0;
		}
		
		$this->load->model('account/signup');
		$user_info = $this->model_account_signup->ValidateUserID($user_id);	
		
		if ($user_info) {
			$request = 'cmd=_notify-validate';
		
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}
			
			if (!$this->config->get('signup_paypal_sandbox')) {
				$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
			} else {
				$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}
			
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					
			$response = curl_exec($curl);
	
			if (!$response) {
				$this->log->write('PP_SIGNUP :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}
			
			$this->log->write('PP_RENEW :: IPN REQUEST: ' . $request);
			$this->log->write('PP_RENEW :: IPN RESPONSE: ' . $response);
			
			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
				
				switch($this->request->post['payment_status']) {
					case 'Completed':
					$user_status = '1';
					break;
					
					default:
					$user_status = '5';
					break;
				}		
				
				if ($user_status == '1') {
					$this->model_account_signup->RenewUserStatus($user_id,$renew_id[1]);
					$this->renewNotification($user_id);
				}
			}
			curl_close($curl);
		}	
	}
	
	public function renewNotification($user_id) {

    	$this->language->load('mail/signup');
		
		$this->load->model('account/signup');
		
		$vendor_data = $this->model_account_signup->getVendorData($user_id);
		$subject = $this->language->get('text_renew_subject');
					
		$text = sprintf($this->language->get('text_to'), $vendor_data['firstname'] . ' ' . $vendor_data['lastname']) . "<br><br>";				
		$text .= $this->language->get('text_renew_message') . "<br><br>";
		$text .= $this->language->get('text_thanks') . "<br>";
		$text .= $this->config->get('config_name') . "<br><br>";
		$text .= $this->language->get('text_system');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($vendor_data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
		$mail->send();

  	}

  	private function validate() {
		if ($this->model_account_signup->getUsernameBySignUp($this->request->post['username'])) {
      		$this->error['warning'] = $this->language->get('error_username_exists');
    	}
		
		if ($this->model_account_signup->getEmailBySignUp($this->request->post['email'])) {
      		$this->error['warning'] = $this->language->get('error_exists');
    	}
		
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
		if (!empty($this->request->post['paypal'])) {
			if ((utf8_strlen($this->request->post['paypal']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['paypal'])) {
				$this->error['paypal'] = $this->language->get('error_paypal');
			}
		}
			
	   	if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}

		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

    	if ($this->request->post['country_id'] == '') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if ($this->request->post['zone_id'] == '') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}

    	if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	} 
		
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
			if ($information_info && !isset($this->request->post['agree'])) {
      			$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}
  
  	public function zone() {
		$output = '<option value="">' . $this->language->get('text_select') . '</option>';
		
		$this->load->model('localisation/zone');

    	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
        
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
		  	$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}
	
		$this->response->setOutput($output);
  	}
}
?>