<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;

class getRDVAction extends AbstractAction{
    private $serviceRDV;

    public function __construct($serviceRDV){
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $id = $args['id'];
        $rdv = $this->serviceRDV->getRDVById($id);

        if ($rdv){
            $data = $rdv->toArray();
            $data['links'] = [
                [
                    'rel' => 'self',
                    'href' => '/rdvs/' . $id
                ],
                [
                    'rel' => 'annuler',
                    'href' => '/rdvs/' . $id . '/annuler'
                ]
            ];
            $response->getBody()->write(json_encode($data));
            //200 ok
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }else{
            $response->getBody()->write(json_encode(['error' => 'RDV not found']));
            //404 not found
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }
}