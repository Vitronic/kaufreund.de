<?php
class ControllerCatalogProStatCtrl extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/prostatctrl');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prostatctrl');

		$this->getList();
  	}

  	public function update() {
    	$this->load->language('catalog/prostatctrl');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prostatctrl');
		
		if (isset($this->request->post['selected']) && $this->validateUpdate()) {
			foreach ($this->request->post['selected'] as $user_id) {				
				if ($this->request->post['user_status' . "$user_id"] == '1') {
					$this->model_catalog_prostatctrl->EnabledAllProducts($user_id);
				} else {
					$this->model_catalog_prostatctrl->DisabledAllProducts($user_id);
				}
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
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('catalog/prostatctrl', 'token=' . $this->session->data['token'] . $url, 'SSL'), 
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['update'] = $this->url->link('catalog/prostatctrl/update', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data = array(
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		$this->data['users_info'] = array();
		
		$total_users = $this->model_catalog_prostatctrl->getTotalUsers($data);
		$results = $this->model_catalog_prostatctrl->getUserInformation($data);
		
		foreach ($results as $result) {
		
			$total_products = $this->model_catalog_prostatctrl->getTotalProductsByUserID($result['user_id']);
			
			if ($result['user_date_end'] == '0000-00-00') {
				$due_date = $this->language->get('text_nil');
			} else {
				$due_date = date('Y-m-d', strtotime($result['user_date_end']));
			}
			
			$this->data['users_info'][] = array(
				'user_id' 			=> $result['user_id'],
				'username' 			=> $result['username'],
				'vendor_name' 		=> $result['vendor_name'],
				'company' 			=> $result['company'],
				'flname'    		=> $result['flname'],
				'telephone'    		=> $result['telephone'],
				'email'    			=> $result['email'],
				'total_products'	=> $total_products,
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'status'     		=> $result['status'],
				'due_date'     		=> $due_date
			);
    	}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$this->data['column_company'] = $this->language->get('column_company');
    	$this->data['column_flname'] = $this->language->get('column_flname');
		$this->data['column_telephone'] = $this->language->get('column_telephone');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_due_date'] = $this->language->get('column_due_date');
		$this->data['column_status'] = $this->language->get('column_status');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['button_update'] = $this->language->get('button_update');

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

		$url = '';		

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$pagination = new Pagination();
		$pagination->total = $total_users;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/prostatctrl', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
			
		$this->template = 'catalog/prostatctrl_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}
	
	protected function validateUpdate() {
    	if (!$this->user->hasPermission('modify', 'catalog/prostatctrl')) {
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