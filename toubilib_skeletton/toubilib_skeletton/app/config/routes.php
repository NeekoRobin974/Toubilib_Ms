<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\annulerRDVAction;
use toubilib\api\actions\getAllPraticiensAction;
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
    $app->get('/rdvs/{id}', \toubilib\api\actions\getRDVAction::class);
    $app->post('/rdvs', \toubilib\api\actions\creerRDVAction::class);
    $app->get('/praticien/{id}/agenda', getAgendaPraticienAction::class);
    $app->get('/praticien/{id}/{date_debut}/agenda', getAgendaPraticienAction::class);
    $app->get('/praticien/{id}/{date_debut}/{date_fin}/agenda', getAgendaPraticienAction::class);
    $app->post('/rdvs/{id}/annuler', annulerRDVAction::class);
    $app->get('/patients/{id}', \toubilib\api\actions\getPatientDetailsAction::class);
    $app->post('/auth/signin', \toubilib\api\actions\SigninAction::class);

    return $app;
};