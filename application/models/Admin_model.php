<?php
class Admin_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


  public function create_user_now(){

    	if($this->input->post('submit')){
    		$full_name = $this->input->post('full_name');
    		$full_name=trim($full_name);

    		$address = $this->input->post('address');
    		$address= trim($address);

    		$user_name = $this->input->post('user_name');
    		$user_name= trim($user_name);

    		$pwd= $this->input->post('pwd');
    		$pwd_hashed = md5(sha1($pwd));

    		$confirm_pwd= $this->input->post('confirm_pwd');

    		$input_ok_all=1;

    		if(empty($full_name)){

    			echo '<div class="error">Please enter your name</div>';
    			$input_ok_all=0;
			}

			if(empty($address)){
				echo '<div class="error">Please enter your address</div>';
				$input_ok_all=0;
			}

			if(empty($user_name)){
				echo '<div class="error">Please enter a username</div>';
				$input_ok_all=0;
			}


			if(empty($pwd)){
				echo '<div class="error">Please provide a password</div>';
				$input_ok_all=0;
			}

			if(strlen($pwd)<5){
				echo '<div class="error">Your password must be at least 5 characters long</div>';
				$input_ok_all=0;

			}

      if(strcmp($pwd, $confirm_pwd)!=0){
				echo '<div class="error">Passwords should match/div>';
				$input_ok_all=0;
      }

      if(isset($_POST['is_admin'])){
  		    $is_admin=1;
			}else{
				$is_admin=0;
			}

      if($input_ok_all==1){
      	$this->db->select('*');
				$this->db->from('users');
  			$this->db->where('username', $user_name);

  			$query = $this->db->get();
  			if($this->db->affected_rows()!=0){
  				echo '<div class="error">User name already exists</div>';
				} else {
					$info = array('name' => $full_name,
                        'username' => $user_name,
                        'address' => $address,
                        'password' => $pwd_hashed,
                        'is_admin'=>$is_admin
                      );
					$this->db->insert('users', $info);
					if($this->db->affected_rows()>0){
						echo '<span class="success">Succesfully inserted the user</span>';
            $this->db->insert('notification', array(
              'notification_title' => "New User",
              'notification_body' => $full_name." has joined",
              'notification_type'  => "message",
              'id_isp'             => $this->session->userdata('id_isp')
            ));
					}
				}
  		}
		}

	}// end of function create_user_now

  public  function get_all_users(){

    $this->db->select('*');
    $this->db->from('users');
    // $this->db->where('username !=','admin');

    return $this->db->get();
  }

	public  function get_all_routers(){

	  $this->db->select('*');
	  $this->db->from('router');
    $this->db->join('router_type', 'router.id_router_type=router_type.id_router_type', 'left');

	  return $this->db->get();
	}

  public function get_router_info($id) {
    $this->db->select('*');
	  $this->db->from('router');
		$this->db->where(array('id' => $id));

	  return $this->db->get();
  }

	public function delete_router_now(){

		$router_id = $this->input->post('router_id');
		$router_id= trim($router_id);

		if(!empty($router_id)){

			$this->db->delete('router', array('id'=>$router_id));

			if($this->db->affected_rows()>0){
				echo '<span class="success">Successfully deleted !</span>';
			}
		}
	}


  public function edit_router_now(){

		if($this->input->post('submit')) {

			$router_id = $this->input->post('router_id');
			$router_name = $this->input->post('router_name');
			$router_ip = $this->input->post('router_ip');
			$router_login = $this->input->post('router_login');
			$router_password = $this->input->post('router_password');
			$id_router_type = $this->input->post('id_router_type');

      $this->db->select('*');
      $this->db->from('router');
      $this->db->where('ip_address',$router_ip);
      $this->db->where('id!=',$router_id);

      $this->db->get();

      if($this->db->affected_rows()>0){

        echo '<span class="already">IP address already exists</span>';

      }else{
        $this->db->set(array('name'=>$router_name,'ip_address '=>$router_ip, 'login' => $router_login, 'password' => $router_password, 'id_router_type' => $id_router_type))
          ->where('id',$router_id)
          ->update('router');
        if($this->db->affected_rows() > 0) {
          echo '<span class="success">Update successful</span>';
        } elseif($this->db->affected_rows() == 0) {
          echo '<span class="success">No change made for edit</span>';
        }
      }
		}
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

	public function fetch_router_info(){
		$router_id = $this->input->post('router_id');

		//echo ' router_id = '.$router_id;

		$this->db->select('*');
		$this->db->from('router');
		$this->db->where(array('id'=>$router_id));

		$query=$this->db->get();

		$data=$query->result_array();

		return $data;

	}




	public function edit_isp_config_now(){

   if($this->input->post('submit')){
    $isp_name = $this->input->post('isp_name');
    $isp_address = $this->input->post('isp_address');
    $sys_update = $this->input->post('sys_update');
    $query_limit = $this->input->post('query_limit');

        // $this->db->set(array('value'=>$isp_name,'last_change'=>date('Y-m-d H:i:s')))
        $this->db->set(array('value' => $isp_name))
          ->where('key','isp_name')
          ->update('settings');

        $this->db->set(array('value'=>$isp_address))
          ->where('key','isp_address')
          ->update('settings');

        $this->db->set(array('value'=>$sys_update))
          ->where('key','sys_update')
          ->update('settings');

        $this->db->set(array('value'=>$query_limit))
          ->where('key','query_limit')
          ->update('settings');


        echo '<span class="success">Success</span>';
		 }
	}// end of function edit_isp_config_now

	public function  delete_user_now(){

		$user_id = $this->input->post('user_id');
		$user_id = trim($user_id);

		if(!empty($user_id)){

      $this->db->where(array('username !=' => 'admin'));
			$this->db->delete('users', array('id' => $user_id));

			if($this->db->affected_rows() > 0){
				echo '<span class="success">Successfully deleted !</span>';
			} else {
        echo '<span class="error">Delete Opeartion Failed!</span>';
      }
		}

	}// end of function delete_user_now



	public function create_router_now(){

		if($this->input->post('submit')){

			$router_name= $this->input->post('router_name');

			$router_ip= $this->input->post('router_ip');
			$router_ip=trim($router_ip);

      $id_router_type   = $this->input->post('id_router_type');
      $router_login     = $this->input->post('router_login');
      $router_password  = $this->input->post('router_password');

			$this->db->select('*');
			$this->db->from('router');
			$this->db->where(array('ip_address'=>$router_ip));

			$this->db->get();

			if($this->db->affected_rows()>0){
				echo '<span class="already">IP address already exists</span>';
			}
      else{

  			$this->db->insert('router', array('name'=>$router_name, 'ip_address'=>$router_ip, 'id_router_type' => $id_router_type, 'login' => $router_login, 'password' => $router_password));
  			if($this->db->affected_rows()>0){
  				echo '<span class="success">Successfully inserted </span>';
          $this->db->insert('notification', array(
            'notification_title' => "New Router",
            'notification_body' => "New Router ".$router_name." added. IP Address is ".$router_ip,
            'notification_type'  => "message",
            'id_isp'             => $this->session->userdata('id_isp')
          ));
  			}
			}

		}

	}// end of function create_router_now



	public  function fetch_individual_user_info($user_id){

	    	$this->db->select('*');
		    $this->db->from('users');
		    $this->db->where(array('id'=>$user_id));
		    return  $this->db->get();

	}// end of function

	public function edit_user_now(){

    if($this->input->post('submit')){

    	$user_id = $this->input->post('user_id');
    	$full_name = $this->input->post('full_name');
    	$address = $this->input->post('address');
    	$user_name = $this->input->post('user_name');

    	$pwd = $this->input->post('pwd');
    	$confirm_pwd = $this->input->post('confirm_pwd');

    	$is_admin = $this->input->post('is_admin');

    	if($is_admin=='on'){
    		$is_admin=1;
  		}else{
  			$is_admin=0;
  		}




	    	if(trim($pwd) !=trim("")){
	    		if(strlen($pwd) < 5){
	    				echo '<span class="error">Password should be at least 5 character long </span>';
				  } elseif($pwd!=$confirm_pwd){
	    			    echo '<span class="error">Passwords do not match</span>';
				      }else{
					      $pwd_hashed = md5(sha1($pwd));
					      $this->db->update('users', array('name'=>$full_name, 'address'=>$address,'password'=>$pwd_hashed,'is_admin'=>$is_admin), array('id'=>$user_id));
                if($this->db->affected_rows()==0){
                  echo '<span class="error">No change made for edit</span>';
                }elseif($this->db->affected_rows()>0){
                  echo '<span class="success">Successfully edited</span>';
                }
				      }
				  }else{

					$this->db->update('users', array('name'=>$full_name,  'address'=>$address,'is_admin'=>$is_admin), array('id'=>$user_id));

					if($this->db->affected_rows()==0){

						echo '<span class="error">No change made for edit</span>';

					}elseif($this->db->affected_rows()>0){

						echo '<span class="success">Successfully edited</span>';

					}
				}
		    }
	}


}

?>
