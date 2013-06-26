<?php
/**
 * Classe per authentication
 * @todo serialize
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

namespace ridesoft\Boiler\model;


class UserAuthentication {
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
            $SQL= "select u.*,w.id as id_whishlist FROM utenti u JOIN whishlist w ON u.id=w.id_iscritto WHERE u.Email='".str_replace("'","''",$email)."' AND Password='".md5($password)."' AND u.attivo>0;";
            $this->errorText = $SQL;
            list($dbd,$i) = $Connection->exeSQL($SQL);
            
            
            $User = unserialize($_SESSION['mobilitareuser']);
            
            if($i>0){
                $User->LogIn($Connection->getResult($dbd,0));
                 for($j=0;$j<count($User->getWhishlist()->getProdotti());$j++)    {
                    ecommerce::insertProdottoToWhishlist($Connection, $User->getWhishlist()->id_whishlist, $User->getWhishlist()->getProdotto($j)->id);
                 }
                list($dbdP,$p) = ecommerce::getProdottiWhishlist($Connection, $User->id);
                if($p>0)    {
                    $pm = new ProdottiManager($Connection);
                    for($x=0;$x<$p;$x++)    {
                        $rec = $Connection->getResult($dbdP,$x);
                        $prodotto = $pm->creaProdotto($rec);
                        $User->addProdottoToWhishlist($prodotto,false);
                    }
                }
                
                
               
                
                $_SESSION['mobilitareuser'] = serialize($User);
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
            $Connection->LogError("UserAutentication->LogIn",$e->getMessage(),$e->getTraceAsString(),$email,$password,1,"Mool2");
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

		return !empty($_SESSION['mobilitareuser']);
	}


	/**
	*	Messaggio d'errore
	*/
	function getErrorMessage() {
        return $this->errorText;

      }


}



?>
