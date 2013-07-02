<?php
/**
 * error 404 page controller
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 */
namespace ridesoft\Boiler\controller;

use ridesoft\Boiler\application\baseController;

class error404Controller Extends baseController {

public function index() 
{
        $this->registry->template->blog_heading = 'page not founf';
        $this->registry->template->show('error404');
       
}

}
?>
