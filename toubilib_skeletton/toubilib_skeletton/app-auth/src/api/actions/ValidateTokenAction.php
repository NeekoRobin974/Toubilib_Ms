<?php
declare(strict_types=1);

namespace toubilib\api\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\provider\JWTAuthnProvider;

class ValidateTokenAction
{
    private JWTAuthnProvider $jwtProvider;

    public function __construct(JWTAuthnProvider $jwtProvider)
    {
        $this->jwtProvider = $jwtProvider;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (! $authHeader || ! preg_match('/Bearer\s+(.*)$/i', $authHeader, $m)) {
            $payload = ['error' => 'missing_token', 'message' => 'Authorization header missing or malformed'];
            $response->getBody()->write((string) json_encode($payload));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $token = $m[1];

        try {
            $result = $this->jwtProvider->validateToken($token);

            $body = ['valid' => true];
            if (is_array($result) && ! empty($result)) {
                $body['payload'] = $result;
            }

            $response->getBody()->write((string) json_encode($body));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $payload = ['error' => 'invalid_token', 'message' => $e->getMessage()];
            $response->getBody()->write((string) json_encode($payload));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}
