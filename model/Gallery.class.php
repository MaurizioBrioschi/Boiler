<?php
/**
 * Class Gallery 
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
class Gallery extends Composite {
    protected $attr = array();
    
    
    function __construct($recordset=array()) {      
        foreach(array_keys($recordset) as $key){
                if($key=="nome")
                    $this->__set($recordset[$key], $recordset["valore"]);
                else 
                    $this->__set($key, $recordset[$key]);
        }
    }
    /**
     * Setta un attributo del paragrafo
     * @param int $index
     * @param mixed $value 
     */
    public function __set($index, $value)
    {
	$this->attr[$index] = $value;
    }  
    /**
     * Ottiene il valore di un attributo del paragrafo
     * @param mixed $index
     * @return mixed 
     */
    public function __get($index)
    {
            return $this->attr[$index];
    }
    
    /**
     * sleep method for serialize object
     * @return mixed array
     */
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
      * @return Gallery
      */
     function __wakeup()
     {
            $this->output();
     }
    
    public function getPhotos() {
        if(count($this->children)>0)    return $this->children;
        else return null;
    }
    
    public function getPhoto($index) {
        if(count($this->children)>$index)    return $this->children[$index];
        else return null;
    }
    
    function output() {
        echo "";
        
    }
}
?>

