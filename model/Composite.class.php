<?php
/**
 * Class composite
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ridesoft\Boiler\model;

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