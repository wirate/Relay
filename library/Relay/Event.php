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

require_once 'Relay/Event/Template.php';

require_once 'Relay/Event/Request.php';

/**
 * 1. trigger syntax
 * 2. register controller classes.
 * 3. algoritm to trigger events.
 */
class Relay_Event
{
    protected $client;
    
    protected $commandDirectory;

    protected $events = array();

    public function __construct(Relay_Client $client, $commandDirectory)
    {
        $this->client = $client;
        $this->commandDirectory = $commandDirectory;
    }

    public function registerTemplate($id, Relay_Event_Template $template)
    {
        if (isset($this->events[$id])) {
            throw new Exception("Id ($id) already set");
        }

        $this->events[$id] = array(
            'object' => $template,
            'commands' => array()
        );
    }

    public function registerEvent($command, $templateId)
    {
        if (!array_key_exists($templateId, $this->events)) {
            throw new Exception("Template id not found");
        }

        // search for command and make a reference.
        foreach($this->events as $event) {
            if (array_key_exists($command, $event['commands'])) {
                $commandClass = $event['commands'][$command];
                break;
            }
        }

        // make a object.
        if (!isset($commandClass)) {
            $commandClass = UCfirst($command) . 'Command';
            $file = $this->commandDirectory . UCfirst($command) . '.php';

            if (!file_exists($file)) {
                throw new Exception("'$file' don't exists");
            }

            require_once $file;

            if (!class_exists($commandClass, false)) {
                throw new Exception("'$commandClass' not found in file '$file'");
            }

            $commandClass = new $commandClass();

            if (!($commandClass instanceof Relay_Event_Command)) {
                throw new Exception("Class must extend Relay_Event_Command");
            }
        }

        $this->events[$templateId]['commands'][$command] = $commandClass;
    }

    public function getAllEvents()
    {
        return $this->events;
    }

    public function check(Relay_Irc_Message $message)
    {
        foreach($this->events as $event) {
            if ($event['object']->check($message) === false) {
                continue;
            }

            foreach($event['commands'] as $name => $command) {
                $this->execute($command, $message);
            }
        }
    }

    protected function execute($command, $message)
    {
        $command->_setCaller($this->client);
        $command->_setMessage($message);
        $command->_setRequest(new Relay_Event_Request($message->getTrail()));

        $command->process();
    }
}
