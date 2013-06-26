<?php
/**
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 */
Class indexController Extends baseController {

public function index() {
    
            $this->registry->template->show('cms/index');

}



}

?>