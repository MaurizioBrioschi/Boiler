<?php
/**
 * Class for authentication
 * @todo serialize object, to fixed
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
class Authentication {
    var $errorCode = "AUTH_NONE";
    var $errorText = "Autenticazione non avvenuta";
	

	/* authentication function: Chiamata all'apertura di ogni form*/
 function __construct() {
   
}

function login($email, $password,$Connection) {
        


		//Inizializza la sessione
	@session_start();

		//set initial error codes
	$this->errorCode = "AUTH_NONE";
	$this->errorText = "Autenticazione non avvenuta";

		//a username must be passed
	if (empty($email)) {
		$this->logout();
		$this->errorCode = "AUTH_FAILED_NO_USERNAME_PASSED";
		$this->errorText = "Utente Obbligatorio";
		return FALSE;
	}

		//a password must be passed
	if (empty($password)) {
		$this->logout();
		$this->errorCode = "AUTH_FAILED_EMPTY_PASSWORD";
		$this->errorText = "Password Obbligatoria";
		return FALSE;
	}

        
        try{
            $SQL= "select u.*,s.IDService,s.ServiceName,s.ServiceDescription,s.Ordine as ServiceOrder,s.Link as ServiceLink,ms.IDSection,ms.Name as SectionName,ms.Description as SectionDescription,ms.Ordine as SectionOrder,ms.Link as SectionLink,mut.IDType,mut.Name as TypeName from tblmanageruser u JOIN tblmanagerusertyperel t ON u.IDUser=t.IDUser JOIN tblmanagerusertypeservices uts ON uts.IDType = t.IDType JOIN tblmanagerservices s ON s.IDService=uts.IDService JOIN tblmanagersections ms ON ms.IDSection=s.IDSection JOIN tblmanagerusertype mut ON mut.IDType=t.IDType WHERE u.Email='".str_replace("'","''",$email)."' AND Password='".md5($password)."' AND u.State=1 Order by ms.Ordine ASC, s.Ordine ASC;";
            $this->errorText = $SQL;
            list($dbd,$i) = $Connection->exeSQL($SQL);

            $services = array();

            if($i>0){
                for($j=0;$j<$i;$j++){
                    $result = $Connection->getResult($dbd,$j);
                    array_push($services,new ManagerService($result));
                }
                $User = new ManagerUser($result);
                $User->setServices($services);
                
                $SQL = "Update tblmanageruser SET LastLogin=NOW() WHERE IDUser=".$User->getIDUser();
                $Connection->exeSQL($SQL);
                $_SESSION["contextUser"] =  serialize($User);
                
                $this->errorCode = "AUTH_SUCCESS";
                $this->errorText = "Autenticazione avvenuta";
                
                return true;
            }else{
                $this->errorCode = "Email o password errati";
                $this->errorText = "Email o password errati";
                return FALSE;
            }
        }catch(Exception $e){
            $this->errorText = $e->getMessage();
            $Connection->LogError("Autentication->LogIn",$e->getMessage(),$e->getTraceAsString(),$email,$password,1,"Pink-secrets");
        }


 }

	/**
	* Logs out the currenlty logged in user
	*/
	function logout() {
		//start/continue the session
		@session_start();

		$_SESSION = array();
		// Infine distrugge la sessione.
		@session_destroy();


		$this->errorCode = "AUTH_LOGOUT";
		$this->errorText = "Utente Scollegato";
		return;
	}

	/**
	*	returns true if the user is logged in
	*/
	function isLoggedIn() {
		//start/continue the session
		@session_start();

		return !empty($_SESSION['contextUser']);
	}


	/**
	*	Messaggio d'errore
	*/
	function getErrorMessage() {
        return $this->errorText;

      }


}



?>
