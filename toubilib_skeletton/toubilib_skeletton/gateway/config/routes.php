<?php
declare(strict_types=1);

use Slim\App;
use gateway\actions\ListerPraticiensAction;
use gateway\actions\ConsulterPraticienAction;

return function (App $app): App {
    $app->get('/praticiens', ListerPraticiensAction::class);
    $app->get('/praticiens/{id}', ConsulterPraticienAction::class);

    return $app;
};