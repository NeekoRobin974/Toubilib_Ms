<?php
namespace toubilib\api\provider;


use toubilib\core\application\ports\api\dtos\AuthDTO;
use toubilib\core\application\ports\api\dtos\CredentialsDTO;
use toubilib\core\application\ports\api\dtos\ProfilDTO;
use toubilib\core\application\ports\api\ServiceUserInterface;

class JWTAuthnProvider implements AuthnProviderInterface{

    private ServiceUserInterface $serviceUser;
    private JWTManager $JWTManager;

    public function __construct(JWTManager $jwtManager, ServiceUserInterface $serviceUser){
        $this->JWTManager = $jwtManager;
        $this->serviceUser = $serviceUser;
    }

    //authentifie l'utilisateur et génère les tokens jwt
    public function signin(CredentialsDTO $credentials): array{
        //recherche l'utilisateur par ses id
        $user = $this->serviceUser->byCredentials($credentials);
        //prépare le payload (le tableau avec les données du token + infos utilisateur) du token jwt
        $payload = [
            'iss' => 'http://charlyMatLoc', //émetteur du token
            'iat' => time(),                //date de création
            'exp' => time()+3600,           //date d'expiration (1h)
            'sub' => $user->id,             //id utilisateur
            'data' => [
                'user' => $user->email,     //email utilisateur
                'role' => $user->role       //ajout du rôle
            ]
        ];
        //génère le token d'accès et le token de rafraîchissement
        $accessToken  = $this->JWTManager->createAccesToken($payload);
        $refreshToken = $this->JWTManager->createRefreshToken($payload);

        //retourne l'objet AuthDTO (tokens) et le profil utilisateur
        return [new AuthDTO($accessToken, $refreshToken), new ProfilDTO($user->id, $user->email, $user->role)];
    }

    //inscrit un nouvel utilisateur et retourne son profil
    public function register(CredentialsDTO $credentials): ProfilDTO{
        //crée l'utilisateur via le service
        $user = $this->serviceUser->register($credentials);
        return new ProfilDTO($user->id, $user->email, $user->role);
    }

    public function refresh(string $token): array{
        try {
            //décode le token
            $payload = $this->JWTManager->decodeToken($token);

            //vérifie que c'est un token de rafraîchissement
            if (!isset($payload['type']) || $payload['type'] !== 'refresh') {
                throw new \Exception("Token invalide (ce n'est pas un token de rafraîchissement)");
            }

            //crée les nouveaux tokens
            //on met à jour les dates
            $payload['iat'] = time();
            $payload['exp'] = time()+3600;

            $accessToken  = $this->JWTManager->createAccesToken($payload);
            $refreshToken = $this->JWTManager->createRefreshToken($payload);

            return [new AuthDTO($accessToken, $refreshToken)];

        } catch (\Exception $e) {
            throw new \Exception("Erreur lors du rafraîchissement token : " . $e->getMessage());
        }
    }

    public function validateToken(string $token): array
    {
        try {
            $payload = $this->JWTManager->decodeToken($token);
        } catch (\Exception $e) {
            throw new \Exception('Token invalide : ' . $e->getMessage());
        }

        if (!is_array($payload)) {
            throw new \Exception('Payload du token invalide');
        }

        if (isset($payload['exp']) && (int)$payload['exp'] < time()) {
            throw new \Exception('Token expiré');
        }
        return $payload;
    }

}