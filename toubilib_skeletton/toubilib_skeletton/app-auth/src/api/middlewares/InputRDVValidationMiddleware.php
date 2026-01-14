<?php
namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\core\application\ports\api\dtos\InputRendezVousDTO;

class InputRDVValidationMiddleware{
    public function __invoke(Request $request, Handler $handler): Response{
        $data = $request->getParsedBody();

        //Contrôles de présence des champs
        $required = ['praticienId', 'patientId', 'dateHeureDebut', 'dateHeureFin', 'motif', 'dureeMinutes'];
        foreach ($required as $field){
            if (empty($data[$field])){
                return $this->errorResponse("Champ $field manquant ou vide");
            }
        }

        //Contrôles des types et format
        if (!is_numeric($data['dureeMinutes'])){
            return $this->errorResponse("dureeMinutes doit être un entier");
        }
        if (!\DateTime::createFromFormat('Y-m-d\TH:i:s', $data['dateHeureDebut'])){
            return $this->errorResponse("dateHeureDebut format invalide");
        }
        if (!\DateTime::createFromFormat('Y-m-d\TH:i:s', $data['dateHeureFin'])){
            return $this->errorResponse("dateHeureFin format invalide");
        }

        //Création du DTO
        $dto = new InputRendezVousDTO(
            $data['praticienId'],
            $data['patientId'],
            $data['dateHeureDebut'],
            $data['dateHeureFin'],
            $data['motif'],
            (int)$data['dureeMinutes']
        );

        //Passage du DTO à la requête
        $request = $request->withAttribute('inputRendezVousDTO', $dto);

        return $handler->handle($request);
    }

    private function errorResponse(string $message): Response{
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
}