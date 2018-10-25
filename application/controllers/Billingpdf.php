<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billingpdf extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('billing_model');
		$this->load->model('settings_model');
		$this->load->model('ppp_model');
	}

	public function index() {
	}

	public function createinvoicepdf(){

		require_once APPPATH.'third_party/fpdf/fpdf-1.8.php';
		$id_invoice = $this->uri->segment(3);
		$data['invoices']=$this->billing_model->get_individual_invoice_info($id_invoice);
    $data['isp_image']=$this->settings_model->get_by_key('isp_image');
		$data['isp_name']=$this->settings_model->get_by_key('isp_name');
		$data['isp_address']=$this->settings_model->get_by_key('isp_address');
		$data['isp_description']=$this->settings_model->get_by_key('isp_description');
		$data['id_isp_info']=$this->settings_model->get_by_key('id_isp_info');
		$selected_template_id = $this->settings_model->get_by_key('invoice_template');
		$data['template_name']=$this->settings_model->get_selected_template_name($selected_template_id);

		$pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',20);
		$CI =& get_instance();
		$CI->fpdf = $pdf;

		$this->load->view('billingpdf_view',$data);
	}
}
