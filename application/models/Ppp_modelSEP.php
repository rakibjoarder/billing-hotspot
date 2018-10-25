<?php
class Ppp_model extends CI_Model {

  function __construct() {
    parent::__construct();
    $this->load->model('connection_model');
    $this->load->model('radius_model');
  }


  public function get_net_user_types(){
    $this->db->select('*');
    $this->db->from('net_user_type');

    return $this->db->get()->result_array();
  }




  public function create_ppp_user() {

    $net_user_name            = $this->input->post('net_user_name');
    $net_user_address         = $this->input->post('net_user_address');
    $net_user_phone           = $this->input->post('net_user_phone');
    $net_user_email           = $this->input->post('net_user_email');
    $net_user_nid             = $this->input->post('net_user_nid');
    $id_net_user_type         = $this->input->post('id_net_user_type');
    $net_user_username        = $this->input->post('net_user_username');
    $net_user_password        = $this->input->post('net_user_password');
    $net_user_mac             = $this->input->post('net_user_mac');
    $net_user_ip_address      = $this->input->post('net_user_ip_address');
    $net_user_ip_address_block= $this->input->post('net_user_ip_address_block');
    $net_user_mrc_price       = $this->input->post('net_user_mrc_price');
    $id_package               = $this->input->post('id_package');
    $id_repeat_every          = $this->input->post('id_repeat_every');
    $service_type             = $this->input->post('optionsRadios');
    $id_zone                  = $this->input->post('id_zone');
    $billing_amount           = $this->input->post('discount_');
    $discount                 = $this->input->post('discount');
    $radio_flag               = $this->input->post('radio_flag');
    $id_router                = $this->input->post('id_router');
    $id_profile               = $this->input->post('id_profile');
    $id_ip_pool               = $this->input->post('id_ip_pool');

    $radius_flag = $this->router_model->is_radius_enable($id_router);

    error_log("Radius Flag".$radius_flag);

  // for last generated invoice date
    $date        = new DateTime("now");
    $curr_month  = $date->format('m');
    $curr_year   = $date->format('Y');
    $curr_day    = $date->format('d');
    $curr_date   = $date->format('Y-m-d');

    ////
    // VALIDATION START
    ////

    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->where("net_user_username = '$net_user_username'");
    $this->db->get();
    if($this->db->affected_rows() >  0) {
      $jsonRes = array('status' => 'failed', 'msg' => 'Customer already exists !');
      echo json_encode($jsonRes);
      return;
    }

    // get the sync flag from MT
    $router_details = $this->router_model->get_routers_indv_info($id_router);

    foreach($router_details as $router_indiv) {
      $router_ip		 	  = $router_indiv["ip_address"];
      $router_name	  	= $router_indiv["name"];
      $router_login 		= $router_indiv["login"];
      $router_password  = $router_indiv["password"];
      $sync_router_flag = $router_indiv["sync_router_flag"];
    }

    // Static user can not create without sync flag enable
    if($sync_router_flag == false && $id_net_user_type == 2) {
      $jsonRes = array('status' => 'failed', 'msg' => 'Static customer can not create with sync flag off!');
      echo json_encode($jsonRes);
      return;
    }



    $db_insert_flag = true;
    $id_mk_pppoe    = 0;
    // if MT sync true then try to insert MT
    if($sync_router_flag == true) {

      // mk login validation
      $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

      if($mk_validation == false){
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
        echo json_encode($jsonRes);
        return;
      }



      //adding pppoe and hotspot user in mikrotik
      //type 1 - pppoe user 3- hotspot User

      $db_insert_flag = false;

      if(($id_net_user_type == 1 || $id_net_user_type == 3)  && $radius_flag == 0 ){

        $res = $this->add_pppoe_to_mikrotik($id_router, $net_user_username, $net_user_password, $net_user_mac, $id_profile, $net_user_ip_address);

        if(trim($res[0]) == "!done"){
          $id_mk_pppoe = str_replace('=ret=', '', $res[1]);
          $db_insert_flag = true;
        } elseif(trim($res[0]) == "!trap"){
          $msg = "Failed to add customer into Mikrotik !";
          if($res[1] == "=message=failure: secret with the same name already exists"){
            $msg = "Customer with the same name already exists !";
          }elseif($res[2] == "=message=invalid value for argument remote-address"){
            $msg = "Invalid Ip Address !";
          }elseif($res[2] == "=message=input does not match any value of profile"){
            $msg = "Profile does not exist in Mikrotik !";
          }elseif($res[1] == "=message=not enough permissions (9)"){
            $msg = "Router write permission denied";
          }

          $jsonRes = array('status' => 'failed', 'msg' => $msg);
        }
      }elseif($id_net_user_type == 2 ){
         // 2-Static User
        $res = $this->add_static_customer_to_mikrotik($id_router, $net_user_mac, $net_user_ip_address);

        if(trim($res[0]) == "!done"){
          $db_insert_flag = true;
          $id_mk_static   = str_replace('=ret=', '', $res[1]);
          $min_order      = $this->get_min_entry_order($id_router);
        }elseif(trim($res[0]) == "!trap"){
          $msg = "Failed to add customer into Mikrotik !";
          if($res[2] == "=message=value of range expects range of ip addresses"){
            $msg ="Invalid Ip Address !";
          }elseif($res[2] == "=message=invalid value of address, mac address required"){
            $msg = "Invalid mac address !";
          }
          $jsonRes = array('status' => 'failed', 'msg' => $msg);
        }
      } else {
        // this is for lease user.
        $db_insert_flag = true;
      }

    }

    // Otherwise go to insert DB
    if($db_insert_flag == true){

     $info = array(
       'net_user_name'       => $net_user_name ,
       'net_user_address'    => $net_user_address,
       'net_user_phone'      => $net_user_phone,
       'net_user_email'      => $net_user_email,
       'id_net_user_type'    => $id_net_user_type,
       'net_user_nid'        => $net_user_nid,
       'net_user_username'   => $net_user_username,
       'net_user_password'   => $net_user_password ,
       'net_user_mac'        => $net_user_mac,
       'net_user_ip_address' => $net_user_ip_address,
       'net_user_ip_address_block' => $net_user_ip_address_block,
       'net_user_mrc_price'  => $net_user_mrc_price,
       'id_package'          => $id_package ,
       'id_repeat_every'     => $id_repeat_every,
       'service_type'        => $service_type,
       'id_zone'             => $id_zone,
       'id_router'           => $id_router,
       'net_user_billing_amount'=>$billing_amount,
       'radio_flag'          => $radio_flag,
       'discount'            => $discount,
       'invoice_gen'         => $curr_date
     );

     $this->db->insert('net_user', $info);
     //last inserted id_net_user
     $id_net_user = $this->db->insert_id();

     if($this->db->affected_rows() > 0) {
       $jsonRes = array('status' => 'success', 'msg' => 'Succesfully created customer '.$net_user_name. '!');

       // update net user mk pppoe id
       if($id_net_user_type == 1 || $id_net_user_type == 3){
         $this->db->update('net_user', array('id_mk_pppoe'=> $id_mk_pppoe),array('id_net_user' => $id_net_user ));
       }elseif($id_net_user_type == 2){

         $firewalls = $this->router_model->get_all_firewall_by_router_id($id_router);
         $this->db->update('net_user', array('id_mk_static'=> $id_mk_static),array('id_net_user' => $id_net_user ));

         $firewall_sql = array();
         $firewall_sql['chain'] = 'forward';
         if(!empty($net_user_ip_address)){
           $firewall_sql['src-address'] = $net_user_ip_address;
         }if(!empty($net_user_mac)){
           $firewall_sql['src-mac-address'] = $net_user_mac;
         }
         $firewall_sql['action'] = 'accept';
         $firewall_sql['id_mk_static'] = $id_mk_static;
         $firewall_sql['id_router']    = $id_router;
         $firewall_sql['entry_order']  = $min_order;
         $this->db->insert('firewall', $firewall_sql);


         // Now need to apply move command to replace the position to 0.
         // $this->router_model->login($router_ip, $router_login, $router_password);
         $swap_sql = array();
         $swap_sql['id'] = $id_mk_static;
         $swap_sql['destination'] = $this->get_mk_static_by_min_order($min_order,$id_router);

         $move_res = $this->mikrotik_api->ip()->firewall()->move_firewall_filter($swap_sql);

         if(trim($move_res[0]) == "!done"){
           //increasing entry_order by one
           foreach ($firewalls as $item) {
             $this->db->update('firewall', array('entry_order'=>($item['entry_order']+1)),array('id_mk_static'=>$item['id_mk_static'],'id_router' => $id_router));
           }
         }
       }

       if($id_profile > 0 || $id_ip_pool > 0) {
         //net_user profile entry
         $this->db->insert('net_user_profile', array('id_profile'=>$id_profile, 'id_net_user' => $id_net_user , 'id_ip_pool' => $id_ip_pool ));

         if($id_ip_pool > 0) {
           $this->db->select('*');
           $this->db->from('ip_pool');
           $this->db->where(array('id_ip_pool' => trim($id_ip_pool)));
           $result = $this->db->get();
           if($this->db->affected_rows() > 0) {
             $ip_pool_name = $result->row()->ip_pool_name;
           } else {
             $ip_pool_name = "";
           }
         } else {
           $ip_pool_name = "";
         }
       }
       //notification table entry
       $this->db->insert('notification', array(
         'notification_title' => "New Customer ",
         'notification_body' =>  $net_user_name." has joined",
         'notification_type'  => "message"
       ));
       // Giving Radius table entry.
       if(strlen($net_user_password)!=0 && strlen($net_user_username)!=0 && $radius_flag == 1){
         if($id_net_user_type == 1 || $id_net_user_type == 3){
           $this->radius_model->add_user_radcheck($net_user_username, $net_user_password, $id_router, $id_net_user);

           if(!empty($net_user_ip_address)){
             $this->radius_model->add_ip_address_radreply($net_user_username, $net_user_ip_address, $id_router, $id_net_user);
           }else{
             if(!empty($ip_pool_name)){
               $this->radius_model->add_user_radreply($net_user_username, $ip_pool_name, $id_router, $id_net_user);
             }
           }
           if(strlen($net_user_mac)!=0){
             $this->radius_model->add_mac_radcheck($net_user_username, $net_user_mac, $id_router, $id_net_user);
           }
         }
       }
     } else {
       $jsonRes = array('status' => 'failed', 'msg' => 'Failed to add customer !');
     }
    }
     echo json_encode($jsonRes);
   }



public function add_pppoe_to_mikrotik($id_router, $net_user_username, $net_user_password, $net_user_mac, $id_profile, $net_user_ip_address){
  $router_details = $this->router_model->get_routers_indv_info($id_router);

  foreach($router_details as $router_indiv) {
    $router_ip		 	  = $router_indiv["ip_address"];
    $router_name	  	= $router_indiv["name"];
    $router_login 		= $router_indiv["login"];
    $router_password  = $router_indiv["password"];
    $sync_router_flag = $router_indiv["sync_router_flag"];
  }

  if($sync_router_flag){
    $this->router_model->login($router_ip,$router_login,$router_password);
    $sql=array();
    $sql['name'] = $net_user_username;
    if(!empty($net_user_password)){
      $sql['password'] = $net_user_password;
    }

    if($id_profile > 0){
      $profileName = $this->router_model->get_profile_name($id_profile);
      $sql['profile'] = $profileName;
    }

    if(!empty($net_user_ip_address)){
      $sql['remote-address'] = $net_user_ip_address;
    }

    if(!empty($net_user_mac)){
      $sql['caller-id'] =$net_user_mac;
    }

    $res = $this->mikrotik_api->ppp()->ppp_secret()->add_ppp_secret($sql);

  } else {
    $res = "Mikrotik Sync Disabled";
  }

  return $res;
}


public function add_static_customer_to_mikrotik($id_router,$net_user_mac,$net_user_ip_address){
  $router_details = $this->router_model->get_routers_indv_info($id_router);

  foreach($router_details as $router_indiv):
    $router_ip		 	  =$router_indiv["ip_address"];
    $router_name	  	=$router_indiv["name"];
    $router_login 		=$router_indiv["login"];
    $router_password  =$router_indiv["password"];
    $sync_router_flag =$router_indiv["sync_router_flag"];
  endforeach;

  if($sync_router_flag){
    $this->router_model->login($router_ip, $router_login, $router_password);
    $firewall_sql = array();
    $firewall_sql['chain'] = 'forward';
    if(!empty($net_user_ip_address)){
      $firewall_sql['src-address'] = $net_user_ip_address;
    }if(!empty($net_user_mac)){
      $firewall_sql['src-mac-address'] = $net_user_mac;
    }
    $firewall_sql['action'] = 'accept';

    $res = $this->mikrotik_api->ip()->firewall()->add_firewall_filter($firewall_sql);

  }else{
    $res = "Mikrotik Sync Disabled";
  }

  return $res;
}

public function get_min_entry_order($id_router){
  $min = 0;

  $this->db->select('MIN(entry_order) as min_order');
  $this->db->from('firewall');
  $this->db->where(array('id_router' => $id_router));
  $query = $this->db->get();

  if($this->db->affected_rows() > 0) {
    if($query->row()->min_order != ''){
      $min = $query->row()->min_order;
    }
  }

  return $min ;
}


public function get_mk_static_by_min_order($min_order,$id_router){

  $this->db->select('id_mk_static');
  $this->db->from('firewall');
  $this->db->where(array('entry_order' => $min_order,'id_router' => $id_router));
  $query = $this->db->get();

  if($this->db->affected_rows() > 0) {
    $id_mk_static = $query->row()->id_mk_static;
  }

   return $id_mk_static ;
}

public function has_customer_already_edit($net_user_username,$id_net_user){
  $this->db->select('*');
  $this->db->from('net_user');
  $this->db->where("net_user_username = '$net_user_username' and id_net_user != '$id_net_user'");
  $this->db->get();
  if($this->db->affected_rows()>0){
    $flag = true;
  }else{
    $flag = false;
  }
  return $flag;
}


public function edit_customer_now(){

  $net_user_name              = $this->input->post('net_user_name');
  $net_user_address           = $this->input->post('net_user_address');
  $net_user_phone             = $this->input->post('net_user_phone');
  $net_user_email             = $this->input->post('net_user_email');
  $id_net_user_type           = $this->input->post('id_net_user_type');
  $net_user_username          = $this->input->post('net_user_username');
  $net_user_password          = $this->input->post('net_user_password');
  $net_user_mac               = $this->input->post('net_user_mac');
  $net_user_ip_address        = $this->input->post('net_user_ip_address');
  $net_user_ip_address_block  = $this->input->post('net_user_ip_address_block');
  $net_user_mrc_price         = $this->input->post('net_user_mrc_price');
  $id_package                 = $this->input->post('id_package');
  $id_repeat_every            = $this->input->post('id_repeat_every');
  $id_net_user                = $this->input->post('id_net_user');
  $prv_mac                    = $this->input->post('prv_mac');
  $prv_username               = $this->input->post('prv_username');
  $prv_password               = $this->input->post('prv_password');
  $service_type               = $this->input->post('optionsRadios');
  $id_zone                    = $this->input->post('id_zone');
  $billing_amount             = $this->input->post('discount_');
  $discount                   = $this->input->post('discount');
  $radio_flag                 = $this->input->post('radio_flag');
  $id_ip_pool                 = $this->input->post('id_pool');
  $id_router                  = $this->input->post('id_router');
  $id_profile                 = $this->input->post('id_profile');
  $is_active                  = $this->input->post('is_active');
  $net_user_nid               = $this->input->post('net_user_nid');

  if(isset($_POST['is_active'])) $is_active =0;
  else $is_active = 1;
  //checking if customer with this name already exist
  $has_customer_already = $this->has_customer_already_edit($net_user_username,$id_net_user);

  if($has_customer_already == true){
    $jsonRes = array('status' => 'failed', 'msg' => 'Customer with this username already exists !');
    echo json_encode($jsonRes);
    return;
  }

  // get the sync flag from MT
  $router_details = $this->router_model->get_routers_indv_info($id_router);

  foreach($router_details as $router_indiv) {
    $router_ip		 	  = $router_indiv["ip_address"];
    $router_name	  	= $router_indiv["name"];
    $router_login 		= $router_indiv["login"];
    $router_password  = $router_indiv["password"];
    $sync_router_flag = $router_indiv["sync_router_flag"];
  }
  // Static user can not edit without sync flag enable
  if($sync_router_flag == false && $id_net_user_type == 2) {
    $jsonRes = array('status' => 'failed', 'msg' => 'Static customer can not edit with sync flag off!');
    echo json_encode($jsonRes);
    return;
  }
  //true - can edit from db
  $db_edit_flag= true;

  if($sync_router_flag == true){

    // mk login validation
    $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

    if($mk_validation == false){
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
      echo json_encode($jsonRes);
      return;
    }

    //if sync flag is true initially edit flag is false
    $db_edit_flag= false;
    $id_mk_pppoe    = $this->get_id_mk_pppoe($id_net_user);
    $id_mk_static   = $this->get_id_mk_static($id_net_user);

    //editing pppoe and hotspot user in mikrotik
    //type 1 - Static user 3- hotspot User
    if($id_net_user_type == 1 || $id_net_user_type == 3){

      $this->router_model->login($router_ip,$router_login,$router_password);

      //this is only for mikrotik edit
      if(strlen($net_user_password)!= 0){
        $password = $net_user_password;
      }else{
        $password = $prv_password;
      }
      //checking if customer exist in mikrotik
      $has_mikrotik_ppp = $this->mikrotik_api->ppp()->ppp_secret()->detail_ppp_secret($id_mk_pppoe);

      if($has_mikrotik_ppp){

        $res = $this->edit_pppoe_mikrotik($id_router, $net_user_username, $password,$net_user_mac,$id_profile,$net_user_ip_address,$id_mk_pppoe);

        if(trim($res[0]) == "!done"){
          $db_edit_flag= true;
          //delete static customer from mikrotik
          $this->delete_static_customer_from_mikrotik($id_router,$id_mk_static);
          //delete firewall from db
          $this->db->delete('firewall',array('id_mk_static' => $id_mk_static,'id_router' => $id_router));
        } elseif(trim($res[0]) == "!trap"){
          $msg = "Failed to Edit customer into Mikrotik !";
          if($res[1] == "=message=failure: secret with the same name already exists"){
            $msg = "Customer with the same name already exists !";
          }elseif($res[2] == "=message=invalid value for argument remote-address"){
            $msg = "Invalid Ip Address !";
          }elseif($res[2] == "=message=input does not match any value of profile"){
            $msg = "Profile does not exist in Mikrotik !";
          }elseif($res[2] == "=message=not enough permissions (9)"){
            $msg = "Router write permission denied";
          }
          $jsonRes = array('status' => 'failed', 'msg' => $msg);
          echo json_encode($jsonRes);
        }
      }else{

        $res = $this->add_pppoe_to_mikrotik($id_router, $net_user_username, $password, $net_user_mac, $id_profile, $net_user_ip_address);

        if(trim($res[0]) == "!done"){
          $id_mk_pppoe = str_replace('=ret=', '', $res[1]);
          $this->db->update('net_user', array('id_mk_pppoe'=> $id_mk_pppoe),array('id_net_user' => $id_net_user ));
          $db_edit_flag= true;
          //delete static customer from mikrotik
          $this->delete_static_customer_from_mikrotik($id_router,$id_mk_static);
          //delete firewall from db
          $this->db->delete('firewall',array('id_mk_static' => $id_mk_static,'id_router' => $id_router));
        } elseif(trim($res[0]) == "!trap"){
          $msg = "Failed to add customer into Mikrotik !";
          if($res[1] == "=message=failure: secret with the same name already exists"){
            $msg = "Customer with the same name already exists !";
          }elseif($res[2] == "=message=invalid value for argument remote-address"){
            $msg = "Invalid Ip Address !";
          }elseif($res[2] == "=message=input does not match any value of profile"){
            $msg = "Profile does not exist in Mikrotik !";
          }elseif($res[1] == "=message=not enough permissions (9)"){
            $msg = "Router write permission denied";
          }
          $jsonRes = array('status' => 'failed', 'msg' => $msg);
          echo json_encode($jsonRes);
        }
      }

    }elseif($id_net_user_type == 2){

      $this->router_model->login($router_ip,$router_login,$router_password);
      //checking if mikrotik has that user
      $has_mikrotik_static = $this->mikrotik_api->ip()->firewall()->detail_firewall_filter($id_mk_static);

      if($has_mikrotik_static){

        $res = $this->edit_static_customer($id_router,$net_user_mac,$net_user_ip_address,$id_net_user,$id_mk_static);

        if(trim($res[0]) == "!done"){
          $db_edit_flag= true;
          //delete pppoe from mikrotik
          $this->delete_pppoe_customer_from_mikrotik($id_router,$id_mk_pppoe);
          //delete net_user_profile
          $this->db->delete('net_user_profile',array('id_net_user'=>$id_net_user));
        } elseif(trim($res[0]) == "!trap"){
          $msg = "Failed to Edit customer into Mikrotik !";
          if($res[2] == "=message=value of range expects range of ip addresses"){
            $msg = "Invalid Ip Address !";
          }elseif($res[2] == "=message=invalid value of address, mac address required"){
            $msg = "Invalid mac address !";
          }elseif($res[2] == "=message=not enough permissions (9)"){
            $msg = "Router write permission denied";
          }
          $jsonRes = array('status' => 'failed', 'msg' => $msg);
          echo json_encode($jsonRes);
        }
      }else{

        $res = $this->add_static_customer_to_mikrotik($id_router,$net_user_mac,$net_user_ip_address);

        if(trim($res[0]) == "!done"){
          $db_edit_flag= true;

          //delete pppoe from mikrotik
          $this->delete_pppoe_customer_from_mikrotik($id_router,$id_mk_pppoe);
          //delete net_user_profile
          $this->db->delete('net_user_profile',array('id_net_user'=>$id_net_user));


          $id_mk_static   = str_replace('=ret=', '', $res[1]);
          $min_order      = $this->get_min_entry_order($id_router);

          $firewalls = $this->router_model->get_all_firewall_by_router_id($id_router);
          $this->db->update('net_user', array('id_mk_static'=> $id_mk_static),array('id_net_user' => $id_net_user ));

          $firewall_sql = array();
          $firewall_sql['chain'] = 'forward';
          if(!empty($net_user_ip_address)){
            $firewall_sql['src-address'] = $net_user_ip_address;
          }if(!empty($net_user_mac)){
            $firewall_sql['src-mac-address'] = $net_user_mac;
          }
          $firewall_sql['action']       = 'accept';
          $firewall_sql['id_mk_static'] = $id_mk_static;
          $firewall_sql['id_router']    = $id_router;
          $firewall_sql['entry_order']  = $min_order;
          $this->db->insert('firewall', $firewall_sql);

          // Now need to apply move command to replace the position to 0.
          $swap_sql = array();
          $swap_sql['id'] = $id_mk_static;
          $swap_sql['destination'] = $this->get_mk_static_by_min_order($min_order,$id_router);

          $move_res = $this->mikrotik_api->ip()->firewall()->move_firewall_filter($swap_sql);

          if(trim($move_res[0]) == "!done"){
            //increasing entry_order by one
            foreach ($firewalls as $item) {
              $this->db->update('firewall', array('entry_order'=>($item['entry_order']+1)),array('id_mk_static'=>$item['id_mk_static'],'id_router' => $id_router));
            }
          }
        }elseif(trim($res[0]) == "!trap"){
          $msg = "Failed to Edit customer into Mikrotik !";
          if($res[2] == "=message=value of range expects range of ip addresses"){
            $msg ="Invalid Ip Address !";
          }elseif($res[2] == "=message=invalid value of address, mac address required"){
            $msg = "Invalid mac address !";
          }elseif($res[1] == "=message=not enough permissions (9)"){
            $msg = "Router write permission denied";
          }
          $jsonRes = array('status' => 'failed', 'msg' => $msg);
          echo json_encode($jsonRes);
        }
      }
    }
  }


  if($db_edit_flag == true){

   //id_profile change
    $updateFlag=0;
    $id_profile_prv = $this->get_id_profile_from_net_user_profile($id_net_user);

    if(!empty($id_profile) && ($id_net_user_type ==1 || $id_net_user_type ==3 )  && ($id_profile != $id_profile_prv)){
      $updateFlag=1;
    }


      $sql = array();
      $sql['net_user_name']             = $net_user_name;
      $sql['net_user_address']          = $net_user_address;
      $sql['net_user_phone']            = $net_user_phone;
      $sql['net_user_email']            = $net_user_email;
      $sql['id_net_user_type']          = $id_net_user_type;
      $sql['net_user_username']         = $net_user_username;
      $sql['net_user_mac']              = $net_user_mac;
      $sql['net_user_nid']              = $net_user_nid;
      $sql['net_user_ip_address']       = $net_user_ip_address;
      $sql['net_user_ip_address_block'] = $net_user_ip_address_block;
      $sql['net_user_mrc_price']        = $net_user_mrc_price;
      $sql['id_package']                = $id_package;
      $sql['service_type']              = $service_type;
      $sql['id_repeat_every']           = $id_repeat_every;
      $sql['service_type']              = $service_type;
      $sql['id_zone']                   = $id_zone;
      $sql['net_user_billing_amount']   = $billing_amount;
      $sql['radio_flag']                = $radio_flag;
      $sql['id_router']                 = $id_router;
      $sql['radio_flag']                = $radio_flag;
      $sql['is_active']                 = $is_active;
      $sql['discount']                  = $discount;

      if(!empty($net_user_password)){
        $sql['net_user_password'] = $net_user_password;
      }

      $this->db->update('net_user',$sql, array('id_net_user'=>$id_net_user));

    if($this->db->affected_rows()==0 && $updateFlag == 0){
      $jsonRes = array('status' => 'failed', 'msg' => 'No change made for edit!');
    }elseif($this->db->affected_rows()>0 || $updateFlag == 1){

      $jsonRes = array('status' => 'success', 'msg' => 'Customer Updated successfully !');

      if($id_net_user_type==1 ||$id_net_user_type==3 ){

        //edit net_user_profile
        if($id_profile > 0){
          $this->db->select('*');
          $this->db->from('net_user_profile');
          $this->db->where(array('id_net_user' => trim($id_net_user)));
          $result = $this->db->get()->result_array();

          if($this->db->affected_rows() > 0) {
            //net_user_profile_update
            $this->db->update('net_user_profile', array('id_profile'=>$id_profile),array('id_net_user' => $id_net_user ));
          }else{
            //net_user profile entry
            $this->db->insert('net_user_profile', array('id_profile'=>$id_profile, 'id_net_user' => $id_net_user ));
          }
        }

        //if net_user_ip_address is null then update the radreply ip pool
        if(empty($net_user_ip_address)) {
          $this->db->select('*');
          $this->db->from('profile');
          $this->db->where(array('id_profile' => trim($id_profile)));
          $result = $this->db->get();
          if($this->db->affected_rows() > 0) {
            $ip_pool_name = $result->row()->profile_remote_address;
          } else {
            $ip_pool_name = "";
          }

          $this->db->select('*');
          $this->db->from('radreply');
          $this->db->where(array('attribute' =>'Framed-Pool','id_net_user' => $id_net_user));
          $this->db->get();

          if($this->db->affected_rows()>0){
            $this->radius_model->update_ip_pool_radcheck($net_user_username, $ip_pool_name, $id_router, $id_net_user);
          }else{
            $this->radius_model->delete_ip_address_radreply($id_router,$id_net_user);
            if(!empty($ip_pool_name)){
              $this->radius_model->add_user_radreply($net_user_username, $ip_pool_name,$id_router ,$id_net_user);
            }
          }
        }


        //if net_user_ip_address is not null then update the radreply ip address
        elseif(!empty($net_user_ip_address)){
          $this->db->select('*');
          $this->db->from('radreply');
          $this->db->where("id_net_user = '$id_net_user' and attribute = 'Framed-IP-Address'");
          $this->db->get();

          if($this->db->affected_rows()>0){
            $this->radius_model->update_ip_address_radreply($net_user_username,$net_user_ip_address, $id_router, $id_net_user);
          }else{
            $this->radius_model->add_ip_address_radreply($net_user_username, $net_user_ip_address,$id_router ,$id_net_user);
            if($this->db->affected_rows()>0){
              $this->radius_model->delete_ip_pool_radreply($id_router,$id_net_user);
            }
          }
        }

        //for rad check
        if(strlen($net_user_password)!=0){
          $this->db->select('*');
          $this->db->from('radcheck');
          $this->db->where("id_net_user = '$id_net_user' and attribute = 'Cleartext-Password'");
          $this->db->get();

          if($this->db->affected_rows()>0){
            $this->radius_model->update_user_radcheck($net_user_username,$net_user_password,$id_router,$id_net_user);
          }else{
            $this->radius_model->add_user_radcheck($net_user_username, $net_user_password, $id_router ,$id_net_user);
          }
        }else{
          $this->db->select('*');
          $this->db->from('radcheck');
          $this->db->where("id_net_user = '$id_net_user' and attribute = 'Cleartext-Password'");
          $this->db->get();
          if($this->db->affected_rows()>0){
            $this->db->update('radcheck', array('username'=>$net_user_username),array('id_net_user'=>$id_net_user));
          }else{
            $this->radius_model->add_user_radcheck($net_user_username, $prv_password, $id_router ,$id_net_user);
          }
        }

        if(strlen($net_user_mac)!=0){
          $this->db->select('*');
          $this->db->from('radcheck');
          $this->db->where("username = '$prv_username' and attribute = 'Calling-Station-Id'");
          $this->db->get();
          if($this->db->affected_rows()>0){
            $this->radius_model->update_mac_radcheck( $net_user_username, $net_user_mac,$id_router,$id_net_user);
          }else{
            $this->radius_model->add_mac_radcheck($net_user_username, $net_user_mac, $id_router ,$id_net_user);
          }
        }else{
          $this->radius_model->delete_mac_radcheck($id_router,$id_net_user);
        }
      }else{
        $this->radius_model->delete_ip_address_radreply($id_router,$id_net_user);
        $this->radius_model->delete_user_radcheck($id_router,$id_net_user);
        $this->radius_model->delete_ip_pool_radreply($id_router,$id_net_user);
        $this->radius_model->delete_mac_radcheck($id_router,$id_net_user);
      }
    }
    echo json_encode($jsonRes);
  }
}

public function edit_pppoe_mikrotik($id_router, $net_user_username, $net_user_password,$net_user_mac,$id_profile,$net_user_ip_address,$id_mk_pppoe){

  $router_details = $this->router_model->get_routers_indv_info($id_router);

  foreach($router_details as $router_indiv):
    $router_ip		 	  =$router_indiv["ip_address"];
    $router_name	  	=$router_indiv["name"];
    $router_login 		=$router_indiv["login"];
    $router_password  =$router_indiv["password"];
    $sync_router_flag =$router_indiv["sync_router_flag"];
  endforeach;

  if($sync_router_flag){
    $this->router_model->login($router_ip,$router_login,$router_password);

    $sql=array();
    $sql['name'] = $net_user_username;

    if(!empty($net_user_password)){
      $sql['password'] = $net_user_password;
    }

    if($id_profile > 0){
      $profileName = $this->router_model->get_profile_name($id_profile);
      $sql['profile'] = $profileName;
    }

    if(!empty($net_user_ip_address)){
      $sql['remote-address'] = $net_user_ip_address;
    }else{
      $this->mikrotik_api->ppp()->ppp_secret()->unset_ppp_secret($id_mk_pppoe, 'remote-address');
    }

    if(!empty($net_user_mac)){
      $sql['caller-id'] =$net_user_mac;
    }else{
      $sql['caller-id'] ='';
    }

    $res = $this->mikrotik_api->ppp()->ppp_secret()->set_ppp_secret($sql,$id_mk_pppoe);
  }else{
    $res ="Mikrotik Sync Disabled !!";
  }

  return $res;
}


public function edit_static_customer($id_router,$net_user_mac,$net_user_ip_address,$id_net_user,$id_mk_static){

  $router_details = $this->router_model->get_routers_indv_info($id_router);
  foreach($router_details as $router_indiv):
    $router_ip		 	  = $router_indiv["ip_address"];
    $router_name	  	= $router_indiv["name"];
    $router_login 		= $router_indiv["login"];
    $router_password  = $router_indiv["password"];
    $sync_router_flag = $router_indiv["sync_router_flag"];
  endforeach;

  if($sync_router_flag){
    $this->router_model->login($router_ip,$router_login,$router_password);
    $id_mk_static = $this->get_id_mk_static($id_net_user);

    $firewall_sql=array();
    $firewall_sql['chain'] = 'forward';
    if(!empty($net_user_ip_address)){
      $firewall_sql['src-address'] = $net_user_ip_address;
    }else{
      $this->mikrotik_api->ip()->firewall()->unset_firewall_filter($id_mk_static, 'src-address');
      // $firewall_sql['src-address']='';
    }
    if(!empty($net_user_mac)){
      $firewall_sql['src-mac-address'] = $net_user_mac;
    }else{
      $this->mikrotik_api->ip()->firewall()->unset_firewall_filter($id_mk_static, 'src-mac-address');
    //  $firewall_sql['src-mac-address']='';
    }
    $firewall_sql['action'] = 'accept';

    $res = $this->mikrotik_api->ip()->firewall()->set_firewall_filter($firewall_sql,$id_mk_static);

    if(trim($res[0])=="!done"){
      if(empty($net_user_ip_address)){
        $firewall_sql['src-address']='';
      }

      if(empty($net_user_mac)){
        $firewall_sql['src-mac-address']='';
      }


      $this->db->update('firewall',$firewall_sql, array('id_mk_static'=> $id_mk_static));
    }

  }else{
    $res = "Mikrotik Sync Disabled";
  }

  return $res;
}




public function delete_single_connection_now()
  {

    $jsonRes = array('status' => 'success', 'msg' => 'Successfully Deleted !', 'data' => '');
    $id_connection = $this->input->post('id_connection');
    $id_net_user = $this->input->post('id_net_user');
      error_log($id_connection);
    // error_log("id_net_user".$id_net_user."--pop_contact_id".$id_net_user_contact);



    if(!empty($id_connection) && !empty($id_net_user)){
      $this->db->delete('connection_customer', array('id_connection'=>$id_connection));

      if($this->db->affected_rows()>0){
        //$jsonRes['data']=$this->get_individual_pop($id_net_user);
        $jsonRes['data']=$this->get_indiv_user_connetions($id_net_user);
      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to delete !');

      }
    }
    echo json_encode($jsonRes);
  }


public  function get_all_repeat_everys(){
  $this->db->select('*');
  $this->db->from('repeat_every');
  return $this->db->get()->result_array();
}

public  function get_all_routers(){
  $this->db->select('*');
  $this->db->from('router');
  $this->db->join('router_type', 'router_type.id_router_type=router.id_router_type', 'left');
  return $this->db->get()->result_array();
}

public  function get_all_packages(){
  $this->db->select('*');
  $this->db->from('package');
  return $this->db->get()->result_array();
}


public function get_net_users_by_zone_access($user_id){

  $this->db->select('*');
  $this->db->from('zone_access');
  $this->db->join('net_user', 'net_user.id_zone = zone_access.id_zone', 'left');
  $this->db->join('net_user_type', 'net_user_type.id_net_user_type=net_user.id_net_user_type', 'left');
  $this->db->join('package', 'package.id_package=net_user.id_package', 'left');
  $this->db->join('repeat_every', 'repeat_every.id_repeat_every=net_user.id_repeat_every', 'left');
  $this->db->where(array('zone_access.id_user' => $user_id, 'net_user.id_net_user >' => '0'));
  $this->db->order_by("net_user.id_net_user", "desc");

  return $this->db->get()->result_array();
}



	public  function get_net_users(){

	  $this->db->select('*');
	  $this->db->from('net_user');
    $this->db->join('net_user_type', 'net_user_type.id_net_user_type=net_user.id_net_user_type', 'left');
    $this->db->join('package', 'package.id_package=net_user.id_package', 'left');
    $this->db->join('repeat_every', 'repeat_every.id_repeat_every=net_user.id_repeat_every', 'left');
    $this->db->order_by("net_user.id_net_user", "desc");
	  return $this->db->get()->result_array();
	}

  public function get_indiv_customers($id_net_user){
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->join('net_user_type', 'net_user_type.id_net_user_type=net_user.id_net_user_type', 'left');
    $this->db->join('package', 'package.id_package=net_user.id_package', 'left');
    $this->db->join('repeat_every', 'repeat_every.id_repeat_every=net_user.id_repeat_every', 'left');
    $this->db->join('zone', 'zone.id_zone=net_user.id_zone', 'left');
    $this->db->where(array('net_user.id_net_user' => $id_net_user));
   return $this->db->get()->result_array();
  }

  public function get_customers_by_id_package($id_package){
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->join('net_user_type', 'net_user_type.id_net_user_type=net_user.id_net_user_type', 'left');
    $this->db->join('package', 'package.id_package=net_user.id_package', 'left');
    $this->db->join('repeat_every', 'repeat_every.id_repeat_every=net_user.id_repeat_every', 'left');
      $this->db->join('zone', 'zone.id_zone=net_user.id_zone', 'left');
    $this->db->where(array('net_user.id_package' => $id_package));
   return $this->db->get()->result_array();
  }

  public  function get_net_users_for_invoice(){

    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->join('net_user_category', 'net_user_category.id_net_user_category=net_user.id_net_user_category', 'left');
    return $this->db->get()->result_array();
  }


  public function get_router_info($id) {
    $this->db->select('*');
	  $this->db->from('router');
		$this->db->where(array('id' => $id));

	  return $this->db->get();
  }

	public function delete_router_now(){

		$router_id = $this->input->post('router_id');
		$router_id= trim($router_id);

		if(!empty($router_id)){
			$this->db->delete('router', array('id'=>$router_id));
			if($this->db->affected_rows()>0){
				echo '<span class="success">Successfully deleted !</span>';
			}
		}
	}


  public function delete_net_user_now(){
    $jsonRes           = array('status' => 'passed', 'msg' => 'Successfully Deleted User!', 'data' => '');
    $id_net_user       = $this->input->post('id_net_user');
    $id_router         = $this->get_router_id_by_customer_id($id_net_user);
    $username          = $this->input->post('net_user_username');
    $id_net_user_type  = $this->get_router_id_net_user_type_customer_id($id_net_user);

    // get the sync flag from MT
    $router_details = $this->router_model->get_routers_indv_info($id_router);

    foreach($router_details as $router_indiv) {
      $router_ip		 	  = $router_indiv["ip_address"];
      $router_name	  	= $router_indiv["name"];
      $router_login 		= $router_indiv["login"];
      $router_password  = $router_indiv["password"];
      $sync_router_flag = $router_indiv["sync_router_flag"];
    }

    // Static user can not edit without sync flag enable
    if($sync_router_flag == false && $id_net_user_type == 2) {
      $jsonRes = array('status' => 'failed', 'msg' => 'Static customer can not delete with sync flag off!');
      echo json_encode($jsonRes);
      return;
    }


    $db_delete_flag =true;
    // if MT sync true then try to insert MT
    if($sync_router_flag == true) {
      $db_delete_flag =false;
      // mk login validation
      $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

      if($mk_validation == false){
        $jsonRes = array('status' => 'failed', 'msg' => 'Delete Opeartion failed, Failed to login with mikrotik !! Invalid username or password');
        echo json_encode($jsonRes);
        return;
      }
    }

    if($id_net_user_type == 1 || $id_net_user_type == 3){
      //delete pppoe customer from mikrotik
      $id_mk_pppoe      = $this->get_id_mk_pppoe($id_net_user);
      if($id_mk_pppoe == "0"){
        $db_delete_flag =true;
      }else{
        $mk_delete_flag   = $this->delete_pppoe_customer_from_mikrotik($id_router,$id_mk_pppoe);
        if($mk_delete_flag == "!done"){
          $db_delete_flag =true;
        }
      }

    }elseif($id_net_user_type == 2){
      //delete static customer from mikrotik
      $id_mk_static   = $this->get_id_mk_static($id_net_user);

      if($id_mk_static == "0"){
        $db_delete_flag =true;
      }else{
        $mk_delete_flag = $this->delete_static_customer_from_mikrotik($id_router,$id_mk_static);
        if($mk_delete_flag == "!done"){
          $db_delete_flag =true;
        }
      }
    }

    if($db_delete_flag){
      $this->db->delete('net_user', array('id_net_user'=>$id_net_user));

      if($this->db->affected_rows()>0){

        $jsonRes['data'] = $this->get_net_users();
        if($id_net_user_type == 1 || $id_net_user_type == 3){
          //delete customer from radcheck
          $this->db->delete('radcheck', array('username'=>$username,'id_net_user'=>$id_net_user));
          //delete customer from radreply
          $this->db->delete('radreply', array('username'=>$username,'id_net_user'=>$id_net_user));

            $this->db->delete('net_user_profile', array('id_net_user'=>$id_net_user));
        }elseif($id_net_user_type == 2){
          //delete static customer from firewall DB
          $this->db->delete('firewall', array('id_mk_static'=> $id_mk_static,'id_router' => $id_router));
        }
      } else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Delete Opeartion Failed !');
      }
    }


    echo json_encode($jsonRes);
  }


  // public function delete_net_user_now(){
  //
  //   $id_net_user       = $this->input->post('id_net_user');
  //   $id_router         = $this->get_router_id_by_customer_id($id_net_user);
  //   $username = $this->input->post('net_user_username');
  //   $id_net_user_type  = $this->get_router_id_net_user_type_customer_id($id_net_user);
  //
  //   // get the sync flag from MT
  //   $router_details = $this->router_model->get_routers_indv_info($id_router);
  //
  //   foreach($router_details as $router_indiv) {
  //     $router_ip		 	  = $router_indiv["ip_address"];
  //     $router_name	  	= $router_indiv["name"];
  //     $router_login 		= $router_indiv["login"];
  //     $router_password  = $router_indiv["password"];
  //     $sync_router_flag = $router_indiv["sync_router_flag"];
  //   }
  //
  //
  //   $db_delete_flag =true;
  //   // if MT sync true then try to insert MT
  //   if($sync_router_flag == true) {
  //       $db_delete_flag =false;
  //     // mk login validation
  //     $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);
  //
  //     if($mk_validation == false){
  //       $jsonRes = array('status' => 'failed', 'msg' => 'Delete Opeartion failed, Failed to login with mikrotik !! Invalid username or password');
  //       echo json_encode($jsonRes);
  //       return;
  //     }
  //
  //     if($id_net_user_type == 1 || $id_net_user_type == 3){
  //       //delete pppoe customer from mikrotik
  //       $id_mk_pppoe      = $this->get_id_mk_pppoe($id_net_user);
  //
  //       if($id_mk_pppoe == "0"){
  //         $db_delete_flag =true;
  //       }else{
  //         $res   = $this->delete_pppoe_customer_from_mikrotik($id_router,$id_mk_pppoe);
  //         // errot_log("::::::::::::::::::::::::::::::::::;".print_r($res));
  //         if($res == 1){
  //           $db_delete_flag = true;
  //         }elseif($res == false){
  //           $jsonRes = array('status' => 'failed', 'msg' => 'Delete Opeartion Failed from mikrotik!');
  //         }
  //           echo json_encode($jsonRes);
  //       }
  //
  //     }elseif($id_net_user_type == 2){
  //       //delete static customer from mikrotik
  //       $id_mk_static   = $this->get_id_mk_static($id_net_user);
  //
  //       if($id_mk_static == "0"){
  //         $db_delete_flag = true;
  //       }else{
  //         $res = $this->delete_static_customer_from_mikrotik($id_router,$id_mk_static);
  //
  //         if(trim($res[0]) == "!done"){
  //           $db_delete_flag =true;
  //         }elseif(trim($res[0]) == "!trap"){
  //           $jsonRes = array('status' => 'failed', 'msg' => 'Delete Opeartion Failed from mikrotik!');
  //         }
  //           echo json_encode($jsonRes);
  //       }
  //
  //     }
  //   }
  //
  //   if($db_delete_flag == true) {
  //       $jsonRes           = array('status' => 'passed', 'msg' => 'Successfully Deleted User!', 'data' => '');
  //       $this->db->delete('net_user', array('id_net_user'=>$id_net_user));
  //
  //       if($this->db->affected_rows()>0){
  //
  //         $jsonRes['data'] = $this->get_net_users();
  //         if($id_net_user_type == 1 || $id_net_user_type == 3){
  //           //delete customer from radcheck
  //           $this->db->delete('radcheck', array('username'=>$username,'id_net_user'=>$id_net_user));
  //           //delete customer from radreply
  //           $this->db->delete('radreply', array('username'=>$username,'id_net_user'=>$id_net_user));
  //         }elseif($id_net_user_type == 2){
  //           //delete static customer from firewall DB
  //           $this->db->delete('firewall', array('id_mk_static'=> $id_mk_static,'id_router' => $id_router));
  //         }
  //       } else {
  //         $jsonRes = array('status' => 'failed', 'msg' => 'Delete Opeartion Failed !');
  //       }
  //         echo json_encode($jsonRes);
  //     }
  //
  //
  // }




    public function delete_pppoe_customer_from_mikrotik($id_router,$id_mk_pppoe){

      $router_details = $this->router_model->get_routers_indv_info($id_router);
      foreach($router_details as $router_indiv):
        $router_ip		 	  =$router_indiv["ip_address"];
        $router_login 		=$router_indiv["login"];
        $router_password  =$router_indiv["password"];
        $sync_router_flag =$router_indiv["sync_router_flag"];
      endforeach;
     if($sync_router_flag){
       $this->router_model->login($router_ip,$router_login,$router_password);
       $res =$this->mikrotik_api->ppp()->ppp_secret()->delete_ppp_secret(trim($id_mk_pppoe));
       if(trim($res[0])=="!done"){
        return $res[0];
       }
     }
    }

    public function delete_static_customer_from_mikrotik($id_router,$id_mk_static){
      $router_details = $this->router_model->get_routers_indv_info($id_router);
      foreach($router_details as $router_indiv):
        $router_ip		 	  =$router_indiv["ip_address"];
        $router_login 		=$router_indiv["login"];
        $router_password  =$router_indiv["password"];
        $sync_router_flag =$router_indiv["sync_router_flag"];
      endforeach;

     if($sync_router_flag){
       $this->router_model->login($router_ip,$router_login,$router_password);
       $res = $this->mikrotik_api->ip()->firewall()->delete_firewall_filter(trim($id_mk_static));
       if(trim($res[0])=="!done"){
        return $res[0];
       }
     }
    }


  public function get_router_id_by_customer_id($id_net_user) {
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->where(array('id_net_user' => $id_net_user));
    $result= $this->db->get()->row();
    return $result->id_router;
  }

  public function get_router_id_net_user_type_customer_id($id_net_user) {
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->where(array('id_net_user' => $id_net_user));
    $result= $this->db->get()->row();
    return $result->id_net_user_type;
  }



  public function get_net_user_info($id_net_user)
  {

    error_log("NET_USR".$id_net_user);
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->where(array('id_net_user' => $id_net_user));
    return $this->db->get()->result_array();
  }

  public function get_indiv_user_connetions($id_net_user)
  {


    $this->db->select('*');
       $this->db->from('connection_customer');
       $this->db->join('router', 'router.id=connection_customer.id_router', 'left');
       $this->db->join('net_user','net_user.id_net_user=connection_customer.id_net_user', 'left');
       $this->db->join('net_user_type','net_user_type.id_net_user_type=connection_customer.id_net_user_type', 'left');
       $this->db->join('net_user_category','net_user_category.id_net_user_category=net_user.id_net_user_category', 'left');
       $this->db->where(array('connection_customer.id_net_user' => $id_net_user));
       return $this->db->get()->result_array();
  }

  public function get_individual_invoice($id_net_user)
  {
    $this->db->select('*');
    $this->db->from('invoice');
    $this->db->where(array('id_net_user' => $id_net_user));
    return $this->db->get()->result_array();
  }


  public function get_individual_transactions($id_net_user)
  {
    $this->db->select('*');
    $this->db->from('payment');
    $this->db->join('invoice', 'invoice.id_invoice=payment.id_invoice', 'left');
    $this->db->where('invoice.id_net_user' , $id_net_user);
    return $this->db->get()->result_array();
  }

	public function edit_router_now(){

		if($this->input->post('submit')) {

			$router_id = $this->input->post('router_id');
			$router_name = $this->input->post('router_name');
			$router_ip = $this->input->post('router_ip');
			$router_login = $this->input->post('router_login');
			$router_password = $this->input->post('router_password');
			$id_router_type = $this->input->post('id_router_type');

			$this->db->set(array('name'=>$router_name,'ip_address '=>$router_ip, 'login' => $router_login, 'password' => 'router_password', 'id_router_type' => $id_router_type))
		    ->where('id',$router_id)
        ->update('router');
      if($this->db->affected_rows() >0) {
        echo '<span class="success">Update successful</span>';
			} elseif($this->db->affected_rows() == 0) {
				echo '<span class="success">No change made for edit</span>';
			}
		}
	}

  public function edit_ppp_user_now()
  {
    if($this->input->post('submit')) {
      $id_net_user   = $this->input->post('id_net_user');
      $net_user_name = $this->input->post('net_user_name');
      $email = $this->input->post('email');
      $phone       = $this->input->post('phone');
      $net_user_addr       = $this->input->post('net_user_addr');
      $id_net_user_category       = $this->input->post('id_net_user_category');
      if(isset($_POST['net_user_status'])) $net_user_status="Active";
      elseif (!isset($_POST['net_user_status'])) $net_user_status="Inactive";


      $this->db->set(array(
                     'net_user_name' => $net_user_name,
                     'email' => $email,
                     'phone' => $phone,
                     'id_net_user_category' => $id_net_user_category,
                     'net_user_addr' => $net_user_addr,
                     'net_user_status'=>$net_user_status))
        ->where('id_net_user',$id_net_user)
        ->update('net_user');
      if($this->db->affected_rows() >0) {
        $jsonRes = array('status' => 'success', 'msg' => 'Succesfully Updated !');
      } elseif($this->db->affected_rows() == 0) {
          $jsonRes = array('status' => 'failed', 'msg' => 'No change made !');
      }
    }
    echo json_encode($jsonRes);
  }

	public function fetch_router_info(){
		$router_id = $this->input->post('router_id');

		$this->db->select('*');
		$this->db->from('router');
		$this->db->where(array('id'=>$router_id));
		$query=$this->db->get();
		$data=$query->result_array();

		return $data;
	}

  public function add_picture_now($id_net_user)
  {
    $path = $this->input->post('path');
    $path='assets/img/'.$path;
    error_log("add_picture_now:".$id_net_user." -- path".$path);
    $this->db->set('picture',$path)
      ->where('id_net_user',$id_net_user)
      ->update('net_user');
    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'success', 'msg' => 'Picture Uploaded!');
    }
    else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed to upload picture!');
    }

  echo json_encode($jsonRes);

  }

  function get_id_pool_from_net_user_ip_pool($id_net_user){

    $this->db->select('*');
    $this->db->from('net_user_ip_pool');
    $this->db->where(array('id_net_user'=>$id_net_user));
    $query=$this->db->get();
        if($this->db->affected_rows() > 0) {
          return $query->row()->id_ip_pool;
        }else{
            return 0;
        }

  }

  function get_id_profile_from_net_user_profile($id_net_user){
    $this->db->select('*');
    $this->db->from('net_user_profile');
    $this->db->where(array('id_net_user'=>$id_net_user));
    $query=$this->db->get();

    if($this->db->affected_rows() > 0) {
      return $query->row()->id_profile;
    }else{
        return 0;
    }

  }

  function get_id_net_user_type($id_net_user){
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->where(array('id_net_user'=>$id_net_user));
    $query=$this->db->get()->row();
    return $query->id_net_user_type;
  }

  function get_id_mk_pppoe($id_net_user){
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->where(array('id_net_user'=>$id_net_user));
    $query=$this->db->get()->row();
    return $query->id_mk_pppoe;
  }

  function get_id_mk_static($id_net_user){
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->where(array('id_net_user'=>$id_net_user));
    $query=$this->db->get()->row();
    return $query->id_mk_static;
  }



}

?>
