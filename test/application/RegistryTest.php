<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-26 at 11:42:06.
 */

use ridesoft\Boiler\application\Registry;

class RegistryTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Registry
     */
    protected $object;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @group annotation
     */
    protected function setUp() {
        $this->object = new Registry;
       
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     * @group annotation
     */
    protected function tearDown() {
       
    }

    /**
     * @covers Registry::__set
     * @group annotation
     */
    public function test__set() {
        $this->object->nome = "maurizio";
        $this->object->cognome = "brioschi"; 
    }

    /**
     * @covers Registry::__get
     * @depends test__set
     * @group annotation
     */
    public function test__get() {
        $this->object->nome = "maurizio";
        $this->object->cognome = "brioschi"; 
        $this->assertEquals($this->object->nome,"maurizio");
        $this->assertEquals($this->object->cognome,"brioschi");
    }

}
