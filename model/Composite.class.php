<?php
/**
 * Classe Composite del pattern Composite usato per istanziare i nodi, le pagine e i relativi componenti delle pagine
 * 
 * @subpackage compositePattern
 * @version 0.2
 */
class Composite implements iComponent {
    protected $children = array();
    
    /**
     * aggiunge un componente
     * @param Component $comp
     * @return Component 
     */
    function add(iComponent $comp) {
        $this->children[] = $comp;
        return $comp;
    }
    /**
     * rimuove un componente
     * @param Component $comp
     * @return bool 
     */
    function remove(iComponent $comp) {
        $index = array_search($comp, $this->children, true);
        if ($index === false) {
            return false;
        }
        array_splice($this->children, $index, 1);
        return true;
    }
    /**
     * clona un componente
     */
    function __clone() {
        $kids = array();
        foreach ($this->children as $child) {
            $kids[] = clone($child);
        }
        $this->children = $kids;
    }

    
}
?>