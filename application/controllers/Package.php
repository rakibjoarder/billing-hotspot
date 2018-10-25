<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('package_model');
		$this->load->model('ppp_model');
		$this->load->model('settings_model');
		$this->load->helper('permission_helper');
	}

	public function index(){
		$data['packages']=$this->package_model->get_all_packages();
		$data['currency']=$this->package_model->get_currency();
		$data['page_heading']="Package";
		$this->load->view('package_view', $data);
	}

	// public function create_package(){
	// 	$data['taxes']=$this->settings_model->get_all_tax();
	// 	$data['ip_pools']=$this->package_model->get_all_free_ip_pools();
	// 	$data['page_heading']="Package";
	// 	$this->load->view('create_package',$data);
	// }

	public function create_package_now(){
		$this->package_model->create_package_now();
	}

	public function delete_package_now(){
		$this->package_model->delete_package_now();
	}
	// public function edit_package(){
	// 	$id_package = $this->uri->segment(3);
	// 	$data['taxes']=$this->settings_model->get_all_tax();
	// 	$data['ip_pools']=$this->package_model->get_all_free_ip_pools_except_own($id_package);
	// 	$data['packages']=$this->package_model->get_package_info($id_package);
	// 	$data['page_heading']="Package";
	// 	$this->load->view('edit_package',$data);
	// }

	public function edit_package_now(){
		$this->package_model->edit_package_now();
	}

}
