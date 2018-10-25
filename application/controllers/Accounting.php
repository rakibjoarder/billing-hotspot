<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('permission_helper');
		$this->load->model('accounting_model');
		$this->load->model('billing_model');
		$this->load->model('payment_model');
	}

	public function index()	{
	}

	public function customer_statement(){
		$data['page_heading']="Customer Statement";
		$this->load->view('customer_statement_view',$data);
	}

	public function customer_statement_by_date(){
		$this->accounting_model->customer_statement_by_date();
	}

	public function search_customer_statement(){
		$this->accounting_model->search_customer_statement();
	}

	public function invoice_statement()	{
		$data['all_invoices']=$this->billing_model->get_all_invoice_statement();
		$data['page_heading']="Invoice Statement";
		$this->load->view('invoice_statement_view',$data);
	}

	public function payment_statement(){
		$data['payment'] = $this->payment_model->get_all_payment_statement();
		$data['page_heading']="Payment Statement";
		$this->load->view('payment_statement_view',$data);
	}

	public function accounts_summary(){
		$drop_down_year_invoice=$this->accounting_model->get_drop_down_year_invoice();
		$drop_down_year_payment=$this->accounting_model->get_drop_down_year_payment();

		if(sizeof($drop_down_year_invoice)>sizeof($drop_down_year_payment)){
			$years=$drop_down_year_invoice;
		}else{
			$years=$drop_down_year_payment;
		}
		$year=Date('Y');
		$data['accounts_summary'] = $this->accounting_model->get_summary($year);
		$data['page_heading']="Accounts Summary";
		$data['years']=$years;
		$this->load->view('view_accounts_summary',$data);
	}

	public function get_summary_by_year(){
		$this->accounting_model->get_summary_by_year();
	}
}
