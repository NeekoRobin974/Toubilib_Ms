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
    $app->get('/', HomeAction::class);
    $app->get('/praticiens', \toubilib\api\actions\getAllPraticiensAction::class);
    $app->get('/praticiens/{id}/creneaux', \toubilib\api\actions\getCreneauxAction::class);
    $app->get('/praticiens/{id}', \toubilib\api\actions\getPraticienDetailsAction::class);
    $app->get('/rdvs/{id}', \toubilib\api\actions\getRDVAction::class);
    $app->post('/rdvs', \toubilib\api\actions\creerRDVAction::class);
    $app->get('/praticiens/{id}/rdvs', getRDVByPraticienAction::class);
    $app->get('/praticien/{id}/agenda', getAgendaPraticienAction::class);
    $app->get('/praticien/{id}/{date_debut}/agenda', getAgendaPraticienAction::class);
    $app->get('/praticien/{id}/{date_debut}/{date_fin}/agenda', getAgendaPraticienAction::class);
    $app->post('/rdvs/{id}/annuler', annulerRDVAction::class);
    $app->get('/patients/consultations', GetHistoriqueConsultationsAction::class)
        ->add(\toubilib\api\middlewares\AuthnMiddleware::class);
    $app->get('/patients/{id}', \toubilib\api\actions\getPatientDetailsAction::class);
    $app->post('/auth/signin', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $authProvider = $container->get(\toubilib\api\provider\AuthnProviderInterface::class);
        $action = new \toubilib\api\actions\SigninAction($authProvider);
        return $action($request, $response, $args);
    });
    $app->post('/auth/signup', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $authProvider = $container->get(\toubilib\api\provider\AuthnProviderInterface::class);
        $action = new \toubilib\api\actions\SignUpAction($authProvider);
        return $action($request, $response, $args);
    });
    $app->post('/rdvs/{id}/status', changerStatusRDVAction::class);

    return $app;
};