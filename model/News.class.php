<?php

/**
 * Class for news
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.2 
 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ridesoft\Boiler\model;

class News extends Composite {

    protected $attr = array(); //attributi della news
    protected $objs = array(); //elementi che costituiscono la news
    protected $categories = array(); //categorie a cui appartengono le news

    /**
     * crea un oggetto news attraverso il recordset ricavato dal database
     * @param mixed $recordset 
     */

    function __construct($recordset = array()) {
        foreach (array_keys($recordset) as $key) {
            $this->__set($key, $recordset[$key]);
        }
    }

    /**
     * Setta un attributo della news
     * @param int $index
     * @param mixed $value 
     */
    public function __set($index, $value) {
        $this->attr[$index] = $value;
    }

    /**
     * Ottiene il valore di un attributo della news
     * @param mixed $index
     * @return mixed 
     */
    public function __get($index) {
        if (isset($this->attr[$index]))
            return stripslashes($this->attr[$index]);
        else {
            return "";
        }
    }

    /**
     * sleep method for serialize object
     * @return mixed array
     */
    function __sleep() {
        $array = array();
        foreach (array_keys($recordset) as $key) {
            array_push($array, $key);
        }
        return $array;
    }

    /**
     * wakeup method for serialize object
     * @return ManagerUser
     */
    function __wakeup() {
        $this->output();
    }

    /**
     * Ottiene il numeri di elementi della news
     * @return int 
     */
    public function getNumeroElementi() {
        return count($this->children);
    }

    /**
     * Ottiene gli elementi della news
     * @return  mixed
     */
    public function getElementi() {
        if (count($this->children) > 0)
            return $this->children;
        else
            return null;
    }

    /**
     * Ottiene tutti gli attributi della news
     * @return mixed 
     */
    public function getAttributi() {
        return $this->attr;
    }

    /**
     * Ottiene tutti gli oggetti che costituiscono una news
     * @return Component 
     */
    public function getObjs() {
        return $this->objs;
    }

    /**
     * Ottiene l'oggetto della news avente indice $index
     * @param int $index
     * @return Component 
     */
    public function getObj($index) {
        return $this->objs[$index];
    }

    /**
     * Imposta un oggetto che costituisce la news
     * @param Component $comp
     * @return Component 
     */
    public function setObj(Component $comp) {
        $this->objs[] = $comp;
        return $comp;
    }

    /**
     * rimuove un oggetto che costituisce la news
     * @param Component $comp
     * @return bool 
     */
    public function removeObj(Component $comp) {
        $index = array_search($comp, $this->objs, true);
        if ($index === false) {
            return false;
        }
        array_splice($this->objs, $index, 1);
        return true;
    }

    /**
     * Aggiunge una categoria sotto identificativo di id categoria
     * @param int $id_category 
     */
    public function addCategory($id_category) {
        array_push($this->categories, $id_category);
    }

    /**
     * remove una categoria con identificativo $id_category
     * @param int $id_category
     * @return bool 
     */
    public function removeCategory($id_category) {
        $index = array_search($id_category, $this->categories, true);
        if ($index === false) {
            return false;
        }
        array_splice($this->categories, $index, 1);
        return true;
    }

    function output() {
        echo "";
    }

}

?>
