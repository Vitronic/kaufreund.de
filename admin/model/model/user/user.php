<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		
			$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', folder = '" . (isset($this->request->post['generate_path']) ? $this->db->escape($data['username']) : '') . "', vendor_permission = '" . (int)$data['vendor_product'] . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', user_group_id = '" . (int)$data['user_group_id'] . "', status = '" . (int)$data['status'] . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "', date_added = NOW()");
			
	}

	public function editUser($user_id, $data) {
		
			if (isset($this->request->post['generate_path'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', folder = '" . (isset($this->request->post['generate_path']) ? $this->db->escape($data['username']) : '') . "', user_group_id = '" . (int)$data['user_group_id'] . "', vendor_permission = '" . (int)$data['vendor_product'] . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', status = '" . (int)$data['status'] . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "' WHERE user_id = '" . (int)$user_id . "'");
			
				if ($data['status'] == 1) {
					$this->language->load('mail/signup');
					$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
					
					$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
					$message .= $this->language->get('text_login') . "\n";
						
					$message .= HTTP_SERVER . "\n\n";
					$message .= $this->language->get('text_services') . "\n\n";
					$message .= $this->language->get('text_thanks') . "\n";
					$message .= $this->config->get('config_name');
					
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
					$mail->setText($message);
					$mail->send();
				}
			} elseif (isset($this->request->post['remove_path'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', folder = '" . (isset($this->request->post['remove_path']) ? '' : $this->db->escape($data['username'])) . "', user_group_id = '" . (int)$data['user_group_id'] . "', vendor_permission = '" . (int)$data['vendor_product'] . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', status = '" . (int)$data['status'] . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "' WHERE user_id = '" . (int)$user_id . "'");
			} else {
				$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', vendor_permission = '" . (int)$data['vendor_product'] . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', status = '" . (int)$data['status'] . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "' WHERE user_id = '" . (int)$user_id . "'");
			}
			

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}

	public function editPassword($user_id, $password) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getUser($user_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getUserByUsername($username) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");

		return $query->row;
	}

	public function getUserByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getUsers($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user`";

			if (isset($this->request->get['filter_status']) && !is_null($this->request->get['filter_status'])) {
				$sql .= " WHERE status = '" . (int)$this->request->get['filter_status'] . "'";
			}
			

		$sort_data = array(
			'username',
			'status',
			'date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY username";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalUsers() {
		
			$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`";
			if (isset($this->request->get['filter_status']) && !is_null($this->request->get['filter_status'])) {
				$sql .= " WHERE status = '" . (int)$this->request->get['filter_status'] . "'";
			}
			$query = $this->db->query($sql);
			

		return $query->row['total'];
	}


			public function getVendors($data = array()) {
				$sql = "SELECT vendor_id,vendor_name as name FROM " . DB_PREFIX . "vendors ORDER BY vendor_id" ;					
				$query = $this->db->query($sql);				
				return $query->rows;
			}
			
			Public function getUserAwaitingApproval($data = array()) {
				$query = $this->db->query("SELECT count(*) AS total FROM " . DB_PREFIX . "user u WHERE u.status = '5'");				
				return $query->row['total'];
			}
			
			Public function DisabledAllProducts($user_id) {
				$this->db->query("UPDATE " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vds ON (v.vendor = vds.vendor_id) LEFT JOIN " . DB_PREFIX . "user u ON (vds.user_id = u.user_id) SET p.status = '0' WHERE u.user_id = '" . (int)$this->db->escape($user_id) . "' AND p.status = '1'");
			}
			
			Public function EnabledAllProducts($user_id) {
				$this->db->query("UPDATE " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vds ON (v.vendor = vds.vendor_id) LEFT JOIN " . DB_PREFIX . "user u ON (vds.user_id = u.user_id) SET p.status = '1' WHERE u.user_id = '" . (int)$this->db->escape($user_id) . "' AND p.status = '0'");
			}
			
	public function getTotalUsersByGroupId($user_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalUsersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}	
}
?>