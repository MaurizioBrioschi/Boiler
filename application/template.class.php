<?php
/**
 * Class for template rendering
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
Class Template {

private $registry;

/*
* @var array
 */
private $vars = array();

/**
 *
 * @param Registry $registry 
 */
function __construct(Registry $registry) {
	$this->registry = $registry;

}


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
 * Mostra il template che sta nella cartella views
 * @param string $name
 * @return type 
 */
function show($name) {
	$path = __SITE_PATH . '/views' . '/' . $name . '.php';
        
        //verifico se esiste il file del template
	if (file_exists($path) == false)
	{
		throw new Exception('Template not found in '. $path);
		return false;
	}

	//carico tutte le variabili necessarie al template
	foreach ($this->vars as $key => $value)
	{
		$$key = $value;
	}

	include ($path);               
}


}

?>
