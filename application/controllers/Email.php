<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('email');
		$this->load->model('email_model');
		$this->load->model('billing_model');
		$this->load->model('ppp_model');
		$this->load->model('settings_model');
		$this->load->helper('permission_helper');
		$this->load->library('curl');
	}

	function send_isp_regisration_email(){

		$email= $this->input->post('email');
		$name= $this->input->post('name');

		$isp_email		= $this->settings_model->get_by_key('isp_email');
		$isp_password = $this->settings_model->get_by_key('isp_password');
		$smtp_host		= $this->settings_model->get_by_key('smtp_host');
		$smtp_port		= $this->settings_model->get_by_key('smtp_port');

		//SMTP
		$config['protocol']    	= 'smtp';
		$config['smtp_host']    = $smtp_host;
		$config['smtp_port']    = $smtp_port;
		$config['smtp_timeout'] = '7';
		$config['smtp_user']    = $isp_email;
		$config['smtp_pass']    = $isp_password;
		$config['charset']    	= 'utf-8';
		$config['newline']    	= "\r\n";
		$config['mailtype'] 		= 'html'; // or html
		$config['validation'] 	= TRUE; // bool whether to validate email or not

		$this->email->initialize($config);

		$this->email->from($isp_email, $isp_name);
		$this->email->to($email);

		$this->email->subject("LightCube Technology Isp Registration");
		$this->email->message("Dear ".$name.",<br/>      You have Successfully registered your isp, We will get in touch with you shortly.<br/><br/>Regards<br/>LightCube Technology.");


		if($this->email->send()){
			error_log('Email Sent Successfully !');
		}else{
			error_log('Failed to Send Email !');
		}
	}


	function send_email(){

		require_once APPPATH.'third_party/fpdf/fpdf-1.8.php';

		$id_invoice = $this->uri->segment(3);

		$isp_image=$this->settings_model->get_by_key('isp_image');
		$isp_name=$this->settings_model->get_by_key('isp_name');
		$isp_address=$this->settings_model->get_by_key('isp_address');
		$isp_description=$this->settings_model->get_by_key('isp_description');
		$id_isp_info=$this->settings_model->get_by_key('id_isp_info');
		$isp_email=$this->settings_model->get_by_key('isp_email');
		$isp_password=$this->settings_model->get_by_key('isp_password');
		$smtp_host=$this->settings_model->get_by_key('smtp_host');
		$smtp_port=$this->settings_model->get_by_key('smtp_port');

		$selected_template_code 	= $this->settings_model->get_by_key('invoice_template');
		$template_name	= $this->settings_model->get_selected_template_name($selected_template_code);


		$invoices=$this->billing_model->get_individual_invoice_info($id_invoice);
		$pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',20);
		$CI =& get_instance();
		$CI->fpdf = $pdf;

		require_once($template_name);

		$newFile  = 'invoice/invoice-'.$id_invoice.'-'.rand().'.pdf';   //create pdf and save
		$this->fpdf->Output($newFile, 'F');

		$net_user_info=$this->email_model->get_net_user_info_by_invoice_id($id_invoice);

		foreach($net_user_info as $customer_indiv):
		$net_user_name=$customer_indiv['net_user_name'];
		$net_user_email=$customer_indiv['net_user_email'];
		$net_user_name=$customer_indiv['net_user_name'];
		endforeach;

		$email_message=$this->settings_model->get_billing_email_template_message();
		$email_message = str_replace("%CUSTOMER_NAME%",$net_user_name,$email_message);
		$email_message = str_replace("%INVOICE_NUMBER%",$id_invoice,$email_message);

		//SMTP
		$config['protocol']    	= 'smtp';
		$config['smtp_host']    = $smtp_host;
		$config['smtp_port']    = $smtp_port;
		$config['smtp_timeout'] = '7';
		$config['smtp_user']    = $isp_email;
		$config['smtp_pass']    = $isp_password;
		$config['charset']    	= 'utf-8';
		$config['newline']    	= "\r\n";
		$config['mailtype'] 		= 'html'; // or html
		$config['validation'] 	= TRUE; // bool whether to validate email or not

		$this->email->initialize($config);

		$this->email->from($isp_email, $isp_name);
		$this->email->to($net_user_email);

		$this->email->subject("Monthly Invoice - #".$id_invoice);
		$this->email->message($email_message);

		$this->email->attach($newFile);

		if($this->email->send()){
			$jsonRes = array('status' => 'success', 'msg' => 'Email Sent Successfully !');
			unlink($newFile);
		}else{
			$jsonRes = array('status' => 'failed', 'msg' => 'Failed to Send Email !');
			unlink($newFile);
		}
		echo json_encode($jsonRes);
	}


	public function send_sms(){

		$id_invoice    = $this->uri->segment(3);
		$invoices      = $this->billing_model->get_individual_invoice_info($id_invoice);
		$net_user_info = $this->email_model->get_net_user_info_by_invoice_id($id_invoice);
		$clientcode    = $this->settings_model->get_by_key('sms_client_code');

		foreach ($invoices as $invoice) {
			$id_invoice=$invoice['id_invoice'];
			$invoice_original_amount=$invoice['invoice_original_amount'];
			$invoice_amount=$invoice['invoice_amount'];
			$invoice_date=$invoice['invoice_date'];
			$email=$invoice['net_user_email'];
			$phone=$invoice['net_user_phone'];
			$payable=$invoice['payable'];
			$due=$invoice['due'];
			$net_user_address=$invoice['net_user_address'];
			$rec_flag=$invoice['rec_flag'];
			$description=$invoice['description'];
		}

		foreach($net_user_info as $customer_indiv):
		$net_user_name=$customer_indiv['net_user_name'];
		$net_user_email=$customer_indiv['net_user_email'];
		endforeach;

		$sms_message=$this->settings_model->get_sms_template_message();
		$sms_message = str_replace("%CUSTOMER_NAME%",$net_user_name,$sms_message);
		$sms_message = str_replace("%BILL%",$invoice_original_amount,$sms_message);
		$sms_message = str_replace("%INVOICE_NUMBER%",$id_invoice,$sms_message);


		$url = "http://sms.nibserver.com/api/nibsms/add_sms";

		$data = array(
			'clientcode' => $clientcode,
			'phone' => $phone,
			'msg' => $sms_message
		);
		$res=$this->curl->simple_get($url, $data);
		echo $res;
	}



}
