<?php
class Nib_model extends CI_Model {

  function __construct() {
    parent::__construct();
    if( ! ini_get('date.timezone') ) {
      date_default_timezone_set('GMT');
    }

    $this->load->model('settings_model');
  }

  function getDatesFromRange($start, $end, $format = 'Y-m-d') {
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) {
        $array[] = $date->format($format);
    }
    foreach ($array as $key => $val) {
       echo $val;
    }

    //return $array;
  }

  function get_row_inserts(){
    //SELECT name,router_ip,DATE(last_changed) AS date_,SUM(row_inserts) AS row_inserts_ FROM file_trace LEFT JOIN router ON file_trace.router_ip=router.ip_address WHERE (DATE(last_changed)> DATE(NOW() - INTERVAL 30 DAY)) GROUP BY DATE(last_changed) ,router_ip ORDER BY DATE(last_changed)

    $routers_count=$this->get_total_routers();
    error_log("Router Count: ".$routers_count);
    if($routers_count>5){
      $sql='SELECT name,router_ip,DATE(last_changed) AS date_,SUM(row_inserts) AS row_inserts_ FROM file_trace LEFT JOIN router ON file_trace.router_ip=router.ip_address WHERE (DATE(last_changed)> DATE(NOW() - INTERVAL 2 DAY)) GROUP BY DATE(last_changed) ,router_ip ORDER BY DATE(last_changed)';
    }
    else{
      $sql='SELECT name,router_ip,DATE(last_changed) AS date_,SUM(row_inserts) AS row_inserts_ FROM file_trace LEFT JOIN router ON file_trace.router_ip=router.ip_address WHERE (DATE(last_changed)> DATE(NOW() - INTERVAL 30 DAY)) GROUP BY DATE(last_changed) ,router_ip ORDER BY DATE(last_changed)';
    }

    error_log($sql);
    $query = $this->db->query($sql);
    $query_result=$query->result_array();

    error_log("Aff Rows:".$this->db->affected_rows());

    echo json_encode($query_result);
  }

  function get_all_trace_table() {
    $query_result = $this->db->list_tables();

    return $query_result;
  }

  function active_users_search(){
    $date       = trim($this->input->post('date'));
    $router_ip  = trim($this->input->post('router_ip'));
    $date = date("Ymd", strtotime($date));

    error_log("Router: ".$router_ip."- Date: ".$date);
    // SELECT `router_ip`,`src_ip` AS scnt FROM trace_20171106 GROUP BY `router_ip`,`src_ip`

    if($router_ip != -1){
      $sql_count='SELECT `router_ip`,COUNT(DISTINCT `src_ip`) AS scnt FROM trace_'.$date.' WHERE router_ip="'.$router_ip.'" GROUP BY `router_ip`';
      $sql='SELECT `router_ip`,`src_ip` FROM trace_'.$date.'  WHERE router_ip="'.$router_ip.'" GROUP BY `router_ip`,`src_ip`';
    } else {
      $sql_count='SELECT `router_ip`,COUNT(DISTINCT `src_ip`) AS scnt FROM trace_'.$date.' GROUP BY `router_ip`';
      $sql='SELECT `router_ip`,`src_ip` FROM trace_'.$date.' GROUP BY `router_ip`,`src_ip`';
    }

    error_log($sql);
    error_log($sql_count);
    $query = $this->db->query($sql);
    $query_result['data']=$query->result_array();

    $query = $this->db->query($sql_count);
    $query_result['count']=$query->result_array();

    //error_log("Aff Rows:".$this->db->affected_rows());

    echo json_encode($query_result);
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

    if($router_ip != "") {
      error_log("router_ip ". $router_ip);
      if($isWhereAdded) {
        $sql .= 'AND router_ip = "'. $router_ip . '" ';
      } else {
        $sql .= 'WHERE router_ip = "'. $router_ip . '" ';
        $isWhereAdded = true;
      }
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


  function search() {


    $dest_ip      = trim($this->input->post('dest_ip'));
    $dest_port    = trim($this->input->post('dest_port'));
    $src_ip       = trim($this->input->post('src_ip'));
    $src_port     = trim($this->input->post('src_port'));
    $mac          = trim($this->input->post('mac_address'));
    $router_ip    = trim($this->input->post('router_ip'));
    $fromDate     = trim($this->input->post('start_date_time'));
    $toDate       = trim($this->input->post('end_date_time'));

    $limit = $this->settings_model->get_by_key('query_limit');

    if(empty($dest_ip) && empty($dest_port) && empty($src_ip) && empty($src_port) && empty($mac) && empty($router_ip) && empty($fromDate) && empty($toDate)) {
      $isWhereAdded = false;
      $table_name='trace_'.date("Ymd");
      $table_name = 'trace_20170613';

      error_log("**TAB**::".$table_name);

      $sql = 'SELECT  `mac`, `user_id`, `router_ip`, `dest_ip`, `dest_port`, `src_ip`, `src_port`, `domain`, `access_time` FROM '. $table_name .' ';
      $sql .= 'LIMIT 100 ';

      error_log ("Search SQL: ". $sql);

      $query = $this->db->query($sql);
      $query_result=$query->result();

      echo json_encode($query_result);

    } elseif (!empty($fromDate) || !empty($toDate)) {

      $fromDate =$fromDate.':00';
      $toDate =$toDate.':00';

      //error_log("FROM DATE: ".$fromDate." TO DATE: ".$toDate);
      $start_date_only = date('Y-m-d', strtotime($fromDate));
      error_log("***START DATE ONLY***  ::: ".$start_date_only);

      $end_date_only = date('Y-m-d', strtotime($toDate));
      error_log("***END DATE ONLY BEFORE***  ::: ".$end_date_only);
      $end_date_only = date('Y-m-d',strtotime("+1 day", strtotime($end_date_only)));
      error_log("***END DATE ONLY AFTER***  ::: ".$end_date_only);

      $period = new DatePeriod(new DateTime($start_date_only), new DateInterval('P1D'), new DateTime($end_date_only));
      foreach ($period as $date) {
        $dates[] = $date->format("Y-m-d");
      }

      $i = 0;
      foreach ($dates as $key => $val) {
        $table_names[] = date('Ymd', strtotime($val));
        $i++;
      }

      $row_limit = 100;
      if(($row_limit / $i) < 10) {
        $row_limit = 10;
      } else {
        $row_limit = intval($row_limit / $i);
      }

      foreach ($table_names as $tn_date) {
        $isWhereAdded = false;
        $table_name='trace_'.$tn_date;


        $sql = 'SELECT  `mac`, `user_id`, `router_ip`, `dest_ip`, `dest_port`, `src_ip`, `src_port`, `domain`, `access_time` FROM '.$table_name.' ';

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

        if($router_ip != "") {
          error_log("router_ip ". $router_ip);
          if($isWhereAdded) {
            $sql .= 'AND router_ip = "'. $router_ip . '" ';
          } else {
            $sql .= 'WHERE router_ip = "'. $router_ip . '" ';
            $isWhereAdded = true;
          }
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
          $sql .= 'ORDER BY access_time DESC LIMIT '. $row_limit;
        } else {
          $sql .= 'LIMIT '. $row_limit;
        }

        error_log ("Search SQL: ". $sql);

        $query = $this->db->query($sql);
        $query_result=$query->result();

        if(!empty($RESULT))
        {
          //error_log("FOR ::".$table_name);
          $RESULT = array_merge($RESULT,$query_result);
        } else {
          //error_log("FOR ::".$table_name);
          $RESULT=$query->result();
        }

      }
      echo json_encode($RESULT);

    }

  }

  function get_today_mac() {
    $date = new DateTime("now");

    $curr_date = $date->format('Y-m-d ');

    $sql = "SELECT COUNT(*) AS cnt FROM trace WHERE DATE(access_time)='". $curr_date ."' AND mac!='' GROUP BY mac";

    error_log($sql);

    $query = $this->db->query($sql);
    $count = 0;
    foreach($query->result_array() as $row) {
      $count = $count + 1;
    }
    error_log("Count User " . $count);

    return $count;
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


	function fetch_status_all() {

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

  // function get_total_ppoe() {
  //   return $this->db->count_all('net_user');
  // }
  //
  // function get_total_routers() {
  //   return $this->db->count_all('router');
  // }
  //
  // function get_total_warning() {
  //
  //   $this->db->select('*');
  //   $this->db->from('notification');
  //   $this->db->where("notification_type", "warning");
  //   //error_log("Returned warn: ".$this->db->count_all_results());
  //    return  $this->db->count_all_results();
  // }
  //
  // function get_total_rows_inserted() {
  //   $date = new DateTime("now");
  //   $curr_date = $date->format('Ymd');
  //   error_log("Today: ". $curr_date);
  //   // $curr_date="20170809";
  //   $sql = "SELECT SUM(row_inserts) AS sum FROM file_trace WHERE file_name LIKE '%". $curr_date ."%'";;
  //
  //   error_log($sql);
  //
  //   $query = $this->db->query($sql);
  //   $rows = $query->row()->sum;
  //   error_log("ROWS: ". $rows);
  //   if(!isset($rows))
  //     $rows=0;
  //   return $rows;
  // }
  //
  // function get_total_error() {
  //   $this->db->select('*');
  //   $this->db->from('notification');
  //   $this->db->where("notification_type", "error");
  //   //error_log("Returned warn: ".$this->db->count_all_results());
  //    return  $this->db->count_all_results();
  // }
  //
	// function get_routers_all() {
  //
	// 	$this->db->select('*');
	// 	$this->db->from('router');
	// 	return $this->db->get()->result_array();
  //
	// }
  //
  //
  // function get_top_5_notification() {
  //   $this->db->select('*');
  //   $this->db->from('notification');
  //   $this->db->order_by("notification_time", "desc");
  //   $this->db->limit(5);
  //   //error_log("Returned noti: ".$this->db->affected_rows());
  //   return $this->db->get()->result_array();
  //
  // }
  //
  // function get_all_notification() {
  //   $this->db->select('*');
  //   $this->db->from('notification');
  //   $this->db->order_by("notification_time", "desc");
  //   return $this->db->get()->result_array();
  // }


}

?>
