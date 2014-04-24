<?php

require_once 'Relay/AllTests.php';

class AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Relay');

        $suite->addTest(Relay_AllTests::suite());

        return $suite;
    }
}
