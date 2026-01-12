<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;

class annulerRDVAction extends AbstractAction{
    private $serviceRDV;

    public function __construct($serviceRDV){
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $id = $args['id'];
        try{
            $this->serviceRDV->annulerRendezVous($id);
            $data = [
                'message' => 'Rendez-vous annulé',
                'links' => [
                    [
                        'rel' => 'self',
                        'href' => '/rdvs/' . $id . '/annuler'
                    ],
                    [
                        'rel' => 'rdv',
                        'href' => '/rdvs/' . $id
                    ]
                ]
            ];
            $response->getBody()->write(json_encode($data));
            //200 ok
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\DomainException $e){
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            //400 bad request
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        } catch (\Exception $e){
            $response->getBody()->write(json_encode(['error' => 'Erreur serveur']));
            //500 serveur
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}