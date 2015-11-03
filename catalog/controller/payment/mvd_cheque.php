<?php
class ControllerPaymentMVDCheque extends Controller {
	protected function index() {
		$this->language->load('payment/mvd_cheque');
		
		$this->load->model('checkout/order');
		$mybankinfo = $this->model_checkout_order->PaymentGateway();
		
		$format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {postcode}' . "\n" . '{zone}, {country}';
		$find = array(
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		
		$this->load->model('localisation/zone');
		$zone = $this->model_localisation_zone->getZone((int)$mybankinfo['zone_id']);
		
		$this->load->model('localisation/country');
		$country = $this->model_localisation_country->getCountry((int)$mybankinfo['country_id']);
		
		$replace = array(
			'company'   => $mybankinfo['company'],
			'address_1' => $mybankinfo['address_1'],
			'address_2' => $mybankinfo['address_2'],
			'city'      => $mybankinfo['city'],
			'postcode'  => $mybankinfo['postcode'],
			'zone' 		=> isset($zone['name']) ? $zone['name'] : '',
			'country'   => $country['name']
		);
							
		$this->data['text_instruction'] = $this->language->get('text_instruction');
    	$this->data['text_payable'] = $this->language->get('text_payable');
		$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['payable'] = $mybankinfo['firstname'] . ' ' . $mybankinfo['lastname'];
		$this->data['address'] = nl2br(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))));
			
		$this->data['continue'] = $this->url->link('checkout/success');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mvd_cheque.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/mvd_cheque.tpl';
		} else {
			$this->template = 'default/template/payment/mvd_cheque.tpl';
		}	
		
		$this->render(); 
	}
	
	public function confirm() {
		$this->language->load('payment/mvd_cheque');
		
		$this->load->model('checkout/order');
		$mybankinfo = $this->model_checkout_order->PaymentGateway();
		
		$format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {postcode}' . "\n" . '{zone}, {country}';
		$find = array(
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		
		$this->load->model('localisation/zone');
		$zone = $this->model_localisation_zone->getZone((int)$mybankinfo['zone_id']);
		
		$this->load->model('localisation/country');
		$country = $this->model_localisation_country->getCountry((int)$mybankinfo['country_id']);
		
		$replace = array(
			'company'   => $mybankinfo['company'],
			'address_1' => $mybankinfo['address_1'],
			'address_2' => $mybankinfo['address_2'],
			'city'      => $mybankinfo['city'],
			'postcode'  => $mybankinfo['postcode'],
			'zone' 		=> isset($zone['name']) ? $zone['name'] : '',
			'country'   => $country['name']
		);
		
		$comment  = $this->language->get('text_payable') . "\n";
		$comment .= $mybankinfo['firstname'] . ' ' . $mybankinfo['lastname'] . "\n\n";
		$comment .= $this->language->get('text_address') . "\n";
		$comment .= nl2br(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))))) . "\n\n";
		$comment .= $this->language->get('text_payment') . "\n";
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('mvd_cheque_order_status_id'), $comment, true);
	}
}
?>