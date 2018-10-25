<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('router_model');
		$this->load->model('settings_model');
	}

	public function index()	{
		$this->load->view('login_view');
	}

	public function verify_user()	{
		$login_result = $this->login_model->verify_user();
		if($login_result == 0) {
			$jsonRes = array('status' => 'failed', 'msg' => 'Authentication problem !!!');
			echo json_encode($jsonRes);
			return;
		} else if($login_result == 2){
			$jsonRes = array('status' => 'balance', 'msg' => 'Insufficient Balance !!!');
			echo json_encode($jsonRes);
			return;
		}else {
			$id_role = $this->session->userdata('id_role');
			$permission_string = $this->login_model->get_permission_string($this->session->userdata('id_role'));
			$this->session->set_userdata(array('permission_string'=>$permission_string));

			$default_module  = $this->login_model->default_module($id_role);
			$settings_config = $this->settings_model->settings_config();

			$jsonRes = array('status' => 'success', 'msg' => $default_module);
			echo json_encode($jsonRes);
		}
	}

	public function logout() {
		$this->session->unset_userdata(array('user_id'));
		$this->session->unset_userdata(array('username'));
		$this->session->unset_userdata(array('name'));
		$this->session->unset_userdata(array('isp_name'));
		$this->session->unset_userdata(array('id_isp'));

		$this->load->view('login_view');
	}

}
