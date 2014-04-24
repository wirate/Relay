<?php

require_once 'Relay/Format/TermColor.php';

class Relay_Format_TermColorTest extends PHPUnit_Framework_TestCase
{
    protected $object = new Relay_Format_TermColor();

    protected $testColors = array(
        'black'   => 0,
        'red'     => 1,
        'green'   => 2,
        'yellow'  => 3,
        'blue'    => 4,
        'magneta' => 5,
        'cyan'    => 6,
        'white'   => 7
    );

    protected $testAttr = array(
        'reset'     => 0,
        'bold'      => 1,
        'dim'       => 2,
        'underline' => 3,
        'blink'     => 5,
        'reverse'   => 7,
        'hidden'    => 8
    );

    public function testColor()
    {
        foreach($testColor as $color_in => $color_out) {
            $input = "before[${color_in}]test";
            $output = "before\033[3${color_out}mtest";

            $this->assertEquals($object->format($input), $output);
        }
    }
}