<?php

$id = posix_getpid();
echo "MANAGER STARTED {$id}" . PHP_EOL;

require("class/Queue.php");
require("class/App.php");

$queue = new Queue();
$queue->init(); 

// Export Queue ID in env var
putenv("MSG_KEY={$queue->queueId}");
array_shift($argv);
$app = new App();

// FACTORY
foreach ($argv as $param) {
    $factoryFunction = function ($param) {
        $argv = array(1 => $param);
        require("factory.php");
    };

    $app->createProcess($factoryFunction, $param);
}

sleep(1);

// WORKER
$workerFunction = function ($param = "") {
    require("worker.php");
};
$app->createProcess($workerFunction, "");

// WAIT FOR CHILDREN
while ($id = pcntl_wait($status)) {
    if ($id == -1) {
        break;
    }

    echo "Child {$id} completed\n";
}
