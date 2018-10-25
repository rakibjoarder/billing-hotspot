<?php
class Role_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function get_all_roles(){
    $this->db->select('*');
    $this->db->from('role');
    $this->db->where(array('id_isp' => $this->session->userdata('id_isp')));
    return $this->db->get()->result_array();
  }

  function get_individual_role_info($id_role){
    $this->db->select('*');
    $this->db->from('role');
    $this->db->where('id_role',$id_role);
    return $this->db->get()->result_array();
  }

  function get_ind_module_operations(){
  $id_role= $this->input->post('id_role');
  $jsonRes = array(
    'all_modules' => '',
    'indv_role_operations' => '',
    'all_operations' => ''
  );

  error_log("ID-ROLE:".$id_role);

  $this->db->select('module.id_module,module.module_name,module.module_code');
  $this->db->from('module');
  $this->db->where(array('is_active' => 1));
  $this->db->order_by("module.sorting_index", "asc");
  $jsonRes["all_modules"] =$this->db->get()->result_array();

  $this->db->select('*');
  $this->db->from('operation');
  $this->db->join('module_operation','operation.id_operation=module_operation.id_operation', 'left');
  $jsonRes["all_operations"] =$this->db->get()->result_array();

  $this->db->select('*');
  $this->db->from('role_operation');
  $this->db->where('id_role',$id_role);
  $jsonRes["indv_role_operations"] =$this->db->get()->result_array();


  echo json_encode($jsonRes);
}

function add_permission_now(){
  $id_role= $this->input->post('id_role');
  $id_operation= $this->input->post('id_operation');
  $id_module= $this->input->post('id_module');
  $check_state= $this->input->post('check_state');
  // error_log("id_module:".$id_module." -- id_sub_module:".$id_sub_module." check_state:".$check_state);

  // Permission ADDING
  if($check_state == 1){
    $this->db->insert('role_operation', array('id_role'=>$id_role,'id_module'=>$id_module,'id_operation'=>$id_operation));
    if($this->db->affected_rows()>0){
      $jsonRes = array('status' => 'success', 'msg' => 'Permission Added !');
    }
    else{
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed !');
    }
  }
  // Permission REMOVE
  else{
    // $this->db->where("id_role = $id_role and id_module = $id_module and id_sub_module = $id_sub_module");
    $this->db->delete('role_operation',array('id_role'=>$id_role,'id_module'=>$id_module,'id_operation'=>$id_operation));
    if($this->db->affected_rows()>0){
      $jsonRes = array('status' => 'success', 'msg' => 'Permission Removed !');
    }
    else{
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed !');
    }
  }
  return $jsonRes;

}

  function create_role_now(){
    $role_name= $this->input->post('role_name');
    $role_desc= $this->input->post('role_desc');
    $id_module= $this->input->post('id_module');

    $this->db->insert('role', array('role_name'=>$role_name, 'role_desc'=>$role_desc,'id_module'=>$id_module,'id_isp' => $this->session->userdata('id_isp')));
    if($this->db->affected_rows()>0){
      $jsonRes = array('status' => 'success', 'msg' => 'Role Created Successfully !');
    }
    else{
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed !');
    }
    echo json_encode($jsonRes);
  }

  function edit_role_now(){
    $id_role= $this->input->post('id_role');
    $role_name= $this->input->post('role_name');
    $role_desc= $this->input->post('role_desc');
    $id_module= $this->input->post('id_module');

    $this->db->update('role', array('role_name'=>$role_name, 'role_desc'=>$role_desc,'id_module'=>$id_module), array('id_role'=>$id_role,'id_isp' => $this->session->userdata('id_isp')));

    if($this->db->affected_rows()>0){
      $jsonRes = array('status' => 'success', 'msg' => 'Role Edited Successfully !');
    }
    else{
      $jsonRes = array('status' => 'failed', 'msg' => 'No change !');
    }
    echo json_encode($jsonRes);
  }


  function  delete_role_now(){

    $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Role !', 'data' => '');
    $id_role = $this->input->post('id_role');
    $id_role= trim($id_role);

    if(!empty($id_role)){
      error_log("Id-role:".$id_role);
      $this->db->delete('role', array('id_role'=>$id_role,'id_isp' => $this->session->userdata('id_isp')));
      if($this->db->affected_rows()>0){
        $jsonRes['data'] = $this->get_all_roles();
      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Delete opertaion failed');
      }
    }
    echo json_encode($jsonRes);
  }

  function get_all_modules(){
    $this->db->select('*');
    $this->db->from('module');
    $this->db->order_by("module.sorting_index", "asc");
    return $this->db->get()->result_array();
  }

}

?>
