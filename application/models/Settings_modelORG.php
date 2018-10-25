<?php
class Settings_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  public function settings_config() {
   $this->db->select('*');
   $this->db->from('settings');
   $this->db->where(array('id_isp'=>$this->session->userdata('id_isp')));
   return $this->db->get()->result_array();
 }

  public function get_all_profiles(){
    $this->db->select('*');
    $this->db->from('profile');
    return $this->db->get()->result_array();
  }


  public function edit_general_settings_now(){
   if($this->input->post('submit')) {
      $currency= $this->input->post('currency');

      $this->db->set(array('value'=>$currency))
                ->where('key','currency')
                ->update('settings');

      $jsonRes = array('status' => 'success', 'msg' => 'Succesfully changed currency !');
      echo json_encode($jsonRes);
    }
  }


  public function add_tax_now() {
    if($this->input->post('submit'))
    {
      $tax_name   = $this->input->post('tax_name');
      $tax_ratio  = $this->input->post('tax_ratio');

      $info = array( 'tax_name' => $tax_name,
                     'tax_ratio'=>  $tax_ratio);
      $this->db->insert('tax', $info);
      if($this->db->affected_rows() > 0) {
        $jsonRes = array('status' => 'success', 'msg' => 'Tax Added !');
      }

    echo json_encode($jsonRes);
      }
  }

  function get_all_tax()
  {
    $this->db->select('*');
    $this->db->from('tax');
    return $this->db->get()->result_array();
  }

  function get_all_tax_expect_no_tax()
  {
    $this->db->select('*');
    $this->db->from('tax');
    $this->db->where('id_tax!=',1);
    return $this->db->get()->result_array();
  }

  function get_all_invoice_template(){
    $this->db->select('*');
    $this->db->from('invoice_template');
    $this->db->where(array('is_active' => 1));
    return $this->db->get()->result_array();
  }


  function get_selected_template_name($selected_template_id){
    $this->db->select('*');
    $this->db->from('invoice_template');
    $this->db->where(array('id_invoice_template'=>$selected_template_id));
    return $this->db->get()->row()->template;
  }

  function get_individual_tax($id_tax)
  {
    $this->db->select('*');
    $this->db->from('tax');
    $this->db->where('id_tax',$id_tax);
    return $this->db->get()->result_array();
  }


  function delete_tax_now()
  {
    $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted !', 'data' => '');
    $tax_id = $this->input->post('tax_id');

    if(!empty($tax_id)){

      $this->db->delete('tax', array('id_tax'=>$tax_id));

      if($this->db->affected_rows()>0){
        $jsonRes['data'] = $this->get_all_tax();
      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to delete !');

      }
    }
    echo json_encode($jsonRes);

  }

  function edit_tax_now()
  {
    if($this->input->post('submit'))
    {
      $id_tax   = $this->input->post('id_tax');
      $tax_name   = $this->input->post('tax_name');
      $tax_ratio  = $this->input->post('tax_ratio');

      $this->db->set(array('tax_name'=>$tax_name,'tax_ratio '=>$tax_ratio))
		    ->where('id_tax',$id_tax)
        ->update('tax');
      if($this->db->affected_rows() > 0) {
        $jsonRes = array('status' => 'success', 'msg' => 'Tax Edited !');
      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'No change made !');
      }

    echo json_encode($jsonRes);
      }
  }


  public function add_isp_information() {
    $isp_name        = $this->input->post('isp_name');
    $isp_address     = $this->input->post('isp_address');
    $isp_description = $this->input->post('isp_description');
    $id_isp_info     = $this->input->post('id_isp_info');
    $isp_email       = $this->input->post('isp_email');
    $isp_password    = $this->input->post('isp_password');
    $smtp_port       = $this->input->post('smtp_port');
    $smtp_host       = $this->input->post('smtp_host');
    $sys_update      = $this->input->post('sys_update');
    $query_limit     = $this->input->post('query_limit');
    $id_zone         = $this->input->post('id_zone');
    $sms_client_code = $this->input->post('sms_client_code');
    $invoice_template= $this->input->post('invoice_template');
    $max_allowed_due = $this->input->post('max_allowed_due');


    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $type = $_FILES['file']['type'];
    $tmp_name = $_FILES['file']['tmp_name'];

    error_log("img-name:".$name);

    $oldpath = $_FILES['file']['tmp_name'];
    $newpath ="assets/img/".$_FILES['file']['name'];
    move_uploaded_file($oldpath, $newpath);

    $jsonRes = array('status' => 'success', 'msg' => ' Updated Successfully !');

    if (empty($name)){

    }else{
      $this->db->set(array('value' => $name))
      ->where(array('key'=>'isp_image','id_isp'=>$this->session->userdata('id_isp')))
      ->update('settings');
    }

    $this->db->set(array('value' => $isp_name))
    ->where(array('key'=>'isp_name','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$isp_address))
    ->where(array('key'=>'isp_address','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$isp_description))
    ->where(array('key'=>'isp_description','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$isp_email))
    ->where(array('key'=>'isp_email','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$isp_password))
    ->where(array('key'=>'isp_password','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$smtp_port))
    ->where(array('key'=>'smtp_port','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$smtp_host))
    ->where(array('key'=>'smtp_host','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$sys_update))
    ->where(array('key'=>'sys_update','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$query_limit))
    ->where(array('key'=>'query_limit','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');


    $this->db->set(array('value'=>$id_zone))
    ->where(array('key'=>'default_zone','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$sms_client_code))
    ->where(array('key'=>'sms_client_code','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$invoice_template))
    ->where(array('key'=>'invoice_template','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    $this->db->set(array('value'=>$max_allowed_due))
    ->where(array('key'=>'max_allowed_due','id_isp'=>$this->session->userdata('id_isp')))
    ->update('settings');

    echo json_encode($jsonRes);
  }


  public function get_otp_template_message(){
    $this->db->select('message');
    $this->db->from('template');
    $this->db->where(array('type'=>'otp','id_isp' =>$this->session->userdata('id_isp')));
    $query = $this->db->get();
    $ret = $query->row();
    return $ret->message;
  }

  public function get_sms_template_message(){
    $this->db->select('message');
    $this->db->from('template');
    $this->db->where(array('type'=>'billing_sms','id_isp' =>$this->session->userdata('id_isp')));
    $query = $this->db->get();
    $ret = $query->row();
    return $ret->message;
  }

  public function get_billing_email_template_message(){
    $this->db->select('message');
    $this->db->from('template');
    $this->db->where(array('type'=>'billing_email','id_isp' =>$this->session->userdata('id_isp')));
    $query = $this->db->get();
    $ret = $query->row();
    return $ret->message;
  }

  public function add_sms_template_now(){
    $message = $this->input->post('message');

    $this->db->update('template', array('message'=>$message), array('type'=>'billing_sms','id_isp' =>$this->session->userdata('id_isp')));
    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'success', 'msg' => 'New Sms Template Added !');
      $jsonRes['data'] = $message;
    }

    echo json_encode($jsonRes);
  }

  public function add_otp_template_now(){
    $otp   = $this->input->post('otp');
    $this->db->update('template', array('message'=>$otp), array('type'=>'otp','id_isp' =>$this->session->userdata('id_isp')));

    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'success', 'msg' => 'New Otp Template Added !');
      $jsonRes['data'] = $otp;
    }

    echo json_encode($jsonRes);
  }


  public function add_billing_email_template_now(){
    $billing_email   = $this->input->post('billing_email');
    $this->db->update('template', array('message'=>$billing_email), array('type'=>'billing_email','id_isp' =>$this->session->userdata('id_isp')));

    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'success', 'msg' => 'New Email Template Added !');
      $jsonRes['data'] = $billing_email;
    }
    echo json_encode($jsonRes);

  }

  public function get_by_key($key) {
    $value = '';

    $this->db->select('value');
    $this->db->from('settings');
    $this->db->where(array('key'=>$key,'id_isp'=> $this->session->userdata('id_isp')));
    $result = $this->db->get()->result_array();

    if($this->db->affected_rows() > 0) {
      foreach($result as $row) {
        $value = $row['value'];
        error_log($key .' value is '. $value);
      }
    }

    return $value;
  }

  public function login($router_ip,$router_login,$router_password){

    $config['mikrotik']['host']     = $router_ip;
    $config['mikrotik']['port']     = '8728';
    $config['mikrotik']['username'] = $router_login;
    $config['mikrotik']['password'] = $router_password;
    $config['mikrotik']['debug']    = FALSE;
    $config['mikrotik']['attempts'] = 3;
    $config['mikrotik']['delay']    = 2;
    $config['mikrotik']['timeout']  = 2;

    $this->session->set_userdata($config);
    $this->session->set_userdata('loggedin', TRUE);
    $config['mikrotik'] = $this->session->userdata('mikrotik');

    // $this->mikrotik_api->initialize($config);
      $this->mikrotik_api->initialize($config);
  }

  public function add_mikrotik_to_db(){

    $id_router        = $this->input->post('id_router');
    $pool_check       = isset($_POST['pool_check']);
    $profile_check    = isset($_POST['profile_check']);
    $customer_check   = isset($_POST['customer_check']);
    $arp_check        = isset($_POST['arp_check']);
    $firewall_check   = isset($_POST['firewall_check']);
    $queue_check      = isset($_POST['queue_check']);

    $routers = $this->router_model->get_routers_indv_info($id_router);

    foreach($routers as $router_indiv):
      $router_name     	  = $router_indiv['name'];
      $router_ip      	  = $router_indiv['ip_address'];
      $router_login     	= $router_indiv['login'];
      $router_password  	= $router_indiv['password'];
      $sync_router_flag   = $router_indiv["sync_router_flag"];
    endforeach;

    if($sync_router_flag == true){

      $date        = new DateTime('now', new DateTimezone('Asia/Dhaka'));;
      $curr_date   = $date->format('Y-m-d h:i:s a');


      $this->login($router_ip, $router_login, $router_password);
      $this->mikrotik_api->system()->save_backup("nib-".$curr_date);

      $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

      if($mk_validation === false) {
        $jsonRes = array('status' => 'failed', 'msg' => '<p style=color:red; >Failed to login with '.$router_name .' router !! Invalid username or password .</p>');
        echo json_encode($jsonRes);
        return;
      } else {
        $this->sync_mikrotik_to_db($id_router,$pool_check,$profile_check,$customer_check,$arp_check,$firewall_check,$queue_check);
      }
    }else{
      $jsonRes = array('status' => 'failed', 'msg' => ''.$router_name .' router Sync off');
      echo json_encode($jsonRes);
      return;
    }
  }



    public function sync_mikrotik_to_db($id_router,$pool_check,$profile_check,$customer_check,$arp_check,$firewall_check,$queue_check){

      $jsonRes = array('status' => 'success', 'msg' => 'Migration Completed !');
      $data="<p style=color:green; >Successfully connected with the router</p>"."</br>";
      // inserting pool into db
      if($pool_check == 1){
        $mikrotik_pool   = $this->mikrotik_api->ip()->pool()->get_all_pool();
        $data            = $this->router_model->router_ip_pool_save_db($id_router, $mikrotik_pool,$data);
      }
      // inserting profile into db
      if($profile_check == 1){
        $mikrotik_profile   = $this->mikrotik_api->ppp()->ppp_profile()->get_all_ppp_profile();
        $data               = $this->router_model->router_profile_save_db($id_router, $mikrotik_profile,$data);
      }
      // inserting pppoe into db
      if($customer_check==1){
        $id_zone        = $this->get_by_key('default_zone');
        $mikrotik_ppp   = $this->mikrotik_api->ppp()->ppp_secret()->get_all_ppp_secret();
        $data           = $this->router_pppoe_customer_save_db($id_zone,$id_router, $mikrotik_ppp,$data);
      }
      //static user from firewall
      if($firewall_check==1){
        $mikrotik_firewall  = $this->mikrotik_api->ip()->firewall()->get_all_firewall_filter();
        $data               = $this->router_model->router_firewall_save_db($id_router, $mikrotik_firewall,$data);
      }

      // //static user from arp
      // if($arp_check==1){
      // $mikrotik_static    = $this->mikrotik_api->ip()->arp()->get_all_arp();
      //   // // inserting static into db
      //   foreach ($mikrotik_static as $row){
      //
      //     $this->db->select('*');
      //     $this->db->from('net_user');
      //     $this->db->where(array('net_user_ip_address' => $row['address'],'id_router' => $id_router));
      //     $this->db->get();
      //     if($this->db->affected_rows()>0){
      //       $data = $data ."static customer ".$row['address']." Already Exists"."</br>";
      //     }
      //     else{
      //       $mac_address="";
      //       if(isset($row['mac-address'])){
      //         $mac_address=$row['mac-address'];
      //       }
      //       $info = array( 'id_net_user_type' => '2',
      //       'id_router'                =>  $id_router,
      //       'net_user_mac'             => $mac_address,
      //       'net_user_ip_address'      => $row['address'],
      //       'net_user_mrc_price'       => 0,
      //       'net_user_billing_amount'  => 0,
      //       'service_type'             =>'Recurring',
      //       'id_zone'                  => 7,
      //       'radio_flag'               => 1,
      //       'id_repeat_every'          => 1);
      //
      //       $this->db->insert('net_user', $info);
      //
      //       if($this->db->affected_rows()>0) {
      //         // error_log("INSERTED: ".$row['name']);
      //         $data = $data . "INSERTED: static customer".$row['address']."</br>";
      //       }
      //       else{
      //         // error_log("NOT-INSERTED: ".$row['name']);
      //         $data = $data ."NOT-INSERTED: static customer ".$row['address']."</br>";
      //       }
      //     }
      //   }
      // }

      $data = $data ."Router Disconnected...";
      $jsonRes['data'] = $data;

      $router_name = $this->router_model->get_router_name($id_router);

      $this->db->insert('notification', array(
        'notification_title' => "Router Migration",
        'notification_body'  =>  $router_name ." router migrated by ".$this->session->userdata('name'),
        'notification_type'  => "message"
      ));



      echo json_encode($jsonRes);
    }

  public function router_pppoe_customer_save_db($id_zone,$id_router, $mikrotik_ppp,$data){

    foreach ($mikrotik_ppp as $row){

      $updateFlag = 0;

      $net_user_update_sql = array();
      //sql for update
      if(isset($row['name'])){
        $net_user_update_sql['net_user_username']  = $row['name'];
      }else{
        $net_user_update_sql['net_user_username']  = " ";
      }
      if(isset($row['password'])){
        $net_user_update_sql['net_user_password']  = $row['password'];
      }else{
        $net_user_update_sql['net_user_password']  = " ";
      }
      if(isset($row['caller-id'])){
        $net_user_update_sql['net_user_mac']  = $row['caller-id'];
      }else{
        $net_user_update_sql['net_user_mac']  = " ";
      }
      if(isset($row['remote-address'])){
        $net_user_update_sql['net_user_ip_address']  = $row['remote-address'];
      }else{
        $net_user_update_sql['net_user_ip_address']  = " ";
      }

      $this->db->select('*');
      $this->db->from('profile');
      $this->db->where(array('profile_name' => $row['profile'],'id_router' => $id_router));
      $query = $this->db->get()->row();

      if($this->db->affected_rows() > 0){
      $id_profile            = $query->id_profile;
      $ip_pool_name          = $query->profile_remote_address;
      }

      $this->db->select('*');
      $this->db->from('net_user');
      $this->db->where(array('id_mk_pppoe'=>$row['.id'],'id_router' => $id_router));
      $query = $this->db->get()->row();

      if($this->db->affected_rows() > 0){
        $prv_id_net_user          = $query->id_net_user;
        $prv_username             = $query->net_user_username;
        $prv_password             = $query->net_user_password;
        $prv_mac                  = $query->net_user_mac;
        $prev_net_user_ip_address = $query->net_user_ip_address;

        $this->db->select('*');
        $this->db->from('net_user_profile');
        $this->db->where(array('id_net_user' => $prv_id_net_user,'id_profile' => $id_profile));
        $this->db->get();
        if($this->db->affected_rows() == 0){
          $updateFlag = 1;
        }

        $this->db->select('*');
        $this->db->from('net_user');
        $this->db->where($net_user_update_sql);
        $this->db->get();
        if($this->db->affected_rows() == 0){
          $updateFlag = 1;
        }

        if(!isset($row['remote-address'])){
          $this->db->select('*');
          $this->db->from('radreply');
          $this->db->where(array('id_net_user' => $prv_id_net_user,'id_router' => $id_router, 'value' => $ip_pool_name));
          $query = $this->db->get();
          if($this->db->affected_rows() == 0){
            $updateFlag = 1;
          }
        }
      }


      $net_user_sql = array();

      $net_user_sql['id_net_user_type']        = '1' ;
      $net_user_sql['id_router']               = $id_router;
      $net_user_sql['net_user_mrc_price']      = 0;
      $net_user_sql['net_user_billing_amount'] = 0;
      $net_user_sql['service_type']            = 'Recurring';
      $net_user_sql['id_zone']                 = $id_zone ;
      $net_user_sql['radio_flag']              = 1 ;
      $net_user_sql['id_repeat_every']         = 1 ;


      if(isset($row['name'])){
        $net_user_sql['net_user_username'] = $row['name'];
      }
      if(isset($row['password'])){
        $net_user_sql['net_user_password'] = $row['password'];
      }
      if(isset($row['.id'])){
        $net_user_sql['id_mk_pppoe'] = $row['.id'];
      }
      if(isset($row['caller-id'])){
        $net_user_sql['net_user_mac'] = $row['caller-id'];
      }
      if(isset($row['remote-address'])){
        $net_user_sql['net_user_ip_address'] = $row['remote-address'];
      }

      $this->db->select('*');
      $this->db->from('net_user');
      $this->db->where(array('id_mk_pppoe'=>$row['.id'],'id_router' => $id_router));
      $this->db->get();

      if($this->db->affected_rows() > 0){

        if($updateFlag == 1){
          $flag = 0 ;
          $this->db->update('net_user',$net_user_update_sql, array('id_mk_pppoe' => $row['.id'],'id_router' => $id_router));
          if($this->db->affected_rows() > 0 ) {
            $flag = 1;
          }
          $this->db->update('net_user_profile',array('id_profile' => $id_profile), array('id_net_user' => $prv_id_net_user));
          if($this->db->affected_rows() > 0 ) {
            $flag = 1;
          }

          if($flag == 1) {
            //radius radcheck username passoword updating
            if(strlen($row['password'])!=0 && strlen($row['name'])!=0){

              $this->db->select('*');
              $this->db->from('radcheck');
              $this->db->where(array('attribute' => 'user-password','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
              $this->db->get();

              if($this->db->affected_rows() > 0){
                $this->radius_model->update_user_radcheck($row['name'],$row['password'],$id_router,$prv_id_net_user);
                // $this->db->update('radcheck', array('username'=>$row['name'], 'value' => $row['password'] , 'op' => '==','attribute'=>'user-password' ),array('attribute' => 'user-password','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
              }else{
                $this->radius_model->add_user_radcheck($row['name'], $row['password'], $id_router ,$prv_id_net_user);
              }

            }else{
              $this->radius_model->delete_user_radcheck($id_router,$prv_id_net_user);
              // $this->db->delete('radcheck',array('attribute' => 'user-password','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
            }
            //radius radcheck username mac updating
            if(strlen($row['caller-id'])!=0 && strlen($row['name'])!=0){

              $this->db->select('*');
              $this->db->from('radcheck');
              $this->db->where(array('attribute' => 'Calling-Station-Id','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
              $this->db->get();

              if($this->db->affected_rows() > 0){
                $this->radius_model->update_mac_radcheck( $row['name'], $row['caller-id'],$id_router,$prv_id_net_user);
                // $this->db->update('radcheck', array('username'=>$row['name'], 'value' => $row['caller-id'] , 'op' => ':=','attribute'=>'Calling-Station-Id' ),array('attribute' => 'Calling-Station-Id','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
              }else{
                $this->radius_model->add_mac_radcheck($row['name'], $row['caller-id'], $id_router ,$prv_id_net_user);
              }
            }else{
                $this->radius_model->delete_mac_radcheck($id_router,$prv_id_net_user);
              // $this->db->delete('radcheck',array('attribute' => 'Calling-Station-Id','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
            }
            //radius radreply username ip-address updating
            if(strlen($net_user_update_sql['net_user_ip_address']) > 1){
              $this->radius_model->delete_ip_pool_radreply($id_router,$prv_id_net_user);

              $this->db->select('*');
              $this->db->from('radreply');
              $this->db->where(array('attribute' => 'Framed-IP-Address','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
              $this->db->get();

              if($this->db->affected_rows() > 0){
                $this->radius_model->update_ip_address_radreply($row['name'],$net_user_update_sql['net_user_ip_address'], $id_router, $prv_id_net_user);
                // $this->db->update('radreply', array('username'=>$row['name'], 'value' => $net_user_update_sql['net_user_ip_address'] ),array('attribute'=>'Framed-IP-Address','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
              }else{
                $this->radius_model->add_ip_address_radreply($row['name'], $net_user_update_sql['net_user_ip_address'],$id_router ,$prv_id_net_user);
              }
            }else{
              $this->radius_model->delete_ip_address_radreply($id_router,$prv_id_net_user);
              // $this->db->delete('radreply',array('attribute' => 'Framed-IP-Address','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
              //radius radreply username ip_pool updating
              if(!empty($ip_pool_name)){

                $this->db->select('*');
                $this->db->from('radreply');
                $this->db->where(array('attribute' => 'Framed-Pool','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
                $this->db->get();

                if($this->db->affected_rows() > 0){
                  $this->radius_model->update_ip_pool_radcheck($row['name'], $ip_pool_name, $id_router, $prv_id_net_user);
                  // $this->db->update('radreply', array('username'=>$row['name'], 'value' => $ip_pool_name  ),array('attribute' => 'Framed-Pool','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
                }else{
                  $this->radius_model->add_user_radreply($row['name'], $ip_pool_name,$id_router ,$prv_id_net_user);
                }
              }else{
                $this->radius_model->delete_ip_pool_radreply($id_router,$prv_id_net_user);
                // $this->db->delete('radreply',array('attribute' => 'Framed-Pool','id_net_user' => $prv_id_net_user,'id_router' => $id_router));
              }
            }
            $data = $data . "<span style=color:#f442df;>UPDATED : customer".$row['name']."</span></br>";
          }
        }
        else{
          if(isset($row['name'])){
            $data = $data ."<span style=color:#f4bf42;>pppoe customer ".$row['name']." Already Exists</span>"."</br>";
          }
        }
      }
      else {

        $this->db->insert('net_user', $net_user_sql);

        $id_net_user = $this->db->insert_id();

        if($this->db->affected_rows()>0) {
          //net_user profile entry
          $this->db->insert('net_user_profile', array('id_profile'=>$id_profile, 'id_net_user' => $id_net_user ));
          //radius radcheck username passoword inserting
          if(strlen($row['password'])!=0 && strlen($row['name'])!=0){
            $this->radius_model->add_user_radcheck($row['name'], $row['password'], $id_router, $id_net_user);
          }
          //radius radcheck username mac inserting
          if(strlen($row['caller-id'])!=0 && strlen($row['name'])!=0){
            $this->radius_model->add_mac_radcheck($row['name'], $row['caller-id'], $id_router, $id_net_user);
          }
          //radius radreply username ip-address inserting
          if(isset($row['remote-address'])){
            $this->radius_model->add_ip_address_radreply($row['name'], $row['remote-address'], $id_router, $id_net_user);
          }else{
            //radius radreply username ip_pool inserting
            if(!empty($ip_pool_name)){
            $this->radius_model->add_user_radreply($row['name'], $ip_pool_name, $id_router, $id_net_user);
            }
          }
          $data = $data . "<span style=color:green; >INSERTED: customer".$row['name']."</span></br>";
        } else {
            $data = $data ."<span style=color:red; >NOT-INSERTED: customer ".$row['name']."</span></br>";
        }
      }
    }
    return $data;
  }

  public function edit_isp_config_now() {

    if($this->input->post('submit')){
      $isp_name     = $this->input->post('isp_name');
      $isp_address  = $this->input->post('isp_address');

      $this->db->set(array('value'=>$isp_name,'last_change'=>date('Y-m-d H:i:s')))
        //$this->db->set(array('value'=>$isp_name))
      ->where('key','isp_name')
      ->update('settings');
      $this->db->set(array('value'=>$isp_address,'last_change'=>date('Y-m-d H:i:s')))
       ->where('key','isp_address')
      ->update('settings');

      echo '<span class="success">Success</span>';
    }
  }// end of function edit_isp_config_now


}
