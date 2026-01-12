<?php

namespace gateway\actions;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ConsulterPraticienAction
{
    private ClientInterface $remote_praticien_service;

    public function __construct(ClientInterface $client){
        $this->remote_praticien_service = $client;
    }
    
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'];
        try {
            return $this->remote_praticien_service->request('GET', "praticiens/$id");
        } catch (ClientException $e) {
            if ($e->getCode() === 404) {
                throw new \Slim\Exception\HttpNotFoundException($request, "Le praticien d'id $id n'existe pas");
            }
            throw $e;
        }
    }
}
