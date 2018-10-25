<?php
class Accounting_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }


  public function get_invoice_summary($year){
  //  $sql="SELECT DATE_FORMAT(invoice_date, '%M') as `invoice_date` , COUNT(id_invoice) as `total_invoice`,SUM(invoice_original_amount) as total_billing_amount FROM invoice WHERE YEAR(invoice_date) = '2018' GROUP BY DATE_FORMAT(invoice_date, '%M')";
    $id_isp = $this->session->userdata('id_isp');
    $sql="SELECT DATE_FORMAT(invoice_date, '%m') as `invoice_date` , COUNT(id_invoice) as `total_invoice`, ROUND(SUM(invoice_original_amount), 2) as total_billing_amount FROM invoice WHERE invoice.id_isp = $id_isp AND YEAR(invoice_date) = '$year' GROUP BY DATE_FORMAT(invoice_date, '%m') ORDER BY DATE_FORMAT(invoice_date, '%m') ASC";

    $query = $this->db->query($sql);
    $query_result=$query->result_array();
    return $query_result;
  }
public function get_payment_summary($year){
  $id_isp = $this->session->userdata('id_isp');
  $sql="SELECT DATE_FORMAT(payment_date, '%m') as `invoice_date` , COUNT(id_payment) as `total_payment`, ROUND(SUM(paid_amount), 2) as total_recieved_amount FROM payment WHERE payment.id_isp = $id_isp AND YEAR(payment_date) = '$year' GROUP BY DATE_FORMAT(payment_date, '%m') ORDER BY DATE_FORMAT(payment_date, '%m') ASC";

  $query = $this->db->query($sql);
  $query_result=$query->result_array();
  return $query_result;
}


public function get_drop_down_year_invoice(){
  $id_isp = $this->session->userdata('id_isp');
  $sql="SELECT DISTINCT DATE_FORMAT(invoice_date, '%Y') as year FROM invoice Where invoice.id_isp = $id_isp ORDER BY DATE_FORMAT(invoice_date, '%Y') ASC";
  $query = $this->db->query($sql);
  $query_result=$query->result_array();
  return $query_result;
}

public function get_drop_down_year_payment(){
  $id_isp = $this->session->userdata('id_isp');

  $sql="SELECT DISTINCT DATE_FORMAT(payment_date, '%Y') as year FROM payment WHERE payment.id_isp = $id_isp ORDER BY DATE_FORMAT(payment_date, '%Y') ASC";
  $query = $this->db->query($sql);
  $query_result=$query->result_array();
  return $query_result;
}

public function get_summary_by_year(){
  $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');
  $year=$this->input->post('year');
  if($year)
  {
      $jsonRes['data'] =$this->get_summary($year);

  }
  echo json_encode($jsonRes);

}

public function get_summary($year){

  $invoice_summary= $this->get_invoice_summary($year);
  $payment_summary= $this->get_payment_summary($year);

  $invoice_length=sizeof($invoice_summary);
  $payment_length=sizeof($payment_summary);

  $summary=array();

  for($i = 1; $i<=12; $i++) {
    $has_data=0;
    $result=array();
    for($j = 0; $j<$invoice_length; $j++) {
      if($i==(int)$invoice_summary[$j]['invoice_date']){
        $result['invoice_date']=$invoice_summary[$j]['invoice_date'];
        $result['total_invoice']=$invoice_summary[$j]['total_invoice'];
        $result['total_billing_amount']=$invoice_summary[$j]['total_billing_amount'];
        $result['total_payment']=0;
        $result['total_recieved_amount']=0;
        $has_data=1;
        break;
      }elseif($i < (int)$invoice_summary[$j]['invoice_date']){
        break;
      }
    }
    for($k = 0; $k<$payment_length; $k++) {
      if($i==(int)$payment_summary[$k]['invoice_date']){
        if($has_data==0){
          $result['total_invoice']=0;
          $result['total_billing_amount']=0;
        }
        $result['invoice_date']=$payment_summary[$k]['invoice_date'];
        $result['total_payment']=$payment_summary[$k]['total_payment'];
        $result['total_recieved_amount']=$payment_summary[$k]['total_recieved_amount'];
        $has_data=1;
        break;
      }elseif($i < (int)$payment_summary[$k]['invoice_date']){
        break;
      }
    }
    if($has_data==1){
      array_push($summary,$result);
    }

  }

  return $summary;

}

// rAKIB MAIN FILE
// public function get_customer_statement(){
//
//   $balance=0;
//   $result=array();
//   $summary=array();
//
//   $sql="CREATE TEMPORARY TABLE `temporary_table` (
//     `id_temporary_table` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     `date_` date NOT NULL,
//     `particular` varchar(200)DEFAULT 0,
//     `billing_amount` double DEFAULT 0,
//     `recieved_amount` double DEFAULT 0,
//     `id_invoice` varchar(200) DEFAULT 0,
//     `id_payment` varchar(200) DEFAULT 0,
//     `flag` varchar(200) DEFAULT 0,
//     `balance` double DEFAULT 0
//   );";
//   $query = $this->db->query($sql);
//
//   $sql="INSERT INTO temporary_table(date_,particular,billing_amount,balance,id_invoice,flag) SELECT invoice.invoice_date,'Invoice',invoice.invoice_original_amount,'0',invoice.id_invoice,'1' FROM invoice WHERE id_net_user=176";
//   $query = $this->db->query($sql);
//
//   $sql="INSERT INTO temporary_table(date_,particular,recieved_amount,balance,id_payment,flag) SELECT payment.payment_date,'payment',payment.paid_amount,'0',payment.id_payment,'2' FROM payment WHERE id_net_user=176";
//   $query = $this->db->query($sql);
//
//   $sql="SELECT * FROM temporary_table ORDER BY date_ DESC";
//   $query = $this->db->query($sql);
//
//   $query_result=$query->result_array();
//
//
//   $length=sizeof($query_result);
//
//   for($i = 0; $i < $length; $i++) {
//
//     if($query_result[$i]['flag']==1){
//       $balance=$balance+$query_result[$i]['billing_amount'];
//       $time = new DateTime($query_result[$i]['date_']);
//       $particular='Invoice-ID : '.$query_result[$i]['id_invoice'];
//       $result['billing_amount']=$query_result[$i]['billing_amount'];
//       $result['recieved_amount']='';
//     }else if($query_result[$i]['flag']==2){
//       $balance=$balance-$query_result[$i]['recieved_amount'];
//       $time = new DateTime($query_result[$i]['date_']);
//       $particular='Payment-ID : '.$query_result[$i]['id_payment'];
//       $result['recieved_amount']=$query_result[$i]['recieved_amount'];
//         $result['billing_amount']='';
//     }
//     $result['date']=$query_result[$i]['date_'];
//     $result['particular']=$particular;
//     $result['balance']=$balance;
//     array_push($summary,$result);
//   }
//   $sql="DROP TABLE temporary_table";
//   $query = $this->db->query($sql);
//   return $summary;
//
// }


public function customer_statement_by_date(){

  $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');

  $start_date=$this->input->post('start_date');
  $end_date=$this->input->post('end_date');
  $id_customer=$this->input->post('id_customer');

  if(!empty($start_date) && !empty($end_date) && !empty($id_customer)){
    $jsonRes['data'] =$this->get_customer_statement_by_date_range($start_date,$end_date,$id_customer);

  }

  echo json_encode($jsonRes);

}

public function search_customer_statement(){

  $jsonRes = array('status' => '', 'msg' => 'Table Updated !', 'data' => '', 'id_customer' => '','customer_name'=>'');
  $start_date     =$this->input->post('start_date');
  $end_date       =$this->input->post('end_date');
  $net_user_email =trim($this->input->post('net_user_email'));
  $phone          = trim($this->input->post('phone'));
  $id_customer    =trim($this->input->post('id_customer'));

  $flag='false';

  $this->db->select('id_net_user');
  $this->db->from('net_user');
  $sql=array();
  if(!empty($id_customer)){

    $sql['id_net_user'] = $id_customer;
  }
  if(!empty($net_user_email)){
      $sql['net_user_email'] = $net_user_email;
  }

  if(!empty($phone)){
    $sql['net_user_phone'] = $phone;

  }

  if(sizeof($sql)>0){
    $this->db->where($sql);
    $query = $this->db->get();
    $ret = $query->row();
    if($this->db->affected_rows() > 0) {
      $id_customer=$ret->id_net_user;
      $jsonRes['id_customer'] = $id_customer;
      $jsonRes['status'] = 'passed';
      $jsonRes['data'] =$this->get_customer_statement_by_date_range($start_date,$end_date,$id_customer);
      $jsonRes['customer_name'] =$this->get_customer_name($id_customer);

    }else{
      $jsonRes = array('status' => 'failed', 'msg' => 'There is no customer with this ID/EMAIL/PHONE!');

    }
  }else{
    $jsonRes = array('status' => 'failed', 'msg' => 'There is no customer with this ID/EMAIL/PHONE!');
  }

  echo json_encode($jsonRes);
}

public function get_customer_statement_by_date_range($start,$end,$id_net_user){
  $balance=0;
  $result=array();
  $summary=array();

  $sql="CREATE TEMPORARY TABLE `temporary_table` (
    `id_temporary_table` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `date_` date NOT NULL,
    `particular` varchar(200)DEFAULT 0,
    `billing_amount` double DEFAULT 0,
    `recieved_amount` double DEFAULT 0,
    `id_invoice` varchar(200) DEFAULT 0,
    `id_payment` varchar(200) DEFAULT 0,
    `flag` varchar(200) DEFAULT 0,
    `balance` double DEFAULT 0
  );";
  $query = $this->db->query($sql);

  $sql="INSERT INTO temporary_table(date_,particular,billing_amount,balance,id_invoice,flag) SELECT invoice.invoice_date,'Invoice',invoice.invoice_original_amount,'0',invoice.id_invoice,'1' FROM invoice WHERE id_net_user=$id_net_user";
  $query = $this->db->query($sql);

  $sql="INSERT INTO temporary_table(date_,particular,recieved_amount,balance,id_payment,flag) SELECT payment.payment_date,'payment',payment.paid_amount,'0',payment.id_payment,'2' FROM payment WHERE id_net_user='$id_net_user'";
  $query = $this->db->query($sql);

  $sql="SELECT * FROM temporary_table WHERE date_ BETWEEN '$start' AND '$end'  ORDER BY date_ ASC";
  $query = $this->db->query($sql);

  $query_result=$query->result_array();


  $length=sizeof($query_result);

  for($i = 0; $i < $length ; $i++) {

    if($query_result[$i]['flag']==1){
      $balance=$balance+$query_result[$i]['billing_amount'];
      $time = new DateTime($query_result[$i]['date_']);
      $particular='Invoice-ID : '.$query_result[$i]['id_invoice'];
      $result['billing_amount']=$query_result[$i]['billing_amount'];
      $result['recieved_amount']='';
    }else if($query_result[$i]['flag']==2){
      $balance=$balance-$query_result[$i]['recieved_amount'];
      $time = new DateTime($query_result[$i]['date_']);
      $particular='Payment-ID : '.$query_result[$i]['id_payment'];
      $result['recieved_amount']=$query_result[$i]['recieved_amount'];
        $result['billing_amount']='';
    }
    $result['date']=$query_result[$i]['date_'];
    $result['particular']=$particular;
    $result['balance']=$balance;
    array_push($summary,$result);
  }
  $sql="DROP TABLE temporary_table";
  $query = $this->db->query($sql);
  return $summary;
}



public function get_customer_name($id_customer){
  $sql="SELECT net_user_username FROM net_user WHERE id_net_user='$id_customer'";
  $query = $this->db->query($sql);
  $ret = $query->row();
  return $ret->net_user_username;

}

}
