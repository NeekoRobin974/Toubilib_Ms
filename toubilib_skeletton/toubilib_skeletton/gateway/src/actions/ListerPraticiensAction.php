<?php

namespace gateway\actions;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ListerPraticiensAction{
    private ClientInterface $remote_praticien_service;

    public function __construct(ClientInterface $client){
        $this->remote_praticien_service = $client;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        try {
            return $this->remote_praticien_service->request('GET', "praticiens");
        } catch (ClientException $e) {
            return $response->withStatus($e->getCode())->withBody($e->getResponse()->getBody());
        }
    }
}