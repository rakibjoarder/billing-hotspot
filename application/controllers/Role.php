<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {

	public function __construct(){
		parent::__construct();
		 $this->load->model('role_model');
		 $this->load->model('login_model');
		 $this->load->helper('permission_helper');
	}

  public function index(){
    $data['roles']=$this->role_model->get_all_roles();
		$data['page_heading']="Role";
		$this->load->view('role_view',$data);
	}

  function create_role(){
		$data['page_heading']="Role";
		$data['modules']=$this->role_model->get_all_modules();
    $this->load->view('create_role',$data);
  }

  function create_role_now(){
    $this->role_model->create_role_now();
  }

	function edit_role(){
		$id_role = $this->uri->segment(3);
		$data['modules']=$this->role_model->get_all_modules();
		$data['role_info']=$this->role_model->get_individual_role_info($id_role);
		$data['page_heading']="Role";
		$this->load->view('edit_role',$data);
	}

	function edit_role_now(){
		$this->role_model->edit_role_now();
	}

	function delete_role_now(){
		$this->role_model->delete_role_now();
	}

	function grant_permission(){
		$data['id_role']= $this->uri->segment(3);
		$data['page_heading']="Permission";
		$this->load->view('role_module_permission',$data);
	}

	function get_ind_module_operations(){
		$this->role_model->get_ind_module_operations();
	}

	function add_permission_now(){
		$jsonRes=$this->role_model->add_permission_now();
		$permission_string = $this->login_model->get_permission_string($this->session->userdata('id_role'));
		$this->session->set_userdata(array('permission_string'=>$permission_string));
		echo json_encode($jsonRes);
	}

}
