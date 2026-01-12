<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;

class creerRDVAction extends AbstractAction{
    private $serviceRDV;

    public function __construct($serviceRDV){
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        //Récupération du DOT validé par le middleware
        $dto = $request->getAttribute('inputRendezVousDTO');
        if (!$dto){
            $response->getBody()->write(json_encode(['error' => 'Données invalides']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            //Appel du service pour créer le rdv
            $this->serviceRDV->creerRendezVous($dto);
            $response->getBody()->write(json_encode(['message' => 'Rendez-vous créé']));
            //201 Created
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            //409 erreur métier, 400 autres erreurs données invalides
            $status = ($e->getCode() === 409) ? 409 : 400;
            return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
        }
    }
}