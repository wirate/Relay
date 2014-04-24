<?php

interface Relay_Server_Transport_Interface
{
	public function read();
	
	public function write($data);
}