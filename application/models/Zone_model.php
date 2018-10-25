<?php
class Zone_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function get_all_zones(){
    $this->db->select('*');
    $this->db->from('zone');
    $this->db->where(array('id_isp' => $this->session->userdata('id_isp')));
    return $this->db->get()->result_array();
  }

  function get_individual_zone_info($id_zone){
    $this->db->select('*');
    $this->db->from('zone');
    $this->db->where('id_zone',$id_zone);
    return $this->db->get()->result_array();
  }

  function create_zone_now(){
    $zone_name= $this->input->post('zone_name');

    $this->db->select('*');
    $this->db->from('zone');
    $this->db->where('zone_name',$zone_name);
    $this->db->get();

    if($this->db->affected_rows() == 0){
    $this->db->insert('zone', array('zone_name'=>$zone_name,'id_isp' => $this->session->userdata('id_isp')));
    if($this->db->affected_rows()>0){
      // allowing zone who has created it.
      $insert_id = $this->db->insert_id();
      $this->allow_zone_access($this->session->userdata('user_id'), $insert_id);
      $jsonRes = array('status' => 'success', 'msg' => 'Zone Created Successfully !');

      $this->db->insert('notification', array(
        'notification_title' => "New Zone",
        'notification_body' => $zone_name." added by ".$this->session->userdata('name'),
        'notification_type'  => "message",
        'id_isp'             => $this->session->userdata('id_isp')
      ));

    }
    else{
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed !');
    }
  }else{
    $jsonRes = array('status' => 'failed', 'msg' => 'Zone with this name already exists !');
  }
    echo json_encode($jsonRes);
  }

  function edit_zone_now(){
    $id_zone= $this->input->post('id_zone');
    $zone_name= $this->input->post('zone_name');
    $prv_zone_name        = $this->get_zone_name_by_id($id_zone);
    $this->db->update('zone', array('zone_name'=>$zone_name), array('id_zone'=>$id_zone,'id_isp' => $this->session->userdata('id_isp')));

    if($this->db->affected_rows()>0){
      $jsonRes = array('status' => 'success', 'msg' => 'Zone Edited Successfully !');

      $this->db->insert('notification', array(
        'notification_title' => "Zone Edit",
        'notification_body' => "Zone Name " .$prv_zone_name." edited into ".$zone_name." by ".$this->session->userdata('name'),
        'notification_type'  => "message",
        'id_isp'             => $this->session->userdata('id_isp')
      ));

    }
    else{
      $jsonRes = array('status' => 'failed', 'msg' => 'No change !');
    }
    echo json_encode($jsonRes);
  }

  function get_zone_name_by_id($id_zone){

      $this->db->select('*');
      $this->db->from('zone');
      $this->db->where(array('id_zone' => $id_zone));
      return $this->db->get()->row()->zone_name;
  }


  function  delete_zone_now(){
    $id_zone          = $this->input->post('id_zone');
    $id_default_zone  = $this->settings_model->get_by_key('default_zone');
    $zone_name        = $this->get_zone_name_by_id($id_zone);

    if(!empty($id_zone) && $id_default_zone != $id_zone){
      $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Zone !', 'data' => '');
      $this->db->delete('zone', array('id_zone' => $id_zone,'id_isp' => $this->session->userdata('id_isp')));

      if($this->db->affected_rows() > 0){
        // removing zone access for all.
        $this->remove_zone_access_for_all($id_zone);
        $jsonRes['data'] = $this->get_allowed_zones($this->session->userdata('user_id'));

        $this->db->insert('notification', array(
          'notification_title' => "Zone Delete",
          'notification_body' => $zone_name." deleted by ".$this->session->userdata('name'),
          'notification_type'  => "message",
          'id_isp'             => $this->session->userdata('id_isp')
        ));

      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Delete opertaion failed');
      }
    }else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Deletion failed, Can not delete default zone ,Please change default zone first !!');
    }
    echo json_encode($jsonRes);
  }

  public function allow_zone_access($user_id, $zone_id) {
    $info = array('id_zone' => $zone_id, 'id_user'=> $user_id);
    $this->db->insert('zone_access', $info);
    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'passed', 'msg' => 'Zone Allowed Successfully!');
    } else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Zone Failed To Allow!');
    }

    return $jsonRes;
  }

  public function remove_zone_access($user_id, $zone_id) {
    $info = array('id_zone' => $zone_id, 'id_user'=> $user_id);
    $this->db->delete('zone_access', $info);
    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'passed', 'msg' => 'Zone Removed Successfully!');
    } else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Zone Failed To Remove!');
    }

    return $jsonRes;
  }

  public function remove_zone_access_for_all($zone_id) {
    $info = array('id_zone' => $zone_id);
    $this->db->delete('zone_access', $info);
    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'passed', 'msg' => 'Zone Removed Successfully!');
    } else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Zone Failed To Remove!');
    }

    return $jsonRes;
  }


  public function get_allowed_zones($user_id) {
    error_log("user id is [". $user_id ."]");

    $this->db->select('*');
    $this->db->from('zone_access');
    $this->db->join('zone', 'zone.id_zone = zone_access.id_zone', 'left');
    $this->db->where(array('zone_access.id_user' => $user_id, 'id_isp' => $this->session->userdata('id_isp')));
    return $this->db->get()->result_array();
  }



}

?>
