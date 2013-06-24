<?php
/**
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 */
Class indexController Extends baseController {

public function index() {
    //this is valid for protected part
//        if (!$this->registry->Authentication->isLoggedIn()) {
//            Header("Location: ".$this->registry->UrlSite."index.php?rt=auth");
//            ob_end_flush();
//            exit;
//        }else{
            
            
            $this->registry->template->show('index');
            
       // }
}

public function ciao($args=null)    {
    $this->registry->template->args=$args;
    $this->registry->template->show('index');
}

}

?>