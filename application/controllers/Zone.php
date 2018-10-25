<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zone extends CI_Controller {

	public function __construct(){
		parent::__construct();
		 $this->load->model('zone_model');
		 $this->load->model('settings_model');
		 $this->load->helper('permission_helper');
	}

  public function index(){
    // $data['zones']=$this->zone_model->get_all_zones();
		$data['zones'] = $this->zone_model->get_allowed_zones($this->session->userdata('user_id'));
		$data['page_heading']="Zone";
		$this->load->view('zone_view',$data);
	}

  function create_zone(){
		$data['page_heading']="Zone";
    $this->load->view('create_zone',$data);
  }

  function create_zone_now(){
    $this->zone_model->create_zone_now();
  }

	function edit_zone(){
		$id_zone = $this->uri->segment(3);
		$data['zone_info']=$this->zone_model->get_individual_zone_info($id_zone);
		$data['page_heading']="Zone";
		$this->load->view('edit_zone',$data);
	}

	function edit_zone_now(){
		$this->zone_model->edit_zone_now();
	}

	function delete_zone_now(){
		$this->zone_model->delete_zone_now();
	}

}
