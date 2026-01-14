<?php
declare(strict_types=1);

use gateway\actions\ProxyAction;
use Slim\App;
use gateway\actions\ListerPraticiensAction;
use gateway\actions\ConsulterPraticienAction;

return function (App $app): App {
    $app->any('/praticiens[/{id}[/{params:.*}]]', ProxyAction::class);
    $app->any('/praticiens/{id}/rdvs', ProxyAction::class);
    $app->any('/auth/register', ProxyAction::class);
    $app->any('/auth/signin', ProxyAction::class);
    return $app;
};