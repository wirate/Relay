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

/**
 * Relay_Network_Socket
 * basic non-blocking socket connection. Wrapps PHP's Stream/File functions
 *
 * @see http://docs.php.net/manual/en/function.stream-socket-client.php
 */
class Relay_Adapter_Socket implements Relay_Adapter_Interface
{
    /**
     * Transfer protocol constants
     * @link http://se2.php.net/manual/en/transports.php
     */
    const TCP   = 'tcp';
    const SSL   = 'ssl';
    const SSLV2 = 'sslv2';
    const SSLV3 = 'sslv3';
    const UDP   = 'udp';
    const TLS   = 'tls';

    /**
     * Socket Resource
     * @var resource|null
     */
    protected $_resource = null;

    /**
     * Create and connect socket.
     *
     * @param String $host
     * @param Int $port
     * @param String $protocol
     */
    public function connect($host, $port, $protocol = self::TCP)
    {
        // Make sure we are disconnected.
        $this->disconnect();

        $resource = @stream_socket_client("$protocol://$host:$port",
            $errno, $errstr, 30, STREAM_CLIENT_CONNECT);

        if ($resource === false) {
            require_once 'Relay/Adapter/Exception.php';
            $message = "Adapter error ($protocol://$host): $errstr";
            throw new Relay_Adapter_Exception($message, $errno);
        }

        // Set stream to non blocking.
        if (stream_set_blocking($resource, 0) === false) {
            require_once 'Relay/Adapter/Exception.php';
            throw new Relay_Adapter_Exception("Could not set blocking mode");
        }

        $this->_resource = $resource;
    }

    /**
     * Write data to stream
     *
     * @param String $data
     * @return (bool) true on success, false on failure.
     */
    public function write($data)
    {
        return @fwrite($this->_resource, $data);
    }

    /**
     * Read data from stream.
     * 
     * @param int $bytes [optional]
     * @return (String) Data read from socket or (bool) false if no data was read.
     */
    public function read($bytes = 1024)
    {
        $stream = array($this->_resource);

        if (@stream_select($stream, $n = null, $n = null, 3) === 0) {
            return;
        }

        if (feof($stream[0])) {
            $this->disconnect();
            require_once 'Relay/Adapter/Exception.php';
            throw new Relay_Adapter_Exception("EOF reached: Connection lost");
        }

        return @fgets($stream[0], $bytes);
    }

    /**
     * Close socket.
     * @return Void
     */
    public function disconnect()
    {
        if ($this->_resource !== null) {
            @fclose($this->_resource);
            $this->_resource = null;
        }
    }

    /**
     * Destructor, Make sure the socket closes
     * @return void
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}
