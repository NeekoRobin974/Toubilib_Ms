<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction;

class getRDVByPraticienAction extends AbstractAction
{
    private $serviceRDV;

    public function __construct($serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $praticienId = $args['id'];

        try {
            $rdvs = $this->serviceRDV->getRDVParPraticien($praticienId);

            $data = array_map(function($rdv) use ($praticienId) {
                $rdvData = $rdv->toArray();
                $rdvData['links'] = [
                    [
                        'rel' => 'self',
                        'href' => '/rdvs/' . $rdv->id
                    ],
                    [
                        'rel' => 'praticien',
                        'href' => '/praticiens/' . $praticienId
                    ],
                    [
                        'rel' => 'annuler',
                        'href' => '/rdvs/' . $rdv->id . '/annuler'
                    ]
                ];
                return $rdvData;
            }, $rdvs);

            $response->getBody()->write(json_encode([
                'praticien_id' => $praticienId,
                'rendez_vous' => $data,
            ], JSON_PRETTY_PRINT));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
