<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class payment extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('payment_model');
		$this->load->model('billing_model');
		$this->load->helper('permission_helper');
	}

	public function index()	{
		$data['page_heading']="Payment";
		$this->load->view('add_payment_view',$data);
	}

	public function payment_view(){
		$data['payment'] = $this->payment_model->get_all_payment();
		$data['page_heading']="Payment";
		$this->load->view('payment_view',$data);
	}

	public function indiv_user_payment_view(){
		$id_net_user= $this->uri->segment(3);
		$data['payment'] = $this->payment_model->get_all_Indiv_user_payment($id_net_user);
		$data['page_heading']="Payment";
		$this->load->view('indiv_payment_view',$data);
	}

	public function indiv_payment_view(){
		$id_net_user= $this->uri->segment(3);
		$data['id_router']=$this->uri->segment(4);
		$data['payment'] = $this->payment_model->get_all_indiv_payment($id_net_user);
		$data['page_heading']="Payment";
		$this->load->view('indiv_payment_view',$data);
	}

	public function indiv_payment(){
		$id_invoice = $this->uri->segment(3);
		$data['invoice'] = $this->billing_model-> get_indv_invoice_info($id_invoice);
		$data['page_heading']="Payment";
		$this->load->view('indiv_payment', $data);
	}

	public function add_payment_now(){
		$this->payment_model->add_payment_now();
	}

	public function search_invoice_now(){
		$this->payment_model->search_invoice_now();
	}

	public function get_payments_by_date_range(){
			$this->payment_model->get_payments_by_date_range();
	}

}
