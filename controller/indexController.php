<?php
/**
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 */
Class indexController Extends baseController {

public function index() {
    
            $this->registry->template->show('index');

}

public function hello($args=null)    {
    $this->registry->template->args=$args;
    $this->registry->template->show('index'); 
}

}

?>