<?php
/**
 * Class Paragraph
 * 
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
class Paragraph extends Composite {
    protected $attr = array();
    
    /**
     * crea un oggetto paragrafo attraverso il recordset ricavato dal database
     * @param mixed $recordset 
     */
    function __construct($recordset=array()) {      
        foreach(array_keys($recordset) as $key){
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
      * @return ManagerUser
      */
     function __wakeup()
     {
            $this->output();
     }
    
    /**
     * Ottiene tutti gli attributi del paragrafo
     * @return mixed array 
     */
    public function getAttributi()  {
        return $this->attr;
    }
    
    /**
     * Ottiene gli elementi del paragrafo(Gallery, documenti...)
     * @return  mixed
     */
    public function getElementi() {
        if(count($this->children)>0)    return $this->children;
        else return null;
    }
    /**
     * Ottiene il componente con indice $index
     * @param int $index
     * @return Component 
     */
    public function getElemento($index) {
        if(count($this->children)>$index)    return $this->children[$index];
        else return null;
    }
    /**
     * Ottiene tutte le gallery legate al paragrafo
     * @return Gallery array 
     */
    public function getGalleries()  {
        $galleries = array();
        if(count($this->children)>0) {
            for($i=0;$i<count($this->children);$i++)    {
                if($this->children[$i]->id_attributo==1)
                    array_push($galleries,$this->children[$i]);
            }
        }
        return $galleries;
    }
    /**
     * Ottiene l'ultimo componente legato al paragrafo
     * @return Component 
     */
    public function getUltimoElemento() {
        try {
        return end($this->children);
        }catch(Exception $e)  {
            $pippo = $e->getMessage();
        }
        
    }
    function output() {
        echo "";
        
    }
}
?>