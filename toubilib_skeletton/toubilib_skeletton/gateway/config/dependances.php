<?php

use Psr\Container\ContainerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;

return [
    ClientInterface::class => function (ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://api.toubilib',
            'timeout'  => 5.0,
        ]);
    },
];

