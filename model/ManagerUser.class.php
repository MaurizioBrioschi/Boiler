<?php

/**
 * Class for backend users
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ridesoft\Boiler\model;

class ManagerUser{
      private $IDUser;
      protected $Name,$Surname,$Email,$Password,$State,$IDType,$TypeName;
      protected $Services ;
      
      /**
       * ottiene l'IDUser
       * @return int 
       */
      public function getIDUser(){
          return $this->IDUser;
      }
      /**
       * ottiene il nome utente
       * @return string
       */
      public function getName(){
          return $this->Name;
      }
      /**
       * ottiene il cognome
       * @return string
       */
      public function getSurname(){
          return $this->Surname;
      }  
      /**
       * ottiene l'email
       * @return string
       */
      public function getEmail(){
          return $this->Email;
      }   
      /**
       * ottiene la password
       * @return string
       */
      public function getPassword(){
          return $this->Password;
      }    
      /**
       * ottiene lo stato dell'utente, 1 per attivo, 0 per disattivo
       * @return int
       */
      public function getState(){
          return $this->State;
      }     
      /**
       * Ottiene l'IDType del gruppo a cui appartiene l'utente
       * @return int
       */
      public function getIDType(){
          return $this->IDType;
      }         
      /**
       * Ottiene il nome del tipo di gruppo dell'utente
       * @return string
       */
      public function getTypeName(){
          return $this->TypeName;
      }
      /**
       * Ottiene tutti i permessi associati all'utente
       * @return ManagerService 
       */
      public function getServices(){
          return $this->Services;
      }
      
      public function getService($index){
          return $this->Services[$index];
      }
      /**
       * Imposta i servizi per l'utente
       * @param ManagerService $values 
       */
      public function setServices($values){
          $this->Services = $values;
      }
      
      /* costruttori*/
      function __construct(){
        $argv = func_get_args();
        switch(func_num_args())
        {
            default:
            case 1:
                self::__construct1($argv[0]);
                break;
           
           
        }
      }
      /**
       * Imposta i dati dell'utente 
       * @param array $recordset 
       */
      function __construct1($recordset){       
            $this->IDUser = $recordset["IDUser"];
            $this->Email = $recordset["Email"];
            //$this->IDNation = $recordset["IDNation"];
            $this->IDType = $recordset["IDType"];
            $this->Name = $recordset["Name"];
            $this->Password = $recordset["Password"];
            $this->State = $recordset["State"];
            $this->Surname = $recordset["Surname"];
            $this->TypeName = $recordset["TypeName"];
            //$this->Nation = $recordset["Nation"];
      }
      
     /**
     * sleep method for serialize object
     * @return serialize array
     
      function __sleep()
        {
                return array('IDUser','Name','Surname','Email','Password','State','IDType','TypeName', 'Services'); 

        }
     /**
      * wakeup method for serialize object
      * @return ManagerUser
     
     function __wakeup()
     {
            echo "";
     } */
      
      
      
      
     
  }
?>