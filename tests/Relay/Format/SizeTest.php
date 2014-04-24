<?php

require_once 'Relay/Format/Size.php';

class Relay_Format_SizeTest extends PHPUnit_Framework_TestCase
{
    protected $object = new Relay_Format_Size();

    public function testTrue()
    {
        $this->assertEquals($object->format("1000"), "1K");
        $this->assertEquals($object->format("10000000"), "1M");
    }

    public function testFlase()
    {

    }
}