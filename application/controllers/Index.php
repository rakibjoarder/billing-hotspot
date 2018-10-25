<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('index_model');
		$routers_all= $this->index_model->get_routers_all();
	  $this->data = array(
      'routers_all' => $routers_all
    );

		 //$data['routers_all']=$this->index_model->get_routers_all();
	}

	public function index()	{
		$this->load->view('search');
	}

	public function search()	{
		$data = $this->data;
		$this->load->view('search',$data);
	}

	public function dashboard()	{
		$this->load->view('dashboard');
	}

	public function verify_user()	{
		$this->load->view('welcome_message');
	}

	public function logout() {
		$this->load->view('login_view');
	}

	public function fetch_info(){
		$this->index_model->fetch_info();
	}

	public function fetch_status_all(){
		$this->index_model->fetch_status_all();
	}
}
