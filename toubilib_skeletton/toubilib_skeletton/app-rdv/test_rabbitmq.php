<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

try {
    echo "Tentative de connexion à RabbitMQ...\n";

    //params : host, port, user, password
    //'rabbitmq' = nom du service dans le .yamal
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'toubi', 'toubi');
    $channel = $connection->channel();

    echo "Connexion réussie !\n";

    //config
    $exchange = 'toubilib.events';
    $routingKey = 'rdv.created';

    $messageBody = json_encode([
        'event' => 'rdv.created',
        'rdv_id' => uniqid(),
        'date' => date('Y-m-d H:i:s'),
        'praticien_id' => 'p-100',
        'patient_id' => 'u-200'
    ]);

    $msg = new AMQPMessage($messageBody, array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

    $channel->basic_publish($msg, $exchange, $routingKey);

    echo " [x] Message envoyé à '$exchange': $messageBody\n";

    $channel->close();
    $connection->close();

} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
