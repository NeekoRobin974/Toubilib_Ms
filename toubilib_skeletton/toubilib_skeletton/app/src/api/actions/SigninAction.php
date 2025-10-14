<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\api\provider\AuthnProviderInterface;

class SigninAction{
    protected AuthnProviderInterface $authProvider;

    public function __construct(AuthnProviderInterface $authProvider){
        $this->authProvider = $authProvider;
    }

    public function __invoke(Request $request, Response $response, array $args): Response{
        //Recupère email et mdp
        $data = $request->getParsedBody();
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        //Validation des entrées
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
            $responseData = ['error' => 'Email valide et mot de passe requis.'];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        //Tente de connecter l'utilisateur
        $result = $this->authProvider->signin($email, $password);

        //Si échec, retourne une erreur
        if (!$result) {
            $responseData = ['error' => 'Identifiants incorrects.'];
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        //Retourne le profil et les tokens
        $responseData = [
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'profil' => [
                'id' => $result['profil']->id,
                'email' => $result['profil']->email,
                'role' => $result['profil']->role
            ]
        ];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}