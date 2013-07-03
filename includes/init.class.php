<?php
 /**
  * init file to configure web application parameters
  * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.2 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ridesoft\Boiler\includes;

use ridesoft\Boiler\application\registry;
use ridesoft\Boiler\application\router;
use ridesoft\Boiler\application\template;

class init {
    
    private $registry;
     
    public function __construct($path) {
            $this->registry = new registry;
            $this->registry->path = $path;
            $this->registry->router = new router($this->registry);
            $this->registry->template = new template($this->registry);
            $this->loader();
    }
     
    public static function start($path)
    {
        if (!isset(self::$init)) {
            $c = __CLASS__;
            self::$init = new $c($path);
        }

        return self::$init;
    }
    /**
    * set a variable
    * @param String $index
    * @param type $value
    */
   public function __set($index, $value)
   {
          $this->registry->$index = $value;
   }
   /**
    * get a variable
    * @param type $index
    * @return type
    */
   public function __get($index)
   {
          return $this->registry->$index;
   }
   /**
    * load the url route
    */
   public function loader(){
       $this->registry->router->loader();
   }
       /**
       * sleep method for serialize object
      * @return serialize array
       */
        public function __sleep()
        {
            return array($this->registry);
        }
        /**
        * wakeup method for serialize object
        * @return init
        */
        public function __wakeup()
        {
            
            return $this;
        }
        /**
        * non permette la clonazione della connessione
        */
        public function __clone()
        {
            echo 'connection already exist!';
            trigger_error('Clone is not allowed.', E_USER_ERROR);
        }
 
 
}
?>
