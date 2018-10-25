<?php
class Syslog_model extends CI_Model {

    function __construct() {
      parent::__construct();
    }

    function getFolder($dir) {
      $files = array();
      $filter_files= array();

      error_log("DIR::: ". $dir);

      if($dir == " " | $dir == "") {
        return $files;
      }

      $files = array_slice(scandir($dir), 2);


      foreach($files as $file) {
        if (trim($file) != "lost+found") {
         array_push($filter_files,$file);
       }else{
         error_log($file. '  folder removed');
       }
      }

      foreach($filter_files as $file) {
        if (!is_dir($dir.'/'.$file)) {
          error_log($file. ' is a file');
        } else {
          error_log($file. ' is a folder');
        }
      }

      return $filter_files;
    }

    function getRouterName($all_file_folders){
      //Getting all from Router Table
      $all_routers=$this->get_all_routers();
      $router_ips = array();
      $router_names = array();
      $router_names_show = array();
      foreach ($all_routers as $router) {
        error_log('ROUTER-IP: '.$router['ip_address']);
        //Creating IP Array
        array_push($router_ips,$router['ip_address']);
        array_push($router_names,$router['name']);

      }
      error_log("* Count Before: ".count($all_file_folders));
      for ($i=0; $i < count($router_ips) ; $i++) {
        error_log("IP: ".$router_ips[$i] ."--NAME: ".$router_names[$i]);
      }

      for ($i=0; $i < count($all_file_folders) ; $i++) {
        //error_log("->FOUND at index ".$i." : ".$router_names[$i]);
        $flist = explode('-', $all_file_folders[$i], 2);
        if(sizeof($flist) > 1) {
          $folder_ip = $flist[1];
          //error_log("->FOUND IP: ".$folder_ip);
          $index=array_search($folder_ip, $router_ips);
          if (is_numeric($index)){
            error_log($folder_ip." Found at ". $index);
            error_log("Router Name: ".$router_names[$index]);
            array_push($router_names_show,$router_names[$index]);
          }
          else{
            array_push($router_names_show,"Unknown");
            error_log($folder_ip." Not Found at ".$index);
            error_log("Router Name: Unknown");
          }
        }
        else {
          array_push($router_names_show,"Unknown");
          continue;
        }
      }
      error_log("* Count After: ".count($router_names_show));

      return $router_names_show;
    }

    function get_all_routers(){
      $this->db->select('*');
      $this->db->from('router');
      return $this->db->get()->result_array();
    }

  }
