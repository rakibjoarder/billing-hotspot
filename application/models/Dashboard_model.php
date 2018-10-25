<?php
class Dashboard_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function get_total_customer(){
    return $this->db->where('id_isp',$this->session->userdata('id_isp'))->from("net_user")->count_all_results();
  }

  function get_recently_added_customer(){
    $sql='SELECT COUNT(*) AS cnt FROM net_user WHERE MONTH(last_change) = MONTH(CURRENT_DATE()) AND YEAR(last_change) = YEAR(CURRENT_DATE()) AND id_isp ='.$this->session->userdata('id_isp');
    $query = $this->db->query($sql);
    $query_result=$query->row('cnt');
    return $query_result;
  }

  function get_current_month_total_invoice_amount(){
    $sql='SELECT FORMAT(SUM(invoice_original_amount), 0) AS total_invoice_amount FROM invoice WHERE invoice.id_isp = '.$this->session->userdata('id_isp').' AND MONTH(invoice_date) = MONTH(CURRENT_DATE()) AND YEAR(invoice_date) = YEAR(CURRENT_DATE())';
    $query = $this->db->query($sql);
    $query_result=$query->row('total_invoice_amount');
    return $query_result;
  }

  function get_current_month_total_due(){
    $sql='SELECT FORMAT(SUM(invoice_amount), 0) AS month_total_due FROM invoice WHERE invoice.id_isp = '.$this->session->userdata('id_isp').' AND MONTH(invoice_date) = MONTH(CURRENT_DATE()) AND YEAR(invoice_date) = YEAR(CURRENT_DATE())';
    $query = $this->db->query($sql);
    $query_result=$query->row('month_total_due');
    return $query_result;
  }

  function get_total_due(){
    $sql='SELECT FORMAT(SUM(invoice_amount), 0) AS total_due FROM invoice WHERE invoice.id_isp ='.$this->session->userdata('id_isp');
    $query = $this->db->query($sql);
    $query_result=$query->row('total_due');
    return $query_result;
  }

  function get_previous_month_total_invoice_amount(){
    $sql='SELECT FORMAT(SUM(invoice_original_amount), 0) AS previous_month_total_invoice_amount FROM invoice WHERE invoice.id_isp = '.$this->session->userdata('id_isp').' AND YEAR(invoice_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(invoice_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)';
    $query = $this->db->query($sql);
    $query_result=$query->row('previous_month_total_invoice_amount');
    return $query_result;
  }

  function get_previous_month_total_due(){
    $sql='SELECT FORMAT(SUM(invoice_amount), 0) AS previous_month_total_due FROM invoice WHERE invoice.id_isp = '.$this->session->userdata('id_isp').' AND YEAR(invoice_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(invoice_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)';
    $query = $this->db->query($sql);
    $query_result=$query->row('previous_month_total_due');
    return $query_result;
  }

  function get_current_payment(){
    $sql='SELECT FORMAT(SUM(paid_amount), 0) AS paid_amount FROM payment WHERE payment.id_isp = '.$this->session->userdata('id_isp').' AND MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE())';
    $query = $this->db->query($sql);
    $query_result=$query->row('paid_amount');
    return $query_result;
  }


  function get_today_pppoe() {
    $date = new DateTime("now");

    $curr_date = $date->format('Y-m-d');

    $sql = "SELECT COUNT(*) AS cnt FROM trace WHERE DATE(access_time)='". $curr_date ."' AND user_id!='' GROUP BY user_id";

    error_log($sql);

    $query = $this->db->query($sql);
    $count = 0;
    foreach($query->result_array() as $row) {
      $count = $count + 1;
    }
    error_log("Count PPPoe " . $count);

    return $count;
  }

	function fetch_status_all(){

		$status_syslog=(file_exists("/var/run/syslogd.pid") ? 1 : 0);
		$status_application=1;
    $status_mysql= (file_exists("/var/run/mariadb/mariadb.pid") ? 1 : 0);
		$status_logfile=1;

		$status_array = array(
      'status_syslog' => $status_syslog,
      'status_application' => $status_application,
      'status_mysql' => $status_mysql,
      'status_logfile' => $status_logfile
      // 'today_user' => $this->get_today_mac(),
      // 'today_pppoe' => $this->get_today_pppoe()


    );

		echo json_encode($status_array);

	}// end of function fetch_status_all



  function get_total_warning()
  {

    $this->db->select('*');
    $this->db->from('notification');
    $this->db->where("notification_type", "warning");
    //error_log("Returned warn: ".$this->db->count_all_results());
     return  $this->db->count_all_results();
  }

  function get_total_rows_inserted()
  {
    $date = new DateTime("now");
    $curr_date = $date->format('Ymd');
    error_log("Today: ".$curr_date);
    // $curr_date="20170809";
    $sql = "SELECT SUM(row_inserts) AS sum FROM file_trace WHERE file_name LIKE '%". $curr_date ."%'";;

    error_log($sql);

    $query = $this->db->query($sql);
    $rows=$query->row()->sum;
    error_log("ROWS: ".$rows);
    if(!isset($rows)) $rows=0;
    return $rows;


  }

  function get_total_error()
  {
    $this->db->select('*');
    $this->db->from('notification');
    $this->db->where(array('id_isp' => $this->session->userdata('id_isp'),'notification_type'=> "error"));
    //error_log("Returned warn: ".$this->db->count_all_results());
     return  $this->db->count_all_results();
  }


  function get_top_5_notification()
{
  $this->db->select('*');
  $this->db->from('notification');
   $this->db->order_by("notification_time", "desc");
   $this->db->where(array('id_isp' => $this->session->userdata('id_isp')));
   $this->db->limit(5);
  //error_log("Returned noti: ".$this->db->affected_rows());
  return $this->db->get()->result_array();

}

function get_all_notification() {
  $this->db->select('*');
  $this->db->from('notification');
  $this->db->where(array('id_isp' => $this->session->userdata('id_isp')));
  $this->db->order_by("notification_time", "desc");
  return $this->db->get()->result_array();
}


function get_row_inserts(){
  //SELECT name,router_ip,DATE(last_changed) AS date_,SUM(row_inserts) AS row_inserts_ FROM file_trace LEFT JOIN router ON file_trace.router_ip=router.ip_address WHERE (DATE(last_changed)> DATE(NOW() - INTERVAL 30 DAY)) GROUP BY DATE(last_changed) ,router_ip ORDER BY DATE(last_changed)

  $routers_count=$this->get_total_routers();
  error_log("Router Count: ".$routers_count);
  if($routers_count>5){
      $sql='SELECT name,router_ip,DATE(file_date) AS date_,SUM(row_inserts) AS row_inserts_ FROM file_trace LEFT JOIN router ON (file_trace.router_ip=router.ip_address AND file_trace.id_isp=router.id_isp) WHERE (DATE(file_date)> DATE(NOW() - INTERVAL 2 DAY)) AND file_trace.id_isp = '.$this->session->userdata('id_isp').' GROUP BY DATE(file_date) ,router_ip ORDER BY DATE(file_date)';
    }
    else{
      $sql='SELECT name,router_ip,DATE(file_date) AS date_,SUM(row_inserts) AS row_inserts_ FROM file_trace LEFT JOIN router ON (file_trace.router_ip=router.ip_address AND file_trace.id_isp=router.id_isp) WHERE (DATE(file_date)> DATE(NOW() - INTERVAL 30 DAY)) AND file_trace.id_isp = '.$this->session->userdata('id_isp').' GROUP BY DATE(file_date) ,router_ip ORDER BY DATE(file_date)';
    }
  error_log($sql);
  $query = $this->db->query($sql);
  $query_result=$query->result_array();

  error_log("Aff Rows:".$this->db->affected_rows());

  echo json_encode($query_result);
}

function get_row_inserts_from_file_trace(){

  $sql='SELECT DATE(file_date) AS date_,SUM(row_inserts) AS row_inserts_ FROM file_trace  WHERE (DATE(file_date) > DATE(NOW() - INTERVAL 30  DAY)) AND file_trace.id_isp = '.$this->session->userdata('id_isp').' GROUP BY DATE(file_date) ORDER BY DATE(file_date)';

  $query = $this->db->query($sql);
  $query_result=$query->result_array();

  error_log("Aff Rows:".$this->db->affected_rows());

  echo json_encode($query_result);
}

function get_payment_by_month(){

  $sql  = 'SELECT YEAR(payment_date) as year, MONTH(payment_date) AS month,SUM(paid_amount) AS paid_amount_ FROM payment  WHERE payment.id_isp = '.$this->session->userdata('id_isp').' AND  (DATE(payment_date) > DATE(NOW() - INTERVAL 365  DAY)) GROUP BY MONTH(payment_date) ORDER BY YEAR(payment_date),MONTH(payment_date)';
  $query = $this->db->query($sql);
  $query_result=$query->result_array();

  error_log("Aff Rows:".$this->db->affected_rows());

  echo json_encode($query_result);
}

function get_invoice_by_month(){

  $sql  = 'SELECT YEAR(invoice_date) as year,MONTH(invoice_date) AS month,SUM(invoice_original_amount) AS invoice_amount_ FROM invoice   WHERE invoice.id_isp = '.$this->session->userdata('id_isp').' AND (DATE(invoice_date) > DATE(NOW() - INTERVAL 365  DAY)) GROUP BY MONTH(invoice_date) ORDER BY YEAR(invoice_date),MONTH(invoice_date)';
  $query = $this->db->query($sql);
  $query_result=$query->result_array();

  error_log("Aff Rows:".$this->db->affected_rows());

  echo json_encode($query_result);
}

//nib-web
function get_total_ppoe() {
  return $this->db->where('id_isp',$this->session->userdata('id_isp'))->from("net_user")->count_all_results();
}

function get_total_routers() {
  return $this->db->where('id_isp',$this->session->userdata('id_isp'))->from("router")->count_all_results();
}

function get_routers_all() {
  $this->db->select('*');
  $this->db->from('router');
  $this->db->where(array('id_isp' => $this->session->userdata('id_isp')));

  return $this->db->get()->result_array();

}






}
