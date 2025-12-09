<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\ServiceRDVInterface;

class GetHistoriqueConsultationsAction
{
    private ServiceRDVInterface $serviceRDV;

    public function __construct(ServiceRDVInterface $serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $profile = $request->getAttribute('profile');

        if ($profile === null) {
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
        $patientId = $profile->id;

        $historique = $this->serviceRDV->getHistoriqueConsultations($patientId);

        $response->getBody()->write(json_encode($historique));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
