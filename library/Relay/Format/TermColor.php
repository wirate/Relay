<?php
/**
 * Linux Terminal Coloring.
 */

/*
 * <ESC>[{attr};{fg};{bg}m
 *
 * {fg} start 3;
 * {bg} start 4;
 */

require_once 'Relay/Format/Abstract.php';

class Relay_Format_TermColor extends Relay_Format_Abstract
{
    const ESC       = '\033';
    //const ESC   = '0x1B';
    const BEGIN     = '[';
    const END       = 'm';
    const DELIMITER = ';';

    /**
     * Attribute codes
     * @var Array
     */
    protected $attr = array(
        'reset'     => 0,
        'bold'      => 1,
        'dim'       => 2,
        'underline' => 3,
        'blink'     => 5,
        'reverse'   => 7,
        'hidden'    => 8
    );

    protected $colortype = array(
        'fg' => 3,
        'bg' => 4
    );

    /**
     * Color codes
     * @var Array
     */
    protected $color = array(
        'black'   => 0,
        'red'     => 1,
        'green'   => 2,
        'yellow'  => 3,
        'blue'    => 4,
        'magneta' => 5,
        'cyan'    => 6,
        'white'   => 7
    );

    /**
     *
     * [a:attr, c:color]
     * [c:color]
     * [/]
     */
    public function format($value)
    {

    }
}