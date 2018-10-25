<?php
class Connection_model extends CI_Model {

  function __construct() {
    parent::__construct();
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

  public function get_individual_netusers($id_net_user)
  {
      $this->db->select('*');
      $this->db->from('net_user');
      $this->db->where('id_net_user ='.$id_net_user);
      return $this->db->get()->result_array();
  }

  public function get_net_user_type()
  {
    $this->db->select('*');
    $this->db->from('net_user_type');
    return $this->db->get()->result_array();
  }
  public function get_net_user_category()
  {
    $this->db->select('*');
    $this->db->from('net_user_category');
    return $this->db->get()->result_array();
  }
public function get_all_district()
{
  $this->db->select('*');
  $this->db->from('district');
  return $this->db->get()->result_array();

}
  public function create_connection_now()
  {
    if($this->input->post('submit')){

      $address          = $this->input->post('address');
      $connectionname   =$this->input->post('connectionname');
      $district_name    =$this->input->post('district_name');
      $town             =$this->input->post('town');
      $id_net_user_type = $this->input->post('id_net_user_type');
      $id_router        = $this->input->post('id_router');
      $id_net_user      = $this->input->post('id_net_user');
      $mac              = $this->input->post('mac');
      $ip_addr          = $this->input->post('ip_addr');


      $info = array( 'id_net_user_type' => $id_net_user_type,
                     'id_router'=>  $id_router,
                     'connectionname'=> $connectionname,
                     'address'=>$address,
                     'town'=>$town,
                     'district_name'=>$district_name,
                     'id_net_user' => $id_net_user ,
                     'mac' => $mac,
                     'ip_addr' => $ip_addr);

      $this->db->insert('connection_customer', $info);

      if($this->db->affected_rows() > 0) {
        $jsonRes = array('status' => 'success', 'msg' => 'Succesfully created connection '.$connectionname. '.');
        $this->db->insert('notification', array(
          'notification_title' => "New Connection",
          'notification_body' =>  $connectionname." has created",
          'notification_type'  => "message",
          'id_isp'             => $this->session->userdata('id_isp')
        ));
      }
      else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to create connection !');
      }
    }
    echo json_encode($jsonRes);

  }




  public function get_all_connections()
  {
    // connection_customer.id_connection,
    //
    //   ,net_user.net_user_name
    //   ,net_user.phone
    //   ,connection_customer.connectionname
    //   ,connection_customer.address
    //   ,connection_customer.town
    //   ,connection_customer.district_name
    //   ,net_user_type.net_user_type
    //   ,net_user_category.category_name
    //   ,router.name
    //   ,connection_customer.mac
    //   ,connection_customer.ip_addr
    //   ,package.package_name
    //   ,package.package_speed
    //   ,package.package_type,
    //   ,package.package_price
    	  $this->db->select('*');
    	     $this->db->from('connection_customer');
           $this->db->join('router', 'router.id=connection_customer.id_router', 'left');
           $this->db->join('net_user','net_user.id_net_user=connection_customer.id_net_user', 'left');
           $this->db->join('net_user_type','net_user_type.id_net_user_type=connection_customer.id_net_user_type', 'left');
           $this->db->join('net_user_category','net_user_category.id_net_user_category=net_user.id_net_user_category', 'left');
           $this->db->where(array('connection_customer.delete_flag' => "NO"));
        // 1 means PPPOE user
      //  $this->db->where(array('id_net_user_type' => 1));

    	  return $this->db->get()->result_array();

  }




  public function edit_connection_now()
  {
    if($this->input->post('submit')) {
      $address = $this->input->post('address');
      $connectionname=$this->input->post('connectionname');
      $id_net_user_type   = $this->input->post('id_net_user_type');
      $id_router  = $this->input->post('id_router');
      $id_net_user = $this->input->post('id_net_user');
      $mac       = $this->input->post('mac');
      $ip_addr             = $this->input->post('ip_addr');
      $id_connection = $this->input->post('id_connection');
      $district_name = $this->input->post('district_name');
      $town = $this->input->post('town');

      $this->db->set(array(
                       'id_net_user_type' => $id_net_user_type,
                       'id_router'=>  $id_router,
                       'connectionname'=> $connectionname,
                       'address'=>$address,
                       'mac' => $mac,
                       'town' =>$town,
                       'district_name'=>  $district_name,
                       'ip_addr' => $ip_addr))
        ->where('id_connection',$id_connection)
        ->update('connection_customer');
      if($this->db->affected_rows() >0) {
        $jsonRes = array('status' => 'success', 'msg' => 'Succesfully Updated !');
      } elseif($this->db->affected_rows() == 0) {
          $jsonRes = array('status' => 'failed', 'msg' => 'No change made !');
      }
    }
    echo json_encode($jsonRes);
  }


  public function update_delete_flag($id_net_user)
  {
    $this->db->set(array('delete_flag' => "YES"))
      ->where('id_net_user',$id_net_user)
      ->update('connection_customer');
  }

  public function get_town()
 {
   $district_name   = $this->input->post('district_name');
   $this->db->select('*');
   $this->db->from('location');
   $this->db->where('district_name=',$district_name);
   $data = $this->db->get()->result_array();
   echo json_encode($data);
 }



 public function  delete_connection(){
   $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Link!', 'data' => '');
   $connection_id = $this->input->post('connection_id');
   $connection_id= trim($connection_id);

   if(!empty($connection_id)){
      $this->db->delete('connection_customer', array('id_connection' => $connection_id));

       if($this->db->affected_rows()>0){
         $jsonRes['data'] = $this->get_all_connections();
       }
       else {
         $jsonRes = array('status' => 'failed', 'msg' => 'Delete Opeartion Failed !');
       }
   }
   echo json_encode($jsonRes);
 }

 public function get_individual_connection($id_connection)
 { $this->db->select('*');
  $this->db->from('connection_customer');
   $this->db->where(array('id_connection'=>$id_connection));
   return $this->db->get()->result_array();
 }

}
