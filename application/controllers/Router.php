<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Router extends CI_Controller {

	public function __construct() {
		parent::__construct();
		 $this->load->model('router_model');
 	 	 $this->load->helper('permission_helper');
		 $this->load->model('settings_model');
		 $this->load->spark('mikrotik_api/0.7.0');
}

	public function create_router() {
		$data['page_heading']="Router";
		$data['routers_type']=$this->router_model->get_routers_type();
		$this->load->view('create_routers',$data);
	}

	public function create_router_now()	{
		$this->router_model->create_router_now();
	}

	public function routers()	{
		// $data['routers'] = $this->router_model->get_all_routers();
		$data['routers'] = $this->router_model->get_allowed_routers($this->session->userdata('user_id'));

	  $data['page_heading'] = "Router";
		$this->load->view('routers', $data);
	}

	public function delete_router_now() {
		$this->router_model->delete_router_now();
	}

	public function edit_routers()	{
		$id_router = $this->uri->segment(3);
		$data['routers_all']=$this->router_model->get_router_info($id_router);
		$data['routers_type']=$this->router_model->get_routers_type();
		$data['page_heading']="Router";
		$this->load->view('edit_routers', $data);
	}

	public function edit_router_now(){
		$this->router_model->edit_router_now();
	}

	public function view_routers_details() {
		$router_id = $this->uri->segment(3);
		error_log("Edit router id :". $router_id);

		$data['router_id'] = $router_id;
		$routers_all = $this->router_model->get_router_info($router_id);
		$data['routers'] = $routers_all;

		$data['page_heading']="Router";
		$this->load->view('view_routers_details', $data);
	}

	public function router_ipaddr() {
		$router_id = $this->uri->segment(3);
		error_log("Edit router id :". $router_id);

		$data['router_id'] = $router_id;
		$data['router_ips'] = $this->router_model->get_all_router_ip_addr($router_id);
		$data['router_name']=$this->router_model->get_router_name($router_id);

		$data['page_heading']="Router";
		$this->load->view('view_router_ipaddr', $data);
	}

	public function router_queue_simple() {
		$router_id = $this->uri->segment(3);
		error_log("Edit router id :". $router_id);

		$data['router_id'] = $router_id;
		// $data['queues'] = $this->router_model->get_all_router_queue_simple($router_id);
   $data['router_name']=$this->router_model->get_router_name($router_id);
		$data['page_heading'] = "Router Queue";
		$this->load->view('view_router_queue_simple', $data);
	}

	public function load_ips() {

		$router_id = $this->input->post('router_id');

		// get the sync flag from MT
		$router_details = $this->router_model->get_routers_indv_info($router_id);

		foreach($router_details as $router_indiv) {
			$router_ip		 	  = $router_indiv["ip_address"];
			$router_name	  	= $router_indiv["name"];
			$router_login 		= $router_indiv["login"];
			$router_password  = $router_indiv["password"];
			$sync_router_flag = $router_indiv["sync_router_flag"];
		}

		if($sync_router_flag){
			// mk login validation
			$mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

			if($mk_validation == false){
				$jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
				echo json_encode($jsonRes);
				return;
			}

			$addresses = $this->load_ip_addr($router_id);

			if(sizeof($addresses) > 0){
				$jsonRes = array('status' => 'passed', 'msg' => 'Router IP Loaded Successfully!', 'data' => '');
				$this->router_model->router_ip_addr_save_db($router_id, $addresses);
				$jsonRes['data'] = $this->router_model->get_all_router_ip_addr($router_id);
			}else{
				$jsonRes = array('status' => 'failed', 'msg' => 'IP Table is Empty in '.$router_name.'!!!');
			}
		}else{
			$jsonRes = array('status' => 'failed', 'msg' => $router_name.' Sync Disabled' );
		}

		echo json_encode($jsonRes);

	}

	public function load_queue() {
		$jsonRes = array('status' => 'passed', 'msg' => 'Router Queue Loaded Successfully!');

		$router_id = $this->input->post('router_id');
		error_log("Edit router id :". $router_id);

		$queue = $this->load_queue_simple($router_id);
		// $this->router_model->router_queue_save_db($router_id, $queue);
		// $data['router_ips'] = $this->router_model->get_all_router_queue_simple($router_id);

		echo json_encode($jsonRes);
	}


	public function load_firewall(){

		$router_id = $this->input->post('router_id');
		// get the sync flag from MT
		$router_details = $this->router_model->get_routers_indv_info($router_id);

		foreach($router_details as $router_indiv) {
			$router_ip		 	  = $router_indiv["ip_address"];
			$router_name	  	= $router_indiv["name"];
			$router_login 		= $router_indiv["login"];
			$router_password  = $router_indiv["password"];
			$sync_router_flag = $router_indiv["sync_router_flag"];
		}
		if($sync_router_flag){
			// mk login validation
			$mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

			if($mk_validation == false){
				$jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
				echo json_encode($jsonRes);
				return;
			}

			$firewall = $this->load_firewall_from_mikrotik($router_id);

			if(sizeof($firewall) > 0){
				$jsonRes = array('status' => 'passed', 'msg' => 'Router Firewall Loaded Successfully!','data' => '');
				$this->router_model->router_firewall_save_db($router_id, $firewall,'');
				$jsonRes['data'] = $this->router_model->get_all_firewall_by_router_id($router_id);
			}else{
				$jsonRes = array('status' => 'failed', 'msg' => 'Firewall Table is Empty in '.$router_name.'!!!');
			}
		}else{
			$jsonRes = array('status' => 'failed', 'msg' => $router_name.' Sync Disabled' );
		}
		echo json_encode($jsonRes);
	}


	public function load_firewall_from_mikrotik($router_id) {
		$routerObj = $this->router_model->get_router_info($router_id);
		foreach($routerObj as $item) {
			$router_name 		  = $item["name"];
			$router_ip 			  = $item["ip_address"];
			$router_login     = $item["login"];
			$router_password  = $item["password"];
			$router_type_id   = $item["id_router_type"];
		}

		error_log("Username: ". $router_login);
		$this->login($router_ip, $router_login, $router_password);
		$result = $this->mikrotik_api->ip()->firewall()->get_all_firewall_filter();

		return $result;
	}

	public function load_profile(){

		$router_id 	= $this->input->post('router_id');

		// get the sync flag from MT
		$router_details = $this->router_model->get_routers_indv_info($router_id);

		foreach($router_details as $router_indiv) {
			$router_ip		 	  = $router_indiv["ip_address"];
			$router_name	  	= $router_indiv["name"];
			$router_login 		= $router_indiv["login"];
			$router_password  = $router_indiv["password"];
			$sync_router_flag = $router_indiv["sync_router_flag"];
		}
		if($sync_router_flag){
			// mk login validation
			$mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

			if($mk_validation == false){
				$jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
				echo json_encode($jsonRes);
				return;
			}


			$profile 		= $this->load_profile_from_mikrotik($router_id);

			if(sizeof($profile) > 0){
				$jsonRes = array('status' => 'passed', 'msg' => 'Router Profile  Loaded Successfully!','data' => '');
				$this->router_model->router_profile_save_db($router_id, $profile,'');
				$jsonRes['data'] = $this->router_model->get_all_profile_by_router_id($router_id);
			}else{
				$jsonRes = array('status' => 'failed', 'msg' => 'Profile Table is Empty in '.$router_name.'!!!');
			}
		}else{
			$jsonRes = array('status' => 'failed', 'msg' => $router_name.' Sync Disabled' );
		}
		echo json_encode($jsonRes);

	}



	public function load_profile_from_mikrotik($router_id) {
		$routerObj = $this->router_model->get_router_info($router_id);
		foreach($routerObj as $item) {
			$router_name 		  = $item["name"];
			$router_ip 			  = $item["ip_address"];
			$router_login     = $item["login"];
			$router_password  = $item["password"];
			$router_type_id   = $item["id_router_type"];
		}


		$this->login($router_ip, $router_login, $router_password);

		$result = $this->mikrotik_api->ppp()->ppp_profile()->get_all_ppp_profile();

		return $result;
	}


	public function load_ip_pool(){

		$router_id 	= $this->input->post('router_id');

		// get the sync flag from MT
		$router_details = $this->router_model->get_routers_indv_info($router_id);

		foreach($router_details as $router_indiv) {
			$router_ip		 	  = $router_indiv["ip_address"];
			$router_name	  	= $router_indiv["name"];
			$router_login 		= $router_indiv["login"];
			$router_password  = $router_indiv["password"];
			$sync_router_flag = $router_indiv["sync_router_flag"];
		}

		if($sync_router_flag){
			// mk login validation
			$mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

			if($mk_validation == false){
				$jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
				echo json_encode($jsonRes);
				return;
			}

			$ip_pools 		= $this->load_ip_pool_from_mikrotik($router_id);

			if(sizeof($ip_pools) > 0){
				$jsonRes = array('status' => 'passed', 'msg' => 'Router Profile  Loaded Successfully!','data' => '');
				$this->router_model->router_ip_pool_save_db($router_id, $ip_pools,'');
				$jsonRes['data'] 	= $this->router_model->get_all_ip_pools_by_router_id($router_id);
			}else{
				$jsonRes = array('status' => 'failed', 'msg' => 'Radius Table is Empty in '.$router_name.' !!!');
			}
		}else{
			$jsonRes = array('status' => 'failed', 'msg' => $router_name.' Sync Disabled' );
		}
		echo json_encode($jsonRes);
	}

	public function load_ip_pool_from_mikrotik($router_id) {
		$routerObj = $this->router_model->get_router_info($router_id);

		foreach($routerObj as $item) {
			$router_name 		  = $item["name"];
			$router_ip 			  = $item["ip_address"];
			$router_login     = $item["login"];
			$router_password  = $item["password"];
			$router_type_id   = $item["id_router_type"];
		}

		error_log("Username: ". $router_login);
		$this->login($router_ip, $router_login, $router_password);

		$result = $this->mikrotik_api->ip()->pool()->get_all_pool();

		return $result;
	}




	public function login($router_ip,$router_login,$router_password){
		error_log("Login CALLED");
		error_log("Logging in with ".$router_ip."-".$router_login);
		$config['mikrotik']['host'] = $router_ip;
		$config['mikrotik']['port'] = '8728';
		$config['mikrotik']['username'] = $router_login;
		$config['mikrotik']['password'] = $router_password;
		$config['mikrotik']['debug'] = FALSE;
		$config['mikrotik']['attempts'] = 3;
		$config['mikrotik']['delay'] = 2;
		$config['mikrotik']['timeout'] = 2;
		$this->session->set_userdata($config);
		$this->session->set_userdata('loggedin', TRUE);
		$config['mikrotik'] = $this->session->userdata('mikrotik');
		$this->mikrotik_api->initialize($config);
		//$this->mikrotik_api->core()->debug("HELLO DEBUG");
		//$test=$this->mikrotik_api->connect();
		// $data['interfaces'] = $this->mikrotik_api->interfaces()->ethernet()->get_all_interface();
		//print_r($test);
	}

	public function load_ip_addr($router_id) {
		$routerObj = $this->router_model->get_router_info($router_id);
		foreach($routerObj as $item) {
			$router_name = $item["name"];
			$router_ip = $item["ip_address"];
			$router_login = $item["login"];
			$router_password = $item["password"];
			$router_type_id = $item["id_router_type"];
		}

		error_log("Username: ". $router_login);
		$this->login($router_ip, $router_login, $router_password);
		// Array ( [.id] => *1 [address] => 10.7.0.2/24 [network] => 10.7.0.0 [interface] => ether1 [actual-interface] => ether1 [invalid] => false [dynamic] => false [disabled] => false ) )
		$addresses = $this->mikrotik_api->ip()->address()->get_all_address();
		return $addresses;
	}


	public function load_queue_simple($router_id) {
		$routerObj = $this->router_model->get_router_info($router_id);
		foreach($routerObj as $item) {
			$router_name = $item["name"];
			$router_ip = $item["ip_address"];
			$router_login = $item["login"];
			$router_password = $item["password"];
			$router_type_id = $item["id_router_type"];
		}

		error_log("Username: ". $router_login);
		$this->login($router_ip, $router_login, $router_password);
		$result = $this->mikrotik_api->queue_simple()->get_all_queue();
		return $result;
	}




	public function ip_pool() {
		$id_router = $this->uri->segment(3);
		$data['ip_pools'] = $this->router_model->get_all_ip_pools_by_router_id($id_router);
		$data['page_heading'] = "IP Pool";
		$data['router_id'] 		= $id_router;

		$data['router_name']=$this->router_model->get_router_name($id_router);
		$this->load->view('ip_pools', $data);
	}


	public function router_profile() {
		$id_router 						= $this->uri->segment(3);
		$data['profiles'] 		= $this->router_model->get_all_profile_by_router_id($id_router);
		$data['router_id'] 		= $id_router;
		$data['page_heading'] = "Profile";
		$data['router_name']=$this->router_model->get_router_name($id_router);
		$this->load->view('view_profile', $data);
	}


	public function add_ip_pool(){
		$data['page_heading']="Ip Pool";
		$this->load->view('add_ip_pool',$data);
	}

	public function add_ip_pool_now(){
		$this->router_model->add_ip_pool_now();
	}

	public function delete_ip_pool_now(){
		$this->router_model->delete_ip_pool_now();
	}

	public function edit_ip_pool(){
		$id_ip_pool = $this->uri->segment(3);
		$data['page_heading']="Ip Pool";
		$data['ip_pools']=$this->router_model->get_individual_ip_pool($id_ip_pool);
		$this->load->view('edit_ip_pool',$data);
	}
	public function edit_ip_pool_now(){
		$this->router_model->edit_ip_pool_now();
	}

		public function add_profile(){
			$data['page_heading']="Profile";
			$id_router = $this->uri->segment(3);
			$data['ip_pools'] = $this->router_model->get_all_ip_pools_by_router_id($id_router);
			$this->load->view('add_profile',$data);
		}

		public function add_profile_now(){
			$this->router_model->add_profile_now();
		}
		public function delete_profile_now(){
			$this->router_model->delete_profile_now();
		}

		public function edit_profile(){
			$id_profile = $this->uri->segment(3);
			$id_router = $this->uri->segment(4);
			$data['page_heading']="Profile";
			$data['ip_pools'] = $this->router_model->get_all_ip_pools_by_router_id($id_router);
			$data['profiles']=$this->router_model->get_individual_profile($id_profile);
			$this->load->view('edit_profile',$data);
		}

		public function edit_profile_now(){
			$this->router_model->edit_profile_now();
		}

		public function router_firewall() {
			$id_router  = $this->uri->segment(3);
			$data['firewalls'] = $this->router_model->get_all_firewall_by_router_id($id_router);
			$data['router_id'] = $id_router;
			$data['page_heading'] = "Firewall";
			$data['router_name']=$this->router_model->get_router_name($id_router);
			$this->load->view('view_router_firewall', $data);
		}

		public function check_router_connection(){
			$this->router_model->check_router_connection();
		}

		public function router_radius() {
			$id_router  = $this->uri->segment(3);
			$data['radius'] = $this->router_model->get_all_radius_mk_by_router_id($id_router);
			$data['router_id'] = $id_router;
			$data['page_heading'] = "Radius";
			$data['router_name']=$this->router_model->get_router_name($id_router);
			$this->load->view('view_router_radius', $data);
		}


		public function load_radius(){

			$router_id = $this->input->post('router_id');

			// get the sync flag from MT
			$router_details = $this->router_model->get_routers_indv_info($router_id);

			foreach($router_details as $router_indiv) {
				$router_ip		 	  = $router_indiv["ip_address"];
				$router_name	  	= $router_indiv["name"];
				$router_login 		= $router_indiv["login"];
				$router_password  = $router_indiv["password"];
				$sync_router_flag = $router_indiv["sync_router_flag"];
			}
			if($sync_router_flag){
				// mk login validation
				$mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

				if($mk_validation == false){
					$jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
					echo json_encode($jsonRes);
					return;
				}

				$radius = $this->router_model->load_radius_from_mikrotik($router_id);

				if(sizeof($radius) > 0){
					$jsonRes = array('status' => 'passed', 'msg' => 'Router Radius Loaded Successfully!','data' => '');
					$this->router_model->router_radius_save_db($router_id, $radius);
					$jsonRes['data'] = $this->router_model->get_all_radius_mk_by_router_id($router_id);
				}else{
					$jsonRes = array('status' => 'failed', 'msg' => 'Radius Table is Empty in '.$router_name.'!!!');
				}

			}else{
				$jsonRes = array('status' => 'failed', 'msg' => $router_name.' Sync Disabled' );
			}
			echo json_encode($jsonRes);
		}
		public function add_radius_mk(){
			$data['page_heading']="Radius";
			$this->load->view('add_radius_mk',$data);
		}

		public function add_radius_mk_now(){
			$this->router_model->add_radius_mk_now();

		}

		public function delete_radius(){
			$this->router_model->delete_radius();
		}

		public function edit_radius(){
			$id_radius_mk = $this->uri->segment(3);
			$data['page_heading']="Radius";
			$data['radius']=$this->router_model->get_individual_radius($id_radius_mk);
			$this->load->view('edit_radius',$data);
		}

		public function edit_radius_now(){
			$this->router_model->edit_radius_now();
		}



}
