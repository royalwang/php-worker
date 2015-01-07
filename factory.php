<?php

require_once("class/Queue.php");
require_once("class/Factory.php");

$pid = posix_getpid();
echo "FACTORY PID: " . $pid;

$message = $argv[1];
echo " | MESSAGE: " . $message;

$queueKey = getenv("MSG_KEY");
echo " | QUEUE KEY: " . $queueKey . PHP_EOL;

$queue = new Queue();
$queue->load($queueKey);

$factory = new Factory($queue);
for ($i = 0; $i < strlen($message); $i++) {
    $factory->send($message[$i], $pid);
}
$factory->send("\0", $pid);