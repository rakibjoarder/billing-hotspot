<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sysnet extends CI_Controller {

	public function __construct()	{
		parent::__construct();
		$this->load->helper('permission_helper');
		$this->load->model('sysnet_model');
		if(!$this->session->userdata('language')) {
			$this->session->set_userdata(array(
				'language' => 'english'
			));
		}

		$idiom = $this->session->userdata('language');
		$this->lang->load(array('header', 'settings'), $idiom);

		$this->load->helper('file');
		$this->ipfile = APPPATH . '../../sysconf/ipaddr';
		define("PHP_EOF", "\n");
	}

	public function index()	{
		$data = array();

		$str = read_file($this->ipfile);

		error_log("Read file". $str);

		$tmp = explode(PHP_EOF, $str);

		foreach ($tmp as $line) {
			$token = explode("=", $line);

			if(sizeof($token) == 2) {
				if(trim($token[0]) === 'IPADDR') {
					$data['ipaddr'] = $token[1];
				} elseif(trim($token[0]) === 'NETMASK') {
					$data['netmask'] = $token[1];
				} elseif(trim($token[0]) === 'GATEWAY') {
					$data['gateway'] = $token[1];
				} elseif(trim($token[0]) === 'DNS1') {
					$data['dns1'] = $token[1];
				} elseif(trim($token[0]) === 'DNS2') {
					$data['dns2'] = $token[1];
				}
			}
		}

		$data['page_heading']="System";
		$this->load->view('ipsettings_view', $data);
	}

	public function add_ip_settings_now()	{
		$data = "";
		$data .= "IPADDR=";
		$data .= $this->input->post('ip_address') . PHP_EOF;
		$data .= "NETMASK=";
		$data .= $this->input->post('net_mask') . PHP_EOF;
		$data .= "GATEWAY=";
		$data .= $this->input->post('gateway_ip') . PHP_EOF;
		$data .= "DNS1=";
		$data .= $this->input->post('dns_1') . PHP_EOF;
		$data .= "DNS2=";
		$data .= $this->input->post('dns_2') . PHP_EOF;

		error_log('Data '. $data);

		$flag=write_file($this->ipfile, $data);
		$cmd = "sudo syschg";
		$output = system($cmd);

	  if($flag) {
			$jsonRes = array('status' => 'success', 'msg' => 'Network Setting Updated Successfully !');
		}

		echo json_encode($jsonRes);
	}

	public function confirm_reboot() {
		$data['page_heading']="System";
		$this->load->view('confirm_reboot_view',$data);
	}

	public function confirm_shut_down()	{
		$data['page_heading']="System";
		$this->load->view('confirm_shut_down_view',$data);
	}

  public function confirm_radius_restart() {
		$data['page_heading']="System";
		$this->load->view('confirm_radius_restart_view', $data);
	}

	function reboot_sys_now() {
		$this->sysnet_model->reboot_sys_now();
	}

	public function shut_down_sys_now() {
		$this->sysnet_model->shut_down_sys_now();
	}

  public function radius_restart_now() {
    $this->sysnet_model->radius_restart_now();
  }
}
