<?php

namespace gateway\actions;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProxyAction
{
    private Client $client;
    private string $toubiUrl;

    public function __construct(Client $client, string $toubiUrl)
    {
        $this->client = $client;
        $this->toubiUrl = $toubiUrl;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();
        $queryParams = $request->getQueryParams();
        $body = (string) $request->getBody();
        $headers = $this->extractHeaders($request);

        try {
            $toubilibResponse = $this->client->request($method, $this->toubiUrl . $uri, [
                'headers' => $headers,
                'query' => $queryParams,
                'body' => $body
            ]);

            $response->getBody()->write($toubilibResponse->getBody()->getContents());

            return $response
                ->withStatus($toubilibResponse->getStatusCode())
                ->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    private function extractHeaders(ServerRequestInterface $request): array
    {
        $headers = [];
        foreach ($request->getHeaders() as $name => $values) {
            if (!in_array(strtolower($name), ['host', 'content-length'])) {
                $headers[$name] = $values[0];
            }
        }
        return $headers;
    }
}
