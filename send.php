<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Membuat koneksi ke server:
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// Untuk mengirim, kita harus mendeklarasikan queue untuk kita kirim
$channel->queue_declare('queue1', false, false, false, false);

// mempublish message ke queue
$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'queue1');

echo " [x] Sent 'Hello World!'\n";

// Menutup channel dan koneksi:
$channel->close();
$connection->close();
