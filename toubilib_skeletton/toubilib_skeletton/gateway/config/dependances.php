<?php

use gateway\actions\ProxyAction;
use Psr\Container\ContainerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use gateway\middleware\CorsMiddleware;

return [
    'settings' => function() {
        return require __DIR__ . '/settings.php';
    },
    ClientInterface::class => function ($container) {
        return new Client([
            'base_uri' => $container->get('settings')['toubilib_api_url'],
            'timeout'  => 5.0,
        ]);
    },
    ProxyAction::class => function($container) {
        return new ProxyAction(
            $container->get(Client::class),
            $container->get('settings')['toubilib_api_url']
        );
    }

];

