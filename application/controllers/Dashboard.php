<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('dashboard_model');
		$this->load->helper('permission_helper');

	}

	public function index(){
		//nib-web
		$data['ppoe']		 = $this->dashboard_model->get_total_ppoe();
		$data['router']	 = $this->dashboard_model->get_total_routers();

		// Getting all rows and showing in thousand.
		$rows = $this->dashboard_model->get_total_rows_inserted();
		if($rows >= 1000) {
			$rows = intval($rows / 1000) . 'K';
		}
		$data['total_rows_inserted'] = $rows;
		$data['error']						   = $this->dashboard_model->get_total_error();
		$data['notification']				 = $this->dashboard_model->get_top_5_notification();

			//nib-billing
		$data['total_customer']	=	$this->dashboard_model->get_total_customer();
		$data['total_invoice_amount_current_month'] = $this->dashboard_model->get_current_month_total_invoice_amount();
		$data['total_due_current_month'] = $this->dashboard_model->get_current_month_total_due();
		$data['total_due']		 = $this->dashboard_model->get_total_due();
		$data['previous_month_total_invoice_amount'] = $this->dashboard_model->get_previous_month_total_invoice_amount();
		$data['previous_month_total_due'] = $this->dashboard_model->get_previous_month_total_due();
		$data['recently_added_customer'] = $this->dashboard_model->get_recently_added_customer();
		$data['current_payment'] = $this->dashboard_model->get_current_payment();
		$data['page_heading'] 	 = "Dashboard";
		$this->load->view('dashboard',$data);
	}

	public function fetch_status_all(){
		$this->dashboard_model->fetch_status_all();
	}

	public function view_notifications(){
		$data['notifications']=$this->dashboard_model->get_all_notification();
		$data['page_heading']="Notifications";
		$this->load->view('notification_view',$data);
	}

	public function get_row_inserts(){
		$this->dashboard_model->get_row_inserts();
	}

	public function get_row_inserts_from_file_trace(){
		$this->dashboard_model->get_row_inserts_from_file_trace();
	}

	public function get_payment_by_month(){
		$this->dashboard_model->get_payment_by_month();
	}

	public function get_invoice_by_month(){
		$this->dashboard_model->get_invoice_by_month();
	}

}

?>
