<?php
/**
 * controller for index page
 * @version 0.2
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

}

?>