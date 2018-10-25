<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppp extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('ppp_model');
		$this->load->model('login_model');
		$this->load->model('package_model');
		$this->load->model('connection_model');
		$this->load->model('billing_model');
		$this->load->model('zone_model');
		$this->load->model('router_model');
		$this->load->model('pool_model');
		$this->load->model('settings_model');
		$this->load->model('router_model');
		$this->load->helper('permission_helper');
		 $this->load->spark('mikrotik_api/0.7.0');
	}

	public function index(){
		// $data['customers']=$this->ppp_model->get_net_users();
		$data['customers'] = $this->ppp_model->get_net_users_by_zone_access($this->session->userdata('user_id'));
		$data['page_heading']="Customer";
		$this->load->view('customer_view', $data);
	}
  public function check_isp_balance(){
    $balance = $this->login_model->get_isp_balance($this->session->userdata('id_isp'));
		if($balance > 0){
			$jsonRes = array('status' => 'success', 'msg' => '');
			echo json_encode($jsonRes);
			return;
		}else{
			$jsonRes = array('status' => 'failed', 'msg' => 'Insufficient Balance !!!');
			echo json_encode($jsonRes);
			return;
		}
	}
	public function create_customer()	{
		// $data['zones']=$this->zone_model->get_all_zones();
		$data['zones'] = $this->zone_model->get_allowed_zones($this->session->userdata('user_id'));
		$data['routers'] = $this->router_model->get_allowed_routers($this->session->userdata('user_id'));

    $data['net_user_types']=$this->ppp_model->get_net_user_types();
		$data['packages']=$this->ppp_model->get_all_packages();
		$data['repeat_everys']=$this->ppp_model->get_all_repeat_everys();
		$data['page_heading']="Customer";
		$this->load->view('create_customer',$data);
	}

	public function create_ppp_user_now(){
		$this->ppp_model->create_ppp_user();
	}

	public function delete_net_user_now(){
		$this->ppp_model->delete_net_user_now();
	}

	public function view_indiv_customer(){
	$id_net_user = $this->uri->segment(3);
	$data['customers']=$this->ppp_model->get_indiv_customers($id_net_user);
	$data['page_heading']="Customer";
	$this->load->view('view_indiv_customer',$data);
	}

	public function edit_customer(){
		$id_net_user 						= $this->uri->segment(3);
		$id_net_user_type       = $this->ppp_model->get_id_net_user_type($id_net_user);
		$data['customers']			=	$this->ppp_model->get_indiv_customers($id_net_user);
		$data['routers'] 				= $this->router_model->get_allowed_routers($this->session->userdata('user_id'));
		$data['net_user_types'] =	$this->ppp_model->get_net_user_types();
		$data['packages']				=	$this->ppp_model->get_all_packages();
		$data['repeat_everys']	=	$this->ppp_model->get_all_repeat_everys();
		if($id_net_user_type==1 || $id_net_user_type==3){
			$data['id_profile']     =	$this->ppp_model->get_id_profile_from_net_user_profile($id_net_user);
			$data['id_ip_pool']     =	$this->ppp_model->get_id_ip_pool_from_net_user_profile($id_net_user);

		}
		$data['page_heading']		= "Customer";
		$data['zones']					=$this->zone_model->get_all_zones();
		$this->load->view('edit_customer',$data);
	}

	public function edit_customer_now(){
		$this->ppp_model->edit_customer_now();
	}

	public function get_all_pool_by_router(){
		$router_id = $this->input->post('router_id');
		error_log('Router ID '. $router_id);

		$result = $this->pool_model->get_all_pool_by_router($router_id);
		if(count($result) > 0) {
			$data['status'] = 'passed';
			$data['msg'] = "Got pool successfully";
			$data['pool'] = $result;
		} else {
			$data['status'] = 'passed';
			$data['msg'] = "Empty pool successfully";
			$data['pool'] = $result;
		}

		echo json_encode($data);
	}


	public function get_all_profile_by_router(){
		$router_id = $this->input->post('router_id');
		error_log('Router ID '. $router_id);
		$result = $this->router_model->get_all_profile_by_router_id($router_id);
		if(count($result) > 0) {
			$data['status']  = 'passed';
			$data['msg']		 = "Got profile successfully";
			$data['profile'] = $result;
		} else {
			$data['status']  = 'passed';
			$data['msg'] 		 = "Empty pool successfully";
			$data['profile'] = $result;
		}
		echo json_encode($data);
	}


	public function get_all_ip_pools_by_router_id(){
		$router_id = $this->input->post('router_id');
		error_log('Router ID '. $router_id);
		$result = $this->router_model->get_all_ip_pools_by_router_id($router_id);
		if(count($result) > 0) {
			$data['status']  = 'passed';
			$data['msg']		 = "Got pool successfully";
			$data['ip_pools'] = $result;
		} else {
			$data['status']  = 'passed';
			$data['msg'] 		 = "Empty pool ";
			$data['ip_pools'] = $result;
		}
		echo json_encode($data);
	}

	public function create_invoice_now(){
		$this->billing_model->create_invoice_now();
	}

	public function create_invoice(){
		$id_net_user=$this->uri->segment(3);
		$data['customers']=$this->ppp_model->get_indiv_customers($id_net_user);
		$data['packages']=$this->package_model->get_all_packages();
		$data['page_heading']="Invoice";
		$this->load->view('create_invoice',$data);
	}

	public function view_indiv_invoice(){
		$id_net_user=$this->uri->segment(3);
		$data['all_invoice']=$this->billing_model->get_indiv_user_invoice($id_net_user);
		$data['page_heading']="Invoice";
		$this->load->view('indiv_invoice_view',$data);
	}

	public function indiv_payment(){
		$id_invoice 						= $this->uri->segment(3);
		$data['invoice'] 				= $this->billing_model-> get_indv_invoice_info($id_invoice);
		$data['page_heading']		= "Payment";
		$this->load->view('indiv_payment_customer', $data);
	}

}
