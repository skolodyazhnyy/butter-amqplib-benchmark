<?php
/*
 * Fill given queue with messages to prepare consuming test.
 */

if ($argc < 2) {
    die(sprintf("Usage: %s <amqp-url> <queue-name>", $argv[0]).PHP_EOL);
}

list($cmd, $url, $queue) = $argv;

require_once __DIR__.'/../vendor/autoload.php';

$connection = \ButterAMQP\ConnectionBuilder::make()
    ->create($url)
    ->open();

$channel = $connection->channel();

$queue = $channel->queue($queue)
    ->define()
    ->purge();

$channel->close();
$connection->close();
