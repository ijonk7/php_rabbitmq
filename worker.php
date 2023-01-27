<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
    echo " [x] Done\n";
    $msg->ack();
};

// Untuk memnentukan Fair Dispatch (Pengiriman yang adil).
// Dengan menggunakan method basic_qos() dengan pengaturan prefetch_count = 1 di parameter ke dua.
$channel->basic_qos(null, 1, null);

// Untuk mengaktifkan Message Acknowledgments yaitu dengan menyetel parameter keempat basic_consume() menjadi false
$channel->basic_consume('task_queue', '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
