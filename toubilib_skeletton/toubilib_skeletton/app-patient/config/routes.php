<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\GetHistoriqueConsultationsAction;


return function( \Slim\App $app):\Slim\App {


    //$app->add(new \toubilib\api\middlewares\CorsMiddleware());
    $app->options('/{routes:.+}', function (Request $rq, Response $rs, array $args) : Response {
        return $rs;
    });
    $app->get('/', HomeAction::class);
    $app->get('/patients/consultations', GetHistoriqueConsultationsAction::class)
        ->add(\toubilib\api\middlewares\AuthnMiddleware::class);
    $app->get('/patients/{id}', \toubilib\api\actions\getPatientDetailsAction::class);

    return $app;
};