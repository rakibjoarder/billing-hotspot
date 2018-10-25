<?php
class Payment_model extends CI_Model {

    function __construct()
    {
      parent::__construct();
    }

    function add_payment_now()
    {

      $date = new DateTime("now");
      $curr_month = $date->format('M');
      $curr_year = $date->format('Y');
      $curr_date = $date->format('Y-m-d');

      $id_invoice       = $this->input->post('id_invoice');
      $pay_amount       = $this->input->post('pay_amount');
      $due_amount       = $this->input->post('due_amount');
      $id_net_user      = $this->input->post('id_net_user');
      if($due_amount>$pay_amount){

        $due_amount=$due_amount-$pay_amount;

        $this->db->insert('payment', array('id_isp' => $this->session->userdata('id_isp'),'id_invoice' => $id_invoice,'paid_amount' => $pay_amount,'payment_date'=>$curr_date,'id_net_user'=>$id_net_user));

        if($this->db->affected_rows() >0) {
          $this->db->set(array('invoice_amount' => $due_amount))
                    ->where('id_invoice',$id_invoice)
                    ->update('invoice');
          if($this->db->affected_rows() >0) {
            $jsonRes = array('status' => 'success', 'msg' => 'Amount paid : '.$pay_amount.' ! Amount due : '. $due_amount);

            $this->db->insert('notification', array(
              'notification_title' => "Payment Recieve",
              'notification_body' =>  "Invoice #".$id_invoice. "  payment recieved by ".$this->session->userdata('name'),
              'notification_type'  => "message",
              'id_isp'             => $this->session->userdata('id_isp')
            ));


          }

        }
      }else if($due_amount<=$pay_amount)
      {  $due_amount=($due_amount-$pay_amount);
         $this->db->insert('payment', array('id_isp' => $this->session->userdata('id_isp'),'id_invoice' => $id_invoice,'paid_amount' => $pay_amount,'payment_date'=>$curr_date,'id_net_user'=>$id_net_user));

        if($this->db->affected_rows() >0) {
          $this->db->set(array('invoice_status' => '1','invoice_amount' => $due_amount))
          ->where('id_invoice',$id_invoice)
          ->update('invoice');
          if($this->db->affected_rows() >0) {
            $jsonRes = array('status' => 'success', 'msg' => 'Invoice #'.$id_invoice.' is add to paid list !');

            $this->db->insert('notification', array(
              'notification_title' => "Payment Recieve",
              'notification_body' =>  "Invoice #".$id_invoice. "  payment recieved by ".$this->session->userdata('name'),
              'notification_type'  => "message",
              'id_isp'             => $this->session->userdata('id_isp')
            ));

          }
          elseif($this->db->affected_rows() == 0) {
            $jsonRes = array('status' => 'failed', 'msg' => 'Failed to add this invoice to paid list !');

          }

        }

      }
      echo json_encode($jsonRes);
    }

    public function get_all_payment(){

      $id_isp = $this->session->userdata('id_isp');

      $sql="SELECT invoice.id_invoice,net_user.net_user_phone,net_user.net_user_email,net_user.net_user_username,invoice.invoice_original_amount,payment.paid_amount,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`,DATE_FORMAT(payment_date, '%D %b, %Y') as `payment_date`
            FROM payment LEFT JOIN invoice ON payment.id_invoice=invoice.id_invoice LEFT JOIN `net_user` ON `net_user`.`id_net_user`=`payment`.`id_net_user` Where payment.id_isp = $id_isp";

      $query = $this->db->query($sql);
      $query_result=$query->result_array();
      return $query_result;
    }


    public function get_payments_by_date_range(){
      $id_isp = $this->session->userdata('id_isp');
      $jsonRes = array('status' => 'passed', 'msg' => 'Table Updated !', 'data' => '');
      $start_date= $this->input->post('start_date');
      $end_date= $this->input->post('end_date');
      $sql="SELECT invoice.id_invoice,net_user.net_user_phone,net_user.net_user_email,net_user.net_user_username,invoice.invoice_original_amount,payment.paid_amount,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`,DATE_FORMAT(payment_date, '%D %b, %Y') as `payment_date`
      FROM `payment` LEFT JOIN `invoice` ON `payment`.`id_invoice`=`invoice`.`id_invoice`  LEFT JOIN `net_user` ON `net_user`.`id_net_user`=`payment`.`id_net_user` WHERE payment.id_isp = $id_isp AND payment_date BETWEEN '$start_date' AND '$end_date' ";

      $query = $this->db->query($sql);
      $jsonRes['data'] =$query->result_array();

      echo json_encode($jsonRes);

    }


    public function get_all_payment_statement(){
      $id_isp = $this->session->userdata('id_isp');
      $sql="SELECT payment.id_payment,payment.id_net_user,payment.paid_amount,DATE_FORMAT(payment_date, '%D %b, %Y') as `payment_date`
      FROM payment LEFT JOIN invoice ON payment.id_invoice=invoice.id_invoice WHERE payment.id_isp = $id_isp ORDER BY payment.id_payment DESC";

      $query = $this->db->query($sql);
      $query_result=$query->result_array();
      return $query_result;
    }




    public function get_all_Indiv_user_payment($id_net_user){
     $id_isp         = $this->session->userdata('id_isp');


      $sql="SELECT invoice.id_invoice,invoice.invoice_original_amount,payment.paid_amount,DATE_FORMAT(invoice_date, '%D %b, %Y') as `invoice_date`,DATE_FORMAT(payment_date, '%D %b, %Y') as `payment_date`
      FROM payment LEFT JOIN invoice ON payment.id_invoice=invoice.id_invoice WHERE payment.id_isp = $id_isp AND payment.id_net_user=$id_net_user";

      $query = $this->db->query($sql);
      $query_result=$query->result_array();
      return $query_result;
    }



    function search_invoice_now()
    {
      $jsonRes = array('status' => '', 'msg' => 'Table Updated !', 'data' => '');
       $id_invoice      = trim($this->input->post('id_invoice'));
       $net_user_email    = trim($this->input->post('net_user_email'));
       $phone    =       trim($this->input->post('phone'));

       $this->db->select('*');
       $this->db->from('invoice');
       $this->db->join('net_user', 'invoice.id_net_user=net_user.id_net_user', 'left');

       $sql=array();
       if(!empty($id_invoice)){

         $sql['id_invoice'] = $id_invoice;
       }
       if(!empty($net_user_email)){
         $sql['net_user_email'] = $net_user_email;
       }

       if(!empty($phone)){
         $sql['net_user_phone'] = $phone;
       }

       if(sizeof($sql)>0){
         $this->db->where($sql);
       }

       $this->db->where(array('invoice.invoice_status'=>0,'invoice.id_isp' =>$this->session->userdata('id_isp')));
       $RESULT=$this->db->get()->result_array();

       if($this->db->affected_rows() > 0) {
         $jsonRes['status'] = 'passed';
         $jsonRes['data'] =$RESULT;
       }else{
         $jsonRes = array('status' => 'failed', 'msg' => 'There is no Invoice with this Invoice ID/EMAIL/PHONE!');
       }

      echo json_encode($jsonRes);

    }










  }
