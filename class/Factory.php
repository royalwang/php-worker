<?php

class Factory
{

    /** @var Queue */
    private $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function send($message, $type)
    {
        return $this->queue->sendMessage($message, $type);
    }

}