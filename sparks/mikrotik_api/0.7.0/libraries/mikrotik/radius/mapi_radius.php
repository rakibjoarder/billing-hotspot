<?php
/**
 * Description of Mapi_Simple_Queues
 *
 * @author      Mohammad Mahabub Alam babu.coder@gmail.com <http://www.lightcubetech.com>
 * @copyright   Copyright (c) 2018, LightCube Technologies.
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @category	  Libraries
 */
class Mapi_Radius extends Mapi_Query {
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
     * This method is used to add the radius
     * @param type $param array
     * @return type array
     *
     */
    public function add_radius($param){
        $input=array(
          'command' => '/radius/add'
        );
        $out=array_merge($input, $param);
        return $this->query($out);
    }


    /**
     * This method is used to display all simple queues
     * @return type array
     *
     */
    public function get_detail_radius(){
      return $this->query('/radius/print');
    }

    /**
     * This method is used to remove the radius by id
     * @param type $id is not an array
     * @return type array
     *
     */
    public function delete_radius($id){
        $input = array(
                   'command'    => '/radius/remove',
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
   public function set_radius($param, $id){
     $input = array(
                 'command'   => '/radius/set',
                 'id'        => $id
     );
     $out=array_merge($input, $param);
     return $this->query($out);
   }




}
