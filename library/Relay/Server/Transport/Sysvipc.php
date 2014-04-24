<?php

class Relay_Server_Transport_Sysvipc implements Relay_Server_Transport_Interface
{
    protected $queue = null;

    public function __construct($id)
    {
        $this->queue = msg_get_queue($id, 'rw');

        if (!is_resource($this->queue)) {
            throw Exception("can't get queue");
        }
    }

    public function read($bytes = 1024)
    {
        $rc = msg_receive($this->queue, 0, $type, $bytes, $msg, false, MSG_IPC_NOWAIT);
        return $rc ? $msg : false;
    }

    public function write($data)
    {
        
    }

    public function  __destruct()
    {
        if (is_resource($this->queue)) {
            @msg_remove_queue($this->queue);
            $this->queue = null;
        }
    }
}