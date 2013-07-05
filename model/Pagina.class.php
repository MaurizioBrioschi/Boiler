<?php

/**
 * Class Page
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.2
 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

namespace ridesoft\Boiler\model;

class Page extends Composite {

    protected $attr = array(); //attributi della pagina
    protected $objs = array(); //elementi che costituiscono la pagina

    /**
     * crea un oggetto pagina attraverso il recordset ricavato dal database
     * @param mixed $recordset 
     */

    function __construct($recordset = array()) {
        foreach (array_keys($recordset) as $key) {
            $this->__set($key, $recordset[$key]);
        }
    }

    /**
     * Setta un attributo della pagina
     * @param int $index
     * @param mixed $value 
     */
    public function __set($index, $value) {
        $this->attr[$index] = $value;
    }

    /**
     * Ottiene il valore di un attributo della pagina
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
     * Ottiene il numeri di elementi della pagina
     * @return int 
     */
    public function getNumeroElementi() {
        return count($this->children);
    }

    /**
     * Ottiene gli elementi della pagina
     * @return  mixed
     */
    public function getElementi() {
        if (count($this->children) > 0)
            return $this->children;
        else
            return null;
    }

    /**
     * Ottiene tutti gli attributi della pagina
     * @return mixed 
     */
    public function getAttributi() {
        return $this->attr;
    }

    /**
     * Ottiene tutti gli oggetti che costituiscono una pagina
     * @return Component 
     */
    public function getObjs() {
        return $this->objs;
    }

    /**
     * Ottiene l'oggetto della pagina avente indice $index
     * @param int $index
     * @return Component 
     */
    public function getObj($index) {
        return $this->objs[$index];
    }

    /**
     * Imposta un oggetto che costituisce la pagina
     * @param Component $comp
     * @return Component 
     */
    public function setObj(Component $comp) {
        $this->objs[] = $comp;
        return $comp;
    }

    /**
     * rimuove un oggetto che costituisce la pagina
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

    function output() {
        echo "";
    }

}

?>