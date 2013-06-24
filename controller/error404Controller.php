<?php
/**
 * error 404 page controller
 * @version 0.2
 */
Class error404Controller Extends baseController {

public function index() 
{
        $this->registry->template->blog_heading = 'Pagina non trovata';
        $this->registry->template->show('error404');
}

}
?>
