<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-26 at 11:42:06.
 */
class registryTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Registry
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * 
     * @group annotation
     */
    protected function setUp() {
        $this->object = new \ridesoft\Boiler\application\registry;
        $this->assertInstanceOf('ridesoft\Boiler\application\registry', $this->object);
        $this->assertObjectHasAttribute("vars", $this->object);
    }

    /**
     * @covers Registry::__set
     * @dataProvider provider
     * @group annotation
     */
    public function test__set($field, $value) {
        $this->object->$field = $value;
        $this->assertEquals($this->object->$field, $value);
    }

    /**
     * @covers Registry::__get
     * @depends test__set
     * @dataProvider provider
     * @group annotation
     */
    public function test__get($field, $value) {
        $myarr = array();
        $this->test__set($field, $value);
        $myarr[$field] = $this->object->$field;
        $this->assertArrayHasKey($field, $myarr);
        $this->assertEquals($myarr[$field], $this->object->$field);
        $this->assertEquals($myarr[$field], $value);
    }

    public function provider() {
        return array(
            array("nome", "maurizio"),
            array("cognome", "brioschi"),
            array("1", "344"),
        );
    }

}