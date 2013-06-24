<?php
/**
 * Questa è la classe che funge da router fra l'url e il relativo controller
 * Questa classe fa parte del pattern MVC dove tutti i controller sono nella sottocartella controller e tutti i template nella sottocartella views
 * nella sottocartella model risiedono invece tutte le classi del progetto
 * 
 * @subpackage application
 * @version 0.2
 */
class router {

 private $registry;

 private $path;

 private $args = array();

 public $file;

 public $controller;

 public $action;

 /**
  * 
  * @param Registry $registry 
  */
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
 * carica il controller e ne instanzio la classe 
 * @return void
 */
 public function loader()
 {
	
	$this->getController();
	
        //se non esite il file del controller chiamo automaticamente la pagina 404
	if (is_readable($this->file) == false)
	{
		$this->file = $this->path.'/error404.php';
                $this->controller = 'error404Controller';
	}

	
	include $this->file;

	
	$class = $this->controller . 'Controller';
	$controller = new $class($this->registry);

	//controllo se il metodo è richiamabile se non lo è chiamo automaticamente il metodo index
	if (is_callable(array($controller, $this->action)) == false)
	{
		$action = 'index';
	}
	else
	{
		$action = $this->action;
	}
               //controllo se sono stati inpostati degli argomenti per il metodo, se sì glieli passo.
               //TODO: trovare il modo di passare più argomenti
               if(count($this->args)>0)    {
                    
                    $controller->$action($this->args);
                }else{
                    $controller->$action();
                }
		/*if(strlen($this->myArg)>0)
		    $controller->$action($this->myArg);
		else
		    $controller->$action();*/


 }


 /**
 * Ottine ed imposta il controller, il metodo del controller ed eventuali argomenti
 * il controller si determina analizzando l'url
 * in sistema che non utilizza url rewrite: l'url è del tipo www.sito.it/index.php?rt=controller/_method/arg0/arg1/ecc..
 * in sistema che utilizza url rewrite: url è del tipo www.sito.it/controller/method/arg0/arg1/
 * Se non esiste il metodo viene chiamato di default il metodo index
 * Se l'ultimo argomento fa parte dell'url rewrite per l'indicizzazione verrà scartato automaticamente dal metodo del controller
 * @return void
 */
private function getController() {

	
	$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

        //se il 
	if (empty($route))
	{
		$route = 'index';
                
	}
	else
	{
		$parts = explode('/', $route);
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

	if (empty($this->controller))
	{
		$this->controller = 'index';
	}

	
	if (empty($this->action))
	{
		$this->action = 'index';
	}

	$this->file = $this->path .'/'. $this->controller . 'Controller.php';

}


}

?>
