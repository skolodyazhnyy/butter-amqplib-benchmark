<?php
/*
 * Fill given queue with messages to prepare consuming test.
 */

if ($argc < 4) {
    die(sprintf("Usage: %s <amqp-url> <queue-name> <number-of-messages> <message-size> <token>", $argv[0]).PHP_EOL);
}

list($cmd, $url, $queue, $messages, $size, $token) = $argv;

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

$payload = str_repeat('.', $size);

for ($i = 0; $i < $messages; $i++) {
    $message = new \PhpAmqpLib\Message\AMQPMessage($payload, [
        'application_headers' => new \PhpAmqpLib\Wire\AMQPTable([
            'x-token' => $token,
            'x-index' => $i,
            'x-total' => $messages,
        ]),
    ]);

    $channel->basic_publish($message, '', (string) $queue);
}

$channel->close();
$connection->close();

echo 'PHPAMQPLib:'.PHP_EOL;
echo ' - ' . number_format(microtime(true) - $start, 5, '.', ''). ' seconds'.PHP_EOL;
echo ' - ' . memory_get_peak_usage().' memory usage peak'.PHP_EOL;
echo PHP_EOL;
