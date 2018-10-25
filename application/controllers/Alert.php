<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alert extends CI_Controller {

	public function __construct() {
		parent::__construct();
		 $this->load->model('alert_model');
		 $this->load->helper('permission_helper');
	}
  public function index()	{
    $data['alerts'] = $this->alert_model->get_all_geneated_alerts();
		$data['page_heading']="Alert";
    $this->load->view('alert', $data);
  }
  function create_alert(){
    $data['alert_types']=$this->alert_model->get_all_alert_types();
		$data['page_heading']="Alert";
    $this->load->view('create_alert',$data);
  }
  function create_alert_now(){
    $this->alert_model->create_alert_now();
  }
  function delete_alert_now(){
    $this->alert_model->delete_alert_now();
  }
  function edit_alert(){
    $alert_id = $this->uri->segment(3);
    $data['alert_types']=$this->alert_model->get_all_alert_types();
    $data['alerts_all']=$this->alert_model->get_alert_info($alert_id);
		$data['page_heading']="Alert";
    $this->load->view('edit_alert', $data);
  }
  function edit_alert_now(){
    $this->alert_model->edit_alert_now();
  }
	function alert_layers(){
		$id_alert = $this->uri->segment(3);
		$data['users']=$this->alert_model->get_all_users();
		$data['selected_users']=$this->alert_model->get_all_selected_users();
		$data['alert_info']=$this->alert_model->get_alert_info($id_alert);
		$data['alert_layers_all']=$this->alert_model->get_indv_alert_layers($id_alert);
		$data['page_heading']="Alert";
		$this->load->view('alert_layers', $data);
	}
	function create_alerts_layer(){
		 $id_alert = $this->uri->segment(3);
		 $data['alert_info']=$this->alert_model->get_alert_info($id_alert);
		 $data['page_heading']="Alert";
		 $this->load->view('create_alerts_layer', $data);
	}
	function create_alert_layer_now(){
		$this->alert_model->create_alert_layer_now();
	}
	function delete_alert_layer_now(){
		$this->alert_model->delete_alert_layer_now();
	}
	function edit_alert_layer(){
		$id_alert = $this->uri->segment(3);
		$id_alert_layers = $this->uri->segment(4);
		$data['alert_info']=$this->alert_model->get_alert_info($id_alert);
		$data['alerts_layers_info']=$this->alert_model->get_alert_layer_info($id_alert_layers);
		$data['page_heading']="Alert";
		$this->load->view('edit_alert_layer', $data);
	}
	function edit_alert_layer_now(){
		$this->alert_model->edit_alert_layer_now();
	}
	function alert_layers_users(){
		$id_alert_layers = $this->uri->segment(3);
		$data['alerts_layers_info']=$this->alert_model->get_alert_layer_info($id_alert_layers);
		$data['alert_layers_users_all']=$this->alert_model->get_alert_layers_users_info($id_alert_layers);
		$data['page_heading']="Alert";
		$this->load->view('alert_layers_users', $data);
	}
	function create_alerts_layers_user(){
		$id_alert_layers = $this->uri->segment(3);
		$data['users']=$this->alert_model->get_all_users();
		$data['alerts_layers_info']=$this->alert_model->get_alert_layer_info($id_alert_layers);
		$data['page_heading']="Alert";
		$this->load->view('create_alerts_layers_user', $data);
	}
	function change_alerts_layers_users(){
		$this->alert_model->change_alerts_layers_users();
	}
	// function create_alerts_layers_user_now(){
	// 	$this->alert_model->create_alerts_layers_user_now();
	// }
	// function delete_alert_layer_user_now(){
	// 	$this->alert_model->delete_alert_layer_user_now();
	// }
	function edit_alert_layer_user(){
		$id_alert_layers = $this->uri->segment(3);
		$id_alert_layers_users = $this->uri->segment(4);
		$data['users']=$this->alert_model->get_all_users();
		$data['alerts_layers_user']=$this->alert_model->get_alert_layer_user_info($id_alert_layers_users);
		$data['alerts_layers_info']=$this->alert_model->get_alert_layer_info($id_alert_layers);
		$data['page_heading']="Alert";
		$this->load->view('edit_alert_layer_user', $data);
	}
	function edit_alerts_layers_user_now(){
		$this->alert_model->edit_alerts_layers_user_now();
	}

	public function configure_alert()	{
    $data['alerts'] = $this->alert_model->get_all_alerts();
		$data['page_heading']="Alert";
    $this->load->view('configure_alert', $data);
  }

	function stop_alert_now(){
		$this->alert_model->stop_alert_now();
	}


}
?>
