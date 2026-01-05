<?php
namespace gateway\actions;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractGatewayAction
{
    protected ClientInterface $remote_service;

    public function __construct(ClientInterface $client)
    {
        $this->remote_service = $client;
    }

    public abstract function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
}