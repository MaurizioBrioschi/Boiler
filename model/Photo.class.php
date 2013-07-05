<?php

/**
 * Class photo
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.2
 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

namespace ridesoft\Boiler\model;

class Photo extends Component {

    protected $attr = array();

    function __construct($recordset = array()) {
        $this->__set("id", $recordset["idattributo"]);
        $this->__set("id_paragrafoattributo", $recordset["id"]);
        $this->__set("nome", $recordset["nome"]); //nome
        $this->__set("valore", $recordset["valore"]); //url della foto
        $this->__set("descrizione", $recordset["descrizione"]); //descrizione
    }

    /**
     * Setta un attributo del paragrafo
     * @param int $index
     * @param mixed $value 
     */
    public function __set($index, $value) {
        $this->attr[$index] = $value;
    }

    /**
     * Ottiene il valore di un attributo del paragrafo
     * @param mixed $index
     * @return mixed 
     */
    public function __get($index) {
        return $this->attr[$index];
    }

    /**
     * Aggiunge un componente
     * @param Component $comp
     * @return Component 
     */
    function add(Component $comp) {
        return $comp;
    }

    function remove(Component $comp) {
        return true;
    }

    function output($level = 0) {
        echo "";
    }

}

?>
