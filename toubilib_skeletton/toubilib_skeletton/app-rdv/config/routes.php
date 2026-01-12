<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\annulerRDVAction;
use toubilib\api\actions\changerStatusRDVAction;
use toubilib\api\actions\getRDVByPraticienAction;


return function( \Slim\App $app):\Slim\App {


    $app->add(new \toubilib\api\middlewares\CorsMiddleware());
    $app->options('/{routes:.+}', function (Request $rq, Response $rs, array $args) : Response {
        return $rs;
    });
    $app->get('/', HomeAction::class);
    $app->get('/rdvs/{id}', \toubilib\api\actions\getRDVAction::class);
    $app->post('/rdvs', \toubilib\api\actions\creerRDVAction::class);
    $app->post('/rdvs/{id}/annuler', annulerRDVAction::class);
    $app->post('/rdvs/{id}/status', changerStatusRDVAction::class);
    $app->get('/praticiens/{id}/rdvs', getRDVByPraticienAction::class);

    return $app;
};