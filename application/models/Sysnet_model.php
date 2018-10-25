<?php
class Sysnet_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  public function reboot_sys_now() {
    $password = $this->input->post('password');
    $this->db->select('*');
    $this->db->from('settings');
    $this->db->where(array('key'=>'reboot_pass', 'value'=>$password));

    $query = $this->db->get();

    if($this->db->affected_rows() > 0) {
      error_log("SUDO REBOOT!");
      $cmd = "sudo reboot";
      $output = system($cmd);
      $jsonRes = array('status' => 'passed', 'msg' => 'Reboot Successful!');
    }
    else {
      error_log("REBOOT DENIED !");
      $jsonRes = array('status' => 'failed', 'msg' => 'Reboot Denied!');
    }
    echo json_encode($jsonRes);
  }


  public function shut_down_sys_now() {
    $password = $this->input->post('password');
    $this->db->select('*');
    $this->db->from('settings');
    $this->db->where(array('key'=>'reboot_pass', 'value'=>$password));

    $query = $this->db->get();

    if($this->db->affected_rows() > 0) {
      error_log("SUDO SHUT DOWN!");
      $cmd = "sudo shutdown";
      $output = system($cmd);
      $jsonRes = array('status' => 'passed', 'msg' => 'Shut Down Successful!');
    }
    else {
      error_log("SHUT DOWN DENIED !");
      $jsonRes = array('status' => 'failed', 'msg' => 'Shut Down Denied!');
    }
    echo json_encode($jsonRes);

  }

  public function radius_restart_now() {
    $password = $this->input->post('password');
    $this->db->select('*');
    $this->db->from('settings');
    $this->db->where(array('key'=>'reboot_pass', 'value'=>$password));

    $query = $this->db->get();

    if($this->db->affected_rows() > 0) {
      error_log("Radius Restart!");
      $cmd = "sudo systemctl restart radiusd";
      $output = system($cmd);
      $jsonRes = array('status' => 'passed', 'msg' => 'Radius Restart Successful!');
    }
    else {
      error_log("Radius Restart DENIED !");
      $jsonRes = array('status' => 'failed', 'msg' => 'Radius Restart Denied!');
    }
    echo json_encode($jsonRes);
  }

}

?>
