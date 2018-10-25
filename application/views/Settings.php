<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index()
	{
		$data['settings_config'] = $this->admin_model->settings_config();
		$this->load->view('settings',$data);
	}

	public function version()
	{
		$data['version'] = $this->admin_model->settings_config();
		$this->load->view('settings_version',$data);
	}

	public function isp_config()	{
		$data['settings_config'] = $this->admin_model->settings_config();
		$this->load->view('settings',$data);
	}

	public function router_config()	{
		$data['settings_config']=$this->admin_model->settings_config();
		$this->load->view('router_config',$data);
	}
}
