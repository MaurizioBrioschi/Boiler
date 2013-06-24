<?php
/*
 * interface for a component web page
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
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