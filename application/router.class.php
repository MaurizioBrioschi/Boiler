<?php
/**
 * Class router to analyze url and get the right controller with parameters
 *  @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
class router {

 private $registry;

 private $path;

 private $args = array();

 public $file;

 public $controller;

 public $action;

 
 function __construct(Registry $registry) {
        $this->registry = $registry;
 }

 /**
 * path controller 
 * @param string $path
 * @return void
 *
 */
 function setPath($path) {

	//controlla se $path è una directory
	if (is_dir($path) == false)
	{
		throw new Exception ("Invalid controller path: '" . $path . "'");
	}
 	$this->path = $path;
}


 /**
 * load the controller 
 * @return void
 */
 public function loader()
 {
	
	$this->getController();
	
        //se non esite il file del controller chiamo automaticamente la pagina 404
	if (is_readable($this->file) == false)
	{
		$this->file = $this->path.'/error404Controller.php';
                $this->controller = 'error404';
	}

	
	include $this->file;

	
	$class = $this->controller . 'Controller';
	$controller = new $class($this->registry);

	//if the method is not callable i call the index 
	if (is_callable(array($controller, $this->action)) == false)
	{
		$action = 'index';
	}
	else
	{
		$action = $this->action;
	}
               
               if(count($this->args)>0)    {
                    
                    $controller->$action($this->args);
                }else{
                    $controller->$action();
                }
		


 }


 /**
 * get and inizialize the controller, the method with its arguments
 * if i have home like first argument i call the home page controller that is the same of a controller with no arguments (indexController.php)
  * all arguments are separated by slash (/) and are store in the method controller in the variable $args
  * example: if i have http://mysite.com/projects/analyze/ridesoft/79/2013/boiler-is-my-framework 
  * boiler execute the controller projectsController
  * call the method analyze
  * with this $args as array ->$args = array("ridesoft","79","2013","boiler-is-my-framework");
  * 
  * if i have no arguments it calls simply the method
  * if i have no method it calls the method index in the controller
  * if i have no controller it calls indexController, indexMethod
  * if there is an error in the url, call the error404Controller.php, index method to show page 404.
  * 
 * @return void
 */
private function getController() {

	
	$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];
        $parts = explode('/', $route);
        
	if (empty($parts))
	{
		$this->controller='index';
                
	}
	else
	{
		if($parts[0]=='home') $this->controller='index';
		else if($parts[0]=='')
                    $this->controller = 'index';
                else
                    $this->controller = $parts[0];
                if(count($parts)>1){
		if(isset($parts[1]))
                    {
                        $this->action = $parts[1];
                        for($j=2;$j<count($parts);$j++) {
                            array_push($this->args, $parts[$j]);
                        }
                    }
                }
	}

	

	
	if (empty($this->action))
	{
		$this->action = 'index';
	}

	$this->file = $this->path .'/'. $this->controller . 'Controller.php';

}


}

?>
