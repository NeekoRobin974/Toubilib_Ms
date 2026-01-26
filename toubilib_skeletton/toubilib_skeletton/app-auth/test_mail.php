<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
//Envoie un message generique(pour tester le consumer)
$connection = new AMQPStreamConnection('localhost', 5672, 'toubi', 'toubi');
$channel = $connection->channel();

$channel->queue_declare('notifications_queue', false, true, false, false);

$message = [
    'type' => 'test',
    'email' => 'test@example.com',
    'subject' => 'Test de notification',
    'message' => 'Ceci est un message de test'
];

$msg = new AMQPMessage(
    json_encode($message),
    ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);

$channel->basic_publish($msg, '', 'notifications_queue');

echo "Message envoyé!\n";

$channel->close();
$connection->close();
