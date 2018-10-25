<?php
class Radius_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  public function get_all_pool() {
    $this->db->select('*');
    $this->db->from('ip_pool');
    return $this->db->get()->result_array();
  }

  public function get_all_pool_by_router($router_id) {
    $this->db->select('*');
    $this->db->from('ip_pool');
    $this->db->where(array('id_router' => $router_id));

    return $this->db->get()->result_array();
  }

  public function has_pool_name($router_id, $name) {
    $this->db->select('*');
    $this->db->from('ip_pool');
    $this->db->where(array('id_router' => $router_id));

    if($this->db->affected_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function add_user_radcheck($user, $pass, $id_router, $id_net_user) {
    $insert_id = 0;
    $info = array(
                'username'    => $user,
                'value'       => $pass,
                'op'          => ':=',
                'attribute'   => 'Cleartext-Password',
                'id_net_user' => $id_net_user
            );

    $this->db->insert('radcheck', $info);
    if($this->db->affected_rows() > 0) {
      $insert_id = $this->db->insert_id();
    }
    return $insert_id;
  }

  public function update_user_radcheck($username,$pass,$id_router,$id_net_user){

    $info = array('username'=>$username, 'value' => $pass , 'op' => ':=','attribute'=>'Cleartext-Password' );

    $this->db->update('radcheck',$info ,array('attribute' => 'Cleartext-Password','id_net_user' => $id_net_user));

  }

  public function delete_user_radcheck($id_router,$id_net_user){
    $this->db->delete('radcheck',array('attribute' => 'Cleartext-Password','id_net_user' => $id_net_user));
  }

  public function add_mac_radcheck($user, $mac, $id_router, $id_net_user) {
    $insert_id = 0;
    $info = array(
                'username'    => $user,
                'value'       => $mac,
                'op'          => '==',
                'attribute'   => 'Calling-Station-Id',
                'id_net_user' => $id_net_user
            );

    $this->db->insert('radcheck', $info);
    if($this->db->affected_rows() > 0) {
      $insert_id = $this->db->insert_id();
    }

    return $insert_id;
  }



  public function update_mac_radcheck( $username, $mac,$id_router,$id_net_user) {

    $info = array('username' => $username,
                  'value'    => $mac ,
                  'op'       => '==',
                  'attribute'=>'Calling-Station-Id' );

    $this->db->update('radcheck', $info, array('attribute' => 'Calling-Station-Id','id_net_user' => $id_net_user));

  }

  public function delete_mac_radcheck($id_router,$id_net_user){
    $this->db->delete('radcheck',array('attribute' => 'Calling-Station-Id','id_net_user' => $id_net_user));
  }

  public function delete_ip_pool_radreply($id_router,$id_net_user){
    $this->db->delete('radreply',array('attribute' => 'Framed-Pool','id_net_user' => $id_net_user));
  }

  public function delete_ip_address_radreply($id_router,$id_net_user){
    $this->db->delete('radreply',array('attribute' => 'Framed-IP-Address','id_net_user' => $id_net_user));
  }


  public function update_ip_address_radreply($user,$ip_address, $id_router, $id_net_user) {

    $info =  array('username'=>$user, 'value' => $ip_address);
    $this->db->update('radreply',$info,array('attribute'=>'Framed-IP-Address','id_net_user' => $id_net_user));

  }


  public function add_ip_address_radreply($user,$ip_address, $id_router, $id_net_user) {
    $insert_id = 0;
    $info = array(
                'username'  => $user,
                'value'     => $ip_address,
                'op'        => '=',
                'attribute' => 'Framed-IP-Address',
                'id_net_user' => $id_net_user
            );

    $this->db->insert('radreply', $info);
    if($this->db->affected_rows() > 0) {
      $insert_id = $this->db->insert_id();
    }

    return $insert_id;


  }


  public function add_user_radreply($user, $pool, $id_router, $id_net_user) {
    $insert_id = 0;
    $info = array(
                'username'  => $user,
                'value'     => $pool,
                'op'        => '=',
                'attribute' => 'Framed-Pool',
                'id_net_user' => $id_net_user
            );

    $this->db->insert('radreply', $info);
    if($this->db->affected_rows() > 0) {
      $insert_id = $this->db->insert_id();
    }

    return $insert_id;
  }

  public function update_ip_pool_radcheck($user, $pool, $id_router, $id_net_user){
    $this->db->update('radreply', array('username'=>$user, 'value' => $pool  ),array('attribute' => 'Framed-Pool','id_net_user' => $id_net_user));

  }


}
?>
