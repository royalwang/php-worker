<?php

class Queue
{

    public $queueId = 6969;

    public $queue;

    public function __construct()
    {

    }

    public function init()
    {
        $this->queue = msg_get_queue($this->queueId);

        // Set queue size to 5 bytes
        msg_set_queue($this->queue, array('msg_qbytes' => '5'));
    }

    public function load($key)
    {
        $this->queueId = $key;
        $this->queue = msg_get_queue($key);
        msg_set_queue($this->queue, array('msg_qbytes' => '5'));
    }

    public function sendMessage($message, $type)
    {
        if (msg_send($this->queue, $type, $message, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function receiveMessage()
    {
        $msg_type = NULL;
        $msg = NULL;
        $max_msg_size = 512;

        msg_receive($this->queue, 0, $msg_type, $max_msg_size, $msg, false, MSG_IPC_NOWAIT);
        return array("message" => $msg, "type" => $msg_type);
    }

    public function listen($callback)
    {
        $msg_type = NULL;
        $msg = NULL;
        $max_msg_size = 512;

        while (msg_receive($this->queue, 0, $msg_type, $max_msg_size, $msg, false)) {
            $callback(array("message" => $msg, "type" => $msg_type));
            $msg_type = NULL;
            $msg = NULL;
        }
    }

}