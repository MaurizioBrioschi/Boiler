<?php
/*
 * interface for a component web page
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ridesoft\Boiler\model;

interface iComponent {
    /**
     * aggiunge un elemento alla struttura
     */
    abstract function add(Component $comp);
    /**
     * rimuove un elemento alla struttura
     */
    abstract function remove(Component $comp);
    /**
     * stampa una rappresentazione delle'elemento
     */
    abstract function output();
    
}

?>