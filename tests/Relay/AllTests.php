<?php

require_once 'Relay/RegistryTest.php';

class Relay_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Relay');

        $suite->addTestSuite('Relay_RegistryTest');

        return $suite;
    }
}
