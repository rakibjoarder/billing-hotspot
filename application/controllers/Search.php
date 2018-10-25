<?php
// header('Content-type: text/html; charset=utf-8');
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('router_model');
		$this->load->model('search_model');
		$this->load->helper('permission_helper');
	}

	public function index()	{
		$data['page_heading']="Search";
		// $data['routers_all']=$this->router_model->get_all_routers();
		$data['routers_all']=$this->router_model->get_allowed_routers($this->session->userdata('user_id'));
		$this->load->view('search',$data);
	}


	public function incremental_search() {

		$tables = array();

		$results = $this->search_model->get_all_trace_table();

		foreach($results as $row) {
			if(strpos($row, 'trace_') === 0){
				array_push($tables, $row);
			}else{
				error_log("Not a trace table. ". $row);
			}
		}

		if(trim($this->input->post('start_date_time')) != "" || trim($this->input->post('end_date_time')) != "") {
			sort($tables);
		} else {
			rsort($tables);
		}

		$tmp = implode('|',$tables);
		error_log("tables ". $tmp);

		$limit = $this->search_model->get_by_key('query_limit');

		// TODO: filter tables.
		// $limit_per_table = intval($limit / count($tables));
		$limit_per_table = 100;

		if($limit < $limit_per_table) {
			$limit_per_table = $limit;
		}

		$count = 0;

		foreach($tables as $table) {
			while($count < $limit) {
				$k = $this->search_model->progress_search_result($table, $count, $limit_per_table);
				$count = $count + $k;
				if($k == 0) {
					break;
				}
			}
			if($count >= $limit) {
				break;
			}
		}

	}


}
