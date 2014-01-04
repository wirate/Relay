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
 * @see Relay_Irc_Helpers_Abstract
 */
require_once 'Relay/Irc/Helpers/Abstract.php';

require_once 'Relay/Protocol/Message.php';

class Relay_Irc_Helpers_Pong extends Relay_Irc_Helpers_Abstract
{
    public function pong($str)
    {
        return new Relay_Irc_Message('', 'PONG', array(), $str);
    }
}
