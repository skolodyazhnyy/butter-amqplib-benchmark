<?php
/*
 * Fill given queue with messages to prepare consuming test.
 */

if ($argc < 3) {
    die(sprintf("Usage: %s <amqp-url> <queue-name> <number-of-messages> <token>", $argv[0]).PHP_EOL);
}

list($cmd, $url, $queue, $messages, $token) = $argv;

$token = uniqid();

require_once __DIR__.'/../vendor/autoload.php';

$connection = \ButterAMQP\ConnectionBuilder::make()
    ->create($url)
    ->open();

$channel = $connection->channel();

$queue = $channel->queue($queue)
    ->define();

$consumer = $channel->consume($queue, function(\ButterAMQP\Delivery $delivery) use(&$messages) {
    $messages--;

    $delivery->ack();

    if ($messages <= 0) {
        $delivery->cancel();
    }
});

while ($consumer->isActive()) {
    $connection->serve();
}

$channel->close();
$connection->close();
