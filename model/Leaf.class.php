<?php
/**
 * Class left for component
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ridesoft\Boiler\model;

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