<?php


namespace gateway\middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    private array $allowedOrigins;
    private bool $requireOrigin;
    private bool $allowAll;

    public function __construct(array $allowedOrigins = [], bool $requireOrigin = false, bool $allowAll = true)
    {
        $this->allowedOrigins = $allowedOrigins;
        $this->requireOrigin = $requireOrigin;
        $this->allowAll = $allowAll;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $origin = $request->getHeaderLine('Origin');

        if ($this->requireOrigin && $origin === '') {
            throw new \Slim\Exception\HttpUnauthorizedException($request, 'missing Origin Header (cors)');
        }

        if ($request->getMethod() === 'OPTIONS') {
            $response = new \Slim\Psr7\Response();
        } else {
            $response = $handler->handle($request);
        }

        if ($this->allowAll) {
            $allowOrigin = '*';
        } elseif (!empty($this->allowedOrigins)) {
            $allowOrigin = in_array($origin, $this->allowedOrigins, true) ? $origin : 'null';
        } else {
            $allowOrigin = $origin !== '' ? $origin : '*';
        }

        return $response
            ->withHeader('Access-Control-Allow-Origin', $allowOrigin)
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Max-Age', '3600')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}
