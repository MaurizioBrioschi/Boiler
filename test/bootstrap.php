<?php

/**
 * Boiler CMS software
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.2 

 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
// put your code here
/* * * error reporting on ** */

error_reporting(E_ALL);

/* * * define the site path ** */

/* * * include the controller class ** */
include '../application/' . 'baseController.class.php';

/* * * include the registry class ** */
include '../application/' . 'registry.class.php';

/* * * include the router class ** */
include '../application/' . 'router.class.php';

/* * * include the template class ** */
include '../application/' . 'template.class.php';

include 'CsvFileInterator.php';
?>
