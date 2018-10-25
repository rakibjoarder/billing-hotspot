<?php
class Search_model extends CI_Model {

  function __construct() {
    parent::__construct();
    if( ! ini_get('date.timezone') ) {
      date_default_timezone_set('GMT');
    }

    $this->load->model('settings_model');
    $this->load->model('router_model');
  }

  function get_all_trace_table() {
    $query_result = $this->db->list_tables();
    return $query_result;
  }

  public function get_by_key($key) {
    $value = '';
    $this->db->select('value');
    $this->db->from('settings');
    $this->db->where('key', $key);
    $result = $this->db->get()->result_array();
    if($this->db->affected_rows() > 0) {
      foreach($result as $row) {
        $value = $row['value'];
        error_log($key .' value is '. $value);
      }
    }
    return $value;
  }


  public function progress_search_result($tablename, $row_start, $row_limit) {
		$result = $this->search_by_table($tablename, $row_start, $row_limit);
		$len = sizeof($result);
		error_log("Length of search query ". $len);

		if($len > 0) {
			echo json_encode($result);
			flush();
			ob_flush();
			usleep(500000);
		}

		return $len;
	}


  function search_by_table($table_name, $row_start, $row_limit) {

    $dest_ip      = trim($this->input->post('dest_ip'));
    $dest_port    = trim($this->input->post('dest_port'));
    $src_ip       = trim($this->input->post('src_ip'));
    $src_port     = trim($this->input->post('src_port'));
    $mac          = trim($this->input->post('mac_address'));
    $router_ip    = trim($this->input->post('router_ip'));
    $fromDate     = trim($this->input->post('start_date_time'));
    $toDate       = trim($this->input->post('end_date_time'));

    // $limit = $this->settings_model->get_by_key('query_limit');
    $isWhereAdded = false;

    $sql = 'SELECT  `mac`, `user_id`, `router_ip`, `dest_ip`, `dest_port`, `src_ip`, `src_port`, `domain`, `access_time` FROM '. $table_name .' ';

    if($dest_ip != "") {
      error_log("dest_ip ". $dest_ip);
      if($isWhereAdded) {
        $sql .= 'AND dest_ip = "'. $dest_ip . '" ';
      } else {
        $sql .= 'WHERE dest_ip = "'. $dest_ip . '" ';
        $isWhereAdded = true;
      }
    }

    if($dest_port != "") {
      error_log("dest_port ". $dest_port);
      if($isWhereAdded) {
        $sql .= 'AND dest_port = "'. $dest_port . '" ';
      } else {
        $sql .= 'WHERE dest_port = "'. $dest_port . '" ';
        $isWhereAdded = true;
      }
    }

    if($mac != "") {
      error_log("mac_address ". $mac);
      if($isWhereAdded) {
        $sql .= 'AND mac = "'. $mac . '" ';
      } else {
        $sql .= 'WHERE mac = "'. $mac . '" ';
        $isWhereAdded = true;
      }
    }

    if($src_ip != "") {
      error_log("src_ip ". $src_ip);
      if($isWhereAdded) {
        $sql .= 'AND src_ip = "'. $src_ip . '" ';
      } else {
        $sql .= 'WHERE src_ip = "'. $src_ip . '" ';
        $isWhereAdded = true;
      }
    }

    if($src_port != "") {
      error_log("src_port ". $src_port);
      if($isWhereAdded) {
        $sql .= 'AND src_port = "'. $src_port . '" ';
      } else {
        $sql .= 'WHERE src_port = "'. $src_port . '" ';
        $isWhereAdded = true;
      }
    }

    if($router_ip != "-1") {
      error_log("router_ip ". $router_ip);
      if($isWhereAdded) {
        $sql .= 'AND router_ip = "'. $router_ip . '" ';
      } else {
        $sql .= 'WHERE router_ip = "'. $router_ip . '" ';
        $isWhereAdded = true;
      }
    } else {
      // Get the result only those router had been allowed.
      $routers = $this->router_model->get_allowed_routers($this->session->userdata('user_id'));
      $tok = "";
      foreach($routers as $router) {
        if($tok != "") {
          $tok .= 'OR router_ip = "'. $router['ip_address'] .'" ';
        } else {
          $tok = '(router_ip = "'. $router['ip_address'] .'" ';
        }
      }
      if($tok != "") {
        $tok .= ')';
        if($isWhereAdded) {
          $sql .= 'AND '. $tok . ' ';
        } else {
          $sql .= 'WHERE '. $tok . ' ';
          $isWhereAdded = true;
        }
      }

      error_log('Router Search SQL is '. $tok);

    }


    if($fromDate != "") {

      error_log("fromDate ". $fromDate);
      if($isWhereAdded) {
        $sql .= 'AND access_time >= "'. $fromDate . '" ';
      } else {
        $sql .= 'WHERE access_time >= "'. $fromDate . '" ';
        $isWhereAdded = true;
      }
    }

    if($toDate != "") {

      error_log("toDate ". $toDate);
      if($isWhereAdded) {
        $sql .= 'AND access_time <= "'. $toDate . '" ';
      } else {
        $sql .= 'WHERE access_time <= "'. $toDate . '" ';
        $isWhereAdded = true;
      }
    }


    if(!$isWhereAdded) {
      $sql .= 'ORDER BY access_time DESC LIMIT '. $row_start .', '. $row_limit;
    } else {
      $sql .= 'LIMIT '. $row_start .', '. $row_limit;
    }

    error_log ("Search SQL: ". $sql);

    $query = $this->db->query($sql);
    $query_result=$query->result();

    return $query_result;

  }



}

?>
