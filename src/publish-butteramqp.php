<?php
/*
 * Fill given queue with messages to prepare consuming test.
 */

if ($argc < 4) {
    die(sprintf("Usage: %s <amqp-url> <queue-name> <number-of-messages> <message-size> <token>", $argv[0]).PHP_EOL);
}

list($cmd, $url, $queue, $messages, $size, $token) = $argv;

require_once __DIR__.'/../vendor/autoload.php';

$start = microtime(true);

$connection = \ButterAMQP\ConnectionBuilder::make()
    ->create($url)
    ->open();

$channel = $connection->channel();

$queue = $channel->queue($queue)
    ->define();

$payload = str_repeat('.', $size);

for ($i = 0; $i < $messages; $i++) {
    $message = new \ButterAMQP\Message($payload, [
        'headers' => [
            'x-token' => $token,
            'x-index' => $i,
            'x-total' => $messages,
        ],
    ]);

    $channel->publish($message, '', (string) $queue);
}

$channel->close();
$connection->close();

echo 'ButterAMQP:'.PHP_EOL;
echo ' - ' . number_format(microtime(true) - $start, 5, '.', ''). ' seconds'.PHP_EOL;
echo ' - ' . memory_get_peak_usage().' memory usage peak'.PHP_EOL;
echo PHP_EOL;
