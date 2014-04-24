<?php

require_once 'Relay/Registry.php';

class Relay_RegistryTest extends PHPUnit_Framework_TestCase
{
    public function  __construct()
    {
        Relay_Registry::set('key', 'value');
        Relay_Registry::set('key2', 'value2');
    }

    /**
     * @expectedException Relay_Exception
     */
    public function testGetNoExist()
    {
        Relay_Registry::get('NonExistant');
    }

    public function testGet()
    {
        $this->assertEquals(Relay_Registry::get('key'), 'value');
        $this->assertEquals(Relay_Registry::getInstance()->get('key2'), 'value2');
    }

    public function testIfRegistered()
    {
        $this->assertTrue(Relay_Registry::getInstance()->isRegistered('key'));
        $this->assertTrue(Relay_Registry::isRegistered('key2'));
    }

    public function testIfNotRegistred()
    {
        $this->assertFalse(Relay_Registry::isRegistered('NonExistant'));
    }
}
