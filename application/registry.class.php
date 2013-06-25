<?php
/**
 * This class store all variables useful for the web site
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
