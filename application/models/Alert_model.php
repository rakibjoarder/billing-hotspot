<?php
class Alert_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_users(){
      $this->db->select('*');
      $this->db->from('users');
      return $this->db->get()->result_array();
    }

    function get_all_selected_users(){
      $this->db->select('alert_layers_users.id_alert_layers,alert_layers_users.id_user');
      $this->db->from('alert_layers_users');
      $this->db->join('alert_layers', 'alert_layers.id_alert_layers = alert_layers_users.id_alert_layers', 'left');
      $this->db->join('alert', 'alert.id_alert = alert_layers.id_alert', 'left');
      return $this->db->get()->result_array();
    }

    function get_all_alerts(){
      $this->db->select('*');
      $this->db->from('alert');
      $this->db->join('alert_type', 'alert_type.id_alert_type=alert.id_alert_type', 'left');
      $this->db->where(array('id_isp' => $this->session->userdata('id_isp')));
      return $this->db->get()->result_array();
    }

    function get_all_geneated_alerts(){
      $this->db->select('*');
      $this->db->from('system_alert');
      return $this->db->get()->result_array();
    }



    function get_alert_info($alert_id){
      $this->db->select('*');
      $this->db->from('alert');
      $this->db->join('alert_type', 'alert_type.id_alert_type=alert.id_alert_type', 'left');
      $this->db->where(array('id_alert' => $alert_id));
      return $this->db->get()->result_array();
    }

    function get_all_alert_types(){
      $this->db->select('*');
      $this->db->from('alert_type');
      return $this->db->get()->result_array();
    }
    function get_indv_alert_layers($id_alert){
      $this->db->select('*'); $this->db->from('alert_layers');
      $this->db->join('alert', 'alert_layers.id_alert=alert.id_alert', 'left');
      $this->db->where(array('alert_layers.id_alert' => $id_alert));
      $this->db->order_by("alert_layers.alert_layers_priority", "desc");
      return $this->db->get()->result_array(); }


    function get_alert_layer_info($id_alert_layers){
      $this->db->select('*');
      $this->db->from('alert_layers');
      $this->db->where(array('id_alert_layers' => $id_alert_layers));
      return $this->db->get()->result_array();
    }

    function get_alert_layers_users_info($id_alert_layers){
      $this->db->select('*');
      $this->db->from('alert_layers_users');
      $this->db->join('alert_layers', 'alert_layers.id_alert_layers = alert_layers_users.id_alert_layers', 'left');
      $this->db->join('users', 'users.id = alert_layers_users.id_user', 'left');
      $this->db->where(array('alert_layers_users.id_alert_layers' => $id_alert_layers));
      return $this->db->get()->result_array();
    }

    function get_alert_layer_user_info($id_alert_layers_users){
      $this->db->select('*');
      $this->db->from('alert_layers_users');
      $this->db->where(array('alert_layers_users.id_alert_layers_users' => $id_alert_layers_users));
      return $this->db->get()->result_array();
    }

    function create_alert_now(){
      $alert_name = $this->input->post('alert_name');
      $id_alert_type = $this->input->post('id_alert_type');
      $alert_time_interval = $this->input->post('alert_time_interval');
      $this->db->insert('alert', array('alert_name' => $alert_name,
        'id_alert_type' => $id_alert_type,
        'alert_time_interval'  => $alert_time_interval,
        'id_isp' => $this->session->userdata('id_isp')
      ));
      if($this->db->affected_rows()>0){
        $jsonRes = array('status' => 'success', 'msg' => 'Succesfully created the alert !');
      }
      else{
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to create the alert !');
      }
      echo json_encode($jsonRes);
    }

    function delete_alert_now(){
      $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Alert!', 'data' => '');

      $alert_id = $this->input->post('alert_id');
      if(!empty($alert_id)){
        $this->db->delete('alert', array('id_alert'=>$alert_id));
        if($this->db->affected_rows()>0){
          $jsonRes['data'] = $this->get_all_alerts();
        }else{
          $jsonRes = array('status' => 'failed', 'msg' => 'Delete opertaion failed');
        }
      }
      echo json_encode($jsonRes);

    }


    function stop_alert_now(){
      $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Stopped !', 'data' => '');

        $id_system_alert = $this->input->post('id_system_alert');
        $date        = new DateTime("now");
        $curr_date   = $date->format('Y-m-d');

        $this->db->update('system_alert',array('id_user' => $this->session->userdata('user_id'),'stopped_by' => $this->session->userdata('name'),'stop_time'=>$curr_date),array("id_system_alert" =>$id_system_alert));

        if($this->db->affected_rows()>0){
          $jsonRes['data'] = $this->get_all_geneated_alerts();
        }else{
          $jsonRes = array('status' => 'failed', 'msg' => 'Stop opertaion failed');
        }
        echo json_encode($jsonRes);
    }




    function edit_alert_now(){
      if($this->input->post('submit')) {
        $id_alert = $this->input->post('id_alert');
        $alert_name = $this->input->post('alert_name');
        $id_alert_type = $this->input->post('id_alert_type');
        $alert_time_interval = $this->input->post('alert_time_interval');

        $this->db->set(array('alert_name'=>$alert_name,'id_alert_type '=>$id_alert_type, 'alert_time_interval' => $alert_time_interval))
          ->where(array('id_alert'=>$id_alert,'id_isp' => $this->session->userdata('id_isp')))
          ->update('alert');
        if($this->db->affected_rows()>0){
          $jsonRes = array('status' => 'success', 'msg' => 'Succesfully Edited the alert ');
        }
        else{
          $jsonRes = array('status' => 'failed', 'msg' => 'No Change made!');
        }
      }
      echo json_encode($jsonRes);

    }

    function create_alert_layer_now(){
      $alert_layers_name = $this->input->post('alert_layers_name');
      $alert_layers_priority = $this->input->post('alert_layers_priority');
      $id_alert = $this->input->post('id_alert');

      $this->db->insert('alert_layers', array(
        'alert_layers_name' => $alert_layers_name,
        'alert_layers_priority' => $alert_layers_priority,
        'id_alert' => $id_alert
      ));
      if($this->db->affected_rows()>0){
        $jsonRes = array('status' => 'success', 'msg' => 'Succesfully created new layer of this alert !');
      }
      else{
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to create the alert layer !');
      }
      echo json_encode($jsonRes);
    }

    function delete_alert_layer_now(){
      $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Alert Layer!', 'data' => '');
      $id_alert_layers = $this->input->post('id_alert_layers');
      $id_alert = $this->input->post('id_alert');
      if(!empty($id_alert_layers)){
        $this->db->delete('alert_layers', array('id_alert_layers'=>$id_alert_layers));
        if($this->db->affected_rows() > 0){
          $jsonRes['data'] = $this->get_indv_alert_layers($id_alert);
        } else {
          $jsonRes = array('status' => 'failed', 'msg' => 'Delete opertaion failed');

        }
      }
      echo json_encode($jsonRes);

    }

    function edit_alert_layer_now(){
      $alert_layers_name = $this->input->post('alert_layers_name');
      $alert_layers_priority = $this->input->post('alert_layers_priority');
      $id_alert_layers = $this->input->post('id_alert_layers');
      error_log("id_alert_layers:".$id_alert_layers."--alert_layers_name:".$alert_layers_name."--alert_layers_priority:".$alert_layers_priority);

      $this->db->set(array('alert_layers_name'=>$alert_layers_name,'alert_layers_priority' => $alert_layers_priority))
        ->where('id_alert_layers',$id_alert_layers)
        ->update('alert_layers');

      if($this->db->affected_rows()>0){
        $jsonRes = array('status' => 'success', 'msg' => 'Succesfully Edited !');
      }
      else{
        $jsonRes = array('status' => 'failed', 'msg' => 'No Change !');
      }
      echo json_encode($jsonRes);
    }

    function change_alerts_layers_users(){
      $id_alert_layers = $this->input->post('id_alert_layers');
      $id_user = $this->input->post('id_user');
      $check_state = $this->input->post('check_state');
      if(!empty($id_alert_layers) && !empty($id_user)){
        //Add new user to this layer
        if($check_state==1){
          $this->db->select('*');
          $this->db->from('alert_layers_users');
          $this->db->where(array('id_alert_layers' => $id_alert_layers , 'id_user' => $id_user));
          $this->db->get();
          error_log("Aff-Rows:".$this->db->affected_rows());
          if($this->db->affected_rows() == 0){
            $this->db->insert('alert_layers_users', array(
              'id_user' => $id_user,
              'id_alert_layers' => $id_alert_layers
            ));
            if($this->db->affected_rows()>0){
              $jsonRes = array('status' => 'success', 'msg' => 'Succesfully Added the user !');
            }
            else{
              $jsonRes = array('status' => 'error', 'msg' => 'Failed to add the user !');
            }
          }
          else{
            $jsonRes = array('status' => 'error', 'msg' => 'User Already added to this layer !');
          }
        }
        //Remove Existing user from this layer
        else{
          $this->db->delete('alert_layers_users', array('id_alert_layers'=>$id_alert_layers , 'id_user'=>$id_user));
          if($this->db->affected_rows()>0){
            $jsonRes = array('status' => 'success', 'msg' => 'User Removed from this Layer !');
          }
          else {
            $jsonRes = array('status' => 'error', 'msg' => 'Failed to Delete !');
          }
        }
      }
      echo json_encode($jsonRes);
    }

    function create_alerts_layers_user_now(){
      $id_alert_layers = $this->input->post('id_alert_layers');
      $id_user = $this->input->post('id_user');

      $this->db->select('*');
      $this->db->from('alert_layers_users');
      $this->db->where(array('id_alert_layers' => $id_alert_layers , 'id_user' => $id_user));
      $this->db->get();
      error_log("Aff-Rows:".$this->db->affected_rows());
      if($this->db->affected_rows() == 0){
        $this->db->insert('alert_layers_users', array(
          'id_user' => $id_user,
          'id_alert_layers' => $id_alert_layers
        ));
        if($this->db->affected_rows()>0){
          echo '<span class="success">Succesfully Added the user !</span>';
        }
        else{
          echo '<span class="error">Failed to add the user !</span>';
        }
      }
      else{
        echo '<span class="error">User Already added to this layer !</span>';
      }
    }

    function delete_alert_layer_user_now(){
      $id_alert_layers = $this->input->post('id_alert_layers');
      $id_user = $this->input->post('id_user');
      if(!empty($id_alert_layers) && !empty($id_user)){
        $this->db->delete('alert_layers_users', array('id_alert_layers'=>$id_alert_layers , 'id_user'=>$id_user));
        if($this->db->affected_rows()>0){
          echo '<span class="success">Successfully deleted !</span>';
        }
        else {
          echo '<span class="error">Failed to Delete !</span>';
        }
      }
    }

    function edit_alerts_layers_user_now(){
      $id_alert_layers = $this->input->post('id_alert_layers');
      $id_alert_layers_users = $this->input->post('id_alert_layers_users');
      $id_user = $this->input->post('id_user');

      $this->db->select('*');
      $this->db->from('alert_layers_users');
      $this->db->where(array('id_alert_layers' => $id_alert_layers , 'id_user' => $id_user));
      $this->db->get();
      if($this->db->affected_rows() == 0){
        $this->db->set(array('id_user' => $id_user))
          ->where('id_alert_layers_users',$id_alert_layers_users)
          ->update('alert_layers_users');

        if($this->db->affected_rows()>0){
          echo '<span class="success">Succesfully Edited !</span>';
        }
        else{
          echo '<span class="error">No Change !</span>';
        }
      }
      else{
        echo '<span class="error">User Already added to this layer !</span>';
      }
    }




  }
  ?>
