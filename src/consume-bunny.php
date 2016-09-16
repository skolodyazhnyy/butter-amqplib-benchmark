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

$channel->consume(
    function (\Bunny\Message $message, \Bunny\Channel $channel, \Bunny\Client $bunny) use(&$messages, $bunny) {
        $messages--;

        $channel->ack($message); // Acknowledge message

        if ($messages <= 0) {
            $channel->cancel($message->consumerTag);
            $bunny->stop();
        }
    },
    $queue
);

$bunny->run(100);

$channel->close();
// $bunny->close();

echo 'Bunny:'.PHP_EOL;
echo ' - ' . number_format(microtime(true) - $start, 5, '.', ''). ' seconds'.PHP_EOL;
echo ' - ' . $messages.' messages not processed'.PHP_EOL;
echo ' - ' . memory_get_peak_usage().' memory usage peak'.PHP_EOL;
echo PHP_EOL;
