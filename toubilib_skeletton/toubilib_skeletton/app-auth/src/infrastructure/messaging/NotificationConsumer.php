<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$queueName = 'notifications_queue';

$connection = new AMQPStreamConnection(
    getenv('RABBITMQ_HOST') ?: 'rabbitmq',
    getenv('RABBITMQ_PORT') ?: 5672,
    getenv('RABBITMQ_USER') ?: 'toubi',
    getenv('RABBITMQ_PASSWORD') ?: 'toubi'
);

$channel = $connection->channel();

//Déclaration de la queue
$channel->queue_declare(
    $queueName,
    false,
    true,
    false,
    false
);

echo "En attente de messages\n";

$callback = function (AMQPMessage $msg) {
    echo "\nMessage reçu à " . date('Y-m-d H:i:s') . "\n";

    try {
        //decodage json du message
        $data = json_decode($msg->body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur de décodage JSON : ' . json_last_error_msg());
        }

        echo "─────────────────────────────────────────\n";
        echo "Contenu du message :\n";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        echo "─────────────────────────────────────────\n";

        $msg->getChannel()->basic_ack($msg->getDeliveryTag());

        echo "Message traité avec succès!\n";

    } catch (\Exception $e) {
        echo "Erreur : " . $e->getMessage() . "\n";
        //Rejection du message
        $msg->getChannel()->basic_nack($msg->getDeliveryTag());
    }
};

//Configuration du consommateur
$channel->basic_qos(null, 1, null);

//consumation des messages
$channel->basic_consume(
    $queueName,
    '',
    false,
    false,
    false,
    false,
    $callback
);

try {
    $channel->consume();
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

$channel->close();
$connection->close();
