<?php
class Registration_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  public function isp_registration_now(){

    $isp_name= $this->input->post('isp_name');
    $address= $this->input->post('address');
    $name= $this->input->post('name');
    $username= $this->input->post('username');
    $email= $this->input->post('email');
    $phone_number= $this->input->post('phone_number');
    $pwd= $this->input->post('pwd');

    $this->db->select('*');
    $this->db->from('users');
    $this->db->where(array('username'=>$username));
    $this->db->get();

    if($this->db->affected_rows()>0){
      $jsonRes = array('status' => 'warning', 'msg' => 'User with this user name already exist !!!');
    }
    else{
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where(array('email'=>$email));
      $this->db->get();

      if($this->db->affected_rows()>0){
        $jsonRes = array('status' => 'warning', 'msg' => 'User with this email already exist !!!');
      }
      else{
        $this->db->insert('isp_registration', array('isp_name' => $isp_name,'address'=>$address,'name'=>$name, 'username'=>$username, 'email' => $email, 'phone' => $phone_number, 'password' => $pwd));
        if($this->db->affected_rows() > 0) {
          // allowing router who has created it.
          $jsonRes = array('status' => 'success', 'msg' => 'We will contact with you within short time .');
        }else{
          $jsonRes = array('status' => 'failed', 'msg' => 'Failed !');
        }
      }
    }
    echo json_encode($jsonRes);
  }

}

?>
