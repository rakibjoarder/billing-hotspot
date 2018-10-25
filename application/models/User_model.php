<?php
class User_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


    public  function get_all_users(){
      $this->db->select('*');
      $this->db->from('users');
      $this->db->join('role','users.id_role=role.id_role', 'left');
      $this->db->where(array('users.id_isp' => $this->session->userdata('id_isp')));

      return $this->db->get()->result_array();
    }

    public  function get_user_name_by_id($user_id){
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where(array('id'=>$user_id));
      return $this->db->get()->row()->name;
    }




    public function  delete_user_now(){

      $jsonRes = array('status' => 'passed', 'msg' => 'Successfully Deleted User!', 'data' => '');
      $user_id = $this->input->post('userid');
      $user_id = trim($user_id);
      $user_name =  $this->get_user_name_by_id($user_id);

      if(!empty($user_id)){

        $this->db->delete('users', array('id' => $user_id));

        if($this->db->affected_rows() > 0){
          $jsonRes['data'] = $this->get_all_users();

          $this->db->insert('notification', array(
            'notification_title' => "User Delete",
            'notification_body' => $user_name." deleted by ".$this->session->userdata('name'),
            'notification_type'  => "message",
            'id_isp'             => $this->session->userdata('id_isp')
          ));

        } else {
          $jsonRes = array('status' => 'failed', 'msg' => 'Delete opertaion failed');

        }
      }
      echo json_encode($jsonRes);
    }

    public function create_user_now(){

      if($this->input->post('submit')){

        $full_name = $this->input->post('full_name');
        $address = $this->input->post('address');
        $user_name = $this->input->post('user_name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $designation = $this->input->post('designation');
        $id_role = $this->input->post('id_role');
        $pwd= $this->input->post('pwd');

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("email = '$email' or phone = '$phone'");
        $this->db->get();

        if($this->db->affected_rows()>0){
          $jsonRes = array('status' => 'failed', 'msg' => 'User already exists !');
        }else{
          $info=array('id_isp'=> $this->session->userdata('id_isp'), 'name'=>$full_name, 'username'=>$user_name, 'phone' => $phone , 'email' => $email, 'address'=>$address, 'designation'=>$designation,'password'=>$pwd,'id_role'=>$id_role );
          $this->db->insert('users', $info);
          if($this->db->affected_rows()>0){
            $jsonRes = array('status' => 'success', 'msg' => 'Succesfully inserted the user !');
            $this->db->insert('notification', array(
              'notification_title' => "New User",
              'notification_body' => $full_name." created by ".$this->session->userdata('name'),
              'notification_type'  => "message",
              'id_isp'             => $this->session->userdata('id_isp')
            ));
          }
        }
      }
      echo json_encode($jsonRes);
    }




  public function edit_user_now(){

    if($this->input->post('submit')){

      $user_id     = $this->input->post('user_id');
      $full_name   = $this->input->post('full_name');
      $address     = $this->input->post('address');
      $user_name   = $this->input->post('user_name');
      $email       = $this->input->post('email');
      $phone       = $this->input->post('phone');
      $pwd         = $this->input->post('pwd');
      $designation = $this->input->post('designation');
      $id_role     = $this->input->post('id_role');
      $id_isp      =  $this->session->userdata('id_isp');

      $this->db->select('*');
      $this->db->from('users');
      $this->db->where("(email = '$email' or phone = '$phone') and (id != '$user_id' and id_isp = $id_isp)");
      $this->db->get();

      if($this->db->affected_rows()>0){
        $jsonRes = array('status' => 'failed', 'msg' => 'User already exists !');
      }
      else{
        if(strlen($pwd)==0){
            $this->db->update('users', array('name'=>$full_name, 'username'=>$user_name,  'address'=>$address, 'designation'=>$designation,'id_role'=>$id_role,'email'=>$email,'phone'=>$phone), array('id'=>$user_id,'id_isp' => $id_isp));
        }
        else{
            $this->db->update('users', array('name'=>$full_name, 'username'=>$user_name,'address'=>$address, 'designation'=>$designation,'password'=>$pwd,'id_role'=>$id_role,'email'=>$email,'phone'=>$phone), array('id'=>$user_id, 'id_isp' => $id_isp));
        }
        if($this->db->affected_rows()==0){
          $jsonRes = array('status' => 'failed', 'msg' => 'No change made for edit!');
        }elseif($this->db->affected_rows()>0){
          $jsonRes = array('status' => 'success', 'msg' => 'User Updated successfully !');

          $this->db->insert('notification', array(
            'notification_title' => "User Edit",
            'notification_body' => $full_name." edited by ".$this->session->userdata('name'),
            'notification_type'  => "message",
            'id_isp'             => $this->session->userdata('id_isp')
          ));


        }
      }
    }
    echo json_encode($jsonRes);
  }

  public function fetch_user_info(){

    $user_id = $this->input->post('user_id');
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where(array('id'=>$user_id));
    $query=$this->db->get();
    $data=$query->result_array();

    return $data;
  }

	public  function fetch_individual_user_info($user_id){
  	$this->db->select('*');
    $this->db->from('users');
    $this->db->where(array('id'=>$user_id));
    return  $this->db->get();
	}




}

?>
