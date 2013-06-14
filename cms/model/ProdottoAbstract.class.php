<?php
/**
 * Classe Prodotto (factory pattern)
 * 
 * @subpackage compositePattern
 * @version 1.0
 */
abstract class ProdottoAbstract {
    protected $attr = array();
    /**
     * crea un oggetto nodo attraverso il recordset ricavato dal database e selezionando i campi contenenti $fieldprefix
     * @param mixed $recordset 
     * @param string $fieldprefix
     */
    function __construct($recordset=array()) {   
        foreach(array_keys($recordset) as $key){
                $this->__set($key, $recordset[$key]);
        }
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
          foreach(array_keys($recordset) as $key){
                array_push($array,$key);
          }
          return $array;

      }
     /**
      * wakeup method for serialize object
      * @return ManagerUser
      
     function __wakeup()
     {
            $this->output();
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
