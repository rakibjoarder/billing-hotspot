<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('settings_model');
		$this->load->model('router_model');
		$this->load->model('zone_model');
		$this->load->model('radius_model');
		$this->load->helper('permission_helper');
		$this->load->spark('mikrotik_api/0.7.0');
	}

	public function general(){
		$data['settings_config']=$this->settings_model->settings_config();
		$data['page_heading']="Settings";
		$this->load->view('general_settings',$data);
	}

   public function view_profile(){
		 $data['profiles']=$this->settings_model->get_all_profiles();
		 $data['page_heading']="Settings";
		 $this->load->view('view_profile',$data);
	 }

	public function isp_info(){
		$data['page_heading']			= "Invoice Information";
		$data['settings_config']  = $this->settings_model->settings_config();
		$data['invoice_templates'] = $this->settings_model->get_all_invoice_template();
		$data['zones']            = $this->zone_model->get_allowed_zones($this->session->userdata('user_id'));
		$this->load->view('isp_information',$data);
	}

	public function add_isp_information(){
		$this->settings_model->add_isp_information();
	}

	public function message_template(){
		$data['page_heading']="Message Template";
		$data['billing_sms_template']=$this->settings_model->get_sms_template_message();
		$data['otp_template']=$this->settings_model->get_otp_template_message();
		$data['billing_email_template']=$this->settings_model->get_billing_email_template_message();
		$this->load->view('message_template',$data);
	}

	public function add_sms_template_now(){
			$this->settings_model->add_sms_template_now();
	}

	public function add_otp_template_now(){
			$this->settings_model->add_otp_template_now();
	}

	public function add_billing_email_template_now(){
		$this->settings_model->add_billing_email_template_now();
	}

	public function version(){
		$data['page_heading']="Settings";
		$data['version'] = $this->settings_model->settings_config();
		$this->load->view('settings_version',$data);
	}

	public function migration(){
		$data['page_heading']="Settings";
		// $data['routers'] = $this->router_model->get_all_routers();
		$data['routers'] = $this->router_model->get_allowed_routers($this->session->userdata('user_id'));
		$this->load->view('migration',$data);
	}

	public function add_mikrotik_to_db(){
		$this->settings_model->add_mikrotik_to_db();
	}

}
