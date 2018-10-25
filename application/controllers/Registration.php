<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('registration_model');
		$this->load->helper('permission_helper');
	}

	public function index(){
		$data['page_heading']="Registraion";
		$this->load->view('isp_registration',$data);
	}

	function isp_registration_now(){
		$this->registration_model->isp_registration_now();
	}

}
