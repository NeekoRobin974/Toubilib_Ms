<?php
namespace toubilib\api\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use toubilib\api\middlewares\exceptions\HttpUnauthorizedException;
use toubilib\api\provider\AuthnProviderInterface;
use toubilib\api\provider\exceptions\AuthProviderExpiredAccessToken;
use toubilib\api\provider\exceptions\AuthProviderInvalidAccessToken;

class AuthnMiddleware {
    private AuthnProviderInterface $authProvider;

    public function __construct(AuthnProviderInterface $authProvider) {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $token_line = $request->hasHeader('Authorization')
            ? $request->getHeaderLine('Authorization')
            : throw new HttpUnauthorizedException($request, "missing authorization header");
        $token = sscanf($token_line, "Bearer %s")[0];
        try {
            $authDto = $this->authProvider->getSignedInUser($token);
        } catch (AuthProviderInvalidAccessToken $e) {
            throw new HttpUnauthorizedException($request, "invalid jwt token");
        } catch (AuthProviderExpiredAccessToken $e) {
            throw new HttpUnauthorizedException($request, "expired jwt token");
        }
        $request = $request->withAttribute('authenticated_user', $authDto);
        $response = $handler->handle($request);
        return $response;
    }
}
