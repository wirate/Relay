<?php
/**
 * Copyright (C) 2011-2014  Henrik Hautakoski <henrik@fiktivkod.org>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */


/**
 * @see Relay_Exception
 */
require_once 'Relay/Exception.php';

/**
 * Class for loading other classes.
 */
class Relay_Loader
{
    /**
     * Name of the function used for autoloading.
     *
     * @var array|string
     */
    protected $_autoloadFunction = array(__CLASS__, 'autoload');

    /**
     * Keep track of if autoloading is enabled or not.
     *
     * @var boolean
     */
    protected $_autoload = false;

    /**
     * Singleton instance
     *
     * @var Relay_Loader
     */
    private static $_instance = null;

    /**
     * Singleton
     */
    private function __construct()
    {
    }

    /**
     * Singleton
     */
    private function __clone()
    {
    }

    /**
     * Get the singleton instance.
     *
     * @return Relay_Loader
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Returns true if autoload is enabled for this loader. false otherwise.
     *
     * @return type
     */
    public function isAutoloadEnabled()
    {
        return $this->_autoload;
    }

    /**
     * Enable or disable autoloading.
     *
     * @param boolean $enable
     * @return Relay_Loader
     */
    public function setAutoload($enable)
    {
        $this->_autoload = (bool) $enable;

        if ($this->_autoload) {
            spl_autoload_register($this->_autoloadFunction);
        } else {
            spl_autoload_unregister($this->_autoloadFunction);
        }

        return $this;
    }

    /**
     * Load a file. returns true if the file was loaded. false otherwise.
     *
     * @param string $filename  The filename
     * @return boolean
     */
    public function loadFile($filename)
    {
        if (self::isReadable($filename)) {
            require_once $filename;
            return true;
        }
        return false;
    }

    /**
     * Check if a file is readable. this will only check
     * if a file is readable from the include path.
     *
     * @param type $filename  The filename
     * @return boolean
     */
    public static function isReadable($filename)
    {
        $paths = explode(PATH_SEPARATOR, get_include_path());

        foreach($paths as $path) {

            $fullpath = $path . DIRECTORY_SEPARATOR . $filename;

            if (is_readable($fullpath)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Load a class
     *
     * @param string $class     Name of the class.
     * @throws Relay_Exception
     * @return void
     */
    public function loadClass($class)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }

        $filename = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        if ($this->loadFile($filename) === false) {
            require_once 'Relay/Exception.php';
            throw new Relay_Exception("Filename '$filename' was not found.");
        }

        // Check if class now after include.
        if (!class_exists($class, false) && !interface_exists($class, false)) {
            require_once 'Relay/Exception.php';
            throw new Relay_Exception("class '$class' was not found");
        }
    }

    /**
     * spl_autoload() implementation.
     *
     * @param string $class
     * @return boolean
     */
    public static function autoload($class)
    {
        $instance = self::getInstance();

        try {
            @$instance->loadClass($class);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }
}