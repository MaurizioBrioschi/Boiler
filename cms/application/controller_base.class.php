<?php
/**
 * Classe astratta del controller base 
 * Ogni classe che implementa questa classe deve stare nella cartella controller
 * Parte del Controller MVC
 * @version 0.2
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

/**
 * tutti i controller devono avere un metodo index
 */
abstract function index();
}

?>
