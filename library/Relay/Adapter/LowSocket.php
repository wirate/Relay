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
 * @see Relay_Adapter_Interface
 */
require_once 'Relay/Adapter/Interface.php';

class Relay_Adapter_LowSocket implements Relay_Adapter_Interface
{
    /**
     * Socket domain (IPv4, IPv6 or local)
     *
     * @var int
     */
    protected $_domain;

    /**
     * Communication type. (stream, datagram, seqpacket etc)
     *
     * @var int
     */
    protected $_type;

    /**
     * Protocol the socket to use. (TCP, UDP, Raw)
     *
     * @var int
     */
    protected $_protocol;

    /**
     * The Socket resource
     *
     * @var resource
     */
    protected $_resource = null;

    /**
     * Default is a IPv4 stream socket using the TCP protocol.
     *
     * @param int $domain
     * @param int $type
     * @param int $protocol
     */
    public function __construct($domain = AF_INET, $type = SOCK_STREAM,
                                $protocol = SOL_TCP)
    {
        $this->_domain = $domain;
        $this->_type = $type;
        $this->_protocol = $protocol;
    }

    public function open()
    {
        $this->close();

        $resource = socket_create($this->_domain, $this->_type, $this->_protocol);

        if ($resource === false) {
            require_once 'Relay/Adapter/Exception.php';
            throw new Relay_Adapter_Exception(socket_strerror(socket_last_error()));
        }

        $this->_resource = $resource;
    }

    /**
     * Establish connection
     *
     * @param string $host
     * @param int    $port
     */
    public function connect($host, $port)
    {
        if ($this->isOpen()) {
            // disconnect first.
            $this->disconnect();
        } else {
            $this->open();
        }

        if (socket_connect($resource, $host, $port) === false) {
            $message = socket_strerror(socket_last_error());
            require_once 'Relay/Adapter/Exception.php';
            throw new Relay_Adapter_Exception('Socket connection failed: ' . $message);
        }

        if (!socket_set_nonblock($resource)) {
            $message = socket_strerror(socket_last_error());
            require_once 'Relay/Adapter/Exception.php';
            throw new Relay_Adapter_Exception("Failed to set block mode: " . $message);
        }
    }

    public function isConnected()
    {
        // Just check if we have the socket open for now.
        return $this->isOpen();
    }

    public function isOpen()
    {
        return is_resource($this->_resource);
    }

    public function write($data)
    {
        return @socket_write($this->_resource, $data);
    }

    public function read($bytes = 1024)
    {
        $sock = array($this->_resource);
        $s = socket_select($sock, $n = null, $n = null, 300);

        if ($s === false) {
            $this->disconnect();
            $message = socket_strerror(socket_last_error());
            require_once 'Relay/Adapter/Exception.php';
            throw new Relay_Adapter_Exception('Socket select error: ' . $message);
        }

        if (socket_recv($sock[0], $buffer, $bytes, 0) === 0) {
            $this->disconnect();
            require_once 'Relay/Adapter/Exception.php';
            throw new Relay_Adapter_Exception('Socket disconnected: ' . $message);
        }

        return $buffer;
    }

    public function disconnect()
    {
        return @socket_shutdown($this->_resource, 2);
    }

    public function close()
    {
        if ($this->_resource !== null) {
            socket_close($this->_resource);
            $this->_resource = null;
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}