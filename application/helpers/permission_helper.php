<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('permission'))
{
  function permission($module,$operation,$permission_string)
  {
    $flag;
    if (strpos($permission_string, $module) !== false) {
        $module_permissions = explode($module, $permission_string);
        // print_r($module_permissions);
    // error_log("# module 2222".$module_permissions[1]);
        $module_permissions = explode(":", $module_permissions[1]);
        // error_log("## ".$module_permissions[1]);
        if (strpos($module_permissions[1], $operation) !== false) {
      //    error_log("Module ".$module." has access to ".$operation);
          $flag=1; // Module exists , Operation exists
    	   }
         else {
          // error_log($module." Module exists but ".$operation." operation has NO access".$operation);
           $flag=0; // Module exists , Operation not exists
         }
    }
    else {
  //    error_log($module." Module NOT exists ");
      $flag=-1; // Module Not exists
    }
    return $flag;
  }
}
?>
