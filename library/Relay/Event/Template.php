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

class Relay_Event_Template 
{
    protected $components = array(
        'prefix'  => null,
        'command' => null,
        'params'  => null,
        'trail'   => null
    );

    public function __construct(array $rules)
    {
        foreach($rules as $k => $v) {
            if (array_key_exists($k, $this->components)) {
                $this->components[$k] = $v;
            }
        }
    }

    public function check(Relay_Irc_Message $values)
    {
        $array['prefix'] = $values->getPrefix();
        $array['command'] = $values->getCommand();
        $array['params'] = $values->getParam(0);
        $array['trail'] = $values->getTrail();

        foreach($array as $comp => $value) {

            if ($this->components[$comp] === null) {
                continue;
            }

            if (preg_match("/{$this->components[$comp]}/", $value) === 0) {
                return false;
            }
        }

        return true;
    }
}
