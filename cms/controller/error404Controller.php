<?php
/**
 * error 404 page controller
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 */
Class error404Controller Extends baseController {

public function index() 
{
        $this->registry->template->blog_heading = 'page not founf';
        $this->registry->template->show('error404');
}

}
?>
