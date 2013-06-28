<?php
/**
 * Boiler CMS software
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 
 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// put your code here
/*** error reporting on ***/

 error_reporting(E_ALL);

 /*** define the site path ***/
include '../includes/conf.php';
/*** include the controller class ***/
include '../application/' . 'controller_base.class.php';

/*** include the registry class ***/
include '../application/' . 'registry.class.php';

/*** include the router class ***/
include  '../application/' . 'router.class.php';

/*** include the template class ***/
include  '../application/' . 'template.class.php';
 
include 'CsvFileInterator.php';

$site_path = realpath(dirname(__FILE__));

define ('__SITE_PATH', $site_path."/../");
 
function __autoload($class_name) {
    $file = "../model/".$class_name . '.class.php';
    
    /*$templates = __SITE_PATH . '/model/templates/' . $filename;*/
    
    if (file_exists($file) == false)
    {
        return false;
    }
    include ($file);
}
?>
