<?php
/*
 * Fill given queue with messages to prepare consuming test.
 */

if ($argc < 3) {
    die(sprintf("Usage: %s <amqp-url> <queue-name> <number-of-messages> <token>", $argv[0]).PHP_EOL);
}

list($cmd, $url, $queue, $messages, $token) = $argv;

require_once __DIR__.'/../vendor/autoload.php';

$url = \ButterAMQP\Url::parse($url);

$start = microtime(true);

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
    $url->getHost(),
    $url->getPort(),
    $url->getUser(),
    $url->getPassword(),
    $url->getVhost()
);

$channel = $connection->channel();

$channel->queue_declare($queue, false, false, false, false);

$consumer = $channel->basic_consume($queue, '', false, false, false, false, function(\PhpAmqpLib\Message\AMQPMessage $delivery) use(&$messages) {
    $messages--;

    $delivery->delivery_info['channel']->basic_ack($delivery->delivery_info['delivery_tag']);

    if ($messages <= 0) {
        $delivery->delivery_info['channel']->basic_cancel($delivery->delivery_info['consumer_tag']);
    }
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

echo 'PHPAMQPLib:'.PHP_EOL;
echo ' - ' . number_format(microtime(true) - $start, 5, '.', ''). ' seconds'.PHP_EOL;
echo ' - ' . $messages.' messages not processed'.PHP_EOL;
echo ' - ' . memory_get_peak_usage().' memory usage peak'.PHP_EOL;
echo PHP_EOL;
