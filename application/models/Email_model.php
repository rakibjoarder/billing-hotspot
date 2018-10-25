<?php
class Email_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }


  function get_net_user_info_by_invoice_id($id_invoice){
    $this->db->select('*');
    $this->db->from('net_user');
    $this->db->join('invoice', 'net_user.id_net_user=invoice.id_net_user', 'left');
    $this->db->where('id_invoice' , $id_invoice);
    return $this->db->get()->result_array();
  }


}
