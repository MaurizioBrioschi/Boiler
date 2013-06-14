<?php
/**
 * Classe Nodo che rappresenta un nodo dell'albero di navigazione
 * 
 * @subpackage compositePattern
 * @version 0.2
 */
class Nodo extends Composite {
    protected $attr = array();
    
    /**
     * crea un oggetto nodo attraverso il recordset ricavato dal database e selezionando i campi contenenti $fieldprefix
     * @param mixed $recordset 
     * @param string $fieldprefix
     */
    function __construct($recordset=array(),$fieldprefix="") {   
        if(strlen($fieldprefix)>0)  {
            foreach(array_keys($recordset) as $key){
                if(strstr($key,$fieldprefix)||strstr($key,"idpadre"))
                    $this->__set($key, $recordset[$key]);
            }
        }else{
            foreach(array_keys($recordset) as $key){
                    $this->__set($key, $recordset[$key]);
            }
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
            return $this->attr[$index];
    }
    /**
     * Ottiene l'id del nodo
     * @param string $keyvalue
     * @return int 
     */
    public function getID($keyvalue="id") {
        foreach(array_keys($this->attr) as $key){
              if(strstr($key,$keyvalue))
                  return $this->attr[$key];
        }
        
    }
    /**
     * Ottiene il valore online: 1 o 0
     * @return int 
     */
    public function getOnline() {
        foreach(array_keys($this->attr) as $key){
              if(strstr($key,"online"))
                  return $this->attr[$key];
        }
        
    }
    /**
     * Ottiene il valore libero:1 o 0
     * @return int 
     */
    public function getLibero() {
        foreach(array_keys($this->attr) as $key){
              if(strstr($key,"libero"))
                  return $this->attr[$key];
        }
    }
    /**
     * Ottiene l'ordine
     * @return int 
     */
    public function getOrdine() {
        foreach(array_keys($this->attr) as $key){
              if(strstr($key,"ordine"))
                  return $this->attr[$key];
        }
    }
    /**
     * Ottiene il livello da db
     * @return int
     */
    public function getLivello() {
        foreach(array_keys($this->attr) as $key){
              if(strstr($key,"livello"))
                  return $this->attr[$key];
        }
    }
    /**
     * Ottiene il nome del nodo
     * @return string
     */
    public function getNome()   {
        foreach(array_keys($this->attr) as $key){
              if(strstr($key,"nome"))
                  return $this->attr[$key];
        }
    }
    /**
     * sleep method for serialize object
     * @return mixed array
     */
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
      */
     function __wakeup()
     {
            $this->output();
     }
    /**
     * Ottiene la profondità del nodo: il numero dei suoi figli + 1;
     * @return int 
     */
    public function getProfondita() {
        return count($this->children) + 1;
    }
    /**
     * Ottiene i figli del nodo
     * @return  mixed
     */
    public function getChildrens() {
        if(count($this->children)>0)    return $this->children;
        else return null;
    }
    /**
     * Ottiene il nodo figlio con index $index
     * @param int $index
     * @return Node 
     */
    public function getChildren($index)  {
        return $this->children[$index];
    }
    /**
     * Ottiene tutti gli attributi del nodo
     * @return mixed 
     */
    public function getAttributi()  {
        return $this->attr;
    }
    function output() {
        echo "";
        
    }
}
?>
