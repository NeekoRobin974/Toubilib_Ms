<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;

class changerStatusRDVAction extends AbstractAction {
    private $rdvRepository;

    public function __construct(RDVRepositoryInterface $rdvRepository) {
        $this->rdvRepository = $rdvRepository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $id = $args['id'] ?? null;
        $body = $request->getParsedBody();
        $status = isset($body['status']) ? (int)$body['status'] : null;
        if (!$id || $status === null) {
            $response->getBody()->write(json_encode(['error' => 'ID ou status manquant']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        $this->rdvRepository->changerStatusRDV($id, $status);
        $response->getBody()->write(json_encode(['message' => 'Status du RDV mis à jour']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}