<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Syslog extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('syslog_model');
		$this->load->helper('permission_helper');
	}

  public function index()	{
		$dir = '/log';
		$all_file_folders = $this->syslog_model->getFolder($dir);
		$data['dic']=$all_file_folders;
		$data['router_name']=$this->syslog_model->getRouterName($all_file_folders);
		$this->session->set_userdata('syslog_path', $dir);
		$data['path'] = $this->session->userdata('syslog_path');
		$data['first_page']=1;
		$data['page_heading']="Syslog";
		error_log("viewing syslog_view file.");
    $this->load->view('syslog_view', $data);
  }

	public function open_folder() {
		$data['page_heading']="Syslog";
		if(isset($_REQUEST["folder"])) {
			$folder_name=$_REQUEST["folder"];
			error_log("*** FOLDER ***".$folder_name);
			if($folder_name === "/log"){
				error_log("*** FOLDER LOG ONLY: ***".$folder_name);
				redirect('/syslog');
			}
		}

		$this->session->set_userdata('syslog_path',$folder_name);
		$folder_path=$this->session->userdata('syslog_path');
		$this->session->set_userdata('syslog_path_previous',substr($folder_path, 0,strrpos($folder_path, '/')));
		error_log("Path: ".$this->session->userdata('syslog_path'));
		error_log("Path-Previous: ".$this->session->userdata('syslog_path_previous'));
		$data['dic']=$this->syslog_model->getFolder($folder_path);
		$data['path']=$this->session->userdata('syslog_path');
		$data['first_page']=0;
		$this->load->view('syslog_view',$data);
	}

	public function download_file()	{
		error_log("download_file");
		if(isset($_GET["file"])) {
			$file_name=$_GET["file"];
			error_log("*** FILE ***".$file_name);
		}

		$file_path=$this->session->userdata('syslog_path').'/'.$file_name;

		error_log("### DOWNLOAD PATH### ".$file_path);

		if (file_exists($file_path)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file_path));
		    readfile($file_path);
		    exit;
		}
		else {
			error_log("File dont exists");
		}
	}

}
