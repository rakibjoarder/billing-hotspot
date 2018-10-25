<?php
class Router_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  public function is_radius_enable($id_router){

    $radius_flag = 0;

    $this->db->select('*');
    $this->db->from('router');
    $this->db->where(array('id' => trim($id_router)));
    $result = $this->db->get();

    if($this->db->affected_rows() > 0) {
      $radius_flag = $result->row()->radius_flag;
    }

    return $radius_flag;
  }

  function get_routers_type(){
    $this->db->select('*');
    $this->db->from('router_type');
    return $this->db->get()->result_array();
  }

  public  function get_all_routers(){
    $this->db->select('*');
    $this->db->from('router');
    $this->db->join('router_type', 'router.id_router_type=router_type.id_router_type', 'left');
    $this->db->where(array('id_isp' => $this->session->userdata('id_isp')));
    return $this->db->get()->result_array();
  }

  public function get_routers_indv_info($id_router){
    $this->db->select('*');
    $this->db->from('router');
    $this->db->join('router_type', 'router.id_router_type=router_type.id_router_type', 'left');
    $this->db->where('id =',$id_router);
    return $this->db->get()->result_array();
  }

  public function get_all_profile_by_router_id($id) {
    $this->db->select('*');
    $this->db->from('profile');
    $this->db->where('id_router =', $id);
    return $this->db->get()->result_array();
  }

  public function get_all_firewall_by_router_id($id_router){
    $this->db->select('*');
    $this->db->from('firewall');
    $this->db->where('id_router =', $id_router);
    return $this->db->get()->result_array();
  }

  public function get_all_ip_pools_by_router_id($id_router){
    $this->db->select('*');
    $this->db->from('ip_pool');
    $this->db->where('id_router =',$id_router);
    return $this->db->get()->result_array();
  }

  public function get_all_profiles_by_router_id($id_router){
    $this->db->select('*');
    $this->db->from('profile');
    $this->db->where('id_router =',$id_router);
    return $this->db->get()->result_array();
  }

  public function has_ip_pool_name_already($ip_pool_name){

    $this->db->select('*');
    $this->db->from('ip_pool');
    $this->db->where("ip_pool_name = '$ip_pool_name'");
    $this->db->get();

    if($this->db->affected_rows() > 0) {
      $flag = true;
    }else{
      $flag=false;
    }

    return $flag;
  }


  public function add_ip_pool_now(){

    if($this->input->post('submit')){
      $ip_pool_name   = $this->input->post('ip_pool_name');
      $ip_pool_start  = $this->input->post('ip_pool_start');
      $ip_pool_end    = $this->input->post('ip_pool_end');
      $id_router      = $this->input->post('id_router');

       $has_ip_pool_already = $this->has_ip_pool_name_already($ip_pool_name);

      if($has_ip_pool_already == true) {
        $jsonRes = array('status' => 'failed', 'msg' => 'Pool with this name already exists !');
        echo json_encode($jsonRes);
        return;
      }

      $router_details = $this->router_model->get_routers_indv_info($id_router);

      foreach($router_details as $router_indiv) {
        $router_ip		 	  = $router_indiv["ip_address"];
        $router_name	  	= $router_indiv["name"];
        $router_login 		= $router_indiv["login"];
        $router_password  = $router_indiv["password"];
        $sync_router_flag = $router_indiv["sync_router_flag"];
      }

      $db_insert_flag = true;
      $id_mk_pool = "0";

      // if MT sync true then try to insert MT
      if($sync_router_flag == true) {

        // mk login validation
        $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

        if($mk_validation == false){
          $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
          echo json_encode($jsonRes);
          return;
        }

        $db_insert_flag = false;

        if(!empty($ip_pool_end)){
          $range = $ip_pool_start.'-'.$ip_pool_end;
        }else{
          $range = $ip_pool_start;
        }

        $res = $this->add_ip_pool_to_mikrotik($id_router, $ip_pool_name, $range);

        if(trim($res[0]) == "!done"){
           $id_mk_pool  = str_replace('=ret=', '', $res[1]);
          $db_insert_flag = true;
        }elseif(trim($res[0]) == "!trap"){
          $msg = "Failed to add ip pool into Mikrotik !";
          if($res[2] == "=message=value of range expects range of ip addresses" || $res[2] == "=message=value of range must have ip address before '-'"){
            $msg = "Invalid Starting Ip Address !";
          }elseif($res[2] == "=message=value of range must have ip address after '-'"){
            $msg = "Invalid Ending Ip Address !";
          }elseif($res[1] == "=message=failure: pool with such name exists"){
            $msg = " Pool with such name exists !";
          }elseif($res[1] == "=message=not enough permissions (9)"){
            $msg = "Router write permission denied";
          }

          $jsonRes = array('status' => 'failed', 'msg' => $msg);
        }

      }


      if($db_insert_flag == true){
        $info = array( 'ip_pool_name' => $ip_pool_name,
        'ip_pool_start'=> $ip_pool_start,
        'ip_pool_end' =>  $ip_pool_end,
        'id_router'   =>  $id_router);
        $this->db->insert('ip_pool', $info);
        $id_ip_pool= $this->db->insert_id();
        if($this->db->affected_rows() > 0) {
          $this->db->update('ip_pool', array('id_mk_pool'=> $id_mk_pool),array('id_ip_pool' => $id_ip_pool ));
          $jsonRes = array('status' => 'success', 'msg' => 'IP Pool Added !');
        }
      }
      echo json_encode($jsonRes);
    }
  }



  public function add_ip_pool_to_mikrotik($id_router, $ip_pool_name, $range){

    $router_details=$this->get_routers_indv_info($id_router);

    foreach($router_details as $router_indiv):
      $router_ip		 	  =$router_indiv["ip_address"];
      $router_name	  	=$router_indiv["name"];
      $router_login 		=$router_indiv["login"];
      $router_password  =$router_indiv["password"];
      $sync_router_flag =$router_indiv["sync_router_flag"];
    endforeach;

    if($sync_router_flag){
      $this->login($router_ip,$router_login,$router_password);
      $param = array("name"  => $ip_pool_name, "ranges" => $range);
      $res = $this->mikrotik_api->ip()->pool()->add_pool($param);
    }else {
      $res = "Mikrotik Sync Disabled";
    }

    return $res;
  }

  public function delete_ip_pool_now(){
    $ip_pool_id = $this->input->post('ip_pool_id');
    $id_router = $this->input->post('id_router');
    $id_mk_pool = $this->get_id_mk_pool_by_id_pool($ip_pool_id);

    $router_details = $this->router_model->get_routers_indv_info($id_router);

    foreach($router_details as $router_indiv) {
      $router_ip		 	  = $router_indiv["ip_address"];
      $router_name	  	= $router_indiv["name"];
      $router_login 		= $router_indiv["login"];
      $router_password  = $router_indiv["password"];
      $sync_router_flag = $router_indiv["sync_router_flag"];
    }

     $db_delete_flag = true;

     if($sync_router_flag =true){
       // mk login validation
       $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

       if($mk_validation == false){
         $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
         echo json_encode($jsonRes);
         return;
       }
       $db_delete_flag = false;

       $has_pool_mk = $this->mikrotik_api->ip()->pool()->detail_pool($id_mk_pool);

       if(sizeof($has_pool_mk) > 0){
         $res = $this->delete_ip_pool_from_mikrotik($id_router,$id_mk_pool);

         if(trim($res[0]) == "!done"){
           $db_delete_flag = true;
         }elseif(trim($res[0]) == "!trap"){
           $jsonRes = array('status' => 'failed', 'msg' => 'Failed to delete from Mikrotik');
           echo json_encode($jsonRes);
         }
       }else{
         $db_delete_flag =true;
       }
     }

    if($db_delete_flag == true){
      $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted !', 'data' => '');
      $this->db->delete('ip_pool', array('id_ip_pool'=>$ip_pool_id));

      if($this->db->affected_rows()>0){
        $jsonRes['data'] = $this->get_all_ip_pools_by_router_id($id_router);
      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to delete !');
      }
        echo json_encode($jsonRes);
    }

  }

  public function delete_ip_pool_from_mikrotik($id_router,$id_mk_pool){

    $router_details=$this->get_routers_indv_info($id_router);
    foreach($router_details as $router_indiv):
      $router_ip		 	  =$router_indiv["ip_address"];
      $router_login 		=$router_indiv["login"];
      $router_password  =$router_indiv["password"];
      $sync_router_flag =$router_indiv["sync_router_flag"];
    endforeach;
    $this->login($router_ip,$router_login,$router_password);


    if($sync_router_flag){
      $res = $this->mikrotik_api->ip()->pool()->delete_pool(trim($id_mk_pool));

      return $res;
    }

  }


  public function get_pool_name($ip_pool_id) {
    $this->db->select('*');
    $this->db->from('ip_pool');
    $this->db->where(array('id_ip_pool' => $ip_pool_id));
    $result= $this->db->get()->result_array();

    foreach($result as $result_indiv):
      $pool_name	=$result_indiv["ip_pool_name"];
    endforeach;
    return $pool_name;
  }



  public function login($router_ip,$router_login,$router_password){
    error_log("Login CALLED");
    error_log("Logging in with ".$router_ip."-".$router_login);
    $config['mikrotik']['host'] = $router_ip;
    $config['mikrotik']['port'] = '8728';
    $config['mikrotik']['username'] = $router_login;
    $config['mikrotik']['password'] = $router_password;
    $config['mikrotik']['debug'] = FALSE;
    $config['mikrotik']['attempts'] = 3;
    $config['mikrotik']['delay'] = 2;
    $config['mikrotik']['timeout'] = 2;
    $this->session->set_userdata($config);
    $this->session->set_userdata('loggedin', TRUE);
    $config['mikrotik'] = $this->session->userdata('mikrotik');
    $this->mikrotik_api->initialize($config);
    //$this->mikrotik_api->core()->debug("HELLO DEBUG");
    //$test=$this->mikrotik_api->connect();
    // $data['interfaces'] = $this->mikrotik_api->interfaces()->ethernet()->get_all_interface();
    //print_r($test);
  }

  public function get_individual_ip_pool($id_ip_pool){
    $this->db->select('*');
    $this->db->from('ip_pool');
    $this->db->where('id_ip_pool',$id_ip_pool);
    return $this->db->get()->result_array();
  }



  public function has_ip_pool_already_edit($ip_pool_name,$id_ip_pool){
    $this->db->select('*');
    $this->db->from('ip_pool');
    $this->db->where("ip_pool_name = '$ip_pool_name' and id_ip_pool != '$id_ip_pool'");
    $this->db->get();
      if($this->db->affected_rows() > 0) {
        $flag = true;
      }else{
        $flag = false;
      }
    return $flag;
  }


  public function get_id_mk_pool_by_id_pool($id_ip_pool){
    $this->db->select('*');
    $this->db->from('ip_pool');
    $this->db->where(array("id_ip_pool" => $id_ip_pool));
    return $this->db->get()->row()->id_mk_pool;
  }

  public function edit_ip_pool_now(){

    if($this->input->post('submit')){

      $id_ip_pool     = $this->input->post('id_ip_pool');
      $ip_pool_name   = $this->input->post('ip_pool_name');
      $ip_pool_start  = $this->input->post('ip_pool_start');
      $ip_pool_end    = $this->input->post('ip_pool_end');
      $id_router      = $this->input->post('id_router');
      $ip_pool_prv_name= $this->input->post('ip_pool_prv_name');
      $id_mk_pool     = $this->get_id_mk_pool_by_id_pool($id_ip_pool);


      $has_ip_pool_already = $this->has_ip_pool_already_edit($ip_pool_name,$id_ip_pool);

      if($has_ip_pool_already){
        $jsonRes = array('status' => 'failed', 'msg' => 'Pool with this name already exists !');
        echo json_encode($jsonRes);
        return;
      }


      $router_details = $this->router_model->get_routers_indv_info($id_router);

      foreach($router_details as $router_indiv) {
        $router_ip		 	  = $router_indiv["ip_address"];
        $router_name	  	= $router_indiv["name"];
        $router_login 		= $router_indiv["login"];
        $router_password  = $router_indiv["password"];
        $sync_router_flag = $router_indiv["sync_router_flag"];
      }

      $db_update_flag = true;
      // if MT sync true then try to insert MT
      if($sync_router_flag == true) {

        // mk login validation
        $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

        if($mk_validation == false){
          $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
          echo json_encode($jsonRes);
          return;
        }

        $db_update_flag = false;

        if(!empty($ip_pool_end)){
          $range=$ip_pool_start.'-'.$ip_pool_end;
        }else{
          $range=$ip_pool_start;
        }



        $has_pool_mk = $this->mikrotik_api->ip()->pool()->detail_pool($id_mk_pool);

        if(sizeof($has_pool_mk) > 0){
          $res = $this->edit_ip_pool_mikrotik($id_router, $ip_pool_name, $range,$id_mk_pool);

          if(trim($res[0]) == "!done"){
            $db_update_flag = true;
          }elseif(trim($res[0]) == "!trap"){
            $msg = "Failed to Edit Ip Pool into Mikrotik !";
            if($res[2] == "=message=value of range expects range of ip addresses" || $res[2] == "=message=value of range must have ip address before '-'"){
              $msg = "Invalid Starting Ip Address !";
            }elseif($res[2] == "=message=value of range must have ip address after '-'"){
              $msg = "Invalid Ending Ip Address !";
            }elseif($res[1] == "=message=failure: pool with such name exists"){
              $msg = " Pool with such name exists !";
            }elseif($res[2] == "=message=not enough permissions (9)"){
              $msg = "Router write permission denied";
            }

            $jsonRes = array('status' => 'failed', 'msg' => $msg);
            echo json_encode($jsonRes);
          }
        }else{

          $res = $this->add_ip_pool_to_mikrotik($id_router, $ip_pool_name, $range);

          if(trim($res[0]) == "!done"){
            $id_mk_pool  = str_replace('=ret=', '', $res[1]);
            $this->db->update('ip_pool', array('id_mk_pool'=> $id_mk_pool),array('id_ip_pool' => $id_ip_pool ));
            $db_update_flag = true;
          }elseif(trim($res[0]) == "!trap"){
            $msg = "Failed to Edit pool into Mikrotik !";
            if($res[2] == "=message=value of range expects range of ip addresses" || $res[2] == "=message=value of range must have ip address before '-'"){
              $msg = "Invalid Starting Ip Address !";
            }elseif($res[2] == "=message=value of range must have ip address after '-'"){
              $msg = "Invalid Ending Ip Address !";
            }elseif($res[1] == "=message=failure: pool with such name exists"){
              $msg = " Pool with such name exists !";
            }elseif($res[1] == "=message=not enough permissions (9)"){
              $msg = "Router write permission denied";
            }

            $jsonRes = array('status' => 'failed', 'msg' => $msg);
            echo json_encode($jsonRes);
          }

        }
      }


      if($db_update_flag == true){
        $this->db->set(array('ip_pool_name' => $ip_pool_name,
                             'ip_pool_start'=>  $ip_pool_start,
                             'ip_pool_end' => $ip_pool_end))
                             ->where('id_ip_pool',$id_ip_pool)
                             ->update('ip_pool');

        if($this->db->affected_rows() > 0) {
          //update ip_pool from radreply
          $this->db->update('radreply', array('value'=>$ip_pool_name),array('id_router' => $id_router,'value' =>$ip_pool_prv_name,'attribute' => 'Framed-Pool'));

          $this->db->update('profile', array('profile_remote_address'=>$ip_pool_name),array('id_router' => $id_router,'profile_remote_address' =>$ip_pool_prv_name));
          // $this->db->update('radreply', array('value'=>$ip_pool_name),array('id_router' => $id_router,'value' =>$ip_pool_prv_name,'attribute' => 'Framed-Pool'));
          $jsonRes = array('status' => 'success', 'msg' => 'IP Pool Edited !','name' => $ip_pool_name);
        }
        else {
          $jsonRes = array('status' => 'failed', 'msg' => 'No change made !');
        }
        echo json_encode($jsonRes);
      }
    }
  }


  public function edit_ip_pool_mikrotik($id_router, $ip_pool_name,$range,$id_mk_pool){
    $router_details=$this->get_routers_indv_info($id_router);
    foreach($router_details as $router_indiv):
      $router_ip		 	  =$router_indiv["ip_address"];
      $router_name	  	=$router_indiv["name"];
      $router_login 		=$router_indiv["login"];
      $router_password  =$router_indiv["password"];
      $sync_router_flag =$router_indiv["sync_router_flag"];
    endforeach;

    if($sync_router_flag){
      //mikrotik login
      $this->login($router_ip,$router_login,$router_password);
      $param = array("name"  => $ip_pool_name, "ranges" => $range);
      //update ip_pool from mikrotik
      $res = $this->mikrotik_api->ip()->pool()->set_pool($param,$id_mk_pool);

      return $res;
    }

  }


  public function create_router_now(){
    $router_name         = $this->input->post('router_name');
    $router_ip           = $this->input->post('router_ip');
    $router_login        = $this->input->post('router_login');
    $router_password     = $this->input->post('router_password');
    $id_router_type      = $this->input->post('id_router_type');

    // if(isset($_POST['sync_router_flag'])) $sync_router_flag =1;
    // else $sync_router_flag=0;
    //
    //
    // if(isset($_POST['radius_flag'])) $radius_flag =1;
    // else $radius_flag = 0;

    $sync_router_flag =1;
    $radius_flag =1;

    $this->db->select('*');
    $this->db->from('router');
    $this->db->where(array('ip_address'=>$router_ip));
    $this->db->get();

    if($this->db->affected_rows()>0){
      $jsonRes = array('status' => 'failed', 'msg' => 'IP address already exists !');
    }
    else{
      $this->db->insert('router', array('id_isp' => $this->session->userdata('id_isp'),'sync_router_flag'=>$sync_router_flag,'name'=>$router_name, 'ip_address'=>$router_ip, 'id_router_type' => $id_router_type, 'login' => $router_login, 'password' => $router_password,'radius_flag' => $radius_flag));
      if($this->db->affected_rows() > 0) {
        // allowing router who has created it.
        $insert_id = $this->db->insert_id();
        $this->allow_router_access($this->session->userdata('user_id'), $insert_id);

        if($radius_flag == 1){
          $this->db->insert('nas', array('nasname'=>$router_ip,'shortname'=>$router_name, 'secret'=>$router_password, 'server' => '', 'id_router' => $insert_id));
        }

        $jsonRes = array('status' => 'success', 'msg' => 'Router Created Successfully !');

        $this->db->insert('notification', array(
          'notification_title' => "New Router",
          'notification_body' => $router_name." Created by ".$this->session->userdata('name'),
          'notification_type'  => "message",
          'id_isp'             => $this->session->userdata('id_isp')
        ));


      }else{
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed !');
      }
    }
    echo json_encode($jsonRes);
  }



  public function delete_router_now(){
    $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Router!', 'data' => '');
    $router_id = $this->input->post('id_router');
    $router_name =$this->get_router_name($router_id);
    if(!empty($router_id)){

      $this->db->delete('router', array('id' => $router_id));

      if($this->db->affected_rows() > 0) {

        // removing all router access to user.
        $this->remove_router_access_for_all($router_id);
        $jsonRes['data'] = $this->get_allowed_routers($this->session->userdata('user_id'));


        $this->db->delete('nas', array('id_router' => $router_id));

        $this->db->insert('notification', array(
          'notification_title' => "Router Delete",
          'notification_body' =>  $router_name." deleted by ".$this->session->userdata('name'),
          'notification_type'  => "message",
          'id_isp'             => $this->session->userdata('id_isp')
        ));

      } else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Delete opertaion failed');
      }
    }
    echo json_encode($jsonRes);
  }


  public function get_router_info($id_router) {
    $this->db->select('*');
    $this->db->from('router');
    $this->db->join('router_type', 'router.id_router_type=router_type.id_router_type', 'left');
    $this->db->where(array('id' => $id_router));
    return $this->db->get()->result_array();
  }


  public function edit_router_now(){

    if($this->input->post('submit')) {

      $router_id       = $this->input->post('router_id');
      $router_name     = $this->input->post('router_name');
      $router_ip       = $this->input->post('router_ip');
      $router_login    = $this->input->post('router_login');
      $router_password = $this->input->post('router_password');
      $id_router_type  = $this->input->post('id_router_type');


      if(isset($_POST['is_active'])) $is_active =0;
      else $is_active = 1;

      $prv_router_name =$this->get_router_name($router_id);

      // if(isset($_POST['sync_router_flag'])) $sync_router_flag =1;
      // else $sync_router_flag=0;
      //
      // if(isset($_POST['radius_flag'])) $radius_flag =1;
      // else $radius_flag = 0;

      $sync_router_flag = 1;
      $radius_flag = 1;

      $this->db->select('*');
      $this->db->from('router');
      $this->db->where('ip_address',$router_ip);
      $this->db->where('id!=',$router_id);
      $this->db->get();

      if($this->db->affected_rows()==0){
        if(strlen($router_password)==0){
          $this->db->set(array('is_active' => $is_active,'sync_router_flag'=>$sync_router_flag,'name'=>$router_name,'ip_address '=>$router_ip, 'login' => $router_login,'id_router_type' => $id_router_type,'radius_flag' => $radius_flag))
          ->where(array('id'=>$router_id,'id_isp' => $this->session->userdata('id_isp')))
          ->update('router');
        }
        else{
          $this->db->set(array('is_active' => $is_active,'name'=>$router_name,'ip_address '=>$router_ip, 'login' => $router_login, 'password' => $router_password, 'id_router_type' => $id_router_type,'radius_flag' => $radius_flag))
          ->where(array('id'=>$router_id,'id_isp' => $this->session->userdata('id_isp')))
          ->update('router');
        }
        if($this->db->affected_rows() > 0) {
          $jsonRes = array('status' => 'success', 'msg' => 'Successfully Updated Router !!!');

        if($radius_flag == 0){
          $this->db->delete('nas', array('id_router' => $router_id));
        }else{

          $this->db->select('*');
          $this->db->from('nas');
          $this->db->where('id_router',$router_id);
          $this->db->get();
          if($this->db->affected_rows() > 0){
            if(strlen($router_password)==0){
              $this->db->set(array('nasname' => $router_ip,'shortname'=>$router_name))
              ->where('id_router',$router_id)
              ->update('nas');
            }else{
              $this->db->set(array('nasname' => $router_ip,'shortname'=>$router_name,'secret'=>$router_password))
              ->where('id_router',$router_id)
              ->update('nas');
            }
          }else{
            $this->db->select('*');
            $this->db->from('router');
            $this->db ->where(array('id'=>$router_id,'id_isp' => $this->session->userdata('id_isp')));
            $password = $this->db->get()->row()->password;
            $this->db->insert('nas', array('nasname'=>$router_ip,'shortname'=>$router_name, 'secret'=>$password, 'server' => '', 'id_router' => $router_id));
          }

        }


          $this->db->insert('notification', array(
            'notification_title' => "Router Edit",
            'notification_body' => "Router Name ".$prv_router_name." edited into ".$router_name." by ".$this->session->userdata('name'),
            'notification_type'  => "message",
            'id_isp'             => $this->session->userdata('id_isp')
          ));


        } elseif($this->db->affected_rows() == 0) {
          $jsonRes = array('status' => 'failed', 'msg' => 'No change made for edit !!!');
        }
      }else{
        $jsonRes = array('status' => 'failed', 'msg' => 'IP address already exists');
      }
    }
    echo json_encode($jsonRes);
  }

  public function router_ip_addr_save_db($router_id, $data) {
    foreach($data as $item) {
      if($item['dynamic'] == 'false') {

        $res = explode("/", $item['address']);
        if(sizeof($res) > 0) {
          $addr = $res[0];
        } else {
          $addr = '';
        }

        $this->db->from('router_ip');
        $this->db->where(array('id' => $item['.id'], 'id_router' => $router_id, 'ip_addr' => $addr));
        $this->db->get();

        if($this->db->affected_rows() == 0 && $addr != '') {

          $invalid = (($item['invalid'] === 'false')? 1 : 0);
          $status  = (($item['disabled'] === 'false')? 1 : 0);
          $info = array(
            'id_router'   => $router_id,
            'id'          => $item['.id'],
            'ip_addr'     => $addr,
            'network'     => $item['network'],
            'interface'   => $item['interface'],
            'invalid'     => $invalid,
            'status'      => $status
          );
          $this->db->insert('router_ip', $info);
        }
      }
    }
  }


  public function router_firewall_save_db($router_id, $firewall,$data) {
    error_log('ROUTER ID ' . $router_id);
    $id_zone = $this->settings_model->get_by_key('default_zone');
    $counter = 0;

    foreach($firewall as $item) {

      if($item['action'] != 'drop' && $item['dynamic'] == 'false' &&  $item['invalid'] == 'false' &&  $item['disabled'] == 'false' && !empty($item['src-address'])){

        $this->db->from('firewall');
        $this->db->where(array('id_mk_static' => $item['.id'], 'id_router' => $router_id));
        $this->db->get();

        $firewall_sql         = array();
        $net_user_sql         = array();
        $update_net_user_sql  = array();

        $firewall_sql['chain']        =  $item['chain'];
        $firewall_sql['action']       =  $item['action'];
        $firewall_sql['id_router']    =  $router_id;
        $firewall_sql['id_mk_static'] =  $item['.id'];
        $firewall_sql['entry_order']  =  $counter;

        if(!empty($item['src-address'])){
          $firewall_sql['src-address'] = $item['src-address'];
          $net_user_sql['net_user_ip_address']   = $item['src-address'];
          $update_net_user_sql['net_user_ip_address']   = $item['src-address'];
        }
        if(!empty($item['src-mac-address'])){
          $firewall_sql['src-mac-address']       = $item['src-mac-address'];
          $net_user_sql['net_user_mac']          = $item['src-mac-address'];
          $update_net_user_sql['net_user_mac']   = $item['src-mac-address'];
        }

        $net_user_sql['id_repeat_every']         = 1;
        $net_user_sql['radio_flag']              = '1';
        $net_user_sql['id_zone']                 =  $id_zone;
        $net_user_sql['service_type']            = 'Recurring';
        $net_user_sql['net_user_billing_amount'] = 0;
        $net_user_sql['net_user_mrc_price']      = 0;
        $net_user_sql['id_router']               = $router_id;
        $net_user_sql['id_mk_static']            = $item['.id'];
        $net_user_sql['id_net_user_type']        = 2;
        $net_user_sql['id_isp']        = $this->session->userdata('id_isp');


        if($this->db->affected_rows() > 0) {
          $flag = 0;
          $this->db->update('firewall', $firewall_sql,array('id_mk_static ' => $firewall_sql['id_mk_static']));
          if($this->db->affected_rows() > 0) {
            $flag = 1;
          }
          $this->db->update('net_user', $update_net_user_sql,array('id_mk_static ' => $net_user_sql['id_mk_static']));
          if($this->db->affected_rows() > 0) {
            $flag = 1;
          }
          if($flag > 0) {
            $data = $data ."<span style=color:#f442df;>UPDATED : static customer ".$item['src-address']."</span></br>";
          }else{
            $data = $data ."<span style=color:#f4bf42;>static customer ".$item['src-address']." Already Exists </span>"."</br>";
          }
        }else{
          $insertFlag = 0;
          $this->db->insert('firewall', $firewall_sql);
          if($this->db->affected_rows() > 0) {
            $insertFlag = 1;
          }
          $this->db->insert('net_user', $net_user_sql);
          if($this->db->affected_rows() > 0) {
            $insertFlag = 1;
          }
          if($insertFlag > 0) {
            $data = $data ."<span style=color:green; >INSERTED : static customer ".$item['src-address']."</span></br>";
          }else{
            $data = $data ."<span style=color:red; >NOT-INSERTED : static customer ".$item['src-address']."</span></br>";
          }
        }
      }
      $counter = $counter +1 ;
    }

    return $data;
  }


  public function router_profile_save_db($router_id, $profile,$data) {

    foreach($profile as $item) {
      $this->db->select('*');
      $this->db->from('profile');
      $this->db->where(array('profile_name' => $item['name'],'id_mk_profile' => $item['.id'], 'id_router' => $router_id));
      $this->db->get();

      $profile_sql=array();

      $profile_sql['id_router']     =  $router_id;
      $profile_sql['id_mk_profile'] =  $item['.id'];

      if(!empty($item['bridge'])){
        $profile_sql['bridge'] = $item['bridge'];
      }
      if(!empty($item['local-address'])){
        $profile_sql['profile_local_address'] = $item['local-address'];
      }
      if(!empty($item['remote-address'])){
        $profile_sql['profile_remote_address'] = $item['remote-address'];
      }
      if(!empty($item['rate-limit'])){
        $profile_sql['rate_limit'] = $item['rate-limit'];
      }
      if(!empty($item['only-one'])){
        $profile_sql['only_one'] = $item['only-one'];
      }if(!empty($item['name'])){
        $profile_sql['profile_name'] = $item['name'];
      }

      if($this->db->affected_rows() > 0) {
        $this->db->update('profile', $profile_sql,array('id_mk_profile ' => $item['.id']));
        if($this->db->affected_rows() > 0) {
          $data = $data . "<span style=color:#f442df;>UPDATED: profile ".$item['name']."</span></br>";
        }else{
          $data = $data ."<span style=color:#f4bf42;>Profile ".$item['name']." Already Exists"."</span></br>";
        }
      }else{
        $this->db->insert('profile', $profile_sql);
        if($this->db->affected_rows() > 0) {
            $data = $data . "<span style=color:green;>INSERTED: profile ".$item['name']."</span></br>";
        }else{
            $data = $data ."<span style=color:red;>NOT-INSERTED: profile ".$item['name']."</span></br>";
        }
      }
    }
    return $data;
  }

  public function router_ip_pool_save_db($router_id, $ip_pools,$data) {

    foreach($ip_pools as $item) {

      $this->db->select('*');
      $this->db->from('ip_pool');
      $this->db->where(array('id_mk_pool' => $item['.id'], 'id_router' => $router_id));
      $this->db->get();

      $ip_address   = explode('-',$item['ranges'],2);

      $ip_pool_sql  = array();
      $ip_pool_sql['id_router'] = $router_id;

      if(isset($item['name'])){
        $ip_pool_sql['ip_pool_name']  = $item['name'];
      }
      if(isset($ip_address[0])){
        $ip_pool_sql['ip_pool_start'] = $ip_address[0];
      }
      if(isset($ip_address[1])){
        $ip_pool_sql['ip_pool_end']   = $ip_address[1];
      }

      if($this->db->affected_rows() > 0) {
        $this->db->update('ip_pool', $ip_pool_sql, array('id_mk_pool' => $item['.id'], 'id_router' => $router_id));
        if($this->db->affected_rows() > 0) {
          $data = $data ."<span style=color:#f442df;>UPDATED: pool ".$item['name']."</span></br>";
        }else{
          $data = $data ."<span style=color:#f4bf42;>Pool ".$item['name']." Already Exists"."</span></br>";
        }
      }else{
        $ip_pool_sql['id_mk_pool']   = $item['.id'];
        $this->db->insert('ip_pool', $ip_pool_sql);
        if($this->db->affected_rows() > 0) {
          $data = $data . "<span style=color:green;>INSERTED: pool ".$item['name']."</span></br>";
        }else{
          $data = $data ."<span style=color:red;>NOT-INSERTED: pool ".$item['name']."</span></br>";
        }
      }
    }
    return $data;
  }


  public function get_all_router_ip_addr($router_id) {
    $this->db->select('*');
    $this->db->from('router_ip');
    $this->db->where(array('id_router' => $router_id));

    return $this->db->get()->result_array();
  }


  public function allow_router_access($user_id, $router_id) {
    $info = array('id_router' => $router_id, 'id_user'=> $user_id);
    $this->db->insert('router_access', $info);
    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'passed', 'msg' => 'Router Allowed Successfully!');
    } else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Router Failed To Allow!');
    }

    return $jsonRes;
  }

  public function remove_router_access($user_id, $router_id) {
    $info = array('id_router' => $router_id, 'id_user'=> $user_id);
    $this->db->delete('router_access', $info);
    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'passed', 'msg' => 'Router Removed Successfully!');
    } else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Router Failed To Remove!');
    }

    return $jsonRes;
  }

  public function remove_router_access_for_all($router_id) {
    $info = array('id_router' => $router_id);
    $this->db->delete('router_access', $info);
    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'passed', 'msg' => 'Router Removed Successfully!');
    } else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Router Failed To Remove!');
    }

    return $jsonRes;
  }

  public function get_allowed_routers($user_id) {
    error_log("user id is [". $user_id ."]");

    $this->db->select('*');
    $this->db->from('router_access');
    $this->db->join('router', 'router.id = router_access.id_router', 'left');
    $this->db->join('router_type', 'router.id_router_type = router_type.id_router_type', 'left');
    $this->db->where(array('router_access.id_user' => $user_id, 'router.id_isp' => $this->session->userdata('id_isp')));
    return $this->db->get()->result_array();
  }


  public function has_profile_already($profile_name){

    $this->db->select('*');
    $this->db->from('profile');
    $this->db->where("profile_name = '$profile_name'");
    $this->db->get();

    if($this->db->affected_rows() > 0) {
      $flag = true;
    }else{
      $flag = false;
    }

    return $flag;
  }

  public function add_profile_now(){

    if($this->input->post('submit')){

      $profile_name   = $this->input->post('profile_name');
      $local_address  = $this->input->post('local_address');
      $remote_address = $this->input->post('remote_address');
      $bridge         = $this->input->post('bridge');
      $rate_limit     = $this->input->post('rate_limit');
      $id_router      = $this->input->post('id_router');


      //check if profile with this name already exist
      $has_profile_already =  $this->has_profile_already($profile_name);

      if($has_profile_already == true) {
        $jsonRes = array('status' => 'failed', 'msg' => 'Profile already exists !');
        echo json_encode($jsonRes);
        return ;
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

       $db_insert_flag = true;
       $id_mk_profile  = 0;

       if($sync_router_flag == true){

         // mk login validation
         $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

         if($mk_validation == false){
           $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
           echo json_encode($jsonRes);
           return;
         }

         $db_insert_flag = false;

         $res = $this->add_profile_to_mikrotik($id_router, $profile_name, $local_address,$remote_address,$rate_limit,$bridge);


         if(trim($res[0]) == "!done"){
           $id_mk_profile  = str_replace('=ret=', '', $res[1]);
           $db_insert_flag = true;
         }elseif(trim($res[0]) == "!trap"){
           $msg = "Failed to add profile into Mikrotik !";
           if($res[2] == "=message=invalid value for argument address"){

             if(!empty($local_address) && !filter_var($local_address, FILTER_VALIDATE_IP)){
               $msg = "Invalid Ip Address!";
             }else{
               $msg = "ip pool does not exist in mikrotik!!!";
             }

           }elseif($res[2] == "=message=input does not match any value of bridge"){
             $msg = "Bridge with this name  does not exist in mikrotik !!!";
           }elseif($res[1] == "=message=not enough permissions (9)"){
             $msg = "Router write permission denied";
           }
           $jsonRes = array('status' => 'failed', 'msg' => $msg);

         }
       }



        if($db_insert_flag == true){

          $sql=array();
          $sql['profile_name'] = $profile_name;
          $sql['id_router']    = $id_router;
          if(!empty($local_address)){
            $sql['profile_local_address'] = $local_address;
          }if(!empty($remote_address)){
            $sql['profile_remote_address'] = $remote_address;
          }if(!empty($bridge)){
            $sql['bridge'] = $bridge;
          }if(!empty($rate_limit)){
            $sql['rate_limit'] =$rate_limit;
          }
          $sql['only_one'] = 'default';

          $this->db->insert('profile', $sql);
          //last inserted id_net_user
          $id_profile= $this->db->insert_id();

          if($this->db->affected_rows() > 0) {
            $jsonRes = array('status' => 'success', 'msg' => 'Profile Added !');
            $this->db->update('profile', array('id_mk_profile'=> $id_mk_profile),array('id_profile' => $id_profile ));
          }
        }
        echo json_encode($jsonRes);
      }
    }


  public function add_profile_to_mikrotik($id_router, $profile_name, $local_address,$remote_address,$rate_limit,$bridge){
    $router_details=$this->get_routers_indv_info($id_router);
    foreach($router_details as $router_indiv):
      $router_ip		 	  =$router_indiv["ip_address"];
      $router_name	  	=$router_indiv["name"];
      $router_login 		=$router_indiv["login"];
      $router_password  =$router_indiv["password"];
      $sync_router_flag =$router_indiv["sync_router_flag"];
    endforeach;

    if($sync_router_flag){
      $this->login($router_ip,$router_login,$router_password);

      $sql=array();
      $sql['name'] = $profile_name;
      if(!empty($local_address)){
        $sql['local-address'] = $local_address;
      }if(!empty($remote_address)){
        $sql['remote-address'] = $remote_address;
      }if(!empty($bridge)){
        $sql['bridge'] = $bridge;
      }if(!empty($rate_limit)){
        $sql['rate-limit'] =$rate_limit;
      }
      $sql['only-one'] = 'default';

      $res = $this->mikrotik_api->ppp()->ppp_profile()->add_ppp_profile($sql);

    }else{
      $res = "Mikrotik Sync Disabled";
    }

    return $res;
  }

  public function delete_profile_now(){
    $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted !', 'data' => '');
    $id_profile     = $this->input->post('id_profile');
    $id_router      = $this->input->post('id_router');
    $id_mk_profile  = $this->get_id_mk_profile($id_profile);

    // get the sync flag from MT
    $router_details = $this->router_model->get_routers_indv_info($id_router);

    foreach($router_details as $router_indiv) {
      $router_ip		 	  = $router_indiv["ip_address"];
      $router_login 		= $router_indiv["login"];
      $router_password  = $router_indiv["password"];
      $sync_router_flag = $router_indiv["sync_router_flag"];
    }

    $db_delete_flag = true;
    // if MT sync true then try to insert MT
    if($sync_router_flag == true) {
      // mk login validation
      $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

      if($mk_validation == false){
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
        echo json_encode($jsonRes);
        return;
      }

      $db_delete_flag = false;
      $has_profile_mk = $this->mikrotik_api->ppp()->ppp_profile()->detail_ppp_profile($id_mk_profile);

      if($has_profile_mk > 0){
        $res = $this->delete_profile_from_mikrotik($id_router,$id_mk_profile);

        if(trim($res[0]) == "!done"){
          $db_delete_flag = true;
        }elseif(trim($res[0]) == "!trap"){
          $msg = 'Failed to delete from Mikrotik';
          if(trim($res[2]) == "=message=can not remove default profile"){
            $msg = "Can not delete default profile";
          }
          $jsonRes = array('status' => 'failed', 'msg' => $msg);
          echo json_encode($jsonRes);
        }
      }else{
        $db_delete_flag =true;
      }
    }

    if($db_delete_flag == true){
      $this->db->delete('profile', array('id_profile'=>$id_profile));

      if($this->db->affected_rows()>0){
        $jsonRes['data'] = $this->get_all_profile_by_router_id($id_router);

      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to delete !');
      }
      echo json_encode($jsonRes);
    }
  }

  public function delete_profile_from_mikrotik($id_router,$id_mk_profile){

    $router_details=$this->get_routers_indv_info($id_router);
    foreach($router_details as $router_indiv):
      $router_ip		 	  =$router_indiv["ip_address"];
      $router_login 		=$router_indiv["login"];
      $router_password  =$router_indiv["password"];
      $sync_router_flag =$router_indiv["sync_router_flag"];
    endforeach;
    if($sync_router_flag){
      $this->login($router_ip,$router_login,$router_password);
      $res =$this->mikrotik_api->ppp()->ppp_profile()->delete_ppp_profile(trim($id_mk_profile));
      return $res;
    }
  }

  public function get_profile_name($id_profile) {
    $this->db->select('*');
    $this->db->from('profile');
    $this->db->where(array('id_profile' => $id_profile));
    $result= $this->db->get()->result_array();
    foreach($result as $result_indiv):
      $profile_name	=$result_indiv["profile_name"];
    endforeach;
    return $profile_name;


  }

  public function get_id_mk_profile($id_profile) {
    $this->db->select('*');
    $this->db->from('profile');
    $this->db->where(array('id_profile' => $id_profile));
    $result= $this->db->get()->row();
    return $result->id_mk_profile;


  }

  public function get_individual_profile($id_profile){
    $this->db->select('*');
    $this->db->from('profile');
    $this->db->where('id_profile',$id_profile);
    return $this->db->get()->result_array();
  }


  public function has_profile_name_already_edit($profile_name,$id_profile){

    $this->db->select('*');
    $this->db->from('profile');
    $this->db->where("profile_name = '$profile_name' and id_profile != '$id_profile'");
    $this->db->get();

    if($this->db->affected_rows() > 0) {
      $flag = true;
    }else{
      $flag = false;
    }

    return $flag;
  }



  public function edit_profile_now(){
    if($this->input->post('submit')){

      $profile_name     = $this->input->post('profile_name');
      $local_address    = $this->input->post('local_address');
      $remote_address   = $this->input->post('remote_address');
      $bridge           = $this->input->post('bridge');
      $rate_limit       = $this->input->post('rate_limit');
      $id_router        = $this->input->post('id_router');
      $prv_profile_name = $this->input->post('prv_profile_name');
      $id_profile       = $this->input->post('id_profile');
      $id_mk_profile    = $this->get_id_mk_profile($id_profile);

      $has_profile_already =  $this->has_profile_name_already_edit($profile_name,$id_profile);

      if($has_profile_already == true) {
        $jsonRes = array('status' => 'failed', 'msg' => 'Profile with this name already exists !');
        echo json_encode($jsonRes);
        return ;
      }

      // get the sync flag from MT
      $router_details = $this->router_model->get_routers_indv_info($id_router);

      foreach($router_details as $router_indiv) {
        $router_ip		 	  = $router_indiv["ip_address"];
        $router_login 		= $router_indiv["login"];
        $router_password  = $router_indiv["password"];
        $sync_router_flag = $router_indiv["sync_router_flag"];
      }

      $db_update_flag =true;
      if($sync_router_flag == true){
        // mk login validation
        $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

        if($mk_validation == false){
          $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
          echo json_encode($jsonRes);
          return;
        }
        //initially update flag is fase
        $db_update_flag =false ;

        $has_profile_mk = $this->mikrotik_api->ppp()->ppp_profile()->detail_ppp_profile($id_mk_profile);

        // size of  $has_profile_mk > 0 -Edit else Add
        if(sizeof($has_profile_mk) > 0){
          $res = $this->edit_profile_mikrotik($id_router, $profile_name,$local_address, $remote_address,$bridge,$rate_limit,$prv_profile_name,$id_mk_profile,$id_profile);

          if(trim($res[0])=="!done"){
            $db_update_flag =true ;
            $this->db->select('*');
            $this->db->from('net_user_profile');
            $this->db->where(array('id_profile' => $id_profile));
            $net_users = $this->db->get()->result_array();

            foreach($net_users as $net_user):
              $this->db->update('radreply', array('value'=>$remote_address),array('id_router' => $id_router,'id_net_user' =>$net_user['id_net_user'],'attribute' => 'Framed-Pool'));
            endforeach;
          }elseif(trim($res[0]) == "!trap"){
            $msg = "Failed to edit profile into Mikrotik !";
            if($res[2] == "=message=invalid value for argument address"){

              if(!empty($local_address) && !filter_var($local_address, FILTER_VALIDATE_IP)){
                $msg = "Invalid Ip Address!";
              }else{
                $msg = "ip pool does not exist in mikrotik!!!";
              }

            }elseif($res[2] == "=message=input does not match any value of bridge"){
              $msg = "Bridge with this name  does not exist in mikrotik !!!";
            }elseif($res[2] == "=message=not enough permissions (9)"){
              $msg = "Router write permission denied";
            }
            $jsonRes = array('status' => 'failed', 'msg' => $msg);
            echo json_encode($jsonRes);
          }
        }else{

          $res = $this->add_profile_to_mikrotik($id_router, $profile_name, $local_address,$remote_address,$rate_limit,$bridge);


          if(trim($res[0]) == "!done"){
            $id_mk_profile  = str_replace('=ret=', '', $res[1]);
            $db_update_flag = true;
            $this->db->update('profile', array('id_mk_profile'=> $id_mk_profile),array('id_profile' => $id_profile ));
          }elseif(trim($res[0]) == "!trap"){
            $msg = "Failed to edit profile into Mikrotik !";
            if($res[2] == "=message=invalid value for argument address"){

              if(!empty($local_address) && !filter_var($local_address, FILTER_VALIDATE_IP)){
                $msg = "Invalid Ip Address!";
              }else{
                $msg = "ip pool does not exist in mikrotik!!!";
              }

            }elseif($res[2] == "=message=input does not match any value of bridge"){
              $msg = "Bridge with this name  does not exist in mikrotik !!!";
            }elseif($res[1] == "=message=not enough permissions (9)"){
              $msg = "Router write permission denied";
            }
            $jsonRes = array('status' => 'failed', 'msg' => $msg);
            echo json_encode($jsonRes);
          }

        }
      }

      if($db_update_flag == true){
        $info = array('profile_name'           => $profile_name,
        'profile_local_address'  => $local_address,
        'profile_remote_address' => $remote_address,
        'rate_limit'             => $rate_limit,
        'bridge'                 => $bridge,
        'id_router'              => $id_router);

        $this->db->set($info)
        ->where('id_profile',$id_profile)
        ->update('profile');


        if($this->db->affected_rows() > 0) {

          $jsonRes = array('status' => 'success', 'msg' => 'Profile Edited !','name' => $profile_name);



        }
        else {
          $jsonRes = array('status' => 'failed', 'msg' => 'No change made !');
        }
        echo json_encode($jsonRes);
      }

    }
  }
  public function edit_profile_mikrotik($id_router, $profile_name,$local_address, $remote_address,$bridge,$rate_limit,$prv_profile_name,$id_mk_profile,$id_profile){
    $router_details=$this->get_routers_indv_info($id_router);
    foreach($router_details as $router_indiv):
      $router_ip		 	  =$router_indiv["ip_address"];
      $router_name	  	=$router_indiv["name"];
      $router_login 		=$router_indiv["login"];
      $router_password  =$router_indiv["password"];
      $sync_router_flag =$router_indiv["sync_router_flag"];
    endforeach;
    if($sync_router_flag){
      //mikrotik login
      $this->login($router_ip,$router_login,$router_password);

      //creating param
      $sql=array();
      $sql['name'] = $profile_name;
      if(!empty($local_address)){
        $sql['local-address'] = $local_address;
      }else{
          $this->mikrotik_api->ppp()->ppp_profile()->unset_profile($id_mk_profile, 'local-address');
      }

      if(!empty($remote_address)){
        $sql['remote-address'] = $remote_address;
      }else{
          $this->mikrotik_api->ppp()->ppp_profile()->unset_profile($id_mk_profile, 'remote-address');
      }
      if(!empty($bridge)){
        $sql['bridge'] = $bridge;
      }else{
          $this->mikrotik_api->ppp()->ppp_profile()->unset_profile($id_mk_profile, 'bridge');
      }

      if(!empty($rate_limit)){
        $sql['rate-limit'] =$rate_limit;
      }else{
          $this->mikrotik_api->ppp()->ppp_profile()->unset_profile($id_mk_profile, 'rate-limit');
      }

      $sql['only-one'] = 'default';

      //update profile from mikrotik
      $res=$this->mikrotik_api->ppp()->ppp_profile()->set_ppp_profile($sql,$id_mk_profile);

       return $res;
    }
  }

  public function get_router_name($id_router) {
    $this->db->select('*');
    $this->db->from('router');
    $this->db->where(array('id' =>  $id_router));
    $result= $this->db->get()->row()->name;


    return $result;
  }


  public function mikrotik_login_validation($router_ip,$router_login,$router_password){
    $flag = false;
    $this->router_model->login($router_ip,$router_login,$router_password);
    $mikrotik_pool   = $this->mikrotik_api->ip()->pool()->get_all_pool();

    if($mikrotik_pool === false) {
      $flag = false;
    } else {
      $flag = true;
    }
    return $flag;
  }

  public function check_router_connection(){
    $router_ip          = $this->input->post('router_ip');
    $router_login       = $this->input->post('login');
    $router_password    = $this->input->post('password');

    $res =  $this->mikrotik_login_validation($router_ip,$router_login,$router_password);

    if($res === true) {
      $jsonRes = array('status' => 'success', 'msg' => 'Test connect passed');
    } else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Test connect failed !');
    }

    echo json_encode($jsonRes);
  }

  public function get_all_radius_mk_by_router_id($id_router){
    $this->db->select('*');
    $this->db->from('radius_mk');
    $this->db->where('id_router =', $id_router);
    return $this->db->get()->result_array();
  }

  public function load_radius_from_mikrotik($id_router) {
    $routerObj = $this->router_model->get_router_info($id_router);

    foreach($routerObj as $item) {
      $router_ip = $item["ip_address"];
      $router_login = $item["login"];
      $router_password = $item["password"];
    }

    $this->login($router_ip, $router_login, $router_password);
    $result = $this->mikrotik_api->radius()->get_detail_radius();
    return $result;
  }

  public function router_radius_save_db($router_id, $radius) {

    foreach($radius as $item) {

      $this->db->select('*');
      $this->db->from('radius_mk');
      $this->db->where(array('id_router ' => $router_id,'id_mk_radius_mk' => $item['.id']));
      $this->db->get();


      $sql = array();
      $sql['id_router'] = $router_id;
      if(!empty($item['service'])){
          $sql['service'] = $item['service'];
      }
      if(!empty($item['secret'])){
        $sql['secret'] = $item['secret'];
      }
      if(!empty($item['address'])){
        $sql['address'] = $item['address'];
      }
      if(!empty($item['authentication-port'])){
        $sql['authentication-port'] = $item['authentication-port'];
      }
      if(!empty($item['accounting-port'])){
        $sql['accounting-port'] = $item['accounting-port'];
      }

      if($this->db->affected_rows() > 0) {
         $this->db->update('radius_mk', $sql,array('id_router ' => $router_id,'id_mk_radius_mk' => $item['.id']));
      }else{
        $sql['id_mk_radius_mk'] = $item['.id'];
        $this->db->insert('radius_mk', $sql);
      }
    }

  }

  public function add_radius_mk_now(){

    if($this->input->post('submit')){

      $accounting_port   = $this->input->post('accounting-port');
      $authentication_port = $this->input->post('authentication-port');
      $address    = $this->input->post('address');
      $secret      = $this->input->post('secret');
      $id_router   =$this->input->post('id_router');

      if(isset($_POST['ppp_check'])) $ppp_check = 'ppp';
      else  $ppp_check = '';
      if(isset($_POST['hotspot_check'])) $hotspot_check ='hotspot';
      else  $hotspot_check = '';


      $router_details = $this->router_model->get_routers_indv_info($id_router);

      foreach($router_details as $router_indiv) {
        $router_ip		 	  = $router_indiv["ip_address"];
        $router_name	  	= $router_indiv["name"];
        $router_login 		= $router_indiv["login"];
        $router_password  = $router_indiv["password"];
        $sync_router_flag = $router_indiv["sync_router_flag"];
      }

      $db_insert_flag = true;
      $id_mk_radius_mk = "0";
      $service = "";
      if(!empty($ppp_check)){
        $service = $ppp_check;
      }

      if(!empty($hotspot_check)){
        if(empty($service)) {
          $service = $hotspot_check;
        } else {
          $service .= ', '. $hotspot_check;
        }
      }

      // if MT sync true then try to insert MT
      if($sync_router_flag == true) {

        // mk login validation
        $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

        if($mk_validation == false){
          $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
          echo json_encode($jsonRes);
          return;
        }

        $db_insert_flag = false;


        $res = $this->add_radius_to_mikrotik($id_router, $service, $accounting_port,$authentication_port,$address,$secret);

        if(trim($res[0]) == "!done"){
          $id_mk_radius_mk  = str_replace('=ret=', '', $res[1]);
          $db_insert_flag = true;
        }elseif(trim($res[0]) == "!trap"){
          $msg = "Failed to add radius into Mikrotik !";
          if($res[2] == "=message=invalid value for argument ipv6-address"){
            $msg = "Invalid Address !";
          }elseif($res[2] == "=message=invalid value ".$authentication_port." for authentication-port, an integer required"){
            $msg = "Invalid value ".$authentication_port." for Authentication Port, an integer required !";
          }elseif($res[2] == "=message=invalid value ".$accounting_port." for accounting-port, an integer required"){
            $msg = "Invalid value ".$accounting_port." for Accounting Port, an integer required !";
          }elseif($res[1] == "=message=not enough permissions (9)"){
            $msg = "Router write permission denied";
          }

          $jsonRes = array('status' => 'failed', 'msg' => $msg);
          echo json_encode($jsonRes);
        }

      }


      if($db_insert_flag == true){
        $sql = array();
        $sql['id_router'] = $id_router;
        $sql['service'] = $service;
        $sql['accounting-port'] =$accounting_port;
        $sql['authentication-port'] =$authentication_port;
        $sql['secret'] =$secret;
        $sql['address'] =$address;
        $this->db->insert('radius_mk', $sql);
        $id_radius_mk= $this->db->insert_id();
        if($this->db->affected_rows() > 0) {
          $this->db->update('radius_mk', array('id_mk_radius_mk'=> $id_mk_radius_mk),array('id_radius_mk' => $id_radius_mk ));
          $jsonRes = array('status' => 'success', 'msg' => 'Radius Added !');
        }
        echo json_encode($jsonRes);
      }

    }
}


public function add_radius_to_mikrotik($id_router, $service, $accounting_port,$authentication_port,$address,$secret){

  $router_details=$this->router_model->get_routers_indv_info($id_router);

  foreach($router_details as $router_indiv):
    $router_ip		 	  =$router_indiv["ip_address"];
    $router_login 		=$router_indiv["login"];
    $router_password  =$router_indiv["password"];
    $sync_router_flag =$router_indiv["sync_router_flag"];
  endforeach;

  if($sync_router_flag){
    $this->login($router_ip,$router_login,$router_password);

    $sql = array();
    $sql['service'] = $service;
    $sql['accounting-port'] =$accounting_port;
    $sql['authentication-port'] =$authentication_port;
    $sql['secret'] =$secret;
    $sql['address'] =$address;

    $res = $this->mikrotik_api->radius()->add_radius($sql);
  }else {
    $res = "Mikrotik Sync Disabled";
  }

  return $res;
}

public function get_id_mk_radius_mk($id_radius_mk){
  $this->db->select('*');
  $this->db->from('radius_mk');
  $this->db->where(array('id_radius_mk' => $id_radius_mk));
  $result= $this->db->get()->row();
  return $result->id_mk_radius_mk;
}

public function delete_radius_from_mikrotik($id_router,$id_mk_radius_mk){

  $router_details=$this->get_routers_indv_info($id_router);
  foreach($router_details as $router_indiv):
    $router_ip		 	  =$router_indiv["ip_address"];
    $router_login 		=$router_indiv["login"];
    $router_password  =$router_indiv["password"];
    $sync_router_flag =$router_indiv["sync_router_flag"];
  endforeach;

  if($sync_router_flag){
    $this->login($router_ip,$router_login,$router_password);
    $res =$this->mikrotik_api->radius()->delete_radius($id_mk_radius_mk);
    return $res;
  }
}

public function delete_radius(){
  $id_radius_mk     = $this->input->post('id_radius_mk');
  $id_router      = $this->input->post('id_router');
  $id_mk_radius_mk  = $this->get_id_mk_radius_mk($id_radius_mk);
  // get the sync flag from MT
  $router_details = $this->router_model->get_routers_indv_info($id_router);

  foreach($router_details as $router_indiv) {
    $router_ip		 	  = $router_indiv["ip_address"];
    $router_name	  	= $router_indiv["name"];
    $router_login 		= $router_indiv["login"];
    $router_password  = $router_indiv["password"];
    $sync_router_flag = $router_indiv["sync_router_flag"];
  }

  $db_delete_flag = true;
  // if MT sync true then try to insert MT
  if($sync_router_flag == true) {
    // mk login validation
    $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

    if($mk_validation == false){
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
      echo json_encode($jsonRes);
      return;
    }

    $db_delete_flag = false;
    if($id_mk_radius_mk == "0"){
      $db_delete_flag = true;
    }else{
      $res = $this->delete_radius_from_mikrotik($id_router,$id_mk_radius_mk);

      if(trim($res[0]) == "!done"){
        $db_delete_flag = true;
      }elseif(trim($res[0]) == "!trap"){
        $msg = 'Failed to delete from Mikrotik';
        if(trim($res[2]) == "=message=no such item (4)"){
          $msg = "Radius does not exist in ".$router_name;
        }
        $jsonRes = array('status' => 'failed', 'msg' => $msg);
        echo json_encode($jsonRes);
      }
    }
  }

  if($db_delete_flag == true){
    $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Radius!', 'data' => '');
    $this->db->delete('radius_mk', array('id_radius_mk'=>$id_radius_mk));

    if($this->db->affected_rows()>0){
      $jsonRes['data'] = $this->get_all_radius_mk_by_router_id($id_router);
    }
    else {
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed to delete !');
    }
    echo json_encode($jsonRes);
  }
}

public function get_individual_radius($id_radius_mk){
  $this->db->select('*');
  $this->db->from('radius_mk');
  $this->db->where(array('id_radius_mk' => $id_radius_mk));
  $result= $this->db->get();
  return $result->result_array();
}

public function edit_radius_now(){

  $accounting_port     = $this->input->post('accounting-port');
  $authentication_port = $this->input->post('authentication-port');
  $address             = $this->input->post('address');
  $secret              = $this->input->post('secret');
  $id_router           = $this->input->post('id_router');
  $id_radius_mk        = $this->input->post('id_radius_mk');
  $id_mk_radius_mk     = $this->input->post('id_mk_radius_mk');

  if(isset($_POST['ppp_check'])) $ppp_check = 'ppp';
  else  $ppp_check = '';
  if(isset($_POST['hotspot_check'])) $hotspot_check ='hotspot';
  else  $hotspot_check = '';

  $router_details = $this->router_model->get_routers_indv_info($id_router);

  foreach($router_details as $router_indiv) {
    $router_ip		 	  = $router_indiv["ip_address"];
    $router_name	  	= $router_indiv["name"];
    $router_login 		= $router_indiv["login"];
    $router_password  = $router_indiv["password"];
    $sync_router_flag = $router_indiv["sync_router_flag"];
  }

  $db_update_flag = true;

  $service = "";
  if(!empty($ppp_check)){
    $service = $ppp_check;
  }

  if(!empty($hotspot_check)){
    if(empty($service)) {
      $service = $hotspot_check;
    } else {
      $service .= ', '. $hotspot_check;
    }
  }

  if($sync_router_flag == true) {

    // mk login validation
    $mk_validation = $this->router_model->mikrotik_login_validation($router_ip,$router_login,$router_password);

    if($mk_validation == false){
      $jsonRes = array('status' => 'failed', 'msg' => 'Failed to login with mikrotik !! Invalid username or password');
      echo json_encode($jsonRes);
      return;
    }

    $db_update_flag = false;


    if($id_mk_radius_mk != "0"){
      $res = $this->edit_radius_mk_to_mikrotik($id_router, $service, $accounting_port,$authentication_port,$address,$secret,$id_mk_radius_mk);

      if(trim($res[0]) == "!done"){
        $db_update_flag = true;
      }elseif(trim($res[0]) == "!trap"){
        $msg = "Failed to edit radius into Mikrotik !";
        if($res[2] == "=message=invalid value for argument ipv6-address"){
          $msg = "Invalid Address !";
        }elseif($res[2] == "=message=invalid value ".$authentication_port." for authentication-port, an integer required"){
          $msg = "Invalid value ".$authentication_port." for Authentication Port, an integer required !";
        }elseif($res[2] == "=message=invalid value ".$accounting_port." for accounting-port, an integer required"){
          $msg = "Invalid value ".$accounting_port." for Accounting Port, an integer required !";
        }elseif($res[2] == "=message=not enough permissions (9)"){
          $msg = "Router write permission denied";
        }

        $jsonRes = array('status' => 'failed', 'msg' => $msg);
        echo json_encode($jsonRes);
      }
    }else{

      $res = $this->add_radius_to_mikrotik($id_router, $service, $accounting_port,$authentication_port,$address,$secret);

      if(trim($res[0]) == "!done"){
        $id_mk_radius_mk  = str_replace('=ret=', '', $res[1]);
        $this->db->update('radius_mk', array('id_mk_radius_mk'=> $id_mk_radius_mk),array('id_radius_mk' => $id_radius_mk ));
        $db_update_flag = true;
      }elseif(trim($res[0]) == "!trap"){
        $msg = "Failed to edit radius into Mikrotik !";
        if($res[2] == "=message=invalid value for argument ipv6-address"){
          $msg = "Invalid Address !";
        }elseif($res[2] == "=message=invalid value ".$authentication_port." for authentication-port, an integer required"){
          $msg = "Invalid value ".$authentication_port." for Authentication Port, an integer required !";
        }elseif($res[2] == "=message=invalid value ".$accounting_port." for accounting-port, an integer required"){
          $msg = "Invalid value ".$accounting_port." for Accounting Port, an integer required !";
        }elseif($res[1] == "=message=not enough permissions (9)"){
          $msg = "Router write permission denied";
        }

        $jsonRes = array('status' => 'failed', 'msg' => $msg);
        echo json_encode($jsonRes);
      }

    }
  }

  if($db_update_flag == true){
    $sql = array();
    $sql['service']             = $service;
    $sql['accounting-port']     = $accounting_port;
    $sql['authentication-port'] = $authentication_port;
    $sql['secret']              = $secret;
    $sql['address']             = $address;

    $this->db->update('radius_mk', $sql,array('id_radius_mk' => $id_radius_mk ,'id_router' => $id_router));

    if($this->db->affected_rows() > 0) {
      $jsonRes = array('status' => 'success', 'msg' => 'Edited Successfully');
    }
    else {
      $jsonRes = array('status' => 'failed', 'msg' => 'No change made !');
    }
    echo json_encode($jsonRes);
  }

}

public function edit_radius_mk_to_mikrotik($id_router, $service, $accounting_port,$authentication_port,$address,$secret,$id_mk_radius_mk){

  $router_details=$this->router_model->get_routers_indv_info($id_router);

  foreach($router_details as $router_indiv):
    $router_ip		 	  =$router_indiv["ip_address"];
    $router_login 		=$router_indiv["login"];
    $router_password  =$router_indiv["password"];
    $sync_router_flag =$router_indiv["sync_router_flag"];
  endforeach;

  if($sync_router_flag){
    $this->login($router_ip,$router_login,$router_password);

    $sql = array();
    $sql['service']             = $service;
    $sql['accounting-port']     =$accounting_port;
    $sql['authentication-port'] =$authentication_port;
    $sql['secret']              =$secret;
    $sql['address']             =$address;

    $res = $this->mikrotik_api->radius()->set_radius($sql,$id_mk_radius_mk);
  }else {
    $res = "Mikrotik Sync Disabled";
  }

  return $res;
}
}

?>
