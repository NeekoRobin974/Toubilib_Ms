<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\annulerRDVAction;
use toubilib\api\actions\changerStatusRDVAction;
use toubilib\api\actions\getAllPraticiensAction;
use toubilib\api\actions\getAgendaPraticienAction;
use toubilib\api\actions\GetHistoriqueConsultationsAction;
use toubilib\api\actions\getRDVByPraticienAction;


return function( \Slim\App $app):\Slim\App {


    $app->add(new \toubilib\api\middlewares\CorsMiddleware());
    $app->options('/{routes:.+}', function (Request $rq, Response $rs, array $args) : Response {
        return $rs;
    });
    $app->post('/auth/signin', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $authProvider = $container->get(\toubilib\api\provider\AuthnProviderInterface::class);
        $action = new \toubilib\api\actions\SigninAction($authProvider);
        return $action($request, $response, $args);
    });
    $app->post('/auth/register', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $authProvider = $container->get(\toubilib\api\provider\AuthnProviderInterface::class);
        $action = new \toubilib\api\actions\SignUpAction($authProvider);
        return $action($request, $response, $args);
    });

    return $app;
};