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
 * TODO: refactor/tweak
 */
class Relay_Event_Request
{
    protected $parameters = array();

    public function __construct($string, $delimiter = ' ')
    {
        $this->parameters = explode($delimiter, $string);
    }

    public function getAll()
    {
        return $this->parameters;
    }

    public function get($key)
    {
        return (isset($this->parameters[$key]))
            ? $this->parameters[$key] : false;
    }

    public function count()
    {
        return count($this->parameters);
    }
}