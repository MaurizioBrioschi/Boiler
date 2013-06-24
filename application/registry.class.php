<?php
/**
 * This class store all variables useful for the web site
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
Class Registry {

 /**
  * 
  * @var array 
  */
 private $vars = array();


 /**
  * set a variable
  * @param String $index
  * @param type $value
  */
 public function __set($index, $value)
 {
	$this->vars[$index] = $value;
 }

 /**
  * get a variable
  * @param type $index
  * @return type
  */
 public function __get($index)
 {
	return $this->vars[$index];
 }


}

?>
