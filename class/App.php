<?php

class App
{

    public $running = array();
    public $completed = array();


    public function createProcess($callable, $argument)
    {
        $pid = pcntl_fork();

        switch ($pid) {
            case -1:
                echo "UNABLE TO FORK";
                exit(0);
                break;
            case 0:
                // This is child
                $callable($argument);
                exit(0);
                break;
            default:
                // This is parent
                $this->running[] = $pid;
        }
    }

}