<?php

class Worker
{

    /** @var Queue */
    private $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function receive()
    {
        $message = $this->queue->receiveMessage();

        return $message;
    }

    public function listen($callback){
        $this->queue->listen($callback);
    }
}