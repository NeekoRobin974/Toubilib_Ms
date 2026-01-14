<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\api\provider\AuthnProviderInterface;

class RefreshAction {
    public function __construct(private readonly AuthnProviderInterface $authnProvider){}

    public function __invoke(Request $request, Response $response): Response{
        try {
            //récupération du header Authorization
            $authHeader = $request->getHeaderLine('Authorization');
            if (empty($authHeader)) {
                throw new \Exception("Token non fourni");
            }
            //on enlève "Bearer "
            $token = str_replace('Bearer ', '', $authHeader);

            //appelle le provider pour rafraîchir le token
            $resRefresh = $this->authnProvider->refresh($token);
            $authDTO = $resRefresh[0];

            $res = [
                'token' => $authDTO->accesToken,
                'refresh_token' => $authDTO->refreshToken
            ];

            $response->getBody()->write(json_encode($res, JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}