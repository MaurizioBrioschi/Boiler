<?php
/**
 * Base class for a controller
 * MUST stay in controller directory
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.2 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ridesoft\Boiler\application;

Abstract Class baseController {

/*
 * @var registry
 */
protected $registry;

/**
 *
 * @param Registry $registry 
 */
function __construct(Registry $registry) {
	$this->registry = $registry;
}


abstract function index();
}

?>
