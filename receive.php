<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

// Membuat koneksi ke server:
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('queue1', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};

// Menerima message dari server secara asynchronously
$channel->basic_consume('queue1', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}
