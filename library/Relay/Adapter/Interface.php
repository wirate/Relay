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
 * Relay_Adapter_Interface
 * Common interface for network adapter/wrapper classes
 */
interface Relay_Adapter_Interface
{
    /**
     * Opens a stream that is not connected.
     *
     * @return void
     */
    public function open();

    /**
     * Returns true if the stream is connected on this end.
     * Note that this does not mean a connection is made.
     * there are for example connectionless protocols.
     * This will only tell you that the stream is open, and may or may
     * not be connected to an endpoint.
     *
     * @return bool
     */
    public function isOpen();

    /**
     * Establish connection
     *
     * @param   string $host
     * @param   int    $port
     * @return  void
     */
    public function connect($host, $port);

    /**
     * Check if a connection is established.
     * Note that some adapters may only be able to tell if a connection
     * is lost when trying to read from the stream.
     * So you can only rely on this method to test if a stream is disconnected.
     *
     * @return bool
     */
    public function isConnected();

    /**
     * Write to stream
     *
     * @param $data the data to write
     * @return (bool) true on success, false on failure.
     */
    public function write($data);

    /**
     * Read from stream
     *
     * @param int $bytes [optional] max numbers of bytes to recive
     * @return (string) Data read from socket or (bool) false if no data was read.
     */
    public function read($bytes = 1024);

    /**
     * Disconnect from end-point.
     *
     * @return bool
     */
    public function disconnect();

    /**
     * Close the stream.
     *
     * @return void
     */
    public function close();
}
