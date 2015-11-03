<?php
class ModelSaleCoup2Ven extends Model {
	public function addCoupon($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', vendor_id = '" . (int)$this->user->getVP() . "', date_added = NOW()");

      	$coupon_id = $this->db->getLastId();
		
		if (isset($data['coupon_product'])) {
      		foreach ($data['coupon_product'] as $product_id) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
      		}			
		}		
	}
	
	public function editCoupon($coupon_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "' WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		if (isset($data['coupon_product'])) {
      		foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
      		}
		}		
	}
	
	public function deleteCoupon($coupon_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");		
	}
	
	public function getCoupon($coupon_id) {
      	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "' AND vendor_id = '" . (int)$this->user->getVP() . "'");
		
		return $query->row;
	}

	public function getCouponByCode($code) {
      	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($code) . "' AND vendor_id = '" . (int)$this->user->getVP() . "'");
		
		return $query->row;
	}
		
	public function getCoupons($data = array()) {
		$sql = "SELECT coupon_id, name, code, discount, date_start, date_end, status FROM " . DB_PREFIX . "coupon WHERE vendor_id ='" . (int)$this->user->getVP() . "' ";
		
		$sort_data = array(
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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

	public function getCouponProducts($coupon_id) {
		$coupon_product_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}
		
		return $coupon_product_data;
	}	
		
	public function getTotalCoupons() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon WHERE vendor_id = '" . (int)$this->user->getVP() . "'");
		
		return $query->row['total'];
	}	
	
	public function ValidateCPUpdate($coupon_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon` WHERE coupon_id = '" . (int)$coupon_id . "' AND vendor_id = '" . (int)$this->user->getVP() . "'");
		return $query->row['total'];
	}
	
}
?>