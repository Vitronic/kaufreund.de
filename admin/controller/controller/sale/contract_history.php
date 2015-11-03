<?php
class ControllerSaleContractHistory extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/contract_history');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/contract_history');

    	$this->getList();
  	}
	
	public function renew() {
    	$this->load->language('sale/contract_history');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/contract_history');

    	if (isset($this->request->post['selected']) && $this->validateUpdate()) {
			foreach ($this->request->post['selected'] as $signup_fee_id) {				
				$this->model_sale_signup_history->DeleteSignUPHistory($signup_fee_id);
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
			'href'      => $this->url->link('sale/contract_history', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$old_contract = $this->model_sale_contract_history->getRenewContract();
		$getSignupRate = $this->model_sale_contract_history->getCommissionRate($old_contract['commission_id']);
		
		if ($getSignupRate) {
			if ($getSignupRate['commission_type'] == '4') {
				$this->data['my_type'] = True;
				$this->data['my_duration'] = (int)$getSignupRate['duration'];
				$this->data['month_year'] = $this->language->get('text_month') . $this->language->get('text_s');
				$this->data['due_date'] = date($this->language->get('date_format_short2'), strtotime("+" . (int)$getSignupRate['duration'] . $this->language->get('text_month'), strtotime($old_contract['user_date_end'])));
			} else {
				$this->data['my_type'] = False;
				$this->data['month_year'] = $this->language->get('text_year') . $this->language->get('text_s');
				$this->data['due_date'] = date($this->language->get('date_format_short2'), strtotime("+" . '1' . $this->language->get('text_year'), strtotime($old_contract['user_date_end'])));
			}
			$this->data['old_date'] = date($this->language->get('date_format_short2'),strtotime($old_contract['user_date_end']));
			$this->data['commission_type'] = $getSignupRate['commission_type'];
			$this->data['renew_plan'] = $getSignupRate['commission_name'];
			$this->data['renew_period'] = $getSignupRate['duration'];
			$this->data['renew_rate'] = $getSignupRate['commission'];
		}
		
		$this->data['renew'] = $this->url->link('sale/contract_history/renew', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$this->data['histories'] = array();

		$data = array(
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$contracts = $this->model_sale_contract_history->getContractHistory($data);
		$total_history = $this->model_sale_contract_history->getTotalContractHistory($data);

    	foreach ($contracts as $contract) {
	
			if ($contract['paid_status']) {
				if ($contract['status'] == '1') {
					if ((strtotime(date($this->language->get('date_format_short2'))) <= strtotime($contract['user_date_end']))) {
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
				'signup_id'			=> $contract['signup_fee_id'],
				'user_id'			=> $contract['user_id'],
				'username'			=> $contract['username'],
				'vendor_name'		=> $contract['vendor_name'],
				'signup_plan'		=> $contract['signup_plan'],
				'status'			=> $status,
				'selected'   		=> isset($this->request->post['selected']) && in_array($contract['signup_fee_id'], $this->request->post['selected']),				
				'signup_fee'    	=> $this->currency->format($contract['signup_fee'], $this->config->get('config_currency')),				
				'date_start'		=> date($this->language->get('date_format_short2'), strtotime($contract['user_date_start'])),
				'date_end'			=> date($this->language->get('date_format_short2'), strtotime($contract['user_date_end'])),
				'paid_status'		=> $contract['paid_status']
				);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_completed'] = $this->language->get('text_completed');
		$this->data['text_pending'] = $this->language->get('text_pending');
		$this->data['text_active'] = $this->language->get('text_active');
		$this->data['text_products'] = $this->language->get('text_products');
		
		$this->data['button_renew'] = $this->language->get('button_renew');
		
		$this->data['column_contract_id'] = $this->language->get('column_contract_id');
		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$this->data['column_signup_plan'] = $this->language->get('column_signup_plan');
		$this->data['column_signup_duration'] = $this->language->get('column_signup_duration');
		$this->data['column_signup_amount'] = $this->language->get('column_signup_amount');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
		$this->data['column_paid_date'] = $this->language->get('column_paid_date');
		$this->data['column_paid_status'] = $this->language->get('column_paid_status');
		
		$this->data['contract_history'] = $this->url->link('sale/contract_history/renew', 'token=' . $this->session->data['token'] . $url, 'SSL');
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
		$pagination->url = $this->url->link('sale/contract_history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/contract_history_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
  	}	
	
	public function ajaxrate() {
		
		$this->load->language('sale/contract_history');
		$this->load->model('sale/contract_history');
		
		$json = array();
		
    	$old_contract = $this->model_sale_contract_history->getRenewContract();
		$getSignupRate = $this->model_sale_contract_history->getCommissionRate($old_contract['commission_id']);
		
		if ($getSignupRate) {
			if ($getSignupRate['commission_type'] == '4') {
				$this->data['my_type'] = True;
				$this->data['my_duration'] = (int)$getSignupRate['duration'];
				$month_year = $this->language->get('text_month');
				$getRate = (float)$getSignupRate['commission']/$getSignupRate['duration'];
			} else {
				$this->data['my_type'] = False;
				$month_year = $this->language->get('text_year');
				$getRate = (float)$getSignupRate['commission'];
			}
			
			$json = array(
				'renew_commission_id' 	=> $getSignupRate['commission_id'],
				'renew_plan'    		=> $getSignupRate['commission_name'],
				'renew_cycle'  			=> $getSignupRate['duration'],
				'renew_rate'    		=> $getRate*$this->request->get['renewcycle'],
				'renew_lrate'    		=> $this->currency->format($getRate*$this->request->get['renewcycle'], $this->config->get('config_currency')),
				'renew_due_date'    	=> date($this->language->get('date_format_short2'), strtotime("+" . $this->request->get['renewcycle'] . $month_year, strtotime($old_contract['user_date_end'])))
			);
		}
		$this->response->setOutput(json_encode($json));
  	}
	
	public function paynow() {
	
		$this->load->language('sale/contract_history');
		$this->load->model('sale/contract_history');
		
		$old_contract = $this->model_sale_contract_history->getRenewContract();
		
		$data = array();

		$data = array(
			'user_id'			=> $this->user->getId(),
			'signup_fee'		=> $this->request->get['renew_rate'],
			'username'			=> $old_contract['username'],
			'vendor_name'		=> $old_contract['vendor_name'],
			'commission_type'	=> $this->request->get['commission_type'],			
			'signup_plan'		=> $this->request->get['renew_plan'],			
			'renew_period'		=> $this->request->get['renew_period'],			
			'user_date_start'	=> date($this->language->get('date_format_short2'), strtotime($this->request->get['old_date'])),
			'user_date_end'		=> date($this->language->get('date_format_short2'), strtotime($this->request->get['next_due_date']))
		);

		$this->model_sale_contract_history->addRenewContract($data);
		
		if ($this->config->get('signup_paypal_email')) {
			$custom_id = $this->user->getId() . ':' . $this->request->get['next_due_date'];
			$request = 'cmd=_xclick';		
			$request .= '&business=' . $this->config->get('signup_paypal_email');
			$request .= '&item_name=' . html_entity_decode($this->language->get('text_signup_plan') . $this->request->get['renew_plan'], ENT_QUOTES, 'UTF-8');			
			$request .= '&notify_url=' . HTTPS_CATALOG . 'index.php?route=account/signup_callback/renew_callback';
			$request .= '&cancel_return=' . HTTPS_CATALOG . 'index.php?route=account/signup';
			$request .= '&return=' . HTTPS_CATALOG . 'index.php?route=account/renewalsuccess';
			$request .= '&currency_code=' . $this->config->get('config_currency');
			$request .= '&amount=' . $this->request->get['renew_rate'];
			$request .= '&custom=' . $custom_id;
			
			if ($this->config->get('signup_paypal_sandbox')) {
				$json['success'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr?' . $request;
			} else {
				$json['success'] = 'https://www.paypal.com/cgi-bin/webscr?' . $request;
			}
		} else {
			$json['error'] = $this->language->get('text_paypal_error');
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	protected function validateUpdate() {
    	if (!$this->user->hasPermission('modify', 'sale/contract_history')) {
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