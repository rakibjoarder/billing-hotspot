<?php
class Package_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function get_currency(){

    $currency = '';
    $this->db->select('*');
    $this->db->from('settings');
    $this->db->where('key', 'currency');
    $result = $this->db->get()->result_array();

    if($this->db->affected_rows() > 0) {
      foreach($result as $row) {
        $currency = $row['value'];
      }
    }

    return $currency;
  }

  function create_package_now() {

    if($this->input->post('submit')){
      $package_name   = $this->input->post('package_name');
      $package_speed  = $this->input->post('package_speed');
      $package_price  = $this->input->post('package_price');
      $package_type   = $this->input->post('package_type');

      $this->db->select('*');
      $this->db->from('package');
      $this->db->where(array('package_name' => $package_name,'id_isp' => $this->session->userdata('id_isp')));
      $this->db->get();

      if($this->db->affected_rows() == 0){
        $info = array(
          'package_name'  => $package_name,
          'package_speed' =>  $package_speed,
          'package_price' => $package_price,
          'package_type'  => $package_type,
          'id_isp'        => $this->session->userdata('id_isp')
        );

        $this->db->insert('package', $info);

        if($this->db->affected_rows() > 0) {
          $jsonRes = array('status' => 'success', 'msg' => 'Succesfully created package '.$package_name. '.');
          $this->db->insert('notification', array(
            'notification_title' => "New Package",
            'notification_body' =>  $package_name." has created",
            'notification_type'  => "message",
            'id_isp'             => $this->session->userdata('id_isp')
          ));
        }else {
          $jsonRes = array('status' => 'failed', 'msg' => 'Failed to create package !');
        }
      }else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Package with this name already exists !');
      }
    }

    echo json_encode($jsonRes);
  }

  function get_all_packages(){
    $this->db->select('*');
    $this->db->from('package');
    $this->db->where(array('id_isp' => $this->session->userdata('id_isp')));
    return $this->db->get()->result_array();
  }

  function get_package_info($id_package){
    $this->db->select('*');
    $this->db->from('package');
    $this->db->where(array('id_package' => $id_package));
    return $this->db->get()->result_array();
  }

  function get_all_free_ip_pools(){
    $sql='SELECT * FROM `ip_pool` WHERE id_ip_pool NOT IN( SELECT id_ip_pool FROM package)';
    error_log("SQL::".$sql);
    $query = $this->db->query($sql);
    $query_result=$query->result_array();
    return $query_result;
  }

  function get_all_free_ip_pools_except_own($id_package){
    $sql='SELECT * FROM `ip_pool` WHERE id_ip_pool NOT IN( SELECT id_ip_pool FROM package WHERE id_package!='.$id_package.')';
    error_log("SQL::".$sql);
    $query = $this->db->query($sql);
    $query_result=$query->result_array();
    return $query_result;
  }

  function delete_package_now(){
    $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Package!', 'data' => '');
    $package_id = $this->input->post('package_id');

    if(!empty($package_id)){
      $this->db->delete('package', array('id_package'=>$package_id));
      if($this->db->affected_rows()>0){
        $jsonRes['data'] = $this->get_all_packages();
      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to delete package !');
      }
    }
    echo json_encode($jsonRes);
  }

  function edit_package_now(){
    if($this->input->post('submit')) {
      $id_package      = $this->input->post('id_package');
      $package_name    = $this->input->post('package_name');
      $package_speed   = $this->input->post('package_speed');
      $package_price   = $this->input->post('package_price');
      $package_type    = $this->input->post('package_type');
      $id_ip_pool      = $this->input->post('id_ip_pool');
      $id_isp          = $this->session->userdata('id_isp');

      $this->db->select('*');
      $this->db->from('package');
      $this->db->where("package_name =  '$package_name' and (id_package != '$id_package' and id_isp = $id_isp)");
      $this->db->get();


      if($this->db->affected_rows() ==0){
        $this->db->set(array('package_name' => $package_name,
        'package_speed'=>  $package_speed,
        'package_price' => $package_price,
        'package_type' => $package_type
      ))
      ->where(array('id_package' => $id_package ,'id_isp' => $this->session->userdata('id_isp')))
      ->update('package');

      if($this->db->affected_rows() > 0) {
        $jsonRes = array('status' => 'success', 'msg' => 'Package Updated successfully !');
        $this->update_net_user_price($id_package,$package_price);
      }elseif($this->db->affected_rows() == 0) {
        $jsonRes = array('status' => 'failed', 'msg' => 'No change made for edit!');
      }
    }else{
      $jsonRes = array('status' => 'failed', 'msg' => 'Package with this name already exists!');
    }
  }
  echo json_encode($jsonRes);
}


   function update_net_user_price($id_package,$package_price){
     $customers = $this->ppp_model->get_customers_by_id_package($id_package);

     foreach($customers as $customer_indiv):
       $id_package  = $customer_indiv['id_package'];
       $id_net_user = $customer_indiv['id_net_user'];
       $radio_flag  = $customer_indiv['radio_flag'];
       $discount    = $customer_indiv['discount'];

       if($radio_flag == 1 ){
         $billing_amount=$package_price;
       }else if($radio_flag==2){
         $discount=$discount/100;
         $billing_amount =$package_price - ($package_price*$discount) ;
       }else if($radio_flag==3){
         $billing_amount =$package_price -$discount;
         error_log($billing_amount);
       }

       //update mrc price and bandwidth of all user
       $this->db->update('net_user',array('net_user_mrc_price' => $package_price,
       'net_user_billing_amount'=>$billing_amount),
       array('id_package' => $id_package,
       'id_net_user' =>$id_net_user));

     endforeach;

   }
}
