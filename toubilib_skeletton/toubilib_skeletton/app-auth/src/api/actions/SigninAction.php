<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\api\provider\AuthnProviderInterface;
use toubilib\core\application\ports\api\dtos\CredentialsDTO;

class SigninAction {
    public function __construct(
        private readonly AuthnProviderInterface $authnProvider
    )
    {}

    public function __invoke(Request $request, Response $response): Response{
        try {
            //recup des données
            $data = $request->getParsedBody();
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $role = isset($data['role']) ? (int)$data['role'] : 0;

            //vérif des champs
            if (($email==='') OR ($password==='')){
                throw new \Exception("Email ou mot de passe non fourni");
            }
            $credentials = new CredentialsDTO($email, $password, $role);
            //appelle le provider d'authentification pour vérifier les identifiants
            $resSignIn = $this->authnProvider->signin($credentials);

            //recup le token et le profil utilisateur
            $authDTO = $resSignIn[0];
            $profile = $resSignIn[1];

            //rep a renvoyer
            $res = [
                'token' => $authDTO->accesToken,
                'profile' => $profile
            ];

            $contentType = $request->getHeaderLine('Content-Type');
            if (str_contains($contentType, 'application/json')) {
                $response->getBody()->write(json_encode($res, JSON_PRETTY_PRINT));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                $view = \Slim\Views\Twig::fromRequest($request);
                return $view->render($response, 'connected.twig', $res);
            }
        }catch (\Exception $e){
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
}