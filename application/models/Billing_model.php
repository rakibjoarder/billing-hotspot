<?php
class Billing_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function get_all_billable_customer(){
    $sql   = 'SELECT * FROM net_user LEFT JOIN package ON net_user.id_package = package.id_package Where is_active = 0';
    $query = $this->db->query($sql);
    $query_result = $query->result_array();
    return $query_result;
  }


function get_total_invoice_amount($id_net_user){
  $total = 0;

  $sql    = "SELECT SUM(invoice_original_amount) as amount FROM invoice WHERE id_net_user = $id_net_user ";
  $query  = $this->db->query($sql);

  if($this->db->affected_rows() > 0) {
    $query_result = $query->result_array();
    if(sizeof($query_result) > 0 && !empty($query_result[0]['amount'])) {
      $total = $query_result[0]['amount'];
    }
  }

  return $total;
}

function get_total_paid_amount($id_net_user){
  $total = 0;

  $sql    = "SELECT SUM(paid_amount) as paid FROM payment WHERE id_net_user = $id_net_user ";
  $query  = $this->db->query($sql);

  if($this->db->affected_rows() > 0) {
    $query_result = $query->result_array();
    if(sizeof($query_result) > 0 && !empty($query_result[0]['paid'])) {
      $total = $query_result[0]['paid'];
    }
  }

  return $total;
}


  function get_customer_due($id_net_user) {

    $due = ($this->get_total_invoice_amount($id_net_user) - $this->get_total_paid_amount($id_net_user));

    // $sql    = "SELECT (SELECT SUM(invoice_original_amount) FROM invoice WHERE id_net_user = $id_net_user)-(SELECT SUM(paid_amount) FROM payment WHERE id_net_user = $id_net_user) as due";
    // $query  = $this->db->query($sql);
    // if($this->db->affected_rows() > 0) {
    //   $query_result = $query->result_array();
    //   if(sizeof($query_result) > 0 && !empty($query_result[0]['due'])) {
    //     $due = $query_result[0]['due'];
    //   }
    // }
    //
    // error_log("customer ID ". $id_net_user ." due is ". $due);

    return $due;
  }


  function has_invoice_already_generated($id_net_user,$curr_month,$curr_year){
    $hasInvoice = false;

    $this->db->select('*');
    $this->db->from('invoice');
    $this->db->where(array('MONTH(invoice_date)' => $curr_month,'YEAR(invoice_date)' => $curr_year,'id_net_user' => $id_net_user,'rec_flag' => 0));
    $this->db->get();

    if($this->db->affected_rows() > 0){
      $hasInvoice = true;
    }

    return $hasInvoice;
  }




  function generate_invoice(){

     $date        = new DateTime("now");
     $curr_month  = $date->format('m');
     $curr_year   = $date->format('Y');
     $curr_day    = $date->format('d');
     $curr_date   = $date->format('Y-m-d');

     $customers = $this->get_all_billable_customer();
     $main_array = array();

    foreach ($customers as $cus){
      $sql = array();
      $total_due = 0;
      $has_generated_flag = $this->has_invoice_already_generated($cus['id_net_user'],$curr_month,$curr_year);

      if($has_generated_flag == false){

        if(empty($cus['invoice_gen'])){
          $billing_amount = $cus['net_user_billing_amount'];
        }else{
          //last_invoice_generated_date
          $invoice_gen_date  =  new DateTime($cus['invoice_gen']);
          $invoice_gen_month = $invoice_gen_date->format('m');
          $invoice_gen_day   = $invoice_gen_date->format('d');
          $invoice_gen_year  = $invoice_gen_date->format('Y');

          //last_date_of_last_invoice_generated_date
          $last_date = new DateTime(date("Y-m-t", strtotime($cus['invoice_gen'])));
          $_day      = $last_date->format('d');
          $_month    = $last_date->format('m');
          $_year     = $last_date->format('m');

          error_log("Number of days in the last month " . $_day) ;
          error_log("invoice_gen_date" . $invoice_gen_day) ;

          if($invoice_gen_month == $_month && $invoice_gen_year = $_year ){
            $total_day =  $_day - $invoice_gen_day;
            $billing_amount =  round((($cus['net_user_billing_amount']/$_day ) * $total_day),2) ;
          }
        }

        $sql['invoice_original_amount']  = round($billing_amount,2);;
        $sql['rec_flag']                 = 0;
        $sql['invoice_status']           = 0;
        $sql['invoice_date']             = $curr_date;
        $sql['id_net_user']              = $cus['id_net_user'];
        $total_due                       =  $this->get_customer_due($cus['id_net_user']);;
        $sql['due']                      =  round($total_due,2);
        $total_payable                   = $billing_amount + $total_due ;
        $sql['payable']                  = round($total_payable,2);
        $sql['invoice_amount']           = round($total_payable,2);

        array_push($main_array,$sql);
        $this->db->insert('invoice',$sql);

        if($this->db->affected_rows() > 0) {
          if($cus['service_type'] == 'Recurring'){
            $this->db->update('net_user', array('invoice_gen' =>$curr_date),array('id_net_user' => $cus['id_net_user'] ));
          }
        }
      }

     }
    print_r($main_array);

  }

  public function get_indiv_payment_term_days($id_payment_term){

    $this->db->select('*');
    $this->db->from('payment_term');
    $this->db->where(array('id_payment_term'=>$id_payment_term));
    $query = $this->db->get()->row();

    return $query->id_payment_term;
  }


  public function disable_connection(){


    //current date
    $date        = new DateTime("now");
    $curr_month  = $date->format('m');
    $curr_year   = $date->format('Y');
    $curr_day    = $date->format('d');
    $current_date   = $date->format('Y-m-d');

    $max_allowed_due = $this->settings_model->get_by_key('max_allowed_due');


    error_log("CURRENT DAY :::::::::::::::::;" .$curr_day);

    //getting all active customer
    $customers   = $this->get_all_billable_customer();

    foreach ($customers as $cus){
      error_log("");

      error_log("NET USER " . $cus['net_user_name']);

      if(!empty($cus['invoice_gen'])){
        //getting last invoice generated date
        $invoice_gen_date  =  new DateTime($cus['invoice_gen']);
        $invoice_gen_month = $invoice_gen_date->format('m');
        $invoice_gen_day   = $invoice_gen_date->format('d');
        $invoice_gen_year  = $invoice_gen_date->format('Y');
        $last_invoice_gen_date   = $invoice_gen_date->format('Y-m-d');

        error_log("Invoice Generated DAY =" .$invoice_gen_day);

        //getting payment terms day
        $payment_term_days = $this->get_indiv_payment_term_days($cus['id_payment_term']);


        error_log("Paymeny Terms Day =" .$payment_term_days);

        //total due
        $total_due         = $this->get_customer_due($cus['id_net_user']);

        error_log("Total Due =" .$total_due);

        $extended_day      =  $payment_term_days + $invoice_gen_day ;


        $extended_date     = date('Y-m-d', strtotime($last_invoice_gen_date. '+ '.$payment_term_days.'days'));

        error_log("Extended Day =" .$extended_day);

        $d = new DateTime($extended_date);

        // echo "Invoice generated day ".$invoice_gen_day .".".$invoice_gen_month .".".$invoice_gen_year ."</br>Payment term days ".$payment_term_days ."</br>Extendend day" .$d->format('d').".".$d->format('m').".".$d->format('Y').'</br>' ;

        if($extended_date  > $current_date ){
          if($total_due > $max_allowed_due){
            // $this->db->update('net_user', array('is_active' =>1),array('id_net_user' => $cus['id_net_user'] ));
              error_log("Connetion Disabled :(");
                  // echo("Connetion Disabled :(".'</br></br></br>');
          }else{
              error_log("Connetion Active :)");
                  // echo("Connetion Disabled :(".'</br></br></br>');
          }
        }else{
          error_log("Connetion Active :)");
              // echo("Connetion Disabled :(".'</br></br></br>');
        }

      }
    }
  }


  function view_invoices_now()
  {

    $invoices=$this->get_individual_active_invoice();
    return $invoices;
  //   foreach ($invoices as $invoice)
  //  {
  //     error_log("ID:".$invoice['id_invoice']);
   //
   //
  //   }

  }
  public function get_indiv_user_invoice($id_net_user){

    $id_isp = $this->session->userdata('id_isp');

    $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,net_user.net_user_phone,net_user.net_user_username,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
    FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.id_isp = $id_isp AND invoice.id_net_user= $id_net_user ";

    $query = $this->db->query($sql);
    $query_result=$query->result_array();
    return $query_result;

  }


public function yesterdays_invoice(){
  $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');
  $invoice_date= $this->input->post('invoice_date');

  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
  FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.invoice_status= 0 AND invoice.invoice_date = '$invoice_date'";

  $query = $this->db->query($sql);
  $jsonRes['data'] =$query->result_array();
  echo json_encode($jsonRes);
}


public function todays_invoice(){
    $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');
  $invoice_date= $this->input->post('invoice_date');

  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
  FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.invoice_status= 0 AND invoice.invoice_date = '$invoice_date'";

  $query = $this->db->query($sql);
  $jsonRes['data'] =$query->result_array();
  echo json_encode($jsonRes);
}

public function current_month_invoice(){
  $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');

  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
  FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.invoice_status= 0 AND MONTH(invoice_date)= MONTH(CURRENT_DATE()) AND YEAR(invoice_date) = YEAR(CURRENT_DATE())";

  $query = $this->db->query($sql);
  $jsonRes['data'] =$query->result_array();

  echo json_encode($jsonRes);

}



public function previous_month_invoice(){
  $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');

  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
  FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.invoice_status= 0 AND MONTH(invoice_date)= MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND YEAR(invoice_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)";

  $query = $this->db->query($sql);
  $jsonRes['data'] =$query->result_array();

  echo json_encode($jsonRes);

}


public function get_invoices_by_date_range(){
  $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');
  $start_date= $this->input->post('start_date');
  $end_date= $this->input->post('end_date');
  $id_isp = $this->session->userdata('id_isp');
  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_username,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date` FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.id_isp = $id_isp AND invoice.invoice_status= 0 AND invoice_date BETWEEN '$start_date' AND '$end_date'";

  $query = $this->db->query($sql);
  $jsonRes['data'] =$query->result_array();

  echo json_encode($jsonRes);

}


public function get_all_invoice_statement(){

  $id_isp = $this->session->userdata('id_isp');
  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date` FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.id_isp = $id_isp ORDER By invoice.id_invoice DESC";

  $query = $this->db->query($sql);
  $query_result=$query->result_array();
  return $query_result;

}


public function get_all_invoice_statement_by_date_range(){

  $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');
  $start_date= $this->input->post('start_date');
  $end_date= $this->input->post('end_date');
  $id_isp = $this->session->userdata('id_isp');
  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date` FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE  invoice.id_isp = $id_isp AND invoice_date BETWEEN '$start_date' AND '$end_date' ORDER By invoice.id_invoice DESC";

  $query = $this->db->query($sql);
  $jsonRes['data'] =$query->result_array();

  echo json_encode($jsonRes);

}

public function get_all_payment_statement_by_date_range(){
  $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');
  $start_date= $this->input->post('start_date');
  $end_date= $this->input->post('end_date');
  $id_isp = $this->session->userdata('id_isp');
  $sql="SELECT payment.id_payment,payment.id_net_user,payment.paid_amount,DATE_FORMAT(payment_date, '%D %b, %Y') as `payment_date` FROM payment LEFT JOIN invoice ON payment.id_invoice=invoice.id_invoice WHERE payment.id_isp =$id_isp AND  payment_date BETWEEN '$start_date' AND '$end_date' ORDER By payment.payment_date DESC";

  $query = $this->db->query($sql);
  $jsonRes['data'] =$query->result_array();

  echo json_encode($jsonRes);

}

public function get_all_invoice(){
  $id_isp = $this->session->userdata('id_isp');

  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_username,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
  FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.id_isp= $id_isp AND invoice.invoice_status= 0";

  $query = $this->db->query($sql);
  $query_result=$query->result_array();
  return $query_result;

}

  public function pppoe_indiv_invoice_view($id_user){
    $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,net_user.net_user_phone,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
    FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.id_isp=".$this->session->userdata('id_isp')." AND invoice.id_net_user=$id_user";
    error_log("SQL::".$sql);
    $query = $this->db->query($sql);
    $query_result=$query->result_array();
    return $query_result;
    //
    // $this->db->select('*');
    // $this->db->from('invoice');
    // $this->db->join('net_user', 'net_user.id_net_user=invoice.id_net_user', 'left');
    // $this->db->where(array('id_isp' => $this->session->userdata('id_isp'),'invoice.id_net_user'=>$id_user));
    // return $this->db->get()->result_array();
  }

  function get_individual_active_invoice()
  {

    // SELECT * FROM `invoice` LEFT JOIN `connection_customer` ON `invoice`.`id_connection`=`connection_customer`.`id_connection` LEFT JOIN `net_user` ON `connection_customer`.`id_net_user`=`net_user`.`id_net_user` WHERE `invoice_status` = 1 ORDER BY `invoice`.`id_connection` ASC
    $this->db->select('*');
    $this->db->from('invoice');
    $this->db->where('invoice_status',1);
    $this->db->order_by("invoice.id_net_user", "asc");
    $this->db->join('net_user', 'invoice.id_net_user=net_user.id_net_user', 'left');
    return $this->db->get()->result_array();
  }

  function get_active_invoice()
  {
    $this->db->select('*');
    $this->db->from('invoice');
    $this->db->where('invoice_status',1);
    return $this->db->get()->result_array();
  }

  function get_individual_invoice_info($id_invoice)
  {
    // $this->db->select('*');
    // $this->db->from('invoice');
    // $this->db->where('id_invoice' , $id_invoice);
    // $this->db->join('net_user', 'invoice.id_net_user=net_user.id_net_user', 'left');
    // $this->db->join('package', 'net_user.id_package=package.id_package', 'left');
    // return $this->db->get()->result_array();

    $sql="SELECT package.package_name,package.package_speed,net_user.id_net_user,net_user.net_user_email,net_user.net_user_address,net_user.net_user_phone,invoice.description,invoice.payable,invoice.due,invoice.rec_flag,invoice.id_invoice,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
    FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user
    LEFT JOIN package ON net_user.id_package=package.id_package
    WHERE invoice.id_invoice=$id_invoice";
    $query = $this->db->query($sql);
    $query_result=$query->result_array();
    return $query_result;
  }

  function get_individual_payment_info($id_invoice){
    $this->db->select('*');
    $this->db->from('payment');
    $this->db->where('id_invoice' , $id_invoice);
    return $this->db->get()->result_array();
  }

  function get_unique_users_info()
  {
    $sql = "SELECT * FROM connection_customer LEFT JOIN package ON connection_customer.id_package = package.id_package WHERE id_connection NOT IN
    ( SELECT id_connection FROM invoice WHERE YEAR(invoice_date) = YEAR(NOW()) AND MONTH(invoice_date)=MONTH(NOW()) )";
    error_log($sql);
    $query = $this->db->query($sql);
    return $query->result_array();


    //Find all distinct Net Users Info
  //  $this->db->distinct();
    // $this->db->select('*');
    // $this->db->from('net_user');
    // $this->db->join('package', 'net_user.id_package=package.id_package', 'left');
    // return $this->db->get()->result_array();

  }


public function get_indv_invoice_info($id_invoice)
{
  $sql="SELECT invoice.id_invoice,net_user.id_net_user,net_user.net_user_email,invoice.invoice_original_amount,invoice.invoice_amount,invoice.invoice_status,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`
  FROM invoice LEFT JOIN net_user ON invoice.id_net_user=net_user.id_net_user WHERE invoice.id_invoice=$id_invoice";

  $query = $this->db->query($sql);
  $query_result=$query->result_array();
  return $query_result;

  // $this->db->select('*');
  // $this->db->from('invoice');
  // $this->db->join('net_user', 'invoice.id_net_user=net_user.id_net_user', 'left');
  // $this->db->where('id_invoice=',$id_invoice);
  // return $this->db->get()->result_array();
}

  // public function create_invoice_now()
  // {
  //       $date = new DateTime("now");
  //       $curr_month = $date->format('m');
  //       $curr_year = $date->format('Y');
  //       $curr_date = $date->format('Y-m-d');
  //
  //
  //
  //   if($this->input->post('submit')){
  //
  //       $id_net_user      = $this->input->post('id_net_user');
  //       $idtax            = $this->input->post('id_tax');
  //       $quantity         =$this->input->post('quantity');
  //       $total            =$this->input->post('total');
  //       $calculated_total =$this->input->post('total_');
  //       $calculated_tax   =$this->input->post('tax_');
  //       $price            =$this->input->post('price');
  //       $item_name        =$this->input->post('item_name');
  //       $discount         =$this->input->post('discount_');
  //       $subtotal         =$this->input->post('subtotal');
  //
  //
  //       $invoice = array('id_tax' => $idtax,
  //                        'discount'  => $discount,
  //                        'id_net_user' => $id_net_user,
  //                        'invoice_original_amount'=> $calculated_total,
  //                        'invoice_amount'=> $calculated_total,
  //                        'invoice_date' => $curr_date,
  //                        'calculated_tax'=>$calculated_tax,
  //                        'subtotal'=>$subtotal);
  //
  //     $this->db->insert('invoice', $invoice);
  //     $lastid = $this->db->insert_id();
  //
  //     for($i=0; $i < count($quantity); $i++) {
  //         $product= array('quantity'=>  $quantity[$i],
  //                         'item_name'=> $item_name[$i],
  //                         'total'=> ($price[$i]*$quantity[$i]),
  //                         'id_invoice'=>$lastid,
  //                         'price'=> $price[$i]);
  //          $this->db->insert('product', $product);
  //       }
  //
  //     if($this->db->affected_rows() > 0) {
  //       $json = array('status' => 'success', 'msg' => 'Succesfully generated Invoice !');
  //       $this->db->insert('notification', array(
  //         'notification_title' => "New Invoice",
  //         'notification_body' =>  " has created",
  //         'notification_type'  => "message"
  //       ));
  //     }
  //     else {
  //       $json = array('status' => 'failed', 'msg' => 'Failed to create Invoice !');
  //     }
  //   }
  //
  //   echo json_encode($json);
  //
  //
  // }



  ////
  public function get_individual_connection()
  {
   $id_net_user   = $this->input->post('id_net_user');
   $this->db->select('*');
   $this->db->from('connection_customer');
   $this->db->where('id_net_user=',$id_net_user);
   $data = $this->db->get()->result_array();
   echo json_encode($data);
 }


 public function create_invoice_now(){
   $date = new DateTime("now");
   $curr_month = $date->format('m');
   $curr_year = $date->format('Y');
   $curr_date = $date->format('Y-m-d');

   if($this->input->post('submit')){

     $id_net_user   = $this->input->post('id_net_user');
     $package_price = $this->input->post('net_user_mrc_price');
     $package_name  = $this->input->post('id_package');

     $invoice = array( 'id_net_user'             => $id_net_user,
     'invoice_original_amount' => $package_price,
     'invoice_amount'          => $package_price,
     'invoice_date'            => $curr_date,
     'due'                     => $package_price,
     'payable'                 => $package_price,
     'description'             => $package_name,
     'rec_flag'                => 1,
     'id_isp'                  => $this->session->userdata('id_isp')
   );

   $this->db->insert('invoice', $invoice);

   $invoice_id = $this->db->insert_id();
   error_log("INVOICE ID :". $invoice_id);

   if($this->db->affected_rows() > 0) {
     $jsonRes = array('status' => 'success', 'msg' => 'Succesfully generated  invoice !','invoice_id' => $invoice_id);
     $this->db->insert('notification', array(
       'notification_title' => "New Invoice",
       'notification_body' =>  "Invoice #".$invoice_id. " generated by ".$this->session->userdata('name'),
       'notification_type'  => "message",
       'id_isp'             => $this->session->userdata('id_isp')
     ));
   }
   else {
     $jsonRes = array('status' => 'failed', 'msg' => 'Failed to generate  invoice !');
   }
 }
 echo json_encode($jsonRes);
}

 public function view_recurring_invoices_now()
{
  // SELECT * FROM `invoice` LEFT JOIN `connection_customer` ON `invoice`.`id_connection`=`connection_customer`.`id_connection` LEFT JOIN `net_user` ON `connection_customer`.`id_net_user`=`net_user`.`id_net_user` WHERE `invoice_status` = 1 ORDER BY `invoice`.`id_connection` ASC
    $this->db->select('*');
    $this->db->from('recurring_invoice');
    $this->db->where('invoice_status',1);
    $this->db->order_by("recurring_invoice.id_net_user", "asc");
    $this->db->join('net_user', 'recurring_invoice.id_net_user=net_user.id_net_user', 'left');
    $this->db->join('tax', 'recurring_invoice.id_tax=tax.id_tax', 'left');
    $this->db->join('connection_customer', 'recurring_invoice.id_connection=connection_customer.id_connection', 'left');
    $this->db->join('package', 'recurring_invoice.id_package=package.id_package', 'left');
    $this->db->join('payment_term', 'recurring_invoice.id_payment_term=payment_term.id_payment_term', 'left');
    $this->db->join('repeat_every', 'recurring_invoice.id_repeat_every=repeat_every.id_repeat_every', 'left');

    return $this->db->get()->result_array();
}


public function delete_recurring_invoice_now(){

  $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted Recurring Invoice!', 'data' => '');
  $id_recurring_invoice = $this->input->post('id_recurring_invoice');
  $id_recurring_invoice= trim($id_recurring_invoice);

  if(!empty($id_recurring_invoice)){

    $this->db->delete('recurring_invoice', array('id_recurring_invoice'=>$id_recurring_invoice));

    if($this->db->affected_rows()>0){
      $jsonRes['data'] = $this->view_recurring_invoices_now();
    }
    else {
        $jsonRes = array('status' => 'failed', 'msg' => 'Failed to delete Recurring Invoice !');
    }
  }
  echo json_encode($jsonRes);
}


	public  function fetch_individual_recurring_invoice($id_recurring_invoice){



	    	$this->db->select('recurring_invoice.id_recurring_invoice,
                           recurring_invoice.id_net_user,
                           net_user.net_user_name,
                           recurring_invoice.id_package,
                           recurring_invoice.id_connection,
                           connection_customer.connectionname,
                           recurring_invoice.id_tax,
                           recurring_invoice.invoice_original_amount,
                           recurring_invoice.invoice_amount,
                           recurring_invoice.id_payment_term,
                           recurring_invoice.id_repeat_every,
                           recurring_invoice.discount,
                           packageprice,
                           recurring_invoice.calculated_tax,
                           recurring_invoice.discount_type,
                           recurring_invoice.discount_percentage'
      );
		    $this->db->from('recurring_invoice');
		    $this->db->where(array('id_recurring_invoice'=>$id_recurring_invoice));
        $this->db->join('net_user', 'net_user.id_net_user=recurring_invoice.id_net_user', 'left');
        $this->db->join('connection_customer', 'connection_customer.id_connection=recurring_invoice.id_connection', 'left');
		    return  $this->db->get()->result_array();

	}// end of function

  public  function get_all_taxes(){
    $this->db->select('*');
    $this->db->from('tax');
    return $this->db->get()->result_array();
  }

  public function get_all_payment_term()
  {
    $this->db->select('*');
    $this->db->from('payment_term');
    return $this->db->get()->result_array();
  }


  public function get_all_repeat_every()
  {
    $this->db->select('*');
    $this->db->from('repeat_every');
    return $this->db->get()->result_array();
  }

  public function edit_recurring_invoice_now()
  {

    $date = new DateTime("now");
    $curr_month = $date->format('m');
    $curr_year = $date->format('Y');
    $curr_date = $date->format('Y-m-d');


    if($this->input->post('submit')) {
      $id_recurring_invoice= $this->input->post('id_recurring_invoice');
      $id_net_user = $this->input->post('id_net_user');
      $id_connection=$this->input->post('connection');
      $id_package = $this->input->post('id_package');
      $package_price = $this->input->post('package_price');
      $id_payment_term = $this->input->post('payment_term');
      $id_repeat_every=$this->input->post('repeat_every');
      $discount=$this->input->post('cal_discount');
      $idtax = $this->input->post('cal_tax');
      $tax = $this->input->post('id_tax');
      $discount_type  =$this->input->post('discount_radio');
      $discount_percentage = $this->input->post('discount');

      $cal_tax=$package_price*($tax/100);
      $invoice_original_amount=$package_price+$cal_tax-$discount;

      $this->db->set(array(
                          'id_net_user' => $id_net_user,
                          'id_package'  => $id_package,
                          'id_connection' => $id_connection,
                          'id_tax'=> $idtax,
                          'packageprice'=> $package_price,
                          'calculated_tax'=> $cal_tax,
                          'invoice_original_amount'=> $invoice_original_amount,
                          'invoice_amount' => $invoice_original_amount,
                          'invoice_date' => $curr_date,
                          'id_payment_term' => $id_payment_term,
                          'id_repeat_every' =>  $id_repeat_every,
                          'discount_type' => $discount_type,
                          'discount' => $discount,
                          'discount_percentage'=>$discount_percentage))
        ->where('id_recurring_invoice',$id_recurring_invoice)
        ->update('recurring_invoice');
      if($this->db->affected_rows() >0) {
        $jsonRes = array('status' => 'success', 'msg' => 'Succesfully Updated !');
      } elseif($this->db->affected_rows() == 0) {
          $jsonRes = array('status' => 'failed', 'msg' => 'No change made !');
      }
    }
    echo json_encode($jsonRes);

  }

  public function get_all_product($id_invoice)
  {
    $this->db->select('*');
    $this->db->from('product');
    $this->db->where(array('id_invoice' => $id_invoice));
    $rows=$this->db->get();
    error_log($this->db->affected_rows());
    return $rows->result_array();
  }

  public function get_individual_invoice($id_invoice)
  {
    $this->db->select('*');
    $this->db->from('invoice');
    $this->db->where(array('id_invoice' => $id_invoice));
    $rows=$this->db->get();
    error_log($this->db->affected_rows());
    return $rows->result_array();
  }




}
