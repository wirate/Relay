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
 * Abstract event class.
 * TODO: design change! update client code
 */
abstract class Relay_Event_Command
{
    protected $caller  = null;
    
    protected $request = null;
    
    protected $message = null;
    
    protected $helpers = array();
    
    /**
     * Make sure constructor is clear of arguments
     *
     * @return void
     */
    final public function __construct()
    {
    }
    
    public function _setCaller(Relay_Client $caller)
    {
        $this->caller = $caller;
    }
    
    public function _setRequest(Relay_Event_Request $request)
    {
    	$this->request = $request;
    }
    
    public function _setMessage(Relay_Irc_Message $message)
    {
    	$this->message = $message;
    }
    
    public function __call($name, $args)
    {
    	echo "called $name";
    }
    
    abstract public function process();
}
