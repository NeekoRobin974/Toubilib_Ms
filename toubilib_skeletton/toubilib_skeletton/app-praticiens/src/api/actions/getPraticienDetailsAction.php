<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;

class getPraticienDetailsAction extends AbstractAction{
    private $servicePraticien;

    public function __construct(PraticienRepositoryInterface $servicePraticien){
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $id = $args['id'];
        $praticien = $this->servicePraticien->detailsPraticien($id);

        if ($praticien){
            $response->getBody()->write(json_encode($praticien->toArray()));
            //200 ok
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }else{
            $response->getBody()->write(json_encode(['error' => 'Praticien not found']));
            //404 not found
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }
}