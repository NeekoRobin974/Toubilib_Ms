<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\api\provider\AuthnProviderInterface;
use toubilib\core\application\ports\api\dtos\CredentialsDTO;

class SignUpAction {
    public function __construct(
        private readonly AuthnProviderInterface $authnProvider
    )
    {}

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            //recup les données envoyées (email, mdp, role)
            $data = $request->getParsedBody();
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $role = isset($data['role']) ? (int)$data['role'] : 0;

            //verif que les champs sont bien fournis
            if (($email==='') OR ($password==='')){
                throw new \Exception("Email ou mot de passe non fourni");
            }
            if (!isset($data['role'])) {
                throw new \Exception("Rôle non fourni");
            }
            $credentials = new CredentialsDTO($email, $password, $role);
            //appelle le provider d'authentification pour enregistrer l'utilisateur
            $profile = $this->authnProvider->register($credentials);

            //rep à retourner
            $res = [
                'profile' => $profile
            ];

            //201 created
            $response->getBody()->write(json_encode($res, JSON_PRETTY_PRINT));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);

        }catch (\Exception $e){
            //400
            $response->getBody()->write($e->getMessage());
            return $response->withStatus(400);
        }
    }
}