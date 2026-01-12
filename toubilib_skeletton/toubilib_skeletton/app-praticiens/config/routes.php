<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\getAgendaPraticienAction;


return function( \Slim\App $app):\Slim\App {


    $app->add(new \toubilib\api\middlewares\CorsMiddleware());
    $app->options('/{routes:.+}', function (Request $rq, Response $rs, array $args) : Response {
        return $rs;
    });
    $app->get('/', HomeAction::class);
    $app->get('/praticiens', \toubilib\api\actions\getAllPraticiensAction::class);
    $app->get('/praticiens/{id}/creneaux', \toubilib\api\actions\getCreneauxAction::class);
    $app->get('/praticiens/{id}', \toubilib\api\actions\getPraticienDetailsAction::class);
    $app->get('/praticien/{id}/agenda', getAgendaPraticienAction::class);
    $app->get('/praticien/{id}/{date_debut}/agenda', getAgendaPraticienAction::class);
    $app->get('/praticien/{id}/{date_debut}/{date_fin}/agenda', getAgendaPraticienAction::class);

    return $app;
};