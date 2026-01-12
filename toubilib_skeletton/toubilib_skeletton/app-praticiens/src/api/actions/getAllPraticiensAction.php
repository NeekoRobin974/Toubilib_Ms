<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;

class getAllPraticiensAction extends AbstractAction{
    private $servicePraticien;

    public function __construct(PraticienRepositoryInterface $servicePraticien){
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $queryParams = $request->getQueryParams();
        $specialite = $queryParams['specialite'] ?? null;
        $ville = $queryParams['ville'] ?? null;
        $praticiens = $this->servicePraticien->listerPraticiens($specialite, $ville);
        $praticiensArray = array_map(fn($p) => $p->toArray(), $praticiens);
        $response->getBody()->write(json_encode($praticiensArray));
        //200 ok
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}