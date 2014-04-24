<?php

class Relay_Registry
{
    private static $_stack = array();

    private static $_registry = null;

    public static function getInstance()
    {
        if (self::$_registry === null) {
            self::$_registry = new self();
        }
        return self::$_registry;
    }

    public static function get($index)
    {
        if (!self::isRegistered($index)) {
            require_once 'Relay/Exception.php';
            throw new Relay_Exception("No entry for key '$index'");
        }
        return self::$_stack[$index];
    }

    public static function set($index, $value)
    {
        self::$_stack[$index] = $value;
    }

    public static function isRegistered($index)
    {
        return array_key_exists($index, self::$_stack);
    }
}
