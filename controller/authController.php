<?php
/**
 * controller per l'autenticazione dell'utente
 * @version 1.0
 */
Class authController Extends baseController {

public function index() {
         $this->registry->template->error = "";
         $this->registry->template->show('login');       
}
/**
 * autentica l'utente
 */
public function login() {
    if (isset($_POST["user"]) &&($_POST["user"]>'') && isset($_POST["pass"])&& ($_POST["pass"]>' ')) {
	//try logging the user in
	if (!$this->registry->Authentication->login($_POST["user"], $_POST["pass"],$this->registry->MySqlConnection)){
		$this->registry->template->loginFailure = true;
                $this->registry->template->error = $this->registry->Authentication->getErrorMessage();
                $this->registry->template->show('login');
        }else {		
             Header("Location: ".$this->registry->UrlSiteCMS);
             exit();
	}
    }else{
        $this->registry->template->loginFailure = true;
        $this->registry->template->error = "Mettere email e password!";
        $this->registry->template->show('login');
    }
    
}
/**
 * slogga l'utente
 */
public function logout()    {
    if (!$this->registry->Authentication->isLoggedIn()) {
            Header("Location: ".$this->registry->UrlSiteCMS."index.php?rt=auth");
            ob_end_flush();
            exit;
        }else{
            $this->registry->Authentication->logout();
            Header("Location: ".$this->registry->UrlSiteCMS."index.php?rt=auth");
        }
}
}

?>