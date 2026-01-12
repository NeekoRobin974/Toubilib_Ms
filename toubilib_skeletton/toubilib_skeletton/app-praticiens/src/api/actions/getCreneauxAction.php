<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction;

class getCreneauxAction extends AbstractAction
{
    private $serviceRDV;

    public function __construct($serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'] ?? null;
        $params = $request->getQueryParams();
        $dateDebut = $params['date_debut'] ?? null;
        $dateFin = $params['date_fin'] ?? null;

        if (!$id || !$dateDebut || !$dateFin) {
            $response->getBody()->write(json_encode(['error' => 'id, date_debut et date_fin requis']));
            //400 bad request
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $creneaux = $this->serviceRDV->listerCreneaux($id, $dateDebut, $dateFin);

        $response->getBody()->write(json_encode($creneaux));
        //200 ok
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}