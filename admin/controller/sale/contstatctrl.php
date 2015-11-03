<?php
class ControllerSaleContStatCtrl extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/contstatctrl');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/contstatctrl');

    	$this->getList();
  	}
	
	public function update() {
    	$this->load->language('sale/contstatctrl');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/contstatctrl');

    	if (isset($this->request->post['selected']) && $this->validateUpdate()) {
			foreach ($this->request->post['selected'] as $signup_fee_id) {				
				$data = array(
					'signup_fee_id'	=> $signup_fee_id,
					'user_id'	  	=> $this->request->post['user_id' . "$signup_fee_id"],
					'date_end'	  	=> date($this->language->get('date_format_short2'), strtotime($this->request->post['date_end' . "$signup_fee_id"])),
					'status'	  	=> $this->request->post['paid_status' . "$signup_fee_id"]
				);
    		}			
			$this->model_sale_contstatctrl->UpdatePaidStatus($data);
			$this->session->data['success'] = $this->language->get('text_success');		
		}
		   	
		$this->getList();
  	}
	
	public function sendNotification() {
    	$this->language->load('mail/email_notification');
		$this->language->load('sale/contstatctrl');
		
		$this->load->model('sale/contstatctrl');
		
		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $signup_fee_id) {
			
				$vendor_data = $this->model_sale_contstatctrl->getVendorData($this->request->post['user_id' . "$signup_fee_id"]);
				$subject = sprintf($this->language->get('text_subject_expire'),$this->request->post['remaining_days' . "$signup_fee_id"]);
					
				$text = sprintf($this->language->get('text_to'), $vendor_data['firstname'] . ' ' . $vendor_data['lastname']) . "<br><br>";				
				$text .= sprintf($this->language->get('text_message_expire'), $this->request->post['remaining_days' . "$signup_fee_id"]) . "<br><br>";
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
			$this->session->data['success'] = $this->language->get('text_email_success');				
		}
		
		$this->getList();
  	}
	
	public function delete() {
    	$this->load->language('sale/contstatctrl');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/contstatctrl');

    	if (isset($this->request->post['selected']) && $this->validateUpdate()) {
			foreach ($this->request->post['selected'] as $signup_fee_id) {				
				$this->model_sale_contstatctrl->DeleteSignUPHistory($signup_fee_id);
    		}		
			$this->session->data['success'] = $this->language->get('text_success');		
		}
		   	
		$this->getList();
  	}
	
   	private function getList() {
			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/contstatctrl', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['sendEmail'] = $this->url->link('sale/contstatctrl/sendNotification', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['update'] = $this->url->link('sale/contstatctrl/update', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/contstatctrl/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$this->data['histories'] = array();

		$data = array(
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$signup_histories = $this->model_sale_contstatctrl->getSignUpHistory($data);
		$total_history = $this->model_sale_contstatctrl->getTotalSignUpHistory($data);

    	foreach ($signup_histories as $signup_history) {
			
			if ($signup_history['paid_status']) {
				if ($signup_history['status'] == '1') {
					if ((strtotime(date($this->language->get('date_format_short2'))) <= strtotime($signup_history['user_date_end']))) {
						$status = $this->language->get('text_active');
					} else {
						$status = $this->language->get('text_expired');
					}
				} else {
					$status = $this->language->get('text_inactive');
				}
			} else {
				$status = $this->language->get('text_inactive');
			}
			
			$this->data['histories'][] = array (
				'signup_id'			=> $signup_history['signup_fee_id'],
				'user_id'			=> $signup_history['user_id'],
				'username'			=> $signup_history['username'],
				'vendor_name'		=> $signup_history['vendor_name'],
				'signup_plan'		=> $signup_history['signup_plan'],
				'status'			=> $status,
				'remaining_days'	=> round((strtotime($signup_history['user_date_end'])-time())/86400 + 1) . $this->language->get('text_days'),
				'selected'   		=> isset($this->request->post['selected']) && in_array($signup_history['signup_fee_id'], $this->request->post['selected']),				
				'signup_fee'    	=> $this->currency->format($signup_history['signup_fee'], $this->config->get('config_currency')),				
				'date_start'		=> date($this->language->get('date_format_short2'), strtotime($signup_history['user_date_start'])),
				'date_end'			=> date($this->language->get('date_format_short2'), strtotime($signup_history['user_date_end'])),
				'paid_status'		=> $signup_history['paid_status']		
				);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_completed'] = $this->language->get('text_completed');
		$this->data['text_pending'] = $this->language->get('text_pending');
		$this->data['text_active'] = $this->language->get('text_active');
		
		$this->data['button_update'] = $this->language->get('button_update');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_sendEmail'] = $this->language->get('button_sendEmail');
		
		$this->data['column_contract_id'] = $this->language->get('column_contract_id');
		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$this->data['column_signup_plan'] = $this->language->get('column_signup_plan');
		$this->data['column_signup_duration'] = $this->language->get('column_signup_duration');
		$this->data['column_signup_amount'] = $this->language->get('column_signup_amount');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_remaining_days'] = $this->language->get('column_remaining_days');
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
		$this->data['column_paid_date'] = $this->language->get('column_paid_date');
		$this->data['column_paid_status'] = $this->language->get('column_paid_status');

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$json['success'] = $this->language->get('text_success');
		
		$pagination = new Pagination();
		$pagination->total = $total_history;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/contstatctrl', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/contstatctrl_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
  	}
	
	protected function validateUpdate() {
    	if (!$this->user->hasPermission('modify', 'sale/contstatctrl')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
}
?>