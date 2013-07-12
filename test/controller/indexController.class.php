<?php

/**
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 */

namespace ridesoft\Boiler\controller;

use ridesoft\Boiler\application\baseController;

Class indexController Extends baseController {

    public function index() {

        $this->registry->template->show('index');
    }

}

?>