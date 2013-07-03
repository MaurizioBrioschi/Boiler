<?php
 /**
 * Boiler CMS software
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.2 
 
 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 /*** error reporting on ***/
 error_reporting(E_ALL);
 /*** define the site path ***/
 $site_path = realpath(dirname(__FILE__))."/";
 define ('__SITE_PATH', $site_path);
 /**
  * namespace autoload
  * @param type $class_path
  * @return boolean
  */
 function __autoload($class_path) {
    $class_path = str_replace("ridesoft\\Boiler\\", "", $class_path);
    $sections = explode("\\", $class_path);
   
    $file = __SITE_PATH . join("/",$sections);
    $file .= ".class.php";
   
    
    if (file_exists($file) == false)    {
        header("Location: error404");
        exit;
    }else{ 
        include ($file);
        
    }
 }

 /** start boiler */
 $init = \ridesoft\Boiler\includes\init::start($site_path);
 
 
?>
