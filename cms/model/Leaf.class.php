<?php
/**
 * Classe per un componente foglia del pattern Composite
 * 
 * @subpackage compositePattern
 * @version 0.2
 */
class Leaf implements Component {
    protected $name;
    
    function __construct($name) {
        $this->name = $name;
    }
    

    function output($level = 0) {
        echo "";
    }
}

?>