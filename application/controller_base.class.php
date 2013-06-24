<?php
/**
 * Base class for a controller
 * MUST stay in controller directory
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
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
