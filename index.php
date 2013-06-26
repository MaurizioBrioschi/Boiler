<?php
 /**
 * Boiler CMS software
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 
 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 /*** error reporting on ***/

 error_reporting(E_ALL);

 /*** define the site path ***/
 $site_path = realpath(dirname(__FILE__));

 define ('__SITE_PATH', $site_path);

 /*** include the init.php file ***/
 include 'includes/init.php';

 /*** load the router ***/
 $registry->router = new router($registry);

 /*** set the controller path ***/
 $registry->router->setPath (__SITE_PATH . '/controller/');

 /*** load up the template ***/
 $registry->template = new template($registry);

 /*** load the controller ***/
 $registry->router->loader();
 
?>
