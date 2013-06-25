<?php
/**
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 */
Class aboutController Extends baseController {

public function index() {
            // this is to render the index.php template inside views directory
            $this->registry->template->show('index');

}

public function rock($args=null)    {
    //$this->registry->template->args is how declare a variable inside the template
    $this->registry->template->args=$args;
    // this is to render the index.php template inside views directory
    // in the template you can use all variables in common php
    $this->registry->template->show('index'); 
}

}

?>