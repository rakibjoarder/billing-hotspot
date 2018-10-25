<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('billing_model');
		$this->load->model('ppp_model');
		$this->load->model('package_model');
		$this->load->model('settings_model');
		$this->load->helper('permission_helper');
	}

	public function index(){
		$data['all_invoice']=$this->billing_model->get_all_invoice();
		$data['page_heading']="Invoice";
		$this->load->view('invoice_view',$data);
	}

	public function todays_invoice(){
		$this->billing_model->todays_invoice();
	}

	public function yesterdays_invoice(){
		$this->billing_model->yesterdays_invoice();
	}

	public function current_month_invoice(){
		$this->billing_model->current_month_invoice();
	}

	public function previous_month_invoice(){
		$this->billing_model->previous_month_invoice();
	}

	public function get_invoices_by_date_range(){
		$this->billing_model->get_invoices_by_date_range();
	}

	public function get_all_invoice_statement_by_date_range(){
		$this->billing_model->get_all_invoice_statement_by_date_range();
	}

	public function get_all_payment_statement_by_date_range(){
		$this->billing_model->get_all_payment_statement_by_date_range();
	}

	public function generate_invoice(){
		$ip = $this->input->ip_address();
		$host = $_SERVER['HTTP_HOST'];
		$host_ip  = explode(':', $host);
    error_log('IP ' . $ip . ' Host '. $host_ip[0]);

		if($ip == $host_ip[0] || $ip == '127.0.0.1' || $ip == 'localhost' || $ip == '::1'){
			$jsonRes = array('status' => 'success', 'msg' => 'Successfully called!');
			$this->billing_model->generate_invoice();
		}else{
			$jsonRes = array('status' => 'failed', 'msg' => 'Access denied !');
		}
		echo json_encode($jsonRes);
	}

	public function disable_connection(){
		$ip 			= $this->input->ip_address();
		$host			= $_SERVER['HTTP_HOST'];
		$host_ip  = explode(':', $host);
    error_log('IP ' . $ip . ' Host '. $host_ip[0]);

		if($ip == $host_ip[0] || $ip == '127.0.0.1' || $ip == 'localhost' || $ip == '::1'){
			$jsonRes = array('status' => 'success', 'msg' => 'Successfully called!');
			$this->billing_model->disable_connection();
		}else{
			$jsonRes = array('status' => 'failed', 'msg' => 'Access denied !');
		}
		echo json_encode($jsonRes);
	}

}
