<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
		parent::__construct();

		 $this->load->model('user_model');
		 $this->load->model('role_model');
		 $this->load->model('router_model');
		 $this->load->model('zone_model');
		 $this->load->helper('permission_helper');
	}

	public function users(){
		$data['users']=$this->user_model->get_all_users();
		$data['page_heading']="User";
		$this->load->view('users',$data);
	}

	public function create_users(){
		$data['roles']=$this->role_model->get_all_roles();
		$data['page_heading']="User";
		$this->load->view('create_users',$data);
	}

	public function edit_users(){
		$user_id_current = $this->uri->segment(3);
		$data['roles']=$this->role_model->get_all_roles();
		$data['user_info']=$this->user_model->fetch_individual_user_info($user_id_current);
		$data['page_heading']="User";
		$this->load->view('edit_users',$data);
	}

	public function delete_users(){
		$users_all = $this->get_all_users();
		$data['users_all']=$users_all;
		$data['page_heading']="User";
		$this->load->view('delete_users',$data);

	}

	public function delete_user_now(){
		$this->user_model->delete_user_now();
	}

	public function create_user_now(){
		$this->user_model->create_user_now();
	}

	public function edit_user_now(){
		$this->user_model->edit_user_now();
	}

	public function get_all_users(){
		return $this->user_model->get_all_users()->result_array();
	}

	public function fetch_user_info(){
		$user_info=$this->user_model->fetch_user_info();
		echo json_encode($user_info);
	}

	public function access_router() {
		$user_id = $this->uri->segment(3);


		$routers = $this->router_model->get_all_routers();
		$accRouters = $this->router_model->get_allowed_routers($user_id);

		$n = count($routers);
		$m = count($accRouters);
		error_log("Router Count ". $n ." Allow Count ". $m);
		for($i = 0; $i < $n; $i++) {

			for($j = 0; $j < $m; $j++) {
				if($accRouters[$j]['id_router'] == $routers[$i]['id']) {
					error_log("Allowing router". $routers[$i]['id']);
					$routers[$i]['allowed'] = true;
					break;
				}
			}

			if($j == $m) {
				$routers[$i]['allowed'] = false;
			}
		}

		$data['user_id'] = $user_id;
		$data['routers'] = $routers;

		$data['user_info'] 		= $this->user_model->fetch_individual_user_info($user_id)->result_array();
		$data['users']	 			= $this->user_model->get_all_users();
		$data['page_heading'] = "Access Router";

		$this->load->view('access_router', $data);
	}

	public function allow_router_access() {
		$jsonResp = $this->router_model->allow_router_access($this->input->post('user_id'), $this->input->post('router_id'));
		echo json_encode($jsonResp);
	}

	public function remove_router_access() {
		$jsonResp = $this->router_model->remove_router_access($this->input->post('user_id'), $this->input->post('router_id'));
		echo json_encode($jsonResp);
	}

	public function access_zone(){
		$user_id = $this->uri->segment(3);

		$zones = $this->zone_model->get_all_zones();
		$accZones = $this->zone_model->get_allowed_zones($user_id);

		$n = count($zones);
		$m = count($accZones);
		error_log("Zone Count ". $n ." Allow Count ". $m);
		for($i = 0; $i < $n; $i++) {

			for($j = 0; $j < $m; $j++) {
				if($accZones[$j]['id_zone'] == $zones[$i]['id_zone']) {
					error_log("Allowing zone". $zones[$i]['id_zone']);
					$zones[$i]['allowed'] = true;
					break;
				}
			}

			if($j == $m) {
				$zones[$i]['allowed'] = false;
			}
		}

		$data['user_id'] = $user_id;
		$data['zones'] = $zones;

		$data['user_info'] 		= $this->user_model->fetch_individual_user_info($user_id)->result_array();
		$data['users']	 			= $this->user_model->get_all_users();
		$data['page_heading'] = "Access Zone";

		$this->load->view('access_zone', $data);
	}

	public function allow_zone_access() {
		$jsonResp = $this->zone_model->allow_zone_access($this->input->post('user_id'), $this->input->post('zone_id'));
		echo json_encode($jsonResp);
	}

	public function remove_zone_access() {
		$jsonResp = $this->zone_model->remove_zone_access($this->input->post('user_id'), $this->input->post('zone_id'));
		echo json_encode($jsonResp);
	}

}
