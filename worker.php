<?php

require_once("class/Queue.php");
require_once("class/Worker.php");

$pid = posix_getpid();

echo "WORKER PID: " . $pid . PHP_EOL;
$queueKey = getenv("MSG_KEY");
$queue = new Queue();
$queue->load($queueKey);

$worker = new Worker($queue);

$callback = function ($message) {
    echo $message["message"] . PHP_EOL;
    sleep(1);
};

$noMessagesCount = 10;

$messages = array();

while ($noMessagesCount > 0) {
    $message = $worker->receive();
    if (false === $message["message"]) {

        // No message this time
        echo "Waiting {$noMessagesCount}" . PHP_EOL;
        $noMessagesCount--;

    } elseif ($message["message"] == "\0") {
        // End of message
        echo implode("", $messages[$message["type"]]) . " FROM {$message["type"]}" . PHP_EOL . PHP_EOL;

    } else {
        // Recieved message, reset counter
        $noMessagesCount = 10;
        echo "RECIEVED {$message['message']} FROM {$message['type']}" . PHP_EOL;
        $messages[$message["type"]][] = $message["message"];
    }
    sleep(1);
}
