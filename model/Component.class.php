<?php
/*
 * Classe base per la creazione del pattern Composite, usato per istanziare i nodi, le pagine e i relativi componenti delle pagine
 * 
 * @subpackage compositePattern
 * @version 0.2
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