<?php
/**
 * Class left for component
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
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