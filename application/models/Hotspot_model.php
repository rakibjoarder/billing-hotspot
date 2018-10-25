<?php
class Hotspot_model extends CI_Model {

  function __construct()
  {
    parent::__construct();
  }

  function add_hotspot_user_now($phone,$rand_num){
	   $this->load->model('settings_model');
    $Otp_message = $this->settings_model->get_otp_template_message();
    $Otp_message = str_replace("%CODE%",$rand_num,$Otp_message);
    $clientcode    = $this->settings_model->get_by_key('sms_client_code');

    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->where("net_user_phone = '$phone'");
    $this->db->get();


    if($this->db->affected_rows()>0){
      $data='Phone Number already registered';
    }else{
      $info = array( 'net_user_username'=>$phone ,'net_user_phone'  => $phone,'net_user_password' =>  $rand_num,'id_net_user_type' => 3,'radio_flag' => 1,'service_type'=> 'Recurring');
      $this->db->insert('net_user', $info);

      if($this->db->affected_rows() > 0) {
// otp send code
        $this->load->library('curl');
        $url = "http://sms.nibserver.com/api/nibsms/add_sms";
        $data = array(
          'clientcode' => $clientcode,
          'phone' => $phone ,
          'msg' => $Otp_message
        );
        $res=$this->curl->simple_get($url, $data);

        $data='success';

        $this->db->insert('notification', array(
          'notification_title' => "New Hotspot User",
          'notification_body' =>  $phone." Hotspot created",
          'notification_type'  => "message",
          'id_isp'             => $this->session->userdata('id_isp')
        ));

      }
      else {
        // $jsonRes = array('status' => 'failed', 'msg' => 'Failed to create hotspot !');
        $data='failed';
      }
    }
    return $data;
  }

  function verify_user_now($password,$phone){

    $this->db->select('*');
    $this->db->from('net_user');

    $this->db->where("net_user_phone = '$phone' and net_user_password = '$password'");

    $this->db->get();

    if($this->db->affected_rows() > 0) {
      $data='success';
      $this->db->insert('radcheck', array('username'=>$phone, 'value' => $password , 'op' => '==','attribute'=>'user-password' ));

    }
    else {
      $data="Incorrect OTP !";
    }
    return $data;
  }


function resend_password($phone){


  $this->db->select('net_user_password');
  $this->db->from('net_user');
  $this->db->where('net_user_phone',$phone);
  $query = $this->db->get();
  $ret = $query->row();
  $passowrd=$ret->net_user_password;
  $clientcode    = $this->settings_model->get_by_key('sms_client_code');


  if($this->db->affected_rows() > 0) {
    $data='success';
            $this->load->library('curl');
            $url = "http://sms.nibserver.com/api/nibsms/add_sms";
            $data = array(
              'clientcode' => $clientcode,
              'phone' => $phone ,
              'msg' => 'Your OTP is '.$passowrd.' Valid for 1 hour. Thanks for using LightCube Wifi.'
            );
            $res=$this->curl->simple_get($url, $data);
  }

}



}
?>
