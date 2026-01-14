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
    },
    'praticien.client' => function ($container) {
        return new Client([
            'base_uri' => $container->get('settings')['praticien_api_url'],
            'timeout'  => 5.0,
        ]);
    },
    'rdv.client' => function ($container) {
        return new Client([
            'base_uri' => $container->get('settings')['rdv_api_url'],
            'timeout'  => 5.0,
        ]);
    },
    'auth.client' => function ($container) {
        return new Client([
            'base_uri' => $container->get('settings')['auth_api_url'],
            'timeout'  => 5.0,
        ]);
    },
    'RdvProxyAction' => function($container) {
        return new ProxyAction(
            $container->get('rdv.client'),
            $container->get('settings')['rdv_api_url']
        );
    },
    'AuthProxyAction' => function($container) {
        return new ProxyAction(
            $container->get('auth.client'),
            $container->get('settings')['auth_api_url']
        );
    },
    \gateway\actions\ListerPraticiensAction::class => function ($container) {
        return new \gateway\actions\ListerPraticiensAction($container->get('praticien.client'));
    },
    \gateway\actions\ConsulterPraticienAction::class => function ($container) {
        return new \gateway\actions\ConsulterPraticienAction($container->get('praticien.client'));
    },
];
