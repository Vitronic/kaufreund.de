<?php
class ModelAccountSignUp extends Model {
	
	public function addVendorSignUp($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', password = '" . $this->db->escape(md5($data['password'])) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '50', date_added = NOW()");
		$user_id = $this->db->getLastId();
		
		if ($this->request->post['singup_plan']) {
			$singup_plan = explode(':',$this->request->post['singup_plan']);	
			$this->db->query("INSERT INTO " . DB_PREFIX . "vendors SET user_id = '" . (int)$user_id . "', vendor_name = '" . $this->db->escape($data['company']) . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', commission_id = '" . (int)$this->encryption->decrypt($singup_plan[0]) . "', product_limit_id = '" . (int)$this->encryption->decrypt($singup_plan[2]) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', iban = '" . $this->db->escape($data['iban']) . "', swift_bic = '" . $this->db->escape($data['swift_bic']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "', bank_address = '" . $this->db->escape($data['bank_address']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', paypal_email = '" . $this->db->escape($data['paypal']) . "', vendor_description = '" . $this->db->escape($data['store_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', store_url = '" . $this->db->escape($data['store_url']) . "', sort_order = '0'");
			$vendor_id = $this->db->getLastId();
		
			if ($this->config->get('signup_auto_approval')) {
				if ($this->encryption->decrypt($singup_plan[1]) == '4') {
					$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '5', folder = '" . $this->db->escape($data['username']) . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . serialize($this->config->get('signup_category')) . "', store_permission = '" . serialize($this->config->get('signup_store')) . "' , user_date_start = Now(), user_date_end = Now() + INTERVAL " . $this->encryption->decrypt($singup_plan[3]) . " MONTH WHERE user_id = '" . (int)$user_id . "'");
					$this->db->query("INSERT INTO `" . DB_PREFIX . "signup_fee_history` SET user_id = '" . (int)$user_id . "', signup_fee = '" . (float)$this->encryption->decrypt($singup_plan[4]) . "', signup_plan = '" . $this->db->escape($data['hsignup_plan']) . "', vendor_name = '" . $this->db->escape($data['company']) . "', username = '" . $this->db->escape($data['username']) . "', commission_type = '" . (int)$this->encryption->decrypt($singup_plan[1]) . "', status = '5', user_date_start = Now(), user_date_end = Now() + INTERVAL " . $this->encryption->decrypt($singup_plan[3]) . " MONTH, date_added = NOW()");		
				} elseif($this->encryption->decrypt($singup_plan[1]) == '5') {
					$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '5', folder = '" . $this->db->escape($data['username']) . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . serialize($this->config->get('signup_category')) . "', store_permission = '" . serialize($this->config->get('signup_store')) . "' , user_date_start = Now(), user_date_end = Now() + INTERVAL " . $this->encryption->decrypt($singup_plan[3]) . " YEAR WHERE user_id = '" . (int)$user_id . "'");
					$this->db->query("INSERT INTO `" . DB_PREFIX . "signup_fee_history` SET user_id = '" . (int)$user_id . "', signup_fee = '" . (float)$this->encryption->decrypt($singup_plan[4]) . "', signup_plan = '" . $this->db->escape($data['hsignup_plan']) . "', vendor_name = '" . $this->db->escape($data['company']) . "', username = '" . $this->db->escape($data['username']) . "', commission_type = '" . (int)$this->encryption->decrypt($singup_plan[1]) . "', status = '5', user_date_start = Now(), user_date_end = Now() + INTERVAL " . $this->encryption->decrypt($singup_plan[3]) . " YEAR, date_added = NOW()");
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '1', folder = '" . $this->db->escape($data['username']) . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . serialize($this->config->get('signup_category')) . "', store_permission = '" . serialize($this->config->get('signup_store')) . "' WHERE user_id = '" . (int)$user_id . "'");
				}
			} else {
				if ($this->encryption->decrypt($singup_plan[1]) == '4') {
					$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '5', folder = '" . $this->db->escape($data['username']) . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . serialize($this->config->get('signup_category')) . "', store_permission = '" . serialize($this->config->get('signup_store')) . "' , user_date_start = Now(), user_date_end = Now() + INTERVAL " . $this->encryption->decrypt($singup_plan[3]) . " MONTH WHERE user_id = '" . (int)$user_id . "'");
					$this->db->query("INSERT INTO `" . DB_PREFIX . "signup_fee_history` SET user_id = '" . (int)$user_id . "', signup_fee = '" . (float)$this->encryption->decrypt($singup_plan[4]) . "', signup_plan = '" . $this->db->escape($data['hsignup_plan']) . "', vendor_name = '" . $this->db->escape($data['company']) . "', username = '" . $this->db->escape($data['username']) . "', commission_type = '" . (int)$this->encryption->decrypt($singup_plan[1]) . "',  status = '5', user_date_start = Now(), user_date_end = Now() + INTERVAL " . $this->encryption->decrypt($singup_plan[3]) . " MONTH, date_added = NOW()");		
				} elseif($this->encryption->decrypt($singup_plan[1]) == '5') {
					$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '5', folder = '" . $this->db->escape($data['username']) . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . serialize($this->config->get('signup_category')) . "', store_permission = '" . serialize($this->config->get('signup_store')) . "' , user_date_start = Now(), user_date_end = Now() + INTERVAL " . $this->encryption->decrypt($singup_plan[3]) . " YEAR WHERE user_id = '" . (int)$user_id . "'");
					$this->db->query("INSERT INTO `" . DB_PREFIX . "signup_fee_history` SET user_id = '" . (int)$user_id . "', signup_fee = '" . (float)$this->encryption->decrypt($singup_plan[4]) . "', signup_plan = '" . $this->db->escape($data['hsignup_plan']) . "', vendor_name = '" . $this->db->escape($data['company']) . "', username = '" . $this->db->escape($data['username']) . "', commission_type = '" . (int)$this->encryption->decrypt($singup_plan[1]) . "', status = '5', user_date_start = Now(), user_date_end = Now() + INTERVAL " . $this->encryption->decrypt($singup_plan[3]) . " YEAR, date_added = NOW()");
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '5', vendor_permission = '" . (int)$vendor_id . "' WHERE user_id = '" . (int)$user_id . "'");
				}
			}
			
			$this->language->load('mail/signup');
			
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "<br><br>";
			
			if ($this->config->get('signup_auto_approval')) {
				$message .= $this->language->get('text_login') . "<br><br>";
			} else {
				if ($this->encryption->decrypt($singup_plan[1]) == '4' || $this->encryption->decrypt($singup_plan[1]) == '5') {
					$message .= $this->language->get('text_login') . "<br><br>";
				} else {
					$message .= $this->language->get('text_approval') . "<br><br>";
				}
			}
			
			$message .= '<a href="' . HTTP_SERVER . 'admin' . '">' . HTTP_SERVER . 'admin' . "</a><br><br>";
			
			$message .= $this->language->get('text_signup_information') . "<br>" ;
			$message .= $this->language->get('text_signup_username') . $this->request->post['username'] . "<br>";
			$message .= '<font >' . $this->language->get('text_signup_plan') . $this->request->post['hsignup_plan'] . "<br>";
			$message .= $this->language->get('text_signup_date') . date('Y-m-d') . "<br>";
			
			if ($this->encryption->decrypt($singup_plan[1]) == '4') {
				$message .= $this->language->get('text_signup_expire_date') . date('Y-m-d', strtotime("+" . $this->encryption->decrypt($singup_plan[3]) . "month")) . "<br>";
			} elseif($this->encryption->decrypt($singup_plan[1]) == '5') {
				$message .= $this->language->get('text_signup_expire_date') . date('Y-m-d', strtotime("+" . $this->encryption->decrypt($singup_plan[3]) . "year")) . "<br>";
			}
			
			$message .= "<br>" . $this->language->get('text_services') . "<br><br>";
			$message .= $this->language->get('text_thanks') . "<br>";
			$message .= $this->config->get('config_name') . "<br><br>";		
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($data['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setHtml($message);
			$mail->send();
		} else {
			$singup_plan = $this->request->post['singup_plan'];	
			$this->db->query("INSERT INTO " . DB_PREFIX . "vendors SET user_id = '" . (int)$user_id . "', vendor_name = '" . $this->db->escape($data['company']) . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', commission_id = '1', product_limit_id = '1', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', iban = '" . $this->db->escape($data['iban']) . "', swift_bic = '" . $this->db->escape($data['swift_bic']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "', bank_address = '" . $this->db->escape($data['bank_address']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', paypal_email = '" . $this->db->escape($data['paypal']) . "', vendor_description = '" . $this->db->escape($data['store_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', store_url = '" . $this->db->escape($data['store_url']) . "', sort_order = '0'");
			$vendor_id = $this->db->getLastId();
		
			if ($this->config->get('signup_auto_approval')) {
				$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '1', folder = '" . $this->db->escape($data['username']) . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . serialize($this->config->get('signup_category')) . "', store_permission = '" . serialize($this->config->get('signup_store')) . "' WHERE user_id = '" . (int)$user_id . "'");
			} else {
				$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '5', vendor_permission = '" . (int)$vendor_id . "' WHERE user_id = '" . (int)$user_id . "'");
			}
			
			$this->language->load('mail/signup');
			
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "<br><br>";
			
			if ($this->config->get('signup_auto_approval')) {
				$message .= $this->language->get('text_login') . "<br><br>";
			} else {
				$message .= $this->language->get('text_approval') . "<br><br>";
			}
			
			$message .= '<a href="' . HTTP_SERVER . 'admin' . '">' . HTTP_SERVER . 'admin' . "</a><br><br>";
			
			$message .= $this->language->get('text_signup_information') . "<br>" ;
			$message .= $this->language->get('text_signup_username') . $this->request->post['username'] . "<br>";
			$message .= '<font >' . $this->language->get('text_signup_plan') . $this->request->post['hsignup_plan'] . "<br>";
			$message .= $this->language->get('text_signup_date') . date('Y-m-d') . "<br>";
			
			$message .= "<br>" . $this->language->get('text_services') . "<br><br>";
			$message .= $this->language->get('text_thanks') . "<br>";
			$message .= $this->config->get('config_name') . "<br><br>";		
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($data['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setHtml($message);
			$mail->send();
		}
		
		/*trigger to store admin*/
		$subject_join = sprintf($this->language->get('text_subject1'), $data['username']);
		
		$text = sprintf($this->language->get('text_to'), $this->config->get('config_owner')) . "<br><br>";
		$text .= sprintf($this->language->get('text_join'), $data['username'], $data['company']) . "<br><br>";
		$text .= $this->language->get('text_thanks') . "<br>";
		$text .= $this->config->get('config_name');
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject_join);
		$mail->setHtml($text);
		$mail->send();
		/*end trigger store admin*/
	}
	
	public function getUsernameBySignUp($username) {
		$query = $this->db->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");
		return $query->row['total'];
	}
	
	public function getEmailBySignUp($email) {
		$query = $this->db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "user` WHERE email = '" . $this->db->escape($email) . "'");
		return $query->row['total'];
	}
	
	public function getProductLimit() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_limit Order by product_limit_id");
		return $query->rows;
	}
	
	public function getCommissionLimits() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission c LEFT JOIN " . DB_PREFIX . "product_limit pc ON (c.product_limit_id = pc.product_limit_id)");
		return $query->rows; 
	}
	
	public function getSignUpRate($commission_id, $commission_type) {
		$query = $this->db->query("SELECT commission AS amount FROM `" . DB_PREFIX . "commission` WHERE commission_id = '" . (int)$this->db->escape($commission_id) . "' AND commission_type = '" . (int)$this->db->escape($commission_type) . "'");
		return $query->row['amount'];
	}
	
	public function getUserID($username) {
		$query = $this->db->query("SELECT user_id FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");
		return $query->row['user_id'];
	}
	
	public function ValidateUserID($user_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");
		return $query->row;
	}
	
	public function UpdateUserStatus($user_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '1' WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "signup_fee_history SET paid_status = '1' , status = '1' WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");
	}
	
	public function RenewUserStatus($user_id,$new_date) {
		$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '1', user_date_end = '" . $this->db->escape($new_date) . "' WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "signup_fee_history SET paid_status = '1' , status = '1' WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");
	}	
		
	public function getVendorData($user_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "vendors` WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");		
		return $query->row;
	}
}
?>