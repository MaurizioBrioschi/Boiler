<?php
/**
 * Questa classe memorizza tutte le variabili necessarie al funzionamento del sito
 * 
 * @subpackage application
 * @version 0.2
 */
Class Registry {

 /**
  * 
  * @var array 
  */
 private $vars = array();


 /**
 * imposta una variabile
 * @param string $index
 * @param mixed $value
 * @return void
 */
 public function __set($index, $value)
 {
	$this->vars[$index] = $value;
 }

 /**
 * ottiene una variabile
 * @param mixed $index
 * @return mixed
 */
 public function __get($index)
 {
	return $this->vars[$index];
 }


}

?>
