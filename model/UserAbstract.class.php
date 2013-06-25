<?php

/***
 * Class UserAbstract for users
 * 
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1
 * 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
abstract class UserAbstract {
    protected $attr = array();
    protected $id_session;
    
    /**
     * crea un oggetto UserAbstract attraverso il recordset ricavato dal database 
     * @param mixed $recordset 
     * @param string $fieldprefix
     */
    function __construct($id_session) {   
        $this->id_session = $id_session;
    }
    /**
     * Setta un attributo del nodo
     * @param int $index
     * @param mixed $value 
     */
    public function __set($index, $value)
    {
	$this->attr[$index] = $value;
    }  
    /**
     * Ottiene il valore di un attributo del nodo
     * @param mixed $index
     * @return mixed 
     */
    public function __get($index)
    {
            return stripslashes($this->attr[$index]);
    }
    
    /**
     * sleep method for serialize object
     * @return mixed array
     
      function __sleep()
      {
          $array = array();
          foreach(array_keys($this->attr) as $key){
                array_push($array,$key);
          }
          return $array;

      }
     /**
      * wakeup method for serialize object
      * @return ManagerUser
      
     function __wakeup()
     {
            echo "";
     }*/
     
     /**
     * Ottiene tutti gli attributi del prodotto
     * @return mixed 
     */
    public function getAttributi()  {
        return $this->attr;
    }
     
}
?>