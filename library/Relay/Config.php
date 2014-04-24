<?php

class Relay_Config implements Countable, Iterator
{
    protected $_count = 0;
    
    protected $_index = 0;
    
    protected $_data = array();

    /**
     * Flag to tell if the data (in memory) is read only.
     *
     * @var bool
     */
    protected $_readOnly = false;
    
    public function __construct(array $data, $readOnly = false)
    {
        $this->_readOnly = (bool) $readOnly;

        foreach($data as $key => $value) {

            if (is_array($value)) {
                $value = new self($value, $readOnly);
            }
            $this->data[$key] = $value;
        }
        $this->count = count($data);
    }

    public function get($name, $default = null)
    {
        $r = $default;
        if (array_key_exists($name, $this->data)) {
            $r = $this->data[$name];
        }
        return $r;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @throws Relay_Exception
     */
    public function set($name, $value)
    {
        if ($this->isReadOnly()) {
            require_once 'Relay/Exception.php';
            throw new Relay_Adapter_Exception('Config is read only.');
        }
        
        if (is_array($value)) {
            $value = new self($value, true);
        }
        $this->_data[$name] = $value;
        $this->_count = count($this->_data);
    }

    /**
     * Sets a key ($name) to $value
     * 
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->_data);
    }
    
    public function __unset($name)
    {
        if ($this->isReadOnly()) {
            require_once 'Relay/Exception.php';
            throw new Relay_Adapter_Exception('Config is read only.');
        }
        
        unset($this->_data[$name]);
        $this->_count = count($this->_data);
    }
    
    public function current()
    {
        return current($this->_data);
    }
    
    public function key()
    {
        return key($this->_data);
    }
    
    public function next()
    {
        next($this->_data);
        $this->_index++;
    }
    
    public function rewind()
    {
        reset($this->_data);
        $this->_index = 0;
    }
    
    public function valid()
    {
        return $this->_count > $this->_index;
    }
    
    public function count()
    {
        return $this->_count;
    }

    /**
     * Returns true if Config is read only, false otherwise.
     *
     * @return bool
     */
    public function isReadOnly()
    {
        return $this->_readOnly;
    }
    
    public function setReadOnly()
    {
        $this->_readOnly = true;

        // Recursivly set read only.
        foreach($this->_data as $value) {
            if ($value instanceof Relay_Config) {
                $value->setReadOnly();
            }
        }
    }
}