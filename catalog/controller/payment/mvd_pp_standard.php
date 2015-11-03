<?php
class ControllerPaymentMVDPPStandard extends Controller {
	protected function index() {
		$this->language->load('payment/mvd_pp_standard');
		    	
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
   		//$this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
		$this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($order_info) {
			$this->load->model('checkout/order');
			$mybankinfo = $this->model_checkout_order->PaymentGateway();
		
			$this->data['business'] = $mybankinfo['paypal_email'];
			$this->data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');				
			
			$this->data['products'] = array();
			
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
	
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
										
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
				
				$this->data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'price'    => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
					'quantity' => $product['quantity'],
					'option'   => $option_data,
					'weight'   => $product['weight']
				);
			}	
			
			$this->data['discount_amount_cart'] = 0;
			
			$total = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $order_info['currency_code'], false, false);

			if ($total > 0) {
				$this->data['products'][] = array(
					'name'     => $this->language->get('text_total'),
					'model'    => '',
					'price'    => $total,
					'quantity' => 1,
					'option'   => array(),
					'weight'   => 0
				);	
			} else {
				$this->data['discount_amount_cart'] -= $total;
			}
			
			$this->data['currency_code'] = $order_info['currency_code'];
			$this->data['first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');	
			$this->data['last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');	
			$this->data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');	
			$this->data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');	
			$this->data['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');	
			$this->data['zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');	
			$this->data['country'] = $order_info['payment_iso_code_2'];
			$this->data['email'] = $order_info['email'];
			$this->data['invoice'] = $this->session->data['order_id'] . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$this->data['lc'] = $this->session->data['language'];
			$this->data['return'] = $this->url->link('checkout/success');
			$this->data['notify_url'] = $this->url->link('payment/mvd_pp_standard/callback', '', 'SSL');
			$this->data['cancel_return'] = $this->url->link('checkout/checkout', '', 'SSL');

			$this->data['paymentaction'] = 'sale';
			
			$this->data['custom'] = $this->session->data['order_id'];
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mvd_pp_standard.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/mvd_pp_standard.tpl';
			} else {
				$this->template = 'default/template/payment/mvd_pp_standard.tpl';
			}
	
			$this->render();
		}
	}
	
	public function callback() {
		if (isset($this->request->post['custom'])) {
			$order_id = $this->request->post['custom'];
		} else {
			$order_id = 0;
		}		
		
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$request = 'cmd=_notify-validate';
		
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}
			
			//$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
			$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');

			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					
			$response = curl_exec($curl);
			
			if (!$response) {
				$this->log->write('PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}
					
			if ($this->config->get('pp_standard_debug')) {
				$this->log->write('PP_STANDARD :: IPN REQUEST: ' . $request);
				$this->log->write('PP_STANDARD :: IPN RESPONSE: ' . $response);
			}
						
			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
				$myPaypal = $this->model_checkout_order->MSPPCallback($order_id);
				
				$order_status_id = $this->config->get('config_order_status_id');
				
				switch($this->request->post['payment_status']) {
					case 'Canceled_Reversal':
						$order_status_id = $this->config->get('mvd_pp_standard_canceled_reversal_status_id');
						break;
					case 'Completed':
						if ((strtolower($this->request->post['receiver_email']) == strtolower($myPaypal['paypal_email'])) && ((float)$this->request->post['mc_gross'] == $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false))) {
							$order_status_id = $this->config->get('mvd_pp_standard_completed_status_id');
							$totalOPs = $this->db->query("SELECT SUM(vendor_total+vendor_tax) as total, vendor_id FROM " . DB_PREFIX . "order_product op WHERE op.order_id = '" . (int)$order_id . "' AND op.vendor_paid_status = '0' GROUP BY op.vendor_id");
							$getOPs = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product op WHERE op.order_id = '" . (int)$order_id . "' AND op.vendor_paid_status = '0'");
							$getOSs = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_shipping os WHERE os.order_id = '" . (int)$order_id . "' AND os.shipping_paid_status = '0'");

							$order_detail = array();				
							
							if ($totalOPs->rows) {
								foreach ($totalOPs->rows AS $pay_to_vendor) {
									foreach($getOPs->rows as $op) {
										if ($pay_to_vendor['vendor_id'] == $op['vendor_id']) {
											$order_detail[] = array(
												'product_id'	=> $op['product_id'],
												'order_id'  	=> $op['order_id'],
												'product_name'  => $op['name']
											);
										}
									}							
									
									if ($getOSs->rows) {
										$ship_cost = 0;
										foreach ($getOSs->rows as $shipping_cost) {
											if ($pay_to_vendor['vendor_id'] == $shipping_cost['vendor_id']) {
												$ship_cost = $shipping_cost['cost']+$shipping_cost['tax'];
											}
										}							
										
										$order_product_plus_shipping = $pay_to_vendor['total']+$ship_cost;
										$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_payment SET vendor_id = '" . (int)$pay_to_vendor['vendor_id'] . "', payment_info = '" . serialize($order_detail) . "', payment_type = '" . $this->language->get('text_title') . "', payment_amount = '" . (float)$order_product_plus_shipping . "', payment_status = '5', payment_date = Now()");
										
										foreach ($getOSs->rows AS $pay_shipping) {
											if ($pay_shipping['vendor_id'] == $pay_to_vendor['vendor_id']) {
												$this->db->query("UPDATE " . DB_PREFIX . "order_product op SET vendor_paid_status = '1' WHERE op.order_id = '" . (int)$order_id . "' AND op.vendor_paid_status = '0'");
												$this->db->query("UPDATE " . DB_PREFIX . "order_shipping os SET shipping_paid_status = '1' WHERE os.order_id = '" . (int)$order_id . "' AND os.shipping_paid_status = '0'");
											}
										}
									} else {
										$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_payment SET vendor_id = '" . (int)$pay_to_vendor['vendor_id'] . "', payment_info = '" . serialize($order_detail) . "', payment_type = '" . $this->language->get('text_title') . "', payment_amount = '" . (float)$pay_to_vendor['total'] . "', payment_status = '5', payment_date = Now()");
										$this->db->query("UPDATE " . DB_PREFIX . "order_product op SET vendor_paid_status = '1' WHERE op.order_id = '" . (int)$order_id . "' AND op.vendor_paid_status = '0'");
									}
									
									unset($order_detail);
								}						
							}
						} else {
							$this->log->write('PP_STANDARD :: RECEIVER EMAIL MISMATCH! ' . strtolower($this->request->post['receiver_email']));
						}
						break;
					case 'Denied':
						$order_status_id = $this->config->get('mvd_pp_standard_denied_status_id');
						break;
					case 'Expired':
						$order_status_id = $this->config->get('mvd_pp_standard_expired_status_id');
						break;
					case 'Failed':
						$order_status_id = $this->config->get('mvd_pp_standard_failed_status_id');
						break;
					case 'Pending':
						$order_status_id = $this->config->get('mvd_pp_standard_pending_status_id');
						break;
					case 'Processed':
						$order_status_id = $this->config->get('mvd_pp_standard_processed_status_id');
						break;
					case 'Refunded':
						$order_status_id = $this->config->get('mvd_pp_standard_refunded_status_id');
						break;
					case 'Reversed':
						$order_status_id = $this->config->get('mvd_pp_standard_reversed_status_id');
						break;	 
					case 'Voided':
						$order_status_id = $this->config->get('mvd_pp_standard_voided_status_id');
						break;								
				}
				
				if (!$order_info['order_status_id']) {
					$this->model_checkout_order->confirm($order_id, $order_status_id);
				} else {
					$this->model_checkout_order->update($order_id, $order_status_id);
				}
			} else {
				$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
			}
			
			curl_close($curl);
		}	
	}
}
?>