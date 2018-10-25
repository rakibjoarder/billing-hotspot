<?php
class Pool_model extends CI_Model {
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


}
?>
