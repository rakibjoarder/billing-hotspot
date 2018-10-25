<?php
class Login_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }


  function default_module($id_role){
    $this->db->select('module.default_module_name');
    $this->db->from('module');
    $this->db->join('role','module.id_module=role.id_module', 'left');
    $this->db->where("id_role = '$id_role' ");
    $query = $this->db->get();
    $ret = $query->row();
    $default_module=$ret->default_module_name;
    return $default_module;
  }

  function verify_user() {
    $email_phone = trim($this->input->post('username'));
    $password = $this->input->post('password');

    error_log("Email/Phone:".$email_phone."--Password:".$password);
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where("(email = '$email_phone' or phone = '$email_phone') and password = '$password'");
    $query = $this->db->get();
    $user_id ="";

    if($this->db->affected_rows() > 0) {

      foreach($query->result_array() as $user_indiv) {
        $user_id=$user_indiv['id'];
        $username=$user_indiv['username'];
        $name=$user_indiv['name'];
        $id_role=$user_indiv['id_role'];
        $id_isp =$user_indiv['id_isp'];
      }

      $balance = $this->get_isp_balance($id_isp);

      if($balance > 0){
        $this->set_isp_information($id_isp);
        $this->session->set_userdata(array('user_id'=>$user_id,'name'=>$name,'id_role'=>$id_role,'username'=>$username));
        return 1;
      }else{
        return 2;
      }
    }
    return 0;
  }

  public function get_isp_balance($id_isp){
    $this->db->select('*');
    $this->db->from('isp');
    $this->db->where(array('id_isp' => $id_isp));
    $query = $this->db->get();

    if($this->db->affected_rows() > 0) {
      foreach($query->result_array() as $indiv) {
        $balance = $indiv['balance'];
      }
      return $balance;
    }
    return 0;
  }

  public function set_isp_information($id_isp){
    $this->db->select('*');
    $this->db->from('isp');
    $this->db->where(array('id_isp' => $id_isp));
    $query = $this->db->get();

    if($this->db->affected_rows() > 0) {
      foreach($query->result_array() as $indiv) {
        $isp_name = $indiv['isp_name'];
      }
      $this->session->set_userdata(array('id_isp'=>$id_isp,'isp_name'=>$isp_name));
    }
  }

  function get_permission_string($id_role){
    $this->db->select('*');
    $this->db->from('role_operation');
    $this->db->join('module','module.id_module=role_operation.id_module', 'left');
    $this->db->join('operation','operation.id_operation=role_operation.id_operation', 'left');
    $this->db->where(array('id_role' => $id_role,'is_active' => 1));
    $this->db->order_by("role_operation.id_module", "asc");
    $results= $this->db->get()->result_array();
    $flag=0;
    $module_prev='';
    $permission_string='';
    foreach ($results as $info) {
      //New Module
      if($module_prev != $info['module_code']){
        $permission_string=$permission_string."-".$info['module_code'].":".$info['operation_name'];
      }
      // Existing Module
      else{
        $permission_string=$permission_string.",".$info['operation_name'];
      }

      $module_prev=$info['module_code'];
    }
    //error_log("***permission String:".$permission_string);

    return $permission_string;
  }


}

?>
