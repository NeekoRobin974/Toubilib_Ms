<?php
namespace toubilib\api\middlewares;

use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpForbiddenException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use toubilib\api\middlewares\exceptions\HttpUnauthorizedException;
use toubilib\core\application\usecases\AuthzPraticienService;
use toubilib\core\application\usecases\exceptions\InsufficientRightsAuthzException;
use toubilib\core\application\usecases\exceptions\InvalidRoleAuthzException;
use toubilib\core\application\usecases\exceptions\NotOwnerAuthzException;

class AuthzPraticienMiddleware{
    private AuthzPraticienService $authzPraticien;

    public function __construct(AuthzPraticienService $authzPraticien){
        $this->authzPraticien = $authzPraticien;
    }

    public function __invoke(Request  $request, RequestHandlerInterface $handler): Response{
        $authDto = $request->getAttribute('authenticated_user') ??
            throw new HttpUnauthorizedException($request, "not authenticated");
        $routeContext = RouteContext::fromRequest($request);
        $routeName = $routeContext->getRoute()->getName();

        $operation = match($routeName) {
            'getAgendaPraticienAction' => AuthzPraticienService::OPERATION_LIST,
            'getRDVAction'             => AuthzPraticienService::OPERATION_READ,
            default                    => throw new HttpForbiddenException($request, "Opération non autorisée")
        };
        try{
            $this->authzPraticien->isGranted($authDto->ID, $authDto->role,
                $routeContext->getRoute()->getArgument('id'), $operation);
        }catch (InvalidRoleAuthzException|NotOwnerAuthzException|InsufficientRightsAuthzException $e){
            throw new HttpForbiddenException($request, "not authorized : " . $e->getMessage());
        }
        $response = $handler->handle($request);
        return $response;
    }
}