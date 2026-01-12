<?php
namespace toubilib\api\actions;

class getAgendaPraticienAction {
    private $service;

    public function __construct($service) {
    $this->service = $service;
    }

    public function __invoke($request, $response, $args) {
        $praticienId = $args['id'];
        $dateDebut = $args['date_debut'] ?? null;
        $dateFin = $args['date_fin'] ?? null;

        $agenda = $this->service->getAgendaPraticien($praticienId, $dateDebut, $dateFin);
        $response->getBody()->write(json_encode($agenda));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
