<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotspot extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('hotspot_model');
		$this->load->model('settings_model');
	}

	function add_hotspot_user(){
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Credentials: true");
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		header('Access-Control-Max-Age: 1000');
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
		$phone = $this->input->get('phone');

		if(strlen($phone)==11){
			$rand_num = rand(pow(10, 4-1), pow(10, 4)-1);;
			$result=$this->hotspot_model->add_hotspot_user_now($phone,$rand_num);
			echo $result;
		}else{
			echo "Invalid Phone Number";
		}
	}

	function verify_password(){
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Credentials: true");
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		header('Access-Control-Max-Age: 1000');
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
		$password = $this->input->get('password');
		$phone = $this->input->get('phone');
		$result=$this->hotspot_model->verify_user_now($password,$phone);
		echo $result;
	}

	function resend_password(){
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Credentials: true");
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		header('Access-Control-Max-Age: 1000');
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
		$phone = $this->input->get('phone');
		$result=$this->hotspot_model->resend_password($phone);
		echo $result;
	}
}
?>
