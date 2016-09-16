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

$connection = [
    'host'      => $url->getHost(),
    'vhost'     => $url->getVhost(),
    'user'      => $url->getUser(),
    'password'  => $url->getPassword(),
];

$bunny = new \Bunny\Client($connection);
$bunny->connect();

$channel = $bunny->channel();
$channel->queueDeclare($queue);

$payload = str_repeat('.', $size);

for ($i = 0; $i < $messages; $i++) {
    $channel->publish(
        $payload,
        [
            'headers' => [
                'x-token' => $token,
                'x-index' => $i,
                'x-total' => $messages,
            ],
        ],
        '',
        $queue
    );
}

$channel->close();
// $bunny->close();

echo 'Bunny:'.PHP_EOL;
echo ' - ' . number_format(microtime(true) - $start, 5, '.', ''). ' seconds'.PHP_EOL;
echo ' - ' . memory_get_peak_usage().' memory usage peak'.PHP_EOL;
echo PHP_EOL;
