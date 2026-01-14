<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction;
use toubilib\core\application\ports\spi\PatientRepositoryInterface;

class getPatientDetailsAction extends AbstractAction{
    private $servicePatient;

    public function __construct(PatientRepositoryInterface $servicePatient){
        $this->servicePatient = $servicePatient;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $id = $args['id'];
        $patient = $this->servicePatient->detailsPatient($id);

        if ($patient){
            $response->getBody()->write(json_encode($patient->toArray()));
            //200 ok
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }else{
            $response->getBody()->write(json_encode(['error' => 'Patient not found']));
            //404 not found
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }
}
