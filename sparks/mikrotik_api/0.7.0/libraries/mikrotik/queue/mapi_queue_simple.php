<?php
/**
 * Description of Mapi_Simple_Queues
 *
 * @author      Mohammad Mahabub Alam babu.coder@gmail.com <http://www.lightcubetech.com>
 * @copyright   Copyright (c) 2018, LightCube Technologies.
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @category	  Libraries
 */
class Mapi_Queue_Simple extends Mapi_Query {
    /**
     *
     * @var type array
     */
    private $param;

    function __construct($param) {
        $this->param = $param;
        parent::__construct($param);
    }

    /**
     * This method is used to add the ip address
     * @param type $param array
     * @return type array
     *
     */
    public function add_queue($param){
        $input=array(
          'command' => '/queue/simple/add'
        );
        $out=array_merge($input, $param);
        return $this->query($out);
    }
    /**
     * This method is used to display all simple queues
     * @return type array
     *
     */
    public function get_all_queue(){
      return $this->query('/queue/simple/getall');
    }

    /**
     * This method is used to activate the ip address by id
     * @param type $id is not an array
     * @return type array
     *
     *
     */
    public function enable_queue($id){
      $input = array(
                    'command'       => '/queue/simple/enable',
                    'id'            => $id
                );
      return $this->query($input);
    }

    /**
     * This method is used to disable ip address by id
     * @param type $id string
     * @return type array
     *
     *
     */
    public function disable_queue($id){
      $input = array(
              'command'       => '/queue/simple/disable',
              'id'            => $id
      );

      return $this->query($input);
    }

    /**
     * This method is used to remove the ip address by id
     * @param type $id is not an array
     * @return type array
     *
     */
    public function delete_queue($id){
        $input = array(
                   'command'    => '/queue/simple/remove',
                   'id'         => $id
        );
        return $this->query($input);
   }

    /**
     * This method is used to set or edit by id
     * @param type $param array
     * @return type array
     *
     */
    public function set_queue($param, $id){
      $input = array(
                  'command'   => '/queue/simple/set',
                  'id'        => $id
      );
      $out=array_merge($input, $param);
      return $this->query($out);
    }

    /**
     * This method is used to display one ip address
     * in detail based on the id
     * @param type $id not array
     * @return type array
     *
     */
    public function detail_queue($id){
        $input = array(
                   'command'    => '/queue/simple/print',
                   'id'         => $id
        );
        return $this->query($input);
    }
}
